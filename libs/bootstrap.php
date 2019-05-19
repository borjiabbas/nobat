<?php
class Bootstrap{
    public $url;
    public function __construct()
    {
        if(!isset($_GET["url"])){
            $this->url="Index";
        }else{
            $this->url=$_GET["url"];
        }

        $this->url=explode('/',$this->url);
        if(!file_exists("Controller/".$this->url[0].".php")){
         //  $this->redirectToIndex();
        }else{
            $file = "Controller/".$this->url[0].".php";
            require($file);
            $controller=new $this->url[0]();
            if(!isset($this->url[1])){
                $method="index";
            }else{
                $method=$this->url[1];
            }
            if(method_exists($controller,$method)) {
                   // array_splice($this->url,0,2);
                if(!isset($this->url[2])){
                    $this->url[2]=null;
                }
                $controller->$method($this->url[2]);

            }else{
              //  $this->redirectToIndex();
            }
        }
    }
    public function redirectToIndex(){
        header('Location: ../index.php');
    }
}