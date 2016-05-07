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

//This is the parameter we get from our xml file above
$params->id = $module->id;
$items = $params->get('items');
foreach($items as $item)
{
	switch ($item)
	{
		case 'clock':
			$clock_time = ModJewishDateHelper::getClockTime($params);
			break;

		case 'day':
			$day_name =  ModJewishDateHelper::getDayName($params);
			break;
			
		case 'jregorian':
			$gregorian_date = ModJewishDateHelper::getGregorianDate($params);
			break;

		case 'jewish':
			$jewish_date = ModJewishDateHelper::getJewishDate($params);
			break;

	}
}
//Returns the path of the layout file
require JModuleHelper::getLayoutPath('mod_jewishdate', $params->get('layout', 'default'));