<?php
class Mysql{
    private $db;
    private $sqlSrv;
    public $time;
    function __construct() {
        $this->connect();
        $this->time=new shTime();
        require_once "libs/sqlSrv.php";
        $this->sqlSrv=new SqlServer();
    }
    private  function connect(){
        $this->db = mysqli_connect('localhost','root','','nobat');
        if (!$this->db){
            die(mysqli_connect_error());
        }
        mysqli_set_charset($this->db,"utf8");
    }
    public function query($sqlString){
        $res= mysqli_query($this->db,$sqlString);
        return $res;
    }
    public function activeRooms(){
        $sqlString="SELECT * from queue";
        $result=$this->query($sqlString);
        $data=array();
        while ($row=mysqli_fetch_assoc($result)){
            array_push($data,$row);
        }
        return $data;
    }
    public function curlRequest($url,$data){
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        curl_exec($ch);
        curl_close($ch);

    }
    public function updateQueue($data){
        $sqlString="UPDATE `queue` SET ";
        if(isset($data[qDate]))
            $sqlString.="`qDate` = '{$data['qDate']}',";
            $sqlString.="`queue` = '{$data['queue']}' WHERE room = '{$data['room']}' ";
        if($this->query($sqlString)){
            $roomData=$this->roomData($data['room']);
            $data['triage']=$this->sqlSrv->triageDataAll($roomData['drCode'],$data['queue'],$roomData['qDate']);
            $this->curlRequest("http://localhost:4000/queue",$data);
        }
    }

    public function updateRoom($data){
        $sqlString="UPDATE `queue` SET
                        `drCode` = '{$data["drCode"]}',
                         `drName` = '{$data["drName"]}',
                          `isEmpty` =0,
                          `queue` ='{$data["queue"]}',
                           `qDate` ='{$data["date"]}'
                            WHERE `queue`.`room` ='{$data["room"]}'";

        $data['triage']=$this->sqlSrv->triageDataAll($data['drCode'],$data['queue'],$data["date"]);
        if($this->query($sqlString)){
            $this->curlRequest('http://localhost:4000/addRoom',$data);
        }
    }

    public function close_db(){
        mysqli_close($this->db);
    }
    public function prep($string){
        $string=trim($string);
        $string=mysqli_real_escape_string($this->db,$string);
        return $string;
    }
    public function onlineDoctors($room){
        $sqlString="SELECT `drcode` From `queue` WHERE isempty=0 AND room<>{$room}";
        $result=$this->query($sqlString);
        $doctors=[];
        while($row=mysqli_fetch_array($result)){
            array_push($doctors,$row[0]);
        }
        return $doctors;
    }
    public function getDrName($drCode){
        $sqlString="SELECT dr_name From dr_list WHERE dr_code=".$drCode;
        $res=$this->query($sqlString);
        $row=mysqli_fetch_assoc($res);
        if($row["dr_name"]){
            return $row["dr_name"];
        }else{
            $data['drName']=$this->sqlSrv->getDrName($drCode);
            $data['drCode']=$drCode;
            $this->insertNewDr($data);
            return $data['drName'];
        }
    }
    public function insertNewDr($data){
            $intCode=$this->NewIntCode();
            $sqlString="INSERT INTO dr_list (dr_name, dr_code,intCode) VALUES ('{$data['drName']}','{$data['drCode']}','{$intCode}')";
            $this->query($sqlString);
    }
    public function emptyRoom($room){
        $sqlString="UPDATE `queue` SET `drcode` =0, `drname` = '',`queue`=0,`isempty`=1 WHERE `queue`.`room` =".$room;
        if($this->query($sqlString)){
            $data=$this->activeRooms();
            //$roomDate=$this->roomData($room);
            //$data['triage']=$this->sqlSrv->triageDataAll($data['drCode'],$data['queue'],$roomDate['qDate']);
            $this->curlRequest('http://localhost:4000/initRoom',$data);
        }
    }
    public function roomManualInit($data){
            $drName=$this->getDrName($data["drCode"]);
            $qDate=$this->time->Today();
            $sqlString="SELECT room FROM queue WHERE drcode=".$data["drCode"];
            $res=$this->query($sqlString);
            while($row=mysqli_fetch_assoc($res)) {
                $this->emptyRoom($row["room"]);
            }
            $data["drName"]=$drName;
            $data["date"]=$qDate;
            $this->updateRoom($data);
    }
    public function roomAutoInit($room){
         $doctors=$this->onlineDoctors($room);
        require_once "libs/sqlSrv.php";
        $this->sqlSrv=new SqlServer();
        $data=array();
        $data['drCode']=$this->sqlSrv->roomAutoInit($doctors);
        if($data){
            if($this->finishLogData($data['drCode'])){
                $data['queue']=$this->finishLogData($data['drCode']);
            }else{
                $data['queue']=0;
            }
           $data['room']=$room;
            $this->roomManualInit($data);
            return $data;
        }else{
            return false;
        }
    }
    public function finishLogData($drCode){
        $sqlString="SELECT queue From dr_list WHERE dr_code=".$drCode." AND qDate=CURDATE()";
        $result=$this->query($sqlString);
        $data=mysqli_fetch_assoc($result);
        return $data['queue'];
    }
    public function roomData($room){
        $sqlString="SELECT * from queue WHERE room=".$room;
        $res=$this->query($sqlString);
        $row=mysqli_fetch_assoc($res);
        return $row;
    }
    public function finishLog($data){
        $sqlString="UPDATE dr_list SET  queue='{$data['queue']}',qDate=CURDATE() where dr_code='{$data['drCode']}'";
        $this->query($sqlString);
    }
    public function nextQueue($room){
        $data=$this->roomData($room);
        require_once "libs/sqlSrv.php";
        $this->sqlSrv=new SqlServer();
        $response=$this->sqlSrv->nextQueue($data['drCode'],$data['qDate'],$data['queue']);

        if($response){
            $data["queue"]=(int)$response["queue"];
            if(isset($response["qDay"])){
                $data["qDate"]=$response["qDate"];
            }
            $this->updateQueue($data);
            return true;
        }else{
            return false;
        }

    }
    public function maxQueue($drCode){
        $this->sqlSrv=new SqlServer();
        return $this->sqlSrv->maxQueue($drCode, $this->time->Today());
    }
    public function NewIntCode(){
        $sqlString="SELECT num FROM freenums WHERE status=0 LIMIT 1";
        $row=mysqli_fetch_assoc($this->query($sqlString));
        $num=$row['num'];
        $sqlString="UPDATE freenums SET status=1 WHERE num=".$num;
        $this->query($sqlString);
        return $num;
    }
    public function intCodeToDrCode($intCode){
        $sqlString="SELECT dr_code FROM dr_list WHERE intCode=".$intCode;
        $row=mysqli_fetch_assoc($this->query($sqlString));
        return $row['dr_code'];
    }
    public function isRoomEmpty($room){
        $sqlString="SELECT isEmpty from queue WHERE room=".$room;
        $result=$this->query($sqlString);
        $row=mysqli_fetch_assoc($result);
        return $row['isEmpty'];
    }
    public function drCodListInRooms(){
        $sqlString="SELECT `drCode`,`queue`,`qDate` From `queue` WHERE isempty=0";
        $result=$this->query($sqlString);
        $data=[];
        while ($row=mysqli_fetch_assoc($result))
            array_push($data,$row);
        return $data;
    }
    public function triage($doctors){
         return $this->sqlSrv->triage($doctors);
    }
}