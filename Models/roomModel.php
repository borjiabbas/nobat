<?php
class roomModel{

    public function raspriAutoInit($room){
        $ms=new Mysql();
        return($ms->roomAutoInit($room));
    }
    public function roomAutoInit($room){
        $ms=new Mysql();
        $data=$ms->roomAutoInit($room);
        $data["drName"]=$ms->getDrName($data['drCode']);
        return $data;
    }
    public function respriNextQueue($room){
        $ms=new Mysql();
       return $ms->nextQueue($room);
    }
    public function manualInit($data){
        $ms=new Mysql();
        if($ms->isRoomEmpty($data['room']))
        $ms->roomManualInit($data);
    }
    public function manualQueue($data){
        $ms=new Mysql();
        $ms->updateQueue($data);
    }
    public function finish($room){
        $ms=new Mysql();
        $roomData=$ms->roomData($room);
        if(!$roomData['isEmpty']) {
            $ms->finishLog($roomData);
            $ms->emptyRoom($room);
             $data['status']=200;
            return $data;
        }else{
            $data['error']='roomAlreadyFinished';
            return $data;
        }
    }
    public function roomData($room){
        $ms=new Mysql();
        return $ms->roomData($room);

    }
    public function maxQueue($drCode){
        $ms=new Mysql();
        return $ms->maxQueue($drCode);
    }
    public function keypadInit($intCode){
        $ms=new Mysql();
        $data['drCode']= $ms->intCodeToDrCode($intCode);
        if($ms->finishLogData($data['drCode'])){
            $data['queue']=$ms->finishLogData($data['drCode']);
        }else{
            $data['queue']=0;
        }
        return $data;
    }
}

?>