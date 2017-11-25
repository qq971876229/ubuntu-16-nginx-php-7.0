<?php

namespace App\Models;

use App\Lib\System\App;
use App\RModels\logRModel;
use bcl\redis\cacheBase;
use App\Domain\usersDomain;


class systemMessModel extends baseModel
{


    public function getSource()
    {
        return "system_mess";
    }


    public static function add($uid, $content)
    {
        $mess = new systemMessModel();

        $mess->content = $content;
        $mess->add_time = time();
        $mess->uid = $uid;
        $mess->save();

    }

    public static function get_list_pc($page)
    {
        $list = systemMessModel::page_pc($page, "select * from system_mess order by id desc");

        foreach ($list['list'] as $k => $v) {
            $list['list'][$k]->add_time = date('Y-m-d H:i:s', $list['list'][$k]->add_time);
        }

        return $list;
    }

    /**
     * simulate 5 online live send message to user
     * @param $to_uid
     * @return bool
     */
    public static function rand_live_to_user($to_uid)
    {


        // 3 hours apart from the last message
        $key = 'rand_send_message_time_'.$to_uid;
        $last_time = cacheBase::redis_get($key);


        if ($last_time !== false) {

            $expire = time()-$last_time-3*3600;
            if ($expire<0) {

                return true;
            }
        }

        cacheBase::redis_set($key, time());

        $content = settingModel::get(12)->input_value;

        $request_time = time() - 5 * 60;
        $sql = "SELECT id FROM users WHERE auth_state=2 AND request_time>".$request_time." ORDER BY rand() LIMIT 5";

        $live_ids = usersModel::DB()->getAll($sql);

        foreach ($live_ids as $v) {
            if ($v->id != $to_uid) {

                App::TxMess()->user_to_user_message($v->id, $to_uid, $content);
            }
        }


    }


}
