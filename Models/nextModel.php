<?php
class nextModel{
   public function next($room){
       $ms=new Mysql();
       print_r($ms->nextQueue($room));
   }
    public function raspriModel($room){
            //require_once("../libs/mySql.php");
        $ms=new Mysql();
        return($ms->nextQueue($room));

    }
}


?>