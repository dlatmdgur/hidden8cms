<?php

namespace App\Model\Tables;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MemberImport implements ToModel, WithHeadingRow
{

    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if (!isset($row['u_id'])) {
            return null;
        }

        if (gettype($row['u_id']) !== "integer") {
            return null;
        }

        return new Member([
            'id' => $row['u_id'],
            'memo' => $row['n_memo'],
            'sale' => $row['c_sale'],
            'price' => $row['i_price'],
            'gameChip' => $row['ul_gamechip'],
            'dayGift' => $row['ul_daygift'],
            'dayGiftCount' => $row['i_daygiftcount'],
            'gameChipLimit' => $row['ul_gamechiplimit'],
            'gameChipSafeLimit' => $row['ul_gamechipsafelimit'],
            'goldLimit' => $row['ul_goldlimit'],
            'goldSafeLimit' => $row['i_safelimit'],
            'gameDiscount' => $row['f_gamediscounts'],
            'gameChipRefill' => $row['ul_gamechiprefill'],
            'dayUseCount' => $row['i_dayusecount'],
            'avatarCardCount' => $row['i_avatarcardcount'],
            'term' => $row['i_term'],
            'name' => $row['s_name'],
            'goldFreeCharge' => $row['i_goldfreecharge'],
            'timeBonusCount' => $row['i_timebonuscount'],
            'ticketId' => $row['i_ticketid'],
            'ticketCount' => $row['i_ticketcnt'],
            'rakeBack' => $row['f_rakeback'],
            'maxRakeBack' => $row['ul_maxrakeback'],
            'goldBonus' => $row['f_goldbonus'],
        ]);
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 2;
    }

}
