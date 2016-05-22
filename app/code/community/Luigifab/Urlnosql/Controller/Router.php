<?php
/**
 * Created V/26/06/2015
 * Updated S/23/04/2016
 * Version 10
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

class Luigifab_Urlnosql_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

	public function initControllerRouters($observer) {

		if (Mage::getStoreConfig('urlnosql/general/enabled') === '1') {
			$router = new Luigifab_Urlnosql_Controller_Router();
			$this->match(Mage::app()->getRequest());
		}
	}

	public function match(Zend_Controller_Request_Http $request) {

		$params = trim($request->getPathInfo(), '/');
		$params = explode('/', $params);

		if (count($params) === 1) {

			// Array ( [0] => 300003-adfghj.html )
			$params = $params[0];

			// recherche de l'id dans l'url
			// l'id étant l'id du produit dans Magento :)
			preg_match('#^([0-9]+)[a-z0-9\-]*'.Mage::helper('catalog/product')->getProductUrlSuffix().'$#', $params, $id);

			if (isset($id[1]) && is_numeric($id[1])) {

				// Array ( [0] => 300003-adfghj.html [1] => 300003 )
				$id = intval($id[1]);

				$product = Mage::getModel('catalog/product')->load($id);
				$oldids = Mage::getStoreConfig('urlnosql/general/oldids');

				// => redirige le produit associé vers le produit parent
				if ($product->getData('visibility') == Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) {

					$parentIds = array_merge(
						Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($id),
						Mage::getResourceSingleton('catalog/product_link')->getParentIdsByChild($id, Mage_Catalog_Model_Product_Link::LINK_TYPE_GROUPED)
					);

					if (isset($parentIds[0]) && is_numeric($parentIds[0])) {
						$product->load($parentIds[0]);
						header('Location: '.$product->getProductUrl(), true, 301);
						exit(0);
					}
				}
				// => affichage du produit
				else if (strpos($product->getProductUrl(), '/'.$params) !== false) {
					$request->setModuleName('catalog')->setControllerName('product')->setActionName('view')->setParam('id', $id);
					$request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $params);
					return true;
				}
				// => redirige le produit vers la bonne url
				else if ($id > 0) {
					header('Location: '.$product->getProductUrl(), true, 301);
					exit(0);
				}

				// si le produit n'existe pas ou plus (plutôt plus que pas...)
				// on recherche le bon produit dans l'attribut oldids
				// => redirige le produit vers la bonne url
				if (strlen($oldids) > 0) {

					$product = Mage::getResourceModel('catalog/product_collection');
					$product->addAttributeToFilter($oldids, array('regexp' => '[[:<:]]'.$id.'[[:>:]]'));
					$product = $product->getFirstItem();

					if ($product->getId() > 0) {
						header('Location: '.$product->getProductUrl(), true, 301);
						exit(0);
					}
				}
			}
		}

		return false;
	}
}