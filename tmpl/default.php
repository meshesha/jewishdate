<?php
/*
 * Module Joomla! 3.x Native Component
 * @version: 1.0
 * @copyright   Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author: Tady Meshesha 
 * @link:http://he-il.joomla.com/jewishdate
 */
 defined( '_JEXEC' ) or die( 'Restricted access' );
 
$document = JFactory::getDocument();

if($params->get('css'))
{
	$document->addStyleDeclaration($params->get('css'));
}

?>

<div class="mod_jewishdate<?php echo $params->get('class_sfx') ?>" id="jewishdate_<?php echo $module->id; ?>">
<?php
	foreach($items as $item){
		switch ($item)
		{
			case 'clock':
				echo '<div class="jwishdate_clock" id="jewishClock_' . $params->id . '"></div>';
				require JPATH_ROOT . '/modules/mod_jewishdate/liveclock.php';
				break;
			case 'day':
				echo '<div class="jwishdate_dayname" id="jwishdate_dayname_' . $params->id . '">' . $day_name . '</div>';
				break;
			case 'jregorian':
				echo '<div class="gregorian_calander" id="gregorian_calander_' . $params->id . '">' . $gregorian_date . '</div>';
				break;
			case 'jewish':
				echo '<div class="jewish_calander" id="jewish_calander_' . $params->id . '">' . $jewish_date . '</div>';
				break;
		}
	}
?>
</div>