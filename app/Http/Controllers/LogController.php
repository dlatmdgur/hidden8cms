<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Account\AccountInfo;
use App\Model\CMS\AdminLog;
use App\Model\Game\Present;
use App\Model\Log\UseTicketSeedLog;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show Money Operation Logs
     *
     * @return Factory|View
     */
    public function money()
    {
        return view('logs.money');
    }

    /**
     * Show Admin Logs about chip gold edit
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function moneyLogs(Request $request)
    {
        $this->middleware('gzip');


        $adminLog = null;
        $accountInfo = null;

        $status = 200;
        $messages = ['로그를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'keyword' => 'required',
            'actionType' => 'required',
            'target' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $platform = $request->input('platform');
        $actionType = $request->input('actionType');
        $target = $request->input('target');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == 'money') {

            if ($type != "daily") {

                // find user_seq & account info
                if ($type === 'nickname') {
                    $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
                } else if ($type === 'userSeq') {
                    $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
                } else if ($type === 'email') {
                    $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
                }

                if (!is_null($accountInfo)) {
                    $status = 200;
                    $messages = null;

                    $adminLog = AdminLog::getChipGoldLogsByUser($accountInfo->user_seq, $startDate, $endDate, $actionType, $target);
                }

            } else {
                $status = 200;
                $messages = null;

                $dailyLogs = AdminLog::getDailyChipGoldLogs($startDate, $endDate, $actionType, $target);

                $adminLog = [];
                if (count($dailyLogs) > 0) {
                    $tmpLog = [
                        'logDate' => '', 'target' => '', 'actionType' => '',
                        'event' => 0, 'maintenance' => 0, 'correction' => 0, 'claim' => 0,
                        'admin' => 0, 'test' => 0, 'total' => 0,];

                    foreach ($dailyLogs as $log) {
                        if ($tmpLog['logDate'] == '') {
                            $tmpLog['logDate'] = $log->logDate;
                            $tmpLog['target'] = $log->target;
                            $tmpLog['actionType'] = $log->actionType;
                        }

                        if ($tmpLog['logDate'] != $log->logDate ||
                            $tmpLog['target'] != $log->target ||
                            $tmpLog['actionType'] != $log->actionType) {

                            // add previous
                            $adminLog[] = $tmpLog;

                            // 초기화
                            $tmpLog = [
                                'logDate' => $log->logDate, 'target' => $log->target, 'actionType' => $log->actionType,
                                'event' => 0, 'maintenance' => 0, 'correction' => 0, 'claim' => 0,
                                'admin' => 0, 'test' => 0, 'total' => 0,];
                        }

                        if ($log->logType == "event") {
                            $tmpLog['event'] += intval($log->changeAmount);
                        } else if ($log->logType == "maintenance") {
                            $tmpLog['maintenance'] += intval($log->changeAmount);
                        } else if ($log->logType == "correction") {
                            $tmpLog['correction'] += intval($log->changeAmount);
                        } else if ($log->logType == "claim") {
                            $tmpLog['claim'] += intval($log->changeAmount);
                        } else if ($log->logType == "admin") {
                            $tmpLog['admin'] += intval($log->changeAmount);
                        } else if ($log->logType == "test") {
                            $tmpLog['test'] += intval($log->changeAmount);
                        }
                        $tmpLog['total'] += intval($log->changeAmount);

                    }
                    $adminLog[] = $tmpLog;
                }
            }

        }

        return response()->json([
            'error' => false,
            'adminLogs' => $adminLog,
            'messages' => $messages
        ], $status);
    }

    /**
     *
     * 운영정보 - 운영로그 - 칩/골드 지급/회수 로그(일별) 검색 후 detail
     * @param Request $request
     * @return void
     */
    public function detailMoneyLogs(Request $request)
    {
        //
        // 요청값에서 초기값 설정
        //
        $type     = $request->get('type');
        $searchDt   = $request->get('date');
        $actionType = $request->get('action_type');

        $startDt    =   new DateTime($searchDt . ' 00:00:00');
        $endDt      =   new DateTime($searchDt . ' 23:59:59');
        $data       = array();


        //데이터를 가져옴
        $dailyLog =  AdminLog::getChipGoldLogsByUser(
                null,
                $startDt->format('Y-m-d H:i:s'),
                $endDt->format('Y-m-d H:i:s'),
                $actionType,
                $type
        );

        foreach ($dailyLog as &$log)
            $log->changeAmount = number_format($log->changeAmount);

        $data['rs'] = $dailyLog;

        $html = view('logs.daily_detail_money', $data)->render();

        return response(['result' => 0, 'html' => $html], 200);

    }
    /**
     * Show Posts Operation Logs
     *
     * @return Application|Factory|View
     */
    public function posts()
    {
        return view('logs.posts');
    }

    /**
     * Show Admin Logs about presents
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postsLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $adminLog = null;
        $accountInfo = null;

        $status = 404;
        $messages = ['로그를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'keyword' => 'required',
            'actionType' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $platform = $request->input('platform');
        $actionType = $request->input('actionType');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == 'posts') {

            if ($type != "daily") {

                // find user_seq & account info
                if ($type === 'nickname') {
                    $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
                } else if ($type === 'userSeq') {
                    $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
                } else if ($type === 'email') {
                    $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
                }

                if (!is_null($accountInfo)) {
                    $error = false;
                    $status = 200;
                    $messages = null;

                    $adminLog = AdminLog::getPostsLogsByUser($accountInfo->user_seq, $startDate, $endDate, $actionType);
                }

            } else {

                $error = false;
                $status = 200;
                $messages = null;

                $adminLog = AdminLog::getDailyPostsLogs($startDate, $endDate, $actionType);

            }

        }

        return response()->json([
            'error' => $error,
            'adminLogs' => $adminLog,
            'messages' => $messages
        ], $status);
    }

    public function detailPostsLogs(Request $request)
    {
        $searchDt = $request->get('date') ?? date('Y-m-d');
        $target = $request->get('type') ?? '';
        $actionType = $request->get('action_type') ?? 'all';

        $startDt = new DateTime($searchDt.' 00:00:00');
        $endDt = new DateTime($searchDt.' 23:59:59');

        $postLogs   = AdminLog::getPostsLogsByUser(
                        null,
                        $startDt->format('Y-m-d H:i:s'),
                        $endDt->format('Y-m-d H:i:s'),
                        $actionType,
                        $target
                    );

        DB::disconnect('mysql');

        $data['rs'] = $postLogs;

        $html = view('logs.daily_detail_posts', $data)->render();

        return response()->json(['result' => 0, 'html' => $html], 200);

    }
    /**
     * Show Gem Operation Logs
     *
     * @return Application|Factory|View
     */
    public function gem()
    {
        return view('logs.gem');
    }

    /**
     * Show Admin Logs about gem
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function gemLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $adminLog = null;
        $accountInfo = null;

        $status = 404;
        $messages = ['로그를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'keyword' => 'required',
            'actionType' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $platform = $request->input('platform');
        $actionType = $request->input('actionType');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == 'gem') {

            if ($type != "daily") {

                // find user_seq & account info
                if ($type === 'nickname') {
                    $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
                } else if ($type === 'userSeq') {
                    $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
                } else if ($type === 'email') {
                    $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();

                }

                if (!is_null($accountInfo)) {
                    $error = false;
                    $status = 200;
                    $messages = null;

                    $adminLog = AdminLog::getGemLogsByUser($accountInfo->user_seq, $startDate, $endDate, $actionType);
                }

            } else {
                $error = false;
                $status = 200;
                $messages = null;

                $dailyLogs = AdminLog::getDailyGemLogs($startDate, $endDate, $actionType);

                $adminLog = [];
                if (count($dailyLogs) > 0) {
                    $tmpLog = [
                        'logDate' => '', 'target' => '', 'actionType' => '',
                        'event' => 0, 'maintenance' => 0, 'correction' => 0, 'claim' => 0,
                        'admin' => 0, 'test' => 0, 'total' => 0,];

                    foreach ($dailyLogs as $log) {
                        if ($tmpLog['logDate'] == '') {
                            $tmpLog['logDate'] = $log->logDate;
                            $tmpLog['target'] = $log->target;
                            $tmpLog['actionType'] = $log->actionType;
                        }

                        if ($tmpLog['logDate'] != $log->logDate ||
                            $tmpLog['target'] != $log->target ||
                            $tmpLog['actionType'] != $log->actionType) {

                            // add previous
                            $adminLog[] = $tmpLog;

                            // 초기화
                            $tmpLog = [
                                'logDate' => $log->logDate, 'target' => $log->target, 'actionType' => $log->actionType,
                                'event' => 0, 'maintenance' => 0, 'correction' => 0, 'claim' => 0,
                                'admin' => 0, 'test' => 0, 'total' => 0,];
                        }

                        if ($log->logType == "event") {
                            $tmpLog['event'] += intval($log->changeAmount);
                        } else if ($log->logType == "maintenance") {
                            $tmpLog['maintenance'] += intval($log->changeAmount);
                        } else if ($log->logType == "correction") {
                            $tmpLog['correction'] += intval($log->changeAmount);
                        } else if ($log->logType == "claim") {
                            $tmpLog['claim'] += intval($log->changeAmount);
                        } else if ($log->logType == "admin") {
                            $tmpLog['admin'] += intval($log->changeAmount);
                        } else if ($log->logType == "test") {
                            $tmpLog['test'] += intval($log->changeAmount);
                        }
                        $tmpLog['total'] += intval($log->changeAmount);

                    }
                    $adminLog[] = $tmpLog;
                }
            }

        }

        return response()->json([
            'error' => $error,
            'adminLogs' => $adminLog,
            'messages' => $messages
        ], $status);
    }

    /**
     * Show Effect Operation Logs
     *
     * @return Application|Factory|View
     */
    public function effect()
    {
        return view('logs.effect');
    }

    /**
     * Show Admin Logs about effect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function effectLogs(Request $request)
    {
        $this->middleware('gzip');
        $adminLog = null;
        $accountInfo = null;

        $status = 200;
        $messages = ['로그를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'type' => 'required',
            'keyword' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $platform = $request->input('platform');
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == 'effect') {

            // find user_seq & account info
            if ($type === 'nickname') {
                $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
            } else if ($type === 'userSeq') {
                $accountInfo = AccountInfo::where('user_seq', $keyword)->first();
            } else if ($type === 'email') {
                $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
            }

            if (!is_null($accountInfo)) {
                $status = 200;
                $messages = null;
                $adminLog = AdminLog::getEffectLogsByUser($accountInfo->user_seq, $startDate, $endDate);
            }
        }

        return response()->json([
            'error' => false,
            'adminLogs' => $adminLog ?? [],
            'messages' => $messages
        ], $status);
    }

    /**
     * Show Send Operation Logs
     *
     * @return Application|Factory|View
     */
    public function send()
    {
        return view('logs.send');
    }

    /**
     * Show Admin Logs about effect
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendLogs(Request $request)
    {
        $this->middleware('gzip');

        $error = true;
        $adminLog = null;
        $accountInfo = null;

        $status = 404;
        $messages = ['로그를 찾지 못했습니다.'];

        $validator = Validator::make($request->input(), array(
            'startDate' => 'required',
            'endDate' => 'required',
            'from' => 'required'
        ));

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
            ], 422);
        }

        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('startDate') . ' 00:00:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('endDate') . ' 23:59:59');
        $from = $request->input('from');

        if ($from == 'send') {

            $error = false;
            $status = 200;
            $messages = null;

            $adminLog = AdminLog::getSendLogs($startDate, $endDate);

        }

        return response()->json([
            'error' => $error,
            'adminLogs' => $adminLog,
            'messages' => $messages
        ], $status);
    }

    /**
     * 티켓이벤트 로그
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function ticketSeed(Request $request)
    {
        $this->middleware('gzip');

        $type = $request->input('search_type');
        $platform = $request->input('login_type');
        $keyword = $request->input('keyword');
        $getUse = $request->input('type') ?? 'use';

        $sdate = $request->input('search_start_date') ?? date('Y-m-d');
        $edate = $request->input('search_end_date') ?? date('Y-m-d');
        $sDateTime = $sdate . ' 00:00:00';
        $eDateTime = $edate . ' 23:59:59';

        $accountInfo = null;
        if($type === 'nickname') {
            $accountInfo = AccountInfo::where(DB::raw('BINARY `nickname`'), $keyword)->first();
        } elseif($type === 'userSeq') {
            $accountInfo = AccountInfo::find($keyword);
        } elseif($type === 'email') {
            $accountInfo = AccountInfo::where('login_type', $platform)->where('account', $keyword)->first();
        }

        $data = [
            'search' => [
                'search_type' => $type,
                'login_type' => $platform,
                'keyword' => $keyword,
                'type' => $getUse,
                'search_start_date' => $sdate,
                'search_end_date' => $edate,
            ],
            'records' => [],
            'numberStart' => 0,
        ];

        $param = http_build_query($data['search']);

        if($accountInfo) {
            if($getUse === 'get') {
                $builder = Present::select('item_id', 'item_ea', 'sender_seq', 'update_date AS log_date')
                    ->where('user_seq', $accountInfo->user_seq)
                    ->whereIn('item_id', Helper::getTicketSeedItemId())
                    ->whereBetween('update_date', [$sDateTime, $eDateTime])
                    ->where('is_read', '=', 1)
                    ->orderBy('present_seq', 'DESC');
            } else {
                $builder = UseTicketSeedLog::select('item_id', 'use_count AS item_ea', 'memo', 'log_date')
                    ->where('user_seq', $accountInfo->user_seq)
                    ->whereBetween('log_date', [$sDateTime, $eDateTime])
                    ->orderBy('log_seq', 'DESC');
            }

            $records = $builder->paginate(2);
            $records->withPath(route('logs.ticketSeed') . '?' . $param);
            foreach($records as $record) {
                $record->item_name = Helper::getTicketSeedItemName(intval($record->item_id));
                $record->reason = isset($record->sender_seq) ? Helper::reasonByKey($record->sender_seq) : $record->memo;
            }
            $numberStart = $records->total() - (($records->currentPage() - 1) * $records->perPage());
            $data['records'] = $records;
            $data['numberStart'] = $numberStart;
        }
        return view('logs.ticketseed', $data);
    }

    public function eventJackpot(Request $request)
    {
        //
        // 기본값 초기화 및 리턴값 정리
        //
        $retval = [];

        $retval['sdate']    = $sdate = $request->get('sdate') ?? date('Y-m-d');
        $retval['edate']    = $edate = $request->get('edate') ?? date('Y-m-d');
        $retval['page']     = $request->get('page') ?? 1;
        $retval['offset']   = $request->get('offset') ?? 20;

        $sdate = $retval['sdate'].' 00:00:00';
        $edate = $retval['edate'].' 23:59:59';

        $logs =  AdminLog::getEvtJackpotLogs($sdate, $edate, $retval['page'], $retval['offset']);

        $now = new DateTime();

        foreach ($logs as &$row)
        {
            $created =  new DateTime($row->created);
            $row->delay_min = '';
            $status = 'Completed';

            if (!empty($row->start_time))
            {
                $startTime = new DateTime($row->start_time);
                $delayMin = abs($startTime->getTimestamp() - $created->getTimestamp()) / 60;
                $row->delay_min = (is_float($delayMin) ? ceil($delayMin) : $delayMin);
            }

            $row->delay_min .= 'Min';


            if (isset($startTime))
            {
                if ($row->status === '0')
				{
					$updated = new DateTime($row->updated);

					if ($updated != $created && $startTime > $updated)
						$status = 'Cancelled';
				}
				else
				{
					$now = new DateTime();
					if ($startTime > $now) {
                        $status = 'Pending';
                    }
				}
            }


            $row->status = $status;
        }

       $logs->withQueryString()->links();

       $retval['result'] = $logs;

        return view('logs.eventJackpot', $retval);
    }
}

