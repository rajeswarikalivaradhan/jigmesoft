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
        if (!$this instanceof DateTime) {
            throw new Exception("Invalid DateTime object.");
        }
    
        $year = (int) $this->format('Y'); // Get the year as integer
        $month = (int) $this->format('m'); // Get the month as integer
        
        $start = new DateTime();
        $end = new DateTime();
    
        // Fiscal year starts from April 1st
        if ($month >= 4) {
            $start->setDate($year, 4, 1);
            $end->setDate($year + 1, 3, 31);
        } else {
            $start->setDate($year - 1, 4, 1);
            $end->setDate($year, 3, 31);
        }
    
        return [
            'start' => $start->format('Y-m-d'),
            'end'   => $end->format('Y-m-d')
        ];
    }
    
}