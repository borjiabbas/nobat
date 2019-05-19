<?php
require_once("jdf.php");
class shTime{
    public function Convert($time){
        $year=substr($time,0,4);
        $month=substr($time,5,2);
        $day=substr($time,8,2);
        $shamsi=gregorian_to_jalali($year,$month,$day,"/");
        $shamsi=explode("/",$shamsi);
        if($shamsi[1]<10){
        $shamsi[1]="0".$shamsi[1];
        }
        if($shamsi[2]<10){
            $shamsi[2]="0".$shamsi[2];
        }
        return $shamsi[0]."/".$shamsi[1]."/".$shamsi[2];
    }
    public function Today(){
        return $this->Convert(date("Y/m/d"));
    }
    public function isToday($date){
         if($this->Convert(date("Y/m/d"))==$date)
             return true;
        else
            return false;
    }
    public function Tomorrow($day){
        $day=explode("/",$day);
        $day=jalali_to_gregorian($day[0],$day[1],$day[2],"-");
        $datetime = new DateTime($day);
        $datetime->modify('+1 day');
        return $this->Convert($datetime->format('Y/m/d'));
    }
}




