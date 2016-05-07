<?php
/**
 * @package     Joomla.Site
 *
 * @copyright   Copyright (C) 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_PLATFORM') or die;
JFormHelper::loadFieldClass('list');
class JFormFieldItems extends JFormFieldList{
	protected $type = 'items';

	protected function getInput(){
		$document = JFactory::getDocument();
		$jsPath   = JURI::root(true) . '/modules/mod_jewishdate';

		$joomlaVersion = new JVersion();

		if($joomlaVersion->isCompatible('3')){
			JHtml::_('jquery.ui', array('core', 'sortable'));
		}else{
			$document->addStyleSheet($jsPath . '/css/chosen.min.css');
			$document->addScript($jsPath . '/js/jquery.min.js');
			$document->addScript($jsPath . '/js/jquery-noconflict.js');
			$document->addScript($jsPath . '/js/chosen.jquery.min.js');
			$document->addScript($jsPath . '/js/jquery-ui.min.js');
		}

		$document->addScript($jsPath . '/js/jquery-chosen-sortable.min.js');

		$script = 'jQuery(function(){jQuery(".chzn-sortable").chosen().chosenSortable();});';
		$document->addScriptDeclaration($script);

		if(!is_array($this->value)){
			$this->value = explode(',',$this->value);
		}

		$html = parent::getInput();
		
		return $html;
	}

	protected function getOptions(){
		$value = $this->value;

		$options = array();
		$options['clock']   = JHtml::_('select.option', 'clock', JText::_('JEWISHDATE_CLOCK'));
		$options['day']       = JHtml::_('select.option', 'day', JText::_('JEWISHDATE_DAY_NAME'));
		$options['jregorian'] = JHtml::_('select.option', 'jregorian', JText::_('JEWISHDATE_GREGORIAN_DATE'));
		$options['jewish']     = JHtml::_('select.option', 'jewish', JText::_('JEWISHDATE_JEWISH_DATE'));

		$options = $this->sort_array_from_array($options,$value);

		return $options;
	}

	function sort_array_from_array($array, $orderArray){
		$ordered = array();
		foreach($orderArray as $key => $value){
			if(array_key_exists($value,$array)){
				$ordered[] = $array[$value];
				unset($array[$value]);
			}
		}
		return $ordered + $array;
	}
}
