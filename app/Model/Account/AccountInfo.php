<?php


namespace App\Model\Account;

use App\BaseModel;
use App\Helpers\Helper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AccountInfo extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'accountdb.account_info';
    protected $primaryKey = 'user_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_type', 'os_type', 'google_uuid', 'google_email', 'platform_id', 'nickname', 'partner_code', 'user_state',
        'game_db_idx', 'push_token', 'option_push', 'option_night_push', 'site_clause',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'platform_pw',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
    ];

    /**
     * @param array $where
     * @return LengthAwarePaginator
     */
    public static function getMemberList(array $where) : LengthAwarePaginator
    {
        return DB::connection('mysql')
            ->table(DB::raw('(SELECT MAX(log_seq) AS log_seq, user_seq FROM logdb.login_log WHERE DATEDIFF(NOW(), log_date) < 32 GROUP BY user_seq) AS L'))
            ->join('logdb.login_log AS L2', 'L.log_seq', '=', 'L2.log_seq')
            ->join('accountdb.account_info AS A', 'L.user_seq', '=', 'A.user_seq')
            ->join('gamedb.user_info AS U', 'L.user_seq', '=', 'U.user_seq')
            // ->leftJoin('auth_platform.member AS M', 'A.user_seq', '=', 'M.user_seq')
            // ->leftJoin('auth_platform.member_recommend AS MR', 'M.id', '=', 'MR.mid')
            ->leftJoin(DB::raw('(SELECT MAX(id) AS id, user_seq FROM cms_game.admin_logs GROUP BY user_seq) AS AL'), 'L.user_seq', '=', 'AL.user_seq')
            ->leftJoin('cms_game.admin_logs AS AL2', 'AL.id', '=', 'AL2.id')
            ->select(
                'L.user_seq', 'A.login_type', 'A.os_type', 'A.account'
                ,'A.nickname', 'A.user_state', 'U.reg_date', 'L2.ip AS last_login_ip'
                , 'L2.log_date AS last_login_date', 'AL2.reason AS admin_reason'
            )
            ->where($where)
            ->orderBy('L.log_seq', 'DESC')
            ->paginate(20);
        //$list = DB::connection('mysql')
        //    ->table('accountdb.account_info as A')
        //    ->join('gamedb.user_info as U', 'U.user_seq', '=', 'A.user_seq')
        //    ->leftJoin('logdb.login_log as L', 'L.log_seq', '=', DB::raw("(SELECT MAX(log_seq) FROM logdb.login_log WHERE user_seq = A.user_seq)"))
        //    ->leftJoin('cms_game.admin_logs as AL', 'L.log_seq', '=', DB::raw("(SELECT MAX(a.id) FROM (SELECT id FROM cms_game.admin_logs WHERE menu = 'member') AS a)"))
        //    ->select('A.*', 'U.reg_date', 'L.log_date AS last_login_date', 'L.ip AS last_login_ip', 'AL.reason AS admin_reason')
        //    ->where($where)
        //    ->orderBy('L.log_date', 'DESC')
        //    ->paginate(20);
        //
        //return $list;
    }

    public static function getMemberListOneDay($searchType, $keyword, $regDate, $referrer = 'all', $list = 20, $page = 1)
    {
        $builder = DB::connection('mysql')->table('accountdb.account_info AS A')
            ->join('gamedb.user_info AS U', 'A.user_seq', '=', 'U.user_seq')
            ->leftJoin('logdb.login_log AS L', 'L.log_seq', '=', DB::raw("(SELECT MAX(log_seq) FROM logdb.login_log WHERE user_seq = A.user_seq)"))
            ->leftJoin('gamedb.present AS P', 'P.present_seq', '=', DB::raw("(SELECT present_seq FROM gamedb.present WHERE user_seq = A.user_seq AND sender_seq = -28)"))
            ->leftJoin('accountdb.certification AS C', 'A.user_seq', '=', 'C.user_seq')
            ->selectRaw("A.*, U.reg_date, L.log_date AS last_login_date, L.ip AS last_login_ip, IFNULL(P.present_seq, 0) AS present_seq, IF (C.di IS NULL OR C.`date` < DATE_SUB(NOW(), INTERVAL 1 YEAR), 0, 1) AS `cert` ");
        switch($searchType) {
            case 'nickname':
                $builder = $builder->where('A.nickname', 'like', $keyword . '%');
                break;
            case 'userid':
                $builder =  $builder->where('A.account', 'like', $keyword . '%');
                break;
            case 'ip':
                $builder = $builder->where('L.ip', 'like', '%' . $keyword . '%');
                break;
        }
        if($referrer == 'Y') $builder = $builder->whereNotNull('P.present_seq');
        elseif($referrer == 'N') $builder = $builder->whereNull('P.present_seq');
        $builder = $builder->whereDate('U.reg_date', $regDate);
        $recordCnt = $builder->count();
        if($recordCnt == 0) return ['record_cnt' => 0, 'records' => null, 'pagination' => Helper::getPagination($list, $page, 10, 0)];

        $start = (intval($page) - 1) * $list;
        $records = $builder->orderBy('A.user_seq', 'DESC')->skip($start)->take($list)->get();

        return [
            'record_cnt' => $recordCnt,
            'records' => $records,
            'pagination' => Helper::getPagination($list, $page, 10, $recordCnt),
        ];
    }

    public static function getMemberByReferrer($referrerId, $list = 20, $page = 1)
    {
        $builder = DB::connection('mysql')->table('auth_platform.member AS M')
            ->join('auth_platform.member_recommend AS MR', 'M.id', '=', 'MR.mid')
            ->join('auth_platform.member AS M2', 'MR.rec_mid', '=', 'M2.id')
            ->join('accountdb.account_info AS A', 'M.user_seq', '=', 'A.user_seq')
            ->select('M.user_seq', 'A.login_type', 'A.nickname', 'A.platform_id', 'A.google_email', 'M.created_at', 'M.login_date', 'M.login_ip')
            ->where('M2.userid', '=', $referrerId);
        $recordCnt = $builder->count();
        if($recordCnt == 0) return ['record_cnt' => 0, 'records' => null, 'pagination' => Helper::getPagination($list, $page, 10, 0)];

        $start = (intval($page) - 1) * $list;
        $records = $builder->orderBy('M.id', 'DESC')->skip($start)->take($list)->get();

        return [
            'record_cnt' => $recordCnt,
            'records' => $records,
            'pagination' => Helper::getPagination($list, $page, 10, $recordCnt),
        ];
    }
}
