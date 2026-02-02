<?php

//Set default local timezone(IST) settings.
date_default_timezone_set('Asia/Kolkata');

class MyDateTime extends DateTime
{
    /**
    * Calculates start and end date of fiscal year
    * @param DateTime $dateToCheck A date withn the year to check
    * @return array('start' => timestamp of start date ,'end' => timestamp of end date) 
    */
    public function fiscalYear()
    {
        $result = array();
        $start = new DateTime();
        $start->setTime(0, 0, 0);
        $end = new DateTime();
        $end->setTime(23, 59, 59);
        $year = $this->format('Y');
        $start->setDate($year, 4, 1);
         
        if($start <= $this){
            $end->setDate($year +1, 3, 31);
            
        } else {
            $start->setDate($year - 1, 4, 1);
            $end->setDate($year, 3, 31);
           
        }
        $result['start'] = $start;
	$result['end'] = $end;
	return $result;   
	
       
    }
}