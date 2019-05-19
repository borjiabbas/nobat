<?php
class load{
    public function __construct(){

    }
    public function view($view,$data=null){
            include'views/'.$view.'.php';
    }
    public function model($model){
        include'models/'.$model.'.php';
        return new $model;
    }
}