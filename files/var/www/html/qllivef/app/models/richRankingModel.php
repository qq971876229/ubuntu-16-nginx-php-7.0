<?php

namespace App\Models;

use bcl\redis\cacheBase;
use App\Domain\usersDomain;
use App\Domain\GiftDomain;


class richRankingModel extends baseModel
{

    public function getSource()
    {
        return "rich_ranking";
    }


}
