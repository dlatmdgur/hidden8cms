<?php

namespace App\Model;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class Report extends BaseModel
{
	protected $connection = 'slot_common';

	/**
	 * 플레이어별 통계
	 * @param string $sdate
	 * @param string $edate
	 * @param string $searchType
	 * @param string $keyword
	 * @param string $datetype
	 * @return void
	 */
	public static function getPlayers(string $sdate, string $edate, string $searchType, string $keyword = '', string $datetype = 'D')
	{
		$where = [];

		if ($keyword && $searchType)
		{
			switch ($searchType)
			{
				case 'id':
					$where[] = ['ai.account', 'like', $keyword.'%'];
					break;
				case 'nick':
					$where[] = ['ai.nickname', 'like', $keyword.'%'];
					break;
				case 'useq':
					$where[] = ['ai.user_seq', 'like', $keyword.'%'];
			}
		}

		$where[] = ['uc.datetype', '=', $datetype];
		$where[] = ['uc.datecode', '>=', $sdate];
		$where[] = ['uc.datecode', '<=', $edate];

		return 	DB::connection('slot_common')
						->table('accountdb.account_info AS ai')
						->leftjoin('sw_user_cumulatives AS uc', 'uc.uid', '=', 'ai.user_seq')
						->select('uc.datecode', 'ai.user_seq', 'ai.nickname', DB::raw('SUM(uc.play) AS tot_play'), DB::raw('SUM(uc.win) AS tot_win'),  DB::raw('SUM(bet) AS tot_bet'), DB::raw('SUM(fee) AS tot_fee'),
								DB::raw('SUM(uc.payout) AS tot_payout'), DB::raw('IFNULL(SUM(payout) / (SUM(bet) + SUM(fee)) * 100, 0)  AS rtp'))
						->where($where)
						->groupby('uc.datecode')->groupby('uc.uid')
						->orderby('uc.datecode', 'DESC')->orderby('uc.uid', 'ASC')->paginate(20);
	}

	/**
	 * 요약별 통계 쿼리
	 *
	 * @param string $sdate
	 * @param string $edate
	 * @param string $datetype
	 * @return void
	 */
	public static function getSummarys(string $sdate, string $edate , string $datetype = 'D')
	{
		$where = [
			['uc.datetype', '=', $datetype],
			['uc.datecode', '>=', $sdate],
			['uc.datecode', '<=', $edate],
		];

		return DB::connection('slot_common')->table('sw_user_cumulatives AS uc')
					->select('uc.datecode', DB::raw('COUNT(uid) AS user_cnt'), DB::raw('SUM(uc.play) AS tot_play'), DB::raw('SUM(uc.win) AS tot_win'),  DB::raw('SUM(bet) AS tot_bet'), DB::raw('SUM(fee) AS tot_fee'),
								DB::raw('SUM(uc.payout) AS tot_payout'), DB::raw('IFNULL(SUM(payout) / (SUM(bet) + SUM(fee)) * 100, 0) AS rtp'))
					->where($where)
					->groupby('uc.datecode')
					->orderby('uc.datecode', 'DESC')->paginate(20);

	}
	/**
	 * 게임별 통계 쿼리
	 *
	 * @param string $sdate
	 * @param string $edate
	 * @param string $slotID
	 * @param string $datetype
	 * @return void
	 */
	public static function getGames(string $sdate, string $edate, string $slotID = '', string $datetype = 'D')
	{
		$where = [];

		$where[] = ['uc.datetype', '=', $datetype];
		$where[] = ['uc.datecode', '>=', $sdate];
		$where[] = ['uc.datecode', '<=', $edate];

		if ($slotID)
			$where[] =  ['uc.slot_id', '=', $slotID];



		return DB::connection('slot_common')->table('sw_user_cumulatives AS uc')
					->select('uc.datecode', 'uc.slot_id',DB::raw('COUNT(uid) AS user_cnt'), DB::raw('SUM(uc.play) AS tot_play'), DB::raw('SUM(uc.win) AS tot_win'),  DB::raw('SUM(bet) AS tot_bet'), DB::raw('SUM(fee) AS tot_fee'),
								DB::raw('SUM(uc.payout) AS tot_payout'), DB::raw('SUM(payout) / (SUM(bet) + SUM(fee)) * 100 AS rtp'))
					->where($where)
					->groupby('uc.datecode')->groupby('uc.slot_id')
					->orderby('uc.datecode', 'DESC')
					->orderby('uc.slot_id', 'ASC')
					->paginate(20);
	}

	/**
	 * 월별, 슬롯별 쿼리
	 *
	 * @param string $sdate
	 * @param string $slotID
	 * @param string $datetype
	 * @return void
	 */
	public static function getMonthly(string $sdate, string $slotID, string $datetype = 'M')
	{
		$where = [
			['datetype', '=', $datetype],
			['datecode', '=', $sdate],
		];

		if ($slotID)
			$where[] = ['slot_id', '=', $slotID];

		return DB::connection('slot_common')->table('sw_user_cumulatives AS uc')
					->select('uc.datecode', 'slot_id', DB::raw('SUM(uc.play) AS tot_play'), DB::raw('SUM(uc.win) AS tot_win'),
								DB::raw('SUM(bet) AS tot_bet'), DB::raw('SUM(fee) AS tot_fee'),
								DB::raw('SUM(uc.payout) AS tot_payout'), DB::raw('SUM(payout) / (SUM(bet) + SUM(fee)) * 100 AS rtp'))
					->where($where)
					->groupby('datecode')
					->groupby('slot_id')
					->orderby('slot_id', 'ASC')
					->paginate(20);
	}
}