<?php

namespace App\Models;

use bcl\redis\cacheBase;
use App\Domain\usersDomain;
use App\Domain\GiftDomain;


class charmRankingModel extends baseModel
{

    public function getSource()
    {
        return "charm_ranking";
    }


}
