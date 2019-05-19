<?php
class next extends Controller {

    public function __construct()
    {
        parent::__construct();
    }
    public function index($room){

        $v=$this->load->model('nextModel');
        $data=($v->next($room[0]));
        $this->load->view("screen",$data);
    }
    public function raspri($room){
        $model=$this->load->model('nextModel');
        $model->raspriModel($room);
    }
}