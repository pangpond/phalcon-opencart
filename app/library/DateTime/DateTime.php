<?php

namespace Phoenix\Library\DateTime;

use Phalcon\Mvc\User\Component;
/**
 * DateTime
 *
 * Helps to build UI elements for the application
 */
class DateTime extends Component
{
    protected $suffix = 'ago';
    protected $thaiDayArray=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
    protected $thaiMonthArray=array(
        "0"=>"",
        "1"=>"มกราคม",
        "2"=>"กุมภาพันธ์",
        "3"=>"มีนาคม",
        "4"=>"เมษายน",
        "5"=>"พฤษภาคม",
        "6"=>"มิถุนายน",
        "7"=>"กรกฎาคม",
        "8"=>"สิงหาคม",
        "9"=>"กันยายน",
        "10"=>"ตุลาคม",
        "11"=>"พฤศจิกายน",
        "12"=>"ธันวาคม"
    );

    protected $locale = 'en';
    protected $thaiShortMonthArray=array(
        "0"=>"",
        "1"=>"ม.ค.",
        "2"=>"ก.พ.",
        "3"=>"มี.ค.",
        "4"=>"เม.ย.",
        "5"=>"พ.ค.",
        "6"=>"มิ.ย.",
        "7"=>"ก.ค.",
        "8"=>"ส.ค.",
        "9"=>"ก.ย.",
        "10"=>"ต.ค.",
        "11"=>"พ.ย.",
        "12"=>"ธ.ค."
    );

    protected $englishShortMonthArray=array(
        '0' => 'Jan',
        '1' => 'Jan',
        '2' => 'Feb',
        '3' => 'Mar',
        '4' => 'Apr',
        '5' => 'May',
        '6' => 'Jun',
        '7' => 'Jul',
        '8' => 'Aug',
        '9' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dec'
    );

    public function timeAgo($created_time)
    {
        $targetDay = strtotime($created_time);
        $today = strtotime(date('Y-m-d H:i:s'));

        // It returns the time difference in Seconds...
        $time_differnce = $today-$targetDay;

        if($today <= $targetDay){
            $time_differnce *= -1;
            if($this->locale == "en"){
                $suffix = 'left';
                $prefix = '';
            }
            else{
                $suffix = '';
                $prefix = "เหลืออีก ";
            }
        }
        else {
            if($this->locale == "en"){
                $suffix = $this->suffix;
                $prefix = '';
            }
            else{
                $suffix = '';
                $prefix = 'เลยกำหนด ';
            }
        }
        
        // To Calculate the time difference in Years...
        $years = 60*60*24*365;

        // To Calculate the time difference in Months...
        $months = 60*60*24*30;

        // To Calculate the time difference in Days...
        $days = 60*60*24;

        // To Calculate the time difference in Hours...
        $hours = 60*60;

        // To Calculate the time difference in Minutes...
        $minutes = 60;

        if($this->locale == "en"){
            $stringDays = "days";
            $stringDay = "day";
            $stringHours = "hours";
            $stringHour = "hour";
        }
        else{
            $stringDays = "วัน";
            $stringDay = "วัน";
            $stringHours = "ชั่วโมง";
            $stringHour = "ชั่วโมง";
        }
        if(intval($time_differnce/$years) > 1)
        {
            return $prefix . intval($time_differnce/$years)." years ". $suffix;
        }else if(intval($time_differnce/$years) > 0)
        {
            return $prefix . intval($time_differnce/$years)." year ". $suffix;
        }else if(intval($time_differnce/$months) > 1)
        {
            return $prefix . intval($time_differnce/$months)." months ". $suffix;
        }else if(intval(($time_differnce/$months)) > 0)
        {
            return $prefix . intval(($time_differnce/$months))." month ". $suffix;
        }else if(intval(($time_differnce/$days)) > 1)
        {
            return $prefix . intval(($time_differnce/$days))." ". $stringDays ." ". $suffix;
        }else if (intval(($time_differnce/$days)) > 0) 
        {
            return $prefix . intval(($time_differnce/$days))." ". $stringDay ." ". $suffix;
        }else if (intval(($time_differnce/$hours)) > 1) 
        {
            return $prefix . intval(($time_differnce/$hours))." ". $stringHours ." ". $suffix;
        }else if (intval(($time_differnce/$hours)) > 0) 
        {
            return $prefix . intval(($time_differnce/$hours))." ". $stringHour ." ". $suffix;
        }else if (intval(($time_differnce/$minutes)) > 1) 
        {
            return $prefix . intval(($time_differnce/$minutes))." minutes ". $suffix;
        }else if (intval(($time_differnce/$minutes)) > 0) 
        {
            return $prefix . intval(($time_differnce/$minutes))." minute ". $suffix;
        }else if (intval(($time_differnce)) > 1) 
        {
            return $prefix . intval(($time_differnce))." seconds ". $suffix;
        }else
        {
            return "few seconds ". $suffix;
        }
    }

    private function translate(){
        $arrayTranslate = '';
        return $arrayTranslate;
    }

    
    function date($time){
        if($this->locale == 'th'){
            $date = "" . date("j", $time);
            $date .=" " . $this->thaiMonthArray[date("n", $time)];
            $date .= " " . (date("Yํ", $time) + 543);
        }else if($this->locale == 'en' || $this->locale == 'cn'){
            $date = date("F jS, Y", $time);
        }

        return $date;
    }

    function weekday($time){
        if($this->locale == 'th'){
            $weekday = "วัน" . $this->thaiDayArray[date("w", $time)];
            $weekday .= "  " . date("H:i", $time) . " น.";
        }else if($this->locale == 'en' || $this->locale == 'cn'){
            $weekday = date("l H:i A", $time);
        }

        return $weekday;
    }

    function setLocale($locale){
        $this->locale = $locale;
    }

    function getThaiMonth($month){

        return $this->thaiMonthArray[(int)$month];
    }

    function getThaiShortMonth($month){

        return $this->thaiShortMonthArray[(int)$month];
    }

    function getThaiYear($year){

        return (int)$year+543;
    }

    function getThaiShortDate($date){
        $dateArray = explode("-", $date);
        $shortDate = (int)$dateArray[2].' '.$this->getThaiShortMonth($dateArray[1]).' '.substr($this->getThaiYear($dateArray[0]), 2, 4);
        return $shortDate;
    }

    function getShortMonthList(){
        if($this->locale == "th")
            return $this->thaiShortMonthArray;
        else
            return $this->englishShortMonthArray;
    }


}
