<?php


use   App\Models\vodSessionModel;


class VodTask extends \Phalcon\CLI\Task
{
    
    
    
    public function runAction()
    {
        vodSessionModel::check_session();
    }
    
}