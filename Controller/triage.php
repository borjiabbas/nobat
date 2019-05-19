<?php
class triage extends Controller{
    public function __construct()
    {
        parent::__construct();
    }
    public function get($doctors){
        require_once "libs/sqlSrv.php";
        header('Content-Type: application/json');
        $sqlSrv=new SqlServer();;
       $data= $sqlSrv->triageDataAll($doctors);
        echo json_encode($data);

    }




}
