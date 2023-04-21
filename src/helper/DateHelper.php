<?php

trait DateHelper
{
    function getAllMonthNamesArray() {
        return array("Jaanuar","Veebruar","Märts", "Aprill", 
        "Mai", "Juuni", "Juuli", "August", 
        "September", "Oktoober", "November", "Detsember");
    }

    function isValidYear($year){
        if (strtotime($year)===false){
            return false;
        }
        if($year < 1900 || $year > 2100){
            return false;
        }
        return true;
    }

    function getPaymentDate($day,$month, $year){
        $paymentDay = date_create_from_format ("d-m-Y",$day."-".$month."-".$year);
        if($paymentDay){
            if($this->isEstonianHoliday($paymentDay->format('Y-m-d'))){
                $paymentDay = new DateTime(date('Y-m-d', strtotime($paymentDay->format('Y-m-d'). ' - 1 days')));
            }
            $weekday = intval(date_format($paymentDay,"N"));
            if($weekday < 6 && $weekday >= 1){
                return $paymentDay->format('Y-m-d');
            }
            else{
                $daysToDecrease = $weekday - 5;
                return date('Y-m-d', strtotime($paymentDay->format('Y-m-d'). ' - '.$daysToDecrease.' days'));
            }
        }
    }

    private function isEstonianHoliday($date) {
        $holidays = array(
            // Fixed holidays
            '01-01', // Uusaasta
            '02-24', // Iseseisvuspäev
            '03-07', // Suur reede
            '05-01', // Kevadpüha
            '06-23', // Võidupüha
            '06-24', // Jaanipäev
            '08-20', // Taasiseseisvumispäev
            '12-24', // Jõululaupäev
            '12-25', // Esimene jõulupüha
            '12-26', // Teine jõulupüha
        );
    
        $date_formatted = date('m-d', strtotime($date));
        return in_array($date_formatted, $holidays);
    }

}
