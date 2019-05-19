<?php
class index extends Controller {

    public function __construct()
    {
        parent::__construct();
        echo "this is index page"."<br>";
    }
    public function index($id){
        $d=new mysql();
        $d->updateQueue("fdf");
        echo "indexxxxMethod is ".$id[0];
    }
    public function xx($data){
        print_r($data);
    }
}