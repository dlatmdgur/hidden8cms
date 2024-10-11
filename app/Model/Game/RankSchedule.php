<?php

namespace App\Model\Game;

use Illuminate\Support\Facades\DB;

class RankSchedule
{
    protected $connection = 'mysql';
    protected $dbname = 'rankdb';
    protected $tableName = 'rankdb.tblschedule';

    public static function getList($gameType, $subType, $startDate, $endDate)
    {
        $whereSql = "WHERE 1 = 1";
        if ($gameType != "") {
            $whereSql .= " AND gametype = '".$gameType."' ";
        }
        if ($subType != "") {
            $whereSql .= " AND subtype = '".$subType."' ";
        }
        if ($startDate != "") {
            $whereSql .= " AND start >= '".$startDate."' ";
        }
        if ($endDate != "") {
            $whereSql .= " AND end <= '".$endDate."' ";
        }

        $sql = "SELECT id, gametype, subtype, start, end ".
                " FROM rankdb.tblschedule ".
                $whereSql.
                " ORDER BY end DESC;";

        return DB::connection('mysql')->select($sql);
    }

    public static function getSchedule($id)
    {
        $sql = "SELECT id, gametype, subtype, `start`, `end` FROM rankdb.tblschedule WHERE id = ?";
        return collect(DB::connection('mysql')->select($sql, [$id]))->first();
    }

    public static function saveSchedule($schedule)
    {
        $sql = "INSERT INTO rankdb.tblschedule ".
                " (gametype, subtype, `start`, `end`) ".
                " VALUES ".
                " (?, ?, ?, ?) ";
        return DB::connection('mysql')->insert($sql, [$schedule['gametype'], $schedule['subtype'], $schedule['start'], $schedule['end']]);
    }

    public static function updateSchedule($schedule)
    {
        $sql = "UPDATE rankdb.tblschedule ".
            " SET ".
            " gametype = ?, ".
            " subtype = ?, ".
            " `start` = ?, ".
            " `end` = ? ".
            " WHERE id = ?";

        return DB::connection('mysql')->update($sql, [$schedule['gametype'], $schedule['subtype'], $schedule['start'], $schedule['end'], $schedule['id']]);
    }
}
