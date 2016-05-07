<?php
/*
Module Joomla! 3.x Native Component
@version: 1.0
@author: Tady Meshesha 
@link:http://he-il.joomla.com/jewishdate
@license GPL2 
*/
defined( '_JEXEC' ) or die;
 

abstract class ModJewishDateHelper
{
	// get Clock param
	public static function getClockTime($params){
		
		$myclock = new stdClass();

		$myclock->offset       = $params->get('timezoneoffset', 'UTC');
		$myclock->format       = $params->get('clock_format', 't12');
		$myclock->seconds      = $params->get('clock_seconds', 1);
		$myclock->leadingZeros = $params->get('leadingZeros', 1);
		$myclock->source       = $params->get('clock_source', 'client');

		date_default_timezone_set($myclock->offset);
		$myclock->time = date("F d, Y H:i:s");

		return $myclock;
		
	}	
	// Gregorian Date
	public static function getGregorianDate($params){
		$translate = $params->get('gregorian_date_language', 1);
		$format    = $params->get('gregorian_date_format', JText::_('DATE_FORMAT_LC1'));
		$offset    = $params->get('timezoneoffset', 'UTC');

		$date      = new JDate('now', $offset);		
		$gregorian_date = $date->calendar($format, true, $translate);

		$dir = $translate ? '' : 'dir="ltr"';

		return '<span ' . $dir . '>' . $gregorian_date . '</span>';
	}
	public static function getJewishDate($params){
		$offset = $params->get('timezoneoffset', 'UTC');
		date_default_timezone_set($offset);
		$lang = $params->get('jewish_date_language', 1);
		$viewJyear = $params->get('jewish_yaer', 1);

		$tz_date =   date('d/m/Y'); 
		$tz_date_ary = explode("/",$tz_date);

		$today = $tz_date_ary[0];
		$now_month = $tz_date_ary[1];
		$now_year = $tz_date_ary[2];
		$jdNumber = gregoriantojd((int)$now_month, (int)$today, (int)$now_year);
		$hebjewishDate = jdtojewish($jdNumber, true, CAL_JEWISH_ADD_GERESHAYIM);
		$hebjewishDate_ary = explode(" ",$hebjewishDate);
		$hebjewishDate_ary_len = count($hebjewishDate_ary);
		$jewishYear_heb = $hebjewishDate_ary[$hebjewishDate_ary_len-1];
		$jewishDate = jdtojewish($jdNumber);
		list($jewishMonth_num, $jewishDay_num, $jewishYear_num) = explode('/', $jewishDate);
		$jewishMonthName = self::getJewishMonthName($jewishMonth_num, $jewishYear_num, $lang);
		$jewishDay_heb_ary = array( "א'", "ב'", "ג'", "ד'", "ה'", "ו'", "ז'", "ח'", "ט'", "'י", 'י"א', 'י"ב',
									'י"ג', 'י"ד', 'ט"ו', 'ט"ז', 'י"ז', 'י"ח', 'י"ט', 'כ', 'כ"א', 
									'כ"ב', 'כ"ג', 'כ"ד', 'כ"ה', 'כ"ו', 'כ"ז', 'כ"ח', 'כ"ט', 'ל' );
		$jewishDay_heb = $jewishDay_heb_ary[$jewishDay_num-1];
		$jewishYear_heb = iconv( 'WINDOWS-1255', 'UTF-8', $jewishYear_heb );
		if($lang==1){ //Heb
			if ($viewJyear==1){ //display jewish year
				return '<span dir="rtl">'.$jewishDay_heb.' '.$jewishMonthName.' '.$jewishYear_heb.'</span>';
			}else{ // don't display jewish year
				return '<span dir="rtl">'.$jewishDay_heb.' '.$jewishMonthName.'</span>';
			}
		}else{ //Eng
			if ($viewJyear==1){ //display jewish year
				return '<span dir="ltr">'.$jewishDay_num.' '.$jewishMonthName.' '.$jewishYear_num.'</span>';
			}else{  // don't display jewish year
				return '<span dir="ltr">'.$jewishDay_num.' '.$jewishMonthName.'</span>';
			}
		}
		
	}
	public static function isJewishLeapYear($year) {
		if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
		  $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
		  $year % 19 == 17)
		return true;
		else
		return false;
	}

	public static function getJewishMonthName($jewishMonth, $jewishYear, $lang) {
		$en_jewishMonthNamesLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
									"Shevat", "Adar I", "Adar II", "Nisan",
									"Iyar", "Sivan", "Tammuz", "Av", "Elul");
		$en_jewishMonthNamesNonLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
									   "Shevat", "", "Adar", "Nisan",
									   "Iyar", "Sivan", "Tammuz", "Av", "Elul");
		$he_jewishMonthNamesLeap = array("בתשרי", "במרחשוון", "בכסלו", "בטבת",
									"בשבט", "באדר א'", "באדר ב'", "בניסן",
									"באייר", "בסיוון", "בתמוז", "באב", "באלול");
		$he_jewishMonthNamesNonLeap = array("בתשרי", "במרחשוון", "בכסלו", "בטבת",
									"בשבט", "", "באדר", "בניסן",
									"באייר", "בסיוון", "בתמוז", "באב", "באלול");
		if($lang==0){	//Eng						
			if (self::isJewishLeapYear($jewishYear)){
				return $en_jewishMonthNamesLeap[$jewishMonth-1];
			}else{
				return $en_jewishMonthNamesNonLeap[$jewishMonth-1];
			}
		}else{ //$lang=='1' > Heb
			if (self::isJewishLeapYear($jewishYear)){
				return $he_jewishMonthNamesLeap[$jewishMonth-1];
			}else{
				return $he_jewishMonthNamesNonLeap[$jewishMonth-1];
			}			
		}
	}
	public static function getDayName($params){
		$offset = $params->get('timezoneoffset', 'UTC');
		$lang = $params->get('day_language', 1);
		$dayformat = $params->get('dayname_format', 1);
		$heb_day_ary = array('ראשון','שני','שלישי','רביעי','חמישי','שיש','שבת');
		$heb_day_ary_short = array("א'","ב'","ג'","ד'","ה'","ו'","ש'");
		$eng_day_ary = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$eng_day_ary_short = array('Sun.','Mon.','Tue.','Wed.','Thu.','Fri.','Sat.');
		$eng_day_ary_very_short = array('Su.','Mo.','Tu.','We.','Th.','Fr.','Sa.');//not in use in this time
		$today    = new JDate('now', $offset);
		$day_num = $today->calendar('w',true);		
		if($lang==1){ //Heb
			if($dayformat==1){ //full name
				$day_name =  '<span dir="rtl">'.$heb_day_ary[$day_num].'</span>';
			}else{ //short name
				$day_name =  '<span dir="rtl">'.$heb_day_ary_short[$day_num].'</span>';
			}	
		}else{ //Eng
			if($dayformat==1){  //full name
				$day_name = '<span dir="ltr">'.$eng_day_ary[$day_num].'</span>';
			}else{ //short name
				$day_name = '<span dir="ltr">'.$eng_day_ary_short[$day_num].'</span>';
			}			
		}
		return 	$day_name;
	}	
}


