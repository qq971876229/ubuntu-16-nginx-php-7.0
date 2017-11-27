<?php
use   App\Models\vodSessionModel;
use App\RModels\logRModel;




class MainTask extends \Phalcon\CLI\Task
{
    
    
    
    public function mainAction()
    {
      self::vodRun();      
      
      logRModel::log("task:main", "main run");
      
    }
    
    
    private function vodRun()
    {
        vodSessionModel::check_session();
    }
    
  
}