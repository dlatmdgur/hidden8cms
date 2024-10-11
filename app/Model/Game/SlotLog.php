<?php
namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;


class SlotLog extends BaseModel
{
    protected $connection = 'slot_log';

	private const category_type = array
	(
		'spin_log'			=>	'스핀',
		'bonus_game_result'	=>	'보너스게임',

		'coin_collect_log'	=>	'미니게임',
		'join_room'			=>	'슬롯입장',
		'exit_room'			=>	'슬롯퇴장',
		'bonus'				=>	'보너스',
		'piggy'				=>	'저금통',

		'gold_to_coin'		=>	'골드 → 코인',
		'coin_to_gold'		=>	'코인 → 골드',

		'user_disconnect'	=>	'접속종료',

		'special_bonus'		=>	'타임보너스',
		'welcome_gift'		=>	'첫접속보상',
		'daily_stamp'		=>	'일일선물',
		'daily_spin'		=>	'데일리스핀',
		'levelup'			=>	'레벨업',

		'free_charge_coins'	=>	'무료리필',
		'ingame_videoads'	=>	'광고보상',
		'safe_change'		=>	'저금통 출금',

		'vip_wheel_mega'	=>	'VIP 메가 휠',
		'vip_wheel_normal'	=>	'VIP 휠',

		'coupon_consume'	=>	'쿠폰사용',
		'mail_accept'		=>	'메일보상 받기',
		'mail_accept_all'	=>	'메일보상 모두받기',
	);



	private static function buildInsertQuery($table, $data)
	{
		$cols		= array ();
		$vals		= array ();
		$params		= array ();
		$updates	= array ();

		foreach ($data as $key => $val)
		{
			array_push($cols,		$key);
			array_push($vals,		'?');
			array_push($params,		$val);
			array_push($updates,	$key.'=?');
		}

		$query		= 'INSERT INTO '.$table.' ('.implode(', ', $cols).') VALUES ('.implode(', ', $vals).') ON DUPLICATE KEY UPDATE '.implode(', ', $updates).' ';
		$params		= array_merge($params, $params);

		return array
		(
			'query'		=>	$query,
			'params'	=>	$params
		);
	}


	public static function getSlotLogs(string $slot_id=null, $start, $end, $normalized='N')
	{
		$query = "SELECT
			*
		FROM
			sw_game_log_day".($normalized == 'Y' ? '_normalized' : '')."
		WHERE
		1
		";
		$params = [];

		if (!empty($slot_id))
		{
			$query .= ' AND slot_id=? ';
			array_push($params, $slot_id);
		}

		if (!empty($start) && !empty($end))
		{
			$query .= ' AND `date` BETWEEN ? AND ? ';
			array_push($params, $start);
			array_push($params, $end);
		}

		$query .= ' ORDER BY `date` DESC, `slot_id` ASC ';

//		$query = 'CALL getGameLogDay(?, ?, ?, ?); ';
//		$params = array (empty($slot_id) ? '' : $slot_id, $start, $end, $normalized);

		$result = collect(DB::connection('slot_log')->select($query, $params));

		if ($result == false)
			return false;

		$retval = array
		(
			'data'		=>	array (),
			'date'		=>	array ()
		);

		foreach ($result as $key => $row)
		{
			$data						= array ();


			$pay						= 0;
			$pay						= floatval($row->win_total) - floatval($row->spin_win_f) - floatval($row->jackpot_win) - floatval($row->bonus_win);

			// 날짜
			$data['date']				= implode('-', [substr($row->date, 0, 4), substr($row->date, 4, 2), substr($row->date, 6, 2)]);

			// 슬롯 추가.
			$data['slot_id']			= empty($row->slot_id) ? 'TOTAL' : $row->slot_id;

			// 환수율
			if ($row->win_total <= 0 ||
				($row->bet_total + $row->fee_total) <= 0)
				$data['rate']			= 0;
			else
				$data['rate']			= $row->win_total <= 0 ? 0 : @round(@(floatval($row->win_total) / @(floatval($row->bet_total) + floatval($row->fee_total))) * 100, 2, PHP_ROUND_HALF_DOWN);

			// 배팅정보
			$data['bet_total']			= floatval($row->bet_total);
			$data['fee_total']			= floatval($row->fee_total);
			$data['win_total']			= floatval($row->win_total);

			// 페이 컨테이너
			$data['pay']				= array ();
			// 일반 페이
			$data['pay']['normal']		= [floatval($pay), $data['bet_total'] > 0 ? @round((floatval($pay) / $data['bet_total']) * 100, 2, PHP_ROUND_HALF_DOWN) : 0];
			// 프리스핀 페이.
			$data['pay']['free']		= [floatval($row->spin_win_f), $data['bet_total'] > 0 ? @round((floatval($row->spin_win_f) / $data['bet_total']) * 100, 2, PHP_ROUND_HALF_DOWN) : 0];
			// 보너스 페이
			$data['pay']['bonus']		= [floatval($row->bonus_win), $data['bet_total'] > 0 ? @round((floatval($row->bonus_win) / $data['bet_total']) * 100, 2, PHP_ROUND_HALF_DOWN) : 0];
			// 잭팟 페이
			$data['pay']['jackpot']		= [floatval($row->jackpot_win), $data['bet_total'] > 0 ? @round((floatval($row->jackpot_win) / $data['bet_total']) * 100, 2, PHP_ROUND_HALF_DOWN) : 0];

			// 개임 플레이 수
			$data['game_played']		= intval($row->game_played);
			// 프리게임 플레이 수
			$data['game_played_f']		= intval($row->game_played_f);

			// 평균 배팅 컨테이너.
			$data['avg_rate']			= array ();
			// 일반 배팅
			$data['avg_rate']['normal']	= $data['bet_total'] <= 0 || round(@($data['game_played'] - $data['game_played_f']) <= 0 ? 0 : @($data['bet_total'] / ($data['game_played'] - $data['game_played_f'])), 2, PHP_ROUND_HALF_DOWN);
			// 프리 배팅
			$data['avg_rate']['free']	= $data['game_played_f'] > 0 ? round(floatval($row->bet_total_f) / $data['game_played_f'], 2, PHP_ROUND_HALF_DOWN) : '--';

			// 프리게임중
			$data['fp']					= array ();
			// 일반 상금
			$data['fp']['normal']		= [floatval($row->spin_win_f), $data['bet_total'] > 0 ? @round((floatval($row->spin_win_f) / $data['bet_total']) * 100, 2, PHP_ROUND_HALF_DOWN) : 0];
			// 보너스 상금
			$data['fp']['bonus']		= floatval($row->bonus_win_f);
			// 잭팟 상금
			$data['fp']['jackpot']		= floatval($row->jackpot_win_f);

			// 입장수 컨테이너.
			$data['enter']				= array ();
			$data['enter']['free']		= [floatval($row->free_game_enter_cnt), @round($data['game_played'] > 0 ? (floatval($row->free_game_enter_cnt) / $data['game_played']) * 100 : 0, 2, PHP_ROUND_HALF_DOWN)];
			$data['enter']['bonus']		= [floatval($row->bonus_game_enter_cnt), @round($data['game_played'] > 0 ? (floatval($row->bonus_game_enter_cnt) / $data['game_played']) * 100 : 0, 2, PHP_ROUND_HALF_DOWN)];

			$data['enter']['in_free']	= [floatval($row->free_game_enter_cnt_f), @round($data['game_played'] > 0 ? (floatval($row->free_game_enter_cnt_f) / $data['game_played']) * 100 : 0, 2, PHP_ROUND_HALF_DOWN)];
			$data['enter']['in_bonus']	= [floatval($row->bonus_game_enter_cnt_f), @round($data['game_played'] > 0 ? (floatval($row->bonus_game_enter_cnt_f) / $data['game_played']) * 100 : 0, 2, PHP_ROUND_HALF_DOWN)];


			array_push($retval['data'], $data);


			if (empty($retval['date'][$data['date']]))
				$retval['date'][$data['date']] = 0;

			++$retval['date'][$data['date']];
		}


		return $retval;
	}


	public static function getGameLog($date, $stime, $etime, $uid=null, $slot_id=null, $page=1, $limit=20)
	{
		$retval = array ();


		$tbl_name	= 'sw_game_log_'.str_replace('-', '', $date);


		$start		= ($page - 1) * $limit;
		$end		= $limit;

		$start_date	= $date.' '.$stime.':00';
		$end_date	= $date.' '.$etime.':59';

		$query_c	= "SELECT COUNT(*) AS `count`, SUM(JSON_EXTRACT(raw_user_info, '$.money') * 1000 - cli_coins * 1000) / 1000 AS `increase` FROM ".$tbl_name." WHERE 1 ";
		$query_l	= "SELECT * FROM (SELECT id FROM ".$tbl_name." WHERE 1 ";

		$params		= array ();

		if (!empty($uid))
		{
			$query_c .= "AND uid=? ";
			$query_l .= "AND uid=? ";
			array_push($params, $uid);
		}

		if (!empty($slot_id))
		{
			$query_c .= "AND slot_id=? ";
			$query_l .= "AND slot_id=? ";
			array_push($params, $slot_id);
		}

		$query_c	.= "AND category IN ('spin_log', 'bonus_game_result') AND `time` BETWEEN ? AND ? ";
		$query_l	.= "AND category IN ('spin_log', 'bonus_game_result') AND `time` BETWEEN ? AND ? ";
		array_push($params, $start_date, $end_date);

		$result_count = collect(DB::connection('slot_log')->select($query_c, $params));
		$retval['count'] = !isset($result_count[0]->count) ? 0 : $result_count[0]->count;
        $retval['increase'] = !isset($result_count[0]->increase) ? 0 : $result_count[0]->increase;

		$query_l	.= "ORDER BY id DESC LIMIT ?, ?) AS a ";
		$query_l	.= "INNER JOIN ".$tbl_name." AS b ON a.id = b.id ";
		array_push($params, $start, $end);

		$result_list = collect(DB::connection('slot_log')->select($query_l, $params));
		$retval['data'] = array ();

		foreach ($result_list as $key => $row)
		{
			$row->userinfo			= empty($row->raw_user_info) ? array () : json_decode($row->raw_user_info);
			$row->spin_result		= empty($row->raw_spin_result) ? array () : json_decode($row->raw_spin_result);

			$row->free_win			= 0;
			$row->normal_win		= (floatval($row->spin_win));

			if ($row->in_free_spin)
			{
				if ($row->category != 'bonus_game_result' && isset($row->spin_result->rule))
					$row->free_win	= floatval($row->spin_result->rule == 'Respin' ? $row->spin_result['win_detail'][0] : $row->spin_result['spin_win']);

				$row->normal_win	-= $row->free_win == null ? 0 : $row->free_win;
			}

			if ($row->category == 'spin_log')
				$row->bonus_win		= 0;

			if ($row->category == 'bonus_game_result')
				$row->bonus_win		= ($row->spin_result != null ? self::getBonusWin($row->spin_result) : 0);


			if (floatval($row->jackpot_win) > 0)
			{
				if ($row->in_free_spin == 1)
				{
					$row->spin_win	= $row->free_win;
					$row->normal_win	= 0;
					$row->free_win	= $row->free_win - floatval($row->jackpot_win);
				}
				else
					$row->normal_win	= 0;
			}


			try {
				$row->wins = json_decode($row->wins);
			} catch (Exception $e){ $row->wins = array (); }

			try {
				$row->raw_input_data = json_decode($row->raw_input_data);
			} catch (Exception $e){ $row->raw_input_data = array (); }

			array_push(
				$retval['data'],
				array
				(
					'id'				=>	intval($row->id),
					'uid'				=>	intval($row->uid),
					'coins'				=>	floatval($row->userinfo->money),
					'bonus'				=>	floatval($row->bonus),
					'cli_coins'			=>	floatval($row->cli_coins),
					'cli_bonus'			=>	floatval($row->cli_bonus),
					'delta_coins'		=>	floatval($row->delta_coins),
					'delta_bonus'		=>	floatval($row->delta_bonus),
					'delta_piggy_bank'	=>	floatval($row->delta_piggy_bank),
					'coins_limit'		=>	floatval($row->coins_limit),
					'piggy_bank'		=>	floatval($row->piggy_bank),
					'level'				=>	intval($row->level),
					'category'			=>	$row->category,
					'category_name'		=>	self::category_type[$row->category],
					'sub_cate_1'		=>	$row->sub_category,
					'sub_cate_1_name'	=>	empty(self::category_type[$row->sub_category]) ? '' : self::category_type[$row->sub_category],
					'sub_cate_2'		=>	empty($row->sub_category2) ? '' : $row->sub_category2,
					'sub_cate_3'		=>	empty($row->sub_category3) ? '' : $row->sub_category3,
					'collect_coins'		=>	floatval($row->collect_coins),
					'slot_id'			=>	$row->slot_id,
					'bet'				=>	intval($row->bet),
					'game_bet'			=>	floatval($row->game_bet),
					'free_spin'			=>	floatval($row->free_spin),
					'spin_win'			=>	floatval($row->spin_win),
					'normal_win'		=>	floatval($row->normal_win),
					'free_win'			=>	floatval($row->free_win),
					'bonus_win'			=>	floatval($row->bonus_win),
					'jackpot_win'		=>	floatval($row->jackpot_win),
					'jackpot_type'		=>	$row->jackpot_type,
					'in_free_spin'		=>	$row->in_free_spin,
					'in_free_symbol'	=>	($row->category == 'bonus_game_result' ? 'B' : ($row->in_free_spin == 1 ? 'F' : 'N')),
					'win_type'			=>	$row->win_type,
					'deck'				=>	$row->deck,
					'timestamp'			=>	$row->timestamp,
					'time'				=>	$row->time,
					'wins'				=>	$row->wins,
					'client_ver'		=>	@$row->raw_input_data->client_ver,
					'today_win_amount'	=>	$row->userinfo->today_win_amount,
					'input_data'		=>	$row->raw_input_data,
					'spin_result'		=>	$row->spin_result
				)
				);
		}


		return $retval;
	}

    private static function getBonusWin($spin_result)
    {
        switch ($spin_result->rule)
        {
            case 'Respin':
                if (isset($spin_result->data[0]))
                    return $spin_result->data[0];
                break;

            //HighLow게임은 여기서 보너스를 더하는게 아니고 GameEnd에서 더한다.
            case 'HighLow':
                return 0;
                break;
        }

        return $spin_result->win;
    }


}
