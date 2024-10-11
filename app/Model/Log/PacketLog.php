<?php

namespace App\Model\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PacketLog
{
    protected $connection = 'mysql';
    protected $primaryKey = 'log_idx';
    protected static $databaseName = 'logdb';

    public static function getPacketLogs($logDate, $gameType, $roomId)
    {
        $tablename = "packet_data_" . $logDate;

        $existsQuery = "SELECT * FROM information_schema.tables WHERE table_schema = 'logdb' AND table_name = '".$tablename."' LIMIT 1;";
        $exists = DB::connection('mysql')->select($existsQuery);
        if (count($exists) == 0) {

            return [];
        } else {

            $packetSql = "SELECT P.* " .
                " FROM " . self::$databaseName . "." . $tablename . " P " .
                " WHERE P.game_type = ? AND P.room_id = ? ORDER BY P.log_seq DESC";

            $packets = DB::connection('mysql')->select($packetSql, array($gameType, $roomId));

            $userSeqs = [];
            foreach ($packets as $packet) {
                array_push($userSeqs, $packet->user_seq);
            }
            $userSeqs = array_unique($userSeqs);

            $namesSql = "SELECT user_seq, nickname FROM gamedb.user_info ".
                " WHERE FIND_IN_SET(user_seq, '" . implode( ',', $userSeqs) . "')";
            $users = DB::connection('mysql')->select($namesSql);

            $nicknames = [];
            foreach ($users as $user) {
                $nicknames[$user->user_seq] = $user->nickname;
            }

            foreach ($packets as $index => $packet) {
                if ($packet->user_seq == 0) {
                    continue;
                }
                $packets[$index]->nickname = $nicknames[$packet->user_seq];
            }

            return $packets;
        }
    }

    public static function getUserPacketLogs($logDate, $gameType, $roomId, $userSeq)
    {
        $tablename = "packet_data_" . $logDate;

        $existsQuery = "SELECT * FROM information_schema.tables WHERE table_schema = 'logdb' AND table_name = '".$tablename."' LIMIT 1;";
        $exists = DB::connection('mysql')->select($existsQuery);
        if (count($exists) == 0) {

            return [];
        } else {

            $packetSql = "SELECT P.* " .
                " FROM " . self::$databaseName . "." . $tablename . " P " .
                " WHERE P.game_type = ? AND P.room_id = ? AND P.user_seq = ? ORDER BY P.log_seq DESC";

            $packets = DB::connection('mysql')->select($packetSql, array($gameType, $roomId, $userSeq));

            $userSeqs = [];
            foreach ($packets as $packet) {
                array_push($userSeqs, $packet->user_seq);
            }
            $userSeqs = array_unique($userSeqs);

            $namesSql = "SELECT user_seq, nickname FROM gamedb.user_info ".
                " WHERE user_seq = ?";
            $users = DB::connection('mysql')->select($namesSql, array($userSeq));

            $nicknames = [];
            foreach ($users as $user) {
                $nicknames[$user->user_seq] = $user->nickname;
            }

            foreach ($packets as $index => $packet) {
                $packets[$index]->nickname = $nicknames[$packet->user_seq];
            }

            return $packets;
        }
    }

}
