<?php


namespace App\Model\Game;

use App\BaseModel;

class GameInfo extends BaseModel
{
    protected $connection = 'mysql';
    protected $table = 'gamedb.game_info';
    protected $primaryKey = 'sequence_no';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_seq', 'game_type', 'play_count', 'win_count', 'lose_count', 'draw_count', 'disconnect_count',
        'allin_count', 'top_pot_money', 'today_win_count', 'today_lose_count', 'today_draw_count', 'last_game_result',
        'seq_win_count', 'today_win_money', 'today_win_money_gold_channel', 'made_count', 'chip_game_play_count',
        'gold_game_play_count', 'last_room_id', 'first_made_bonus', 'second_made_bonus', 'third_made_bonus',
        'max_seq_win_count', 'masterplaycnt_1vs1'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
//        'update_date' => 'datetime',
    ];

}
