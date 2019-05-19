<?php
class room extends Controller {

    public function __construct()
    {
        parent::__construct();
    }
    public function index($room){
        $v=$this->load->model('jsonModel');

        $data=$v->initSreenData();
        $this->load->view("json",$data);
    }
    public function raspriAutoInit($room){
        $model=$this->load->model('roomModel');
        $data=$model->raspriAutoInit($room[0]);
        $this->load->view("json",$data);
    }
    public function respriNextQueue($room){
        $model=$this->load->model('roomModel');
        $data=$model->respriNextQueue($room);
        $this->load->view("json",$data);
    }
    public function finish($room){
        $model=$this->load->model('roomModel');
        $data=$model->finish($room);
        $this->load->view("json",$data);

    }
    public function roomAutoInit($room){
        $model=$this->load->model('roomModel');
        $roomData=$model->roomData($room);
        if($roomData['isEmpty']) {
            $data = $model->roomAutoInit($room[0]);
        }else {
            $data=['error'=>'roomIsNotEmpty'];
        }
        $this->load->view("json", $data);
    }
    public function manualInit($room){
    if(isset($_POST["drCode"])){

                $data=$_POST;
    }
    $data["room"]=$room;
    $model=$this->load->model("roomModel");
        $model->manualInit($data);
    }
    public function keyPadInit($room){
        $model=$this->load->model("roomModel");
        if(isset($_POST["intCode"])){
            $data=$model->keypadInit($_POST['intCode']);
        }
        $data["room"]=$room;
        $model->manualInit($data);
    }
    public function manualQueue($room){
        if(isset($_POST["queue"])){
            $data['queue']=$_POST["queue"];
            $data['room']=$room;
            $model=$this->load->model("roomModel");
            $roomData=$model->roomData($room);
            $max=$model->maxQueue($roomData['drCode']);
            if($max >= $data['queue']){
                $model->manualQueue($data);
                $res['status']=200;
            }else{
                $res['maxQueue']=$max;
            }
            $this->load->view("json", $res);
        }
    }
}