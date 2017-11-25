<?php

namespace App\Models;

use bcl\redis\cacheBase;
use App\Domain\usersDomain;
use App\Domain\GiftDomain;


class onlineTimeModel extends baseModel
{

    public function getSource()
    {
        return "online_time";
    }


}
