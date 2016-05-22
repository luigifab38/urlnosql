<?php
/**
 * Created S/22/08/2015
 * Updated S/22/08/2015
 * Version 2
 *
 * Copyright 2015-2016 | Fabrice Creuzot <fabrice.creuzot~label-park~com>, Fabrice Creuzot (luigifab) <code~luigifab~info>
 * https://redmine.luigifab.info/projects/magento/wiki/urlnosql
 *
 * This program is free software, you can redistribute it or modify
 * it under the terms of the GNU General Public License (GPL) as published
 * by the free software foundation, either version 2 of the license, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but without any warranty, without even the implied warranty of
 * merchantability or fitness for a particular purpose. See the
 * GNU General Public License (GPL) for more details.
 */

class Luigifab_Urlnosql_Block_Adminhtml_Config_Comment extends Mage_Adminhtml_Block_System_Config_Form_Field {

	public function render(Varien_Data_Form_Element_Abstract $element) {

		$html = parent::render($element);
		$html = str_replace('{{', '<a href="'.$this->getUrl('*/*/edit', array('section' => 'urlnosql')).'">', $html);
		$html = str_replace('}}', '</a>', $html);

		if (Mage::getStoreConfig('urlnosql/general/enabled') === '1')
			$html = str_replace('<select', '<select disabled="disabled" ', $html);

		return $html;
	}
}