<?php

class SqlServer{
    private $server = '192.168.166.20';
    private $database = 'shafa';
    private $username = 'EmdadiReadOnly';
    private $password = 'QQEmdadi2019';
    private $connection;
    public $time;
    public $queryPrefixQ="SELECT TOP 1 [Paper Code3],[Adm Code] FROM paper WHERE [Part Code]=28 ";
    function __construct()
    {
        $this->time= new shTime();

    }
    public function Query($sqlString){

    }
    public function FreeSql($result){
        sqlsrv_free_stmt( $result);
    }
    public function ServerClose(){
    }
    public function hasRows($Result){
        $rows = sqlsrv_has_rows($Result);
        return $rows;
    }
    public function getDrName($drCode){
        if($drCode==1){
            return "دکتر تست تستی";
        }else{
            return false;
        }

    }
    public function nextQueue($drCode,$date,$lastQueue,$nextDayFlag=true){
        $response=array();

            $response["queue"]=$lastQueue+1;
                $response["date"]="1398/11/23";
            return $response;
            return false;
    }
    public function roomAutoInit($doctors){

            return  "74851";

    }
}
//$s=new SqlServer();
//$s->nextDay("dd");
//$s->nextQueue(148726,'1397/11/01',1254);
//$doctors=array(148726,100658);
//print_r($s->roomAutoInit($doctors));

