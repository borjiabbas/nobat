<?php
class initModel{
    public function next(){
        $res=array(
            'one'=>"tooo"
        );
        return $res;
    }
    public function auto($room){
        require_once("libs/mySql.php");
        $ms=new Mysql();
        return($ms->roomAutoInit($room));

    }
}


?>