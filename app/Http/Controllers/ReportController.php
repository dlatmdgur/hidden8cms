<?php

namespace App\Http\Controllers;

use App\Model\Game\SlotInfoV2;
use App\Model\Game\SlotLogV2;
use App\Model\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

/**
 * Report 관련
 */
class ReportController extends Controller
{
    /**
     * 플레이어별 리포트
     *
     * @param Request $request
     * @return void
     */
    public function player(Request $request)
    {

        //
        // 초기값 설정
        //
        $data['sdate'] = $request->get('sdate') ?? date('Y-m-d');
        $data['edate'] = $request->get('edate') ?? date('Y-m-d');
        $data['keyword'] = $request->get('keyword') ?? '';
        $data['search_type'] = $request->get('search_type') ?? 'uid';

        $sdate = str_replace('-', '', $data['sdate']);
        $edate = str_replace('-', '', $data['edate']);


        //합계 관련 정의
        $total = new stdClass();
        $total->win             = 0;
        $total->lose            = 0;
        $total->play            = 0;
        $total->bet             = 0;
        $total->fee             = 0;
        $total->payout          = 0;
        $total->win_lose        = 0;
        $total->win_rate        = 0;
        $total->rtp             = 0;

        //
        // DB데이터조회
        //
        $dbData = Report::getPlayers($sdate, $edate, $data['search_type'], $data['keyword']);

        //
        // 뷰 출력전 데이터 가공 처리
        //
        foreach ($dbData as &$row)
        {
            $loseCnt = (int)$row->tot_play - (int)$row->tot_win;

            $row->datecode = sprintf('%s-%s-%s', substr($row->datecode, 0, 4), substr($row->datecode, 4, 2), substr($row->datecode, 6, 2));
            $row->win_lose = $row->tot_win - $loseCnt;
            $row->win_rate = ((int)$row->tot_win !== 0) ? ((int)$row->tot_win / (int)$row->tot_play * 100) : 0;

            //토탈값 계산
            $total->win             += (int)$row->tot_win;
            $total->lose            += (int)$loseCnt;
            $total->play            += (int)$row->tot_play;
            $total->bet             += (int)$row->tot_bet;
            $total->fee             += (int)$row->tot_fee;
            $total->payout          += (int)$row->tot_payout;
            $total->win_lose        += (int)$row->win_lose;
        }

        $total->win_rate    =   ($total->win !== 0) ? $total->win / $total->play * 100 : 0;
        $total->rtp         =   ($total->payout !== 0) ? $total->payout / ($total->bet + $total->fee) * 100 : 0;

        //페이지네이션 링크생성
        $dbData->withQueryString()->links();

        $data['result'] = $dbData;
        $data['total']  = $total;

        return view('report.player', $data);

    }

    /**
     * 요약별 리포트
     *
     * @param Request $request
     * @return void
     */
    public function summary(Request $request)
    {
        //
        // 초기값 설정
        //
        $data['sdate'] = $request->get('sdate') ?? date('Y-m-d');
        $data['edate'] = $request->get('edate') ?? date('Y-m-d');

        $sdate = str_replace('-', '', $data['sdate']);
        $edate = str_replace('-', '', $data['edate']);


        //
        // DB데이터조회
        //
        $dbData = Report::getSummarys($sdate, $edate);

        //합계 관련 정의
        $total = new stdClass();
        $total->win             = 0;
        $total->lose            = 0;
        $total->play            = 0;
        $total->user_cnt        = 0;
        $total->play_users      = 0;
        $total->bet             = 0;
        $total->fee             = 0;
        $total->payout          = 0;
        $total->win_lose        = 0;
        $total->win_rate        = 0;
        $total->rtp             = 0;


        //
        // 뷰 출력전 데이터 가공 처리
        //
        foreach ($dbData as &$row)
        {
            $loseCnt = (int)$row->tot_play - (int)$row->tot_win;

            $row->datecode = sprintf('%s-%s-%s', substr($row->datecode, 0, 4), substr($row->datecode, 4, 2), substr($row->datecode, 6, 2));
            $row->win_lose = $row->tot_win - $loseCnt;
            $row->win_rate = (int)$row->tot_win / (int)$row->tot_play * 100;
            $row->play_users = ((int)$row->tot_play !== 0) ? number_format((int)$row->tot_play / (int)$row->user_cnt, 1) : 0;

            //토탈값 계산
            $total->win             += (int)$row->tot_win;
            $total->lose            += (int)$loseCnt;
            $total->play            += (int)$row->tot_play;
            $total->user_cnt        += (int)$row->user_cnt;
            $total->bet             += (int)$row->tot_bet;
            $total->fee             += (int)$row->tot_fee;
            $total->payout          += (int)$row->tot_payout;
            $total->play_users      += (float)$row->play_users;
        }

        $total->win_lose = $total->win - $total->lose;
        $total->win_rate = ($total->win !== 0) ? $total->win / $total->play * 100 : 0;
        $total->rtp      = ($total->payout !== 0) ? $total->payout / ($total->bet + $total->fee) * 100 : 0;

        //페이지네이션 링크 생성
        $dbData->withQueryString()->links();

        $data['result']     = $dbData;
        $data['total']      = $total;

        return view('report.summary', $data);
    }

    /**
     * 게임별 리포트
     *
     * @param Request $request
     * @return void
     */
    public function game(Request $request)
    {
        //
        // 초기값설정
        //
        $data['slot_id']    = $request->get('slot_id') ?? '';
        $data['sdate']      = $request->get('sdate') ?? date('Y-m-d');
        $data['edate']      = $request->get('edate') ?? date('Y-m-d');

        $sdate =    str_replace('-', '', $data['sdate']);
        $edate =    str_replace('-', '', $data['edate']);


        //
        // DB데이터 가져오기
        //
        $dbData = Report::getGames($sdate, $edate, $data['slot_id']);
        $referSlots = SlotInfoV2::getSlots();

        $slotsArr = [];

        foreach ($referSlots as $item)
            $slotsArr[$item->slot_id] = $item->slot_name;

        unset($referSlots);

        //
        // view에 뿌려주기전 데이터가공
        //
        $total = [
            'play'          => 0,
            'win'           => 0,
            'lose'          => 0,
            'user_cnt'      => 0,
            'play_users'    => 0,
            'bet'           => 0,
            'fee'           => 0,
            'payout'        => 0,
            'win_lose'      => 0,
            'win_rate'      => 0,
            'rtp'           => 0,
        ];

        foreach ($dbData as &$row)
        {
            //슬롯명을 넣어주기 위함
            foreach ($slotsArr as $id => $name)
            {
                if ($id !== $row->slot_id)
                    continue;

                $row->slot_name = $name;
            }

            $loseCnt = (int)$row->tot_play - (int)$row->tot_win;

            $row->datecode      = sprintf('%s-%s-%s', substr($row->datecode, 0, 4), substr($row->datecode, 4, 2), substr($row->datecode, 6, 2));
            $row->play_users    = ((int)$row->tot_play !== 0) ?  (int)$row->tot_play / (int)$row->user_cnt : 0;
            $row->tot_payout    = $row->tot_payout;
            $row->win_lose      = $row->tot_win - $loseCnt;
            $row->win_rate      = ((int)$row->tot_win !== 0) ? (int)$row->tot_win / (int)$row->tot_play * 100 : 0;

            //
            //토탈값 계산
            //
            $total['play']          += (int)$row->tot_play;
            $total['user_cnt']      += (int)$row->user_cnt;
            $total['win']           += (int)$row->tot_win;
            $total['win_lose']      +=  (int)$row->win_lose;
            $total['lose']          +=  $loseCnt;
            $total['bet']           += (int)$row->tot_bet;
            $total['fee']           += (int)$row->tot_fee;
            $total['payout']        += (int)$row->tot_payout;
            $total['play_users']    += (float)$row->play_users;
        }


        //나머지 토탈값 계산
        $total['win_rate']  = ($total['win'] !== 0) ? ($total['win'] / $total['play']) * 100 : 0;
        $total['rtp']       = ($total['payout'] !== 0) ? ($total['payout'] / ($total['bet'] + $total['fee']) * 100) : 0;

        $dbData->withQueryString()->links();

        $data['result'] = $dbData;
        $data['slots']  = $slotsArr;
        $data['total']  = (object)$total;

        return view('report.game', $data);
    }
    /**
     * 월별 리포트
     *
     * @param Request $request
     * @return view
     */
    public function month(Request $request)
    {
        //
        // 초기값 설정
        //
        $data['sdate']      = $request->get('sdate') ?? date('Y-m');
        $data['slot_id']    = $request->get('slot_id') ?? '';

        $sdate = str_replace('-', '', $data['sdate']);

        // DB 데이터 가져오기
        $dbData = Report::getMonthly($sdate, $data['slot_id']);
        // 슬롯 레퍼 데이터 가져옴
        $referSlots = SlotInfoV2::getSlots();
        $slots = [];

        foreach ($referSlots as $item)
            $slots[$item->slot_id] = $item->slot_name;

        unset($referSlots);

        //합계 값 초기화
        $total =  new stdClass();
        $total->play        = 0;
        $total->win         = 0;
        $total->lose        = 0;
        $total->bet         = 0;
        $total->fee         = 0;
        $total->payout      = 0;
        $total->win_lose    =0;

        //
        // 뷰에 뿌려줄 데이터 가공
        //
        foreach ($dbData as &$row)
        {
            $row->datecode = sprintf('%s-%s', substr($row->datecode, 0, 4), substr($row->datecode, 4));
            $lose = (int)$row->tot_play - (int)$row->tot_win;

            $row->win_lose = (int)$row->tot_win - $lose;

            $total->play += (int)$row->tot_play;
            $total->bet += (int)$row->tot_bet;
            $total->fee += (int)$row->tot_fee;
            $total->payout += (int)$row->tot_payout;
            $total->win_lose += $row->win_lose;
        }

        $total->rtp =  ($total->payout !== 0) ? $total->payout / ($total->bet + $total->fee) * 100 : 0;
        $dbData->withQueryString()->links();

        $data['result'] = $dbData;
        $data['total']  = $total;
        $data['slots']  = $slots;

        return view('report.month', $data);

    }

}
