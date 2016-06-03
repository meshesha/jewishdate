<?php
/*
Module Joomla! 3.x Native Component
@version: 1.0
@author: Tady Meshesha 
@link:http://he-il.joomla.com/jewishdate
@license GPL2 
 */
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

//general params
$module_id = $module->id;
$module_title = $module->title;
$getoffset    = $params->get('timezoneoffset', 'UTC');
$timeSource = $params->get('clock_source', 'client');
//Clock params
$clockformat = $params->get('clock_format', 't12');
$clockseconds = $params->get('clock_seconds', 1);
$clockleadingZeros = $params->get('leadingZeros', 1);
//Gregorian Date params
$gregolang = $params->get('gregorian_date_language', 1);
$gregoformat    = $params->get('gregorian_date_format', JText::_('DATE_FORMAT_LC2'));
$gregoformat = str_replace(" ","PACEE",$gregoformat);
//Jewish Date params
$jewishdatelang = $params->get('jewish_date_language', 1);
$jewishdateviewJyear = $params->get('jewish_yaer', 1);
//Day Name params
$daynamelang = $params->get('day_language', 1);
$daynameformat = $params->get('dayname_format', 1);	

$document = JFactory::getDocument();
$jsPath   = JURI::root(true) . '/modules/mod_jewishdate';
$document->addScript($jsPath . '/js/jstz.min.js');
$document->addScriptDeclaration("
jQuery(document).ready(function(){
	//window.setInterval(function(){
		var tz = jstz.determine();// Determines the time zone of the browser client
		var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
		var alldata = timezone+'SPRETOR$module_id'+'SPRETOR$timeSource'+'SPRETOR$getoffset';
		alldata +='SPRETOR$clockformat'+'SPRETOR$clockseconds'+'SPRETOR$clockleadingZeros';
		alldata +='SPRETOR$gregolang'+'SPRETOR$gregoformat';
		alldata +='SPRETOR$jewishdatelang'+'SPRETOR$jewishdateviewJyear';
		alldata +='SPRETOR$daynamelang'+'SPRETOR$daynameformat';
		var request = {
					'option' : 'com_ajax',
					'module' : 'jewishdate',
					'title'	 : '$module_title',
					'data'   : encodeURIComponent(alldata), 
					'format' : 'raw'
				};
		jQuery.ajax({
			type   : 'POST',
			data   : request,
			success: function (response) {
				jQuery('.mod_jewishdate_$module_id').html(response);
			}

		});
		//return false;
	//},1000);
});
");


//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_jewishdate', $params->get('layout', 'default'));