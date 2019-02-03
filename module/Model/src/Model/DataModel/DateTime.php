<?php

namespace Model\DataModel;

class DateTime
{

	public static $timeZoneStr;

	public static function setTimeZone() 
	{
		if (DateTime::$timeZoneStr) {
			return;
		} else {
			DateTime::$timeZoneStr = "Europe/Madrid";
			date_default_timezone_set(DateTime::$timeZoneStr);
		}
	}

    public static function strToTime( $str, $dbFormat = FALSE )
    {
        DateTime::setTimeZone();

        $timeValue = strtotime($str);

        if ($dbFormat) {
            return DateTime::timeToDbValue($timeValue);
        }

        return $timeValue;
    }

    public static function formatTimeVal( $format, $timeValue = 0 ) 
    {
        DateTime::setTimeZone();

        if ($timeValue==0) {
            $timeValue = strtotime("now");
        }

        return date($format, $timeValue);
    }

    public static function getCurrentTime()
    {
        return DateTime::strToTime("now");
    }

    public static function dbToTimeValue($dbDateTime) 
    {
        return DateTime::strToTime($dbDateTime);
    }

    public static function timeToDbValue($timeValue) 
    {
        return DateTime::formatTimeVal("Y-m-d H:i:s", $timeValue );
    }

    public static function currentTimeToDbValue() 
    {
        return DateTime::timeToDbValue(DateTime::getCurrentTime());   
    }    

    public static function letraDiaSem( $timeValue ) 
    {
        $idx = DateTime::formatTimeVal("w",$timeValue);
        
        $arrVal = array('D','L','M','X','J','V','S');
        
        return $arrVal[$idx];
    }

	public static function getDateFromTimeValue($timeValue, $isEnglishFormat = false, $withTime = false)
    {
        $strFormat = "";
    	if ($isEnglishFormat) {
            $strFormat = ($withTime)? "m/d/Y H:i" : "m/d/Y";
    	} else {
            $strFormat = ($withTime)? "d/m/Y H:i" : "d/m/Y";
    	}
        return date($strFormat, $timeValue);        
   	}

    public static function formatDateFromDbValue($dbDateTime, $isEnglishFormat = false, $withTime = false)
    {
        $timeValue = DateTime::dbToTimeValue($dbDateTime);
        return DateTime::getDateFromTimeValue($timeValue,$isEnglishFormat,$withTime);
    }

    public static function tvToDateStringInDays( $timeValue, $langCaption = "ES" )
    {
        $today = DateTime::getCurrentTime();

        if ($timeValue>$today) {
            // FIXME: no hay cadenas para "futuro"
            return '';
        }

        $strResult = '';

        // Diferencia fechas en días:
        $daysDiff = (int) ( ( ((int)$today) - ((int)$timeValue) ) / (24*3600) );

        if ( $daysDiff == 0 ) {
            return 'hoy';
        } 
        if ( $daysDiff == 1 ) {
            return 'ayer';
        } 
        if ( $daysDiff <= 7 ) {
            return ( ( (string) $daysDiff ) . ' días' );
        }

        // Diferencia fechas en semanas:
        $weeksDiff = (int) ( ( ((int)$today) - ((int)$timeValue) ) / (24*3600*7) );
        if ( $weeksDiff <= 4 ) {
            $plural = ($weeksDiff>1)? 's' : '';
            return ( ( (string) $weeksDiff ) . ' semana' . $plural);
        }

        $todaysYear  = (int) DateTime::formatTimeVal("Y", $today );
        $todaysMonth = ($todaysYear * 12) + ((int) DateTime::formatTimeVal("m", $today ));
        $tvYear  = (int) DateTime::formatTimeVal("Y", $timeValue );
        $tvMonth = ($tvYear * 12) + ((int) DateTime::formatTimeVal("m", $timeValue ));

        // Diferencia fechas en meses:
        $monthsDiff = $todaysMonth - $tvMonth;
        if ( $monthsDiff <= 12 ) {
            $plural = ($monthsDiff>1)? 'es' : '';
            return ( ( (string) $monthsDiff ) . ' mes' . $plural );
        }

        // Diferencia fechas en años:
        $yearsDiff = $todaysYear - $tvYear;
        $plural = ($yearsDiff>1)? 's' : '';
        return ( ( (string) $yearsDiff ) . ' año' . $plural );
    }

    public static function csvToTimevalue( $csvDateValue )
    {
        DateTime::setTimeZone();

        if ($csvDateValue>0) {
            $year           = $csvDateValue / 10000;
            $csvDateValue   = $csvDateValue % 10000;
            $month          = $csvDateValue / 100;
            $day            = $csvDateValue % 100;
            $csvDateValue   = mktime(0,0,0,$month,$day,$year);
        } else {
            $csvDateValue   = 0;
        }

        return $csvDateValue;
    }

    public static function csvTimeToDbValue( $csvDateValue )
    {
        return DateTime::timeToDbValue( DateTime::csvToTimevalue( $csvDateValue ) );
    }

    public static function cleanDateInput( $strInput )
    {
        DateTime::setTimeZone();
        return str_replace("/","-",$strInput);
    }

    public static function reverseDateInput( $strInput )
    {
        $separator = ( strpos($strInput,"/") > 0 )? "/" : "-";

        $invert = explode($separator, $strInput);

        if ( empty($invert[0]) || empty($invert[1]) || empty($invert[2]) ) {
            return "0";
        }

        return $invert[2]."-".$invert[1]."-".$invert[0];
    }

    public static function escapeDbDate( $dbDate, $isEnglishFormat = 0 )
    {
        if (!empty($dbDate)) {
            $timeValue = DateTime::dbToTimeValue( $dbDate );
            if ($timeValue>0) {
                return DateTime::getDateFromTimeValue($timeValue, $isEnglishFormat);
            }
        }
        return '';        
    }

    public static function datetimeDbToString($datetime)
    {
        DateTime::setTimeZone();
        $date = strtotime($datetime);
        return date('d-m-Y', $date);
    }

    public static function datetimeDbToStringWithHour($datetime)
    {
        DateTime::setTimeZone("Europe/Madrid");
        $date = strtotime($datetime);
        return date('d/m/Y - H:i:s', $date);
    }

    public static function getMonthFromNumber($month, $lang, $self){

        $viewHelperManager = $self->getServiceLocator()->get('ViewHelperManager');
        $translator = $viewHelperManager->get('translate');


        $stringMonth = '';
        switch($month){
            case "01": {
                $stringMonth = $translator('Enero', $lang);
                break;
            }
            case "02": {
                $stringMonth = $translator('Febrero', $lang);
                break;
            }
            case "03": {
                $stringMonth = $translator('Marzo', $lang);
                break;
            }
            case "04": {
                $stringMonth = $translator('Abril', $lang);
                break;
            }
            case "05": {
                $stringMonth = $translator('Mayo', $lang);
                break;
            }
            case "06": {
                $stringMonth = $translator('Junio', $lang);
                break;
            }
            case "07": {
                $stringMonth = $translator('Julio', $lang);
                break;
            }
            case "08": {
                $stringMonth = $translator('Agosto', $lang);
                break;
            }
            case "09": {
                $stringMonth = $translator('Septiembre', $lang);
                break;
            }
            case "10": {
                $stringMonth = $translator('Octubre', $lang);
                break;
            }
            case "11": {
                $stringMonth = $translator('Noviembre', $lang);
                break;
            }
            case "12": {
                $stringMonth = $translator('Diciembre', $lang);
                break;
            }
        }
        return $stringMonth;
    }

    public static function firstDayOfMonth(){
        $month = DateTime::formatTimeVal("m");
        $year = DateTime::formatTimeVal("Y");
        $initialDate = date("Y-m-d", mktime(0,0,0,$month,1,$year));

        return $initialDate;
    }

    public static function lastDayOfMonth(){
        $month = DateTime::formatTimeVal("m");
        $year = DateTime::formatTimeVal("Y");
        $lastDayMonth = date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        $finalDate = date("Y-m-d", mktime(0,0,0,$month,$lastDayMonth,$year));

        return $finalDate;
    }


    public static function intervalDaysBetweenDates($date_i, $date_f){
        $dias	= (strtotime($date_i)-strtotime($date_f))/86400;
        $dias 	= abs($dias); $dias = floor($dias);
        return $dias;
    }


}

DateTime::$timeZoneStr = NULL;

