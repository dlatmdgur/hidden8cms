<?php


namespace App\Model\Game;

use App\BaseModel;
use Illuminate\Support\Facades\DB;

class UserInfo extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.user_info';
    protected $primaryKey = 'user_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'level', 'avatar_id', 'carddeck_seq', 'gold', 'chip', 'gem', 'gem_event',
        'safe_chip', 'safe_chip_period', 'safe_gold', 'safe_period', 'members_type', 'members_period',
        'members_chip_refill', 'members_attendance', 'members_attendance_received',
        'exp', 'accumulated_exp',
        'chip_game_play_count', 'gold_game_play_count', 'free_charge_count', 'free_charge_gold_count',
        'server_state', 'today_overgold'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
//        'reg_date' => 'datetime',
//        'safe_chip_start_date' => 'datetime',
//        'safe_start_date' => 'datetime',
//        'members_start_date' => 'datetime',
    ];

    public static function getUserNames($userSeqs)
    {
        $sql = "SELECT user_seq, nickname FROM gamedb.user_info ".
                " WHERE FIND_IN_SET(user_seq, '" . $userSeqs . "')";
        return DB::connection('mysql')->select($sql);
    }

    public static function getUsersByNickname($nicknames)
    {
        $sql = "SELECT user_seq, nickname FROM gamedb.user_info ".
            " WHERE FIND_IN_SET(nickname, '" . $nicknames . "')";
        return DB::connection('mysql')->select($sql);
    }

    public static function getUserForBan($userSeq)
    {
        $sql = "SELECT IFNULL(A.google_email, A.platform_id) as account, A.user_state, U.user_seq, U.nickname, ".
                "   U.chip, U.safe_chip, U.gold, U.safe_gold, U.gem, U.gem_event ".
                "FROM gamedb.user_info U ".
                "JOIN accountdb.account_info A ON U.user_seq = A.user_seq ".
                "WHERE U.user_seq = ?";
        return collect(DB::connection('mysql')->select($sql, [$userSeq]))->first();
    }
}
