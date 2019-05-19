<?php
class json extends Controller {

    public function __construct()
    {
        parent::__construct();
    }
    public function queue($room){

    }
    public function screen(){

        $v=$this->load->model('jsonModel');
        echo $v->screenData();
        //$this->load->view("screen",$data);
    }
    public function raspri($room){
        $model=$this->load->model('nextQueue');
        $model->raspriModel($room);
    }
    public function initScreen(){
        $model=$this->load->model('jsonModel');
        $data['rooms']=$model->initSreenData();
        $data['drList']=$model->drList();
        $data['triage']=$model->triageList();
        $this->load->view("json",$data);
    }
}