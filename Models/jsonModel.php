<?php
class jsonmodel{
    public function screenData(){
        $res=array(
            'one'=>"tooo",
            'sol'=>'yeah'
        );

        return json_encode($res);
    }
    public function initSreenData(){
        $ms=new mysql();
        $res=$ms->query("SELECT * from queue");
           $array=[];
        while ($row=mysqli_fetch_assoc($res)){
            array_push($array,$row);
        }

        return $array;

    }
    public function drList(){
        $sqlString="SELECT dr_code,dr_name FROM dr_list";
        $ms=new mysql();
        $result=$ms->query($sqlString);
        $data=array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($data,$row);
        }
        return $data;
    }
    public function triageList(){
        $ms= new mysql();
        $doctors=$ms->drCodListInRooms();
        return($ms->triage($doctors));
    }
//    public function raspriModel($room){
//        require_once("../inc/mySql.php");
//        $ms=new Mysql();
//        return($ms->nextQueue($room));
//
//    }
}


?>