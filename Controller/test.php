<?php
class test extends Controller {

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
    public function manualQueue($room){
        $s= new Mysql();
        print_r($s->maxQueue('152508'));

    }
    public function free(){
        $s= new Mysql();
        $end=$s->query("SELECT num from freeNums ORDER BY id DESC LIMIT 1");
        $end=mysqli_fetch_assoc($end);
        print_r($end);
        $end=(int)$end['num'];
        $end++;
        echo $end;
        $svt="INSERT INTO freeNums (`num`) VALUES(".$end.")";
        $v=$s->query($svt);
    }

}