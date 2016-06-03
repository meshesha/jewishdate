<?php
/*
Module Joomla! 3.x Native Component
@version: 1.1
@author: Tady Meshesha 
@link:http://he-il.joomla.com/jewishdate
@license GPL2 
*/
defined( '_JEXEC' ) or die;
 

class modJewishdateHelper
{
	private static $savelocaltz;

	public static function getAjax(){
		jimport('joomla.application.module.helper');
		$input  = JFactory::getApplication()->input;
		
		$module = JModuleHelper::getModule('mod_jewishdate', $input->getString('title'));
		$params = new JRegistry();
		$params->loadString($module->params);
		
		$get_ajax_data = $input->get('data');
		
		$get_ajax_data_ary = explode("SPRETOR",$get_ajax_data);
		
		$localtz_data = $get_ajax_data_ary[0];
		$module_id = $get_ajax_data_ary[1];
		//and i don't use php Session becouse the data seved in web server.
		
		$chek_localtz_dash = strpos($localtz_data,"%2F");
		if ($chek_localtz_dash!=false){
			$localtz_data = str_replace("%2F","/",$localtz_data);
		}else{
			$localtz_data = str_replace("2F","/",$localtz_data);
		}
		
		/*
		$chek_localtz_pls = strpos($localtz_data,"%2B");
		if ($chek_localtz_pls!=false){
			$localtz_data = str_replace("%2B","+",$localtz_data);
		}else{
			$chek_localtz_pls2 = strpos($localtz_data,"2B");
			if ($chek_localtz_pls2!=false){
				$localtz_data = str_replace("2B","+",$localtz_data);
			}
		}
		$chek_localtz_mns = strpos($localtz_data,"%2D");
		if ($chek_localtz_mns!=false){
			$localtz_data = str_replace("%2D","-",$localtz_data);
		}else{
			$chek_localtz_mns2 = strpos($localtz_data,"2D");
			if ($chek_localtz_mns2!=false){
				$localtz_data = str_replace("2D","-",$localtz_data);
			}
		}*/		
		$mydata = new stdClass();
		//general params
		$offset    = $get_ajax_data_ary[3];//$params->get('timezoneoffset', 'UTC');
		$timeSource = $get_ajax_data_ary[2];//$params->get('clock_source', 'client');		
		$mydata->timezonesorce = $timeSource;
		if($timeSource=="client"){
			//if($localtz_data=="") $localtz_data="UTC";
			$mydata->timezone = $localtz_data;
			$choosen_tz = $localtz_data;
		}else if($timeSource=="gmt"){
			$mydata->timezone = $offset;
			$choosen_tz = $offset;
		}
		//Clock params
		$mydata->clockformat = $get_ajax_data_ary[4];//$params->get('clock_format', 't12');
		$mydata->clockseconds = $get_ajax_data_ary[5];//$params->get('clock_seconds', 1);
		$mydata->clockleadingZeros = $get_ajax_data_ary[6];//$params->get('leadingZeros', 1);
		$mydata->clocktime = self::getClockTime($choosen_tz);
		$mydata->clock_id = $module_id;
		$clocktime = self::getClockTime($choosen_tz);
		//Gregorian Date params
		$gregoriantranslate = $get_ajax_data_ary[7];//$params->get('gregorian_date_language', 1);
		$gregorianformat    = str_replace("PACEE"," ",$get_ajax_data_ary[8]);//$params->get('gregorian_date_format', JText::_('DATE_FORMAT_LC2'));		
		$gregoriandate = self::getGregorianDate($gregoriantranslate,$gregorianformat,$choosen_tz);
		
		//Jewish Date params
		$jewishlang = $get_ajax_data_ary[9];//$params->get('jewish_date_language', 1);
		$jewishviewJyear = $get_ajax_data_ary[10];//$params->get('jewish_yaer', 1);
		$jewishdate = self::getJewishDate($jewishlang,$jewishviewJyear,$choosen_tz);
		
		//Day Name params
		$daylang = $get_ajax_data_ary[11];//$params->get('day_language', 1);
		$dayformat = $get_ajax_data_ary[12];//$params->get('dayname_format', 1);				
		$dayname = self::getDayName($daylang,$dayformat,$choosen_tz);
		
		$items = $params->get('items');
		$returndata="";
		//$returndata.='<div class="jwishdate_tz" id="jwishdate_tz_' . $module_id . '">'.$choosen_tz.'</div>';
		foreach($items as $item)
		{
			switch ($item)
			{
				case 'clock':
					$clock_time = $mydata;
					$returndata .= '<div class="jwishdate_clock" id="jewishClock_' . $module_id . '"></div>';
					require_once JPATH_ROOT . '/modules/mod_jewishdate/liveclock.php';
					break;

				case 'day':
					$returndata .= '<div class="jwishdate_dayname" id="jwishdate_dayname_' . $module_id . '">' . $dayname . '</div>';
					break;
					
				case 'jregorian':
					$returndata .= '<div class="gregorian_calander" id="gregorian_calander_' . $module_id . '">' . $gregoriandate . '</div>';
					break;

				case 'jewish':
					$returndata .= '<div class="jewish_calander" id="jewish_calander_' . $module_id . '">' . $jewishdate . '</div>';
					break;

			}
		}		
		//return $mydata;
		return $returndata;
	}
	public static function getClockTime($tz){
		date_default_timezone_set($tz);
		$myclock = date("F d, Y H:i:s");
		return $myclock;	
	}	
	// Gregorian Date
	public static function getGregorianDate($translate,$format,$tz){
		$date      = new JDate('now', $tz);
		$gregorian_date = $date->calendar($format, true, $translate);
		$dir = $translate ? '' : 'dir="ltr"';
		return '<span ' . $dir . '>' . $gregorian_date . '</span>';
	}
	public static function getJewishDate($lang,$viewJyear,$tz){
		date_default_timezone_set($tz);
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
		$jewishDay_heb_ary = array( "א'", "ב'", "ג'", "ד'", "ה'", "ו'", "ז'", "ח'", "ט'", "י'", 'י"א', 'י"ב',
									'י"ג', 'י"ד', 'ט"ו', 'ט"ז', 'י"ז', 'י"ח', 'י"ט', "כ'", 'כ"א', 
									'כ"ב', 'כ"ג', 'כ"ד', 'כ"ה', 'כ"ו', 'כ"ז', 'כ"ח', 'כ"ט', "ל'" );
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
	public static function getDayName($lang,$dayformat,$tz){
		
		$heb_day_ary = array('ראשון','שני','שלישי','רביעי','חמישי','שיש','שבת');
		$heb_day_ary_short = array("א'","ב'","ג'","ד'","ה'","ו'","ש'");
		$eng_day_ary = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$eng_day_ary_short = array('Sun.','Mon.','Tue.','Wed.','Thu.','Fri.','Sat.');
		$eng_day_ary_very_short = array('Su.','Mo.','Tu.','We.','Th.','Fr.','Sa.');//not in use in this time
		
		$today      = new JDate('now', $tz);
		
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


