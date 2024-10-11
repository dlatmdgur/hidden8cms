<?php


namespace App\Model\Game;

use App\BaseModel;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class Present extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.present';
    protected $primaryKey = 'present_seq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'item_id', 'item_ea', 'sender_seq', 'period_time', 'update_date', 'is_read',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
    ];

    public static function getPresents($userSeq, $searchType, $itemType, $startDate, $endDate)
    {
        $isRead = "";
        if ($searchType == "used") {
            $isRead = " AND is_read = '1' ";
        } else if ($searchType == "unused") {
            $isRead = " AND is_read = '0' ";
        }
        $itemSql = "";
        if ($itemType != "all") {
            $itemSql = " AND item_id = '".$itemType."' ";
        }
        $sql = " SELECT P.present_seq, P.user_seq, P.item_id, I.ItemType as item_type, I.memo as item_name,
                        P.item_ea, P.sender_seq, P.period_time, P.update_date, P.is_read, P.unlimited
                    FROM gamedb.present P
                    LEFT JOIN cms_game.tbl_items I ON P.item_id = I.id
                    WHERE P.user_seq = ?
                    AND P.update_date BETWEEN ? AND ?
                    ". $isRead . "
                    ".$itemSql."
                    ORDER BY P.update_date DESC";

        $presents = DB::connection('mysql')->select($sql, array($userSeq, $startDate, $endDate));

        foreach ($presents as $present) {
            // Chip = 2016, Gold = 2018, Gem = 2017, GemEvent = 2025
            if($present->item_id == '2016') {
                $present->item_type = "Chip";
                $present->item_name = "칩";
            } elseif($present->item_id == '2017') {
                $present->item_type = "Gem";
                $present->item_name = "보석";
            } elseif($present->item_id == '2018') {
                $present->item_type = "Gold";
                $present->item_name = "골드";
            } elseif($present->item_id == '2025') {
                $present->item_type = "GemEvent";
                $present->item_name = "이벤트 보석";
            } elseif(in_array($present->item_id, Helper::getTicketSeedItemId())) {
                $present->item_type = "SeedTicket";
                $present->item_name = Helper::getTicketSeedItemName(intval($present->item_id));
            } elseif(in_array($present->item_id, Helper::getTicketTournamentItemId())) {
                $present->item_type = "TournamentTicket";
                $present->item_name = Helper::getTicketTournamentItemName(intval($present->item_id));
            }

            $present->reason = Helper::reasonByKey($present->sender_seq);
        }

        return $presents;
    }

    public static function getUnreadCount($userSeq)
    {
        $sql = " SELECT COUNT(*) as unreadCount
                    FROM gamedb.present
                    WHERE user_seq = ?
                        AND is_read = 0
                        AND (UNIX_TIMESTAMP(update_date) + period_time) > UNIX_TIMESTAMP(now()) ";

        return DB::connection('mysql')->select($sql, array($userSeq));
    }

    public static function getUnreadPresents($userSeq)
    {
        $sql = " SELECT P.present_seq, P.user_seq, P.item_id, I.ItemType as item_type, I.memo as item_name,
                        P.item_ea, P.sender_seq, P.period_time, P.update_date, P.is_read
                    FROM gamedb.present P
                    LEFT JOIN cms_game.tbl_items I ON P.item_id = I.id
                    WHERE P.user_seq = ?
                        AND P.is_read = 0
                        AND (UNIX_TIMESTAMP(P.update_date) + P.period_time) > UNIX_TIMESTAMP(now())
                    ORDER BY P.update_date DESC";

        $presents = DB::connection('mysql')->select($sql, array($userSeq));
        foreach ($presents as $present) {
            // Chip = 2016, Gold = 2018, Gem = 2017, GemEvent = 2025
            if($present->item_id == '2016') {
                $present->item_type = "Chip";
                $present->item_name = "칩";
            } elseif($present->item_id == '2017') {
                $present->item_type = "Gem";
                $present->item_name = "보석";
            } elseif($present->item_id == '2018') {
                $present->item_type = "Gold";
                $present->item_name = "골드";
            } elseif($present->item_id == '2025') {
                $present->item_type = "GemEvent";
                $present->item_name = "이벤트 보석";
            } elseif(in_array($present->item_id, Helper::getTicketSeedItemId())) {
                $present->item_type = "SeedTicket";
                $present->item_name = Helper::getTicketSeedItemName(intval($present->item_id));
            } elseif(in_array($present->item_id, Helper::getTicketTournamentItemId())) {
                $present->item_type = "TournamentTicket";
                $present->item_name = Helper::getTicketTournamentItemName(intval($present->item_id));
            }

            $present->reason = Helper::reasonByKey($present->sender_seq);
        }

        return $presents;
    }

    public static function multiSendPresents($data)
    {
        return DB::connection('mysql')->table('gamedb.present')->insert($data);
    }
}
