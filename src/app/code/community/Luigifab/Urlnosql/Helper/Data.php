<?php
/**
 * Created V/26/06/2015
 * Updated J/19/11/2020
 *
 * Copyright 2015-2020 | Fabrice Creuzot (luigifab) <code~luigifab~fr>
 * Copyright 2015-2016 | Fabrice Creuzot <fabrice.creuzot~label-park~com>
 * https://www.luigifab.fr/openmage/urlnosql
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

class Luigifab_Urlnosql_Helper_Data extends Mage_Core_Helper_Abstract {

	public function getVersion() {
		return (string) Mage::getConfig()->getModuleConfig('Luigifab_Urlnosql')->version;
	}

	public function _(string $data, ...$values) {
		return (mb_stripos($txt = $this->__(' '.$data, ...$values), ' ') === 0) ? $this->__($data, ...$values) : $txt;
	}

	public function escapeEntities($data, bool $quotes = false) {
		return htmlspecialchars($data, $quotes ? ENT_SUBSTITUTE | ENT_COMPAT : ENT_SUBSTITUTE | ENT_NOQUOTES);
	}

	public function normalizeChars(string $locale, string $data) {

		// https://github.com/WordPress/WordPress/blob/master/wp-includes/formatting.php#L1601
		// dd3ad3a 14/09/2019
		$chars = [
			// Decompositions for Latin-1 Supplement
			'ª' => 'a', 'º' => 'o', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE',
			'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Ú' => 'U',
			'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 's', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
			'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i',
			'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
			'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y',
			'Ø' => 'O',
			// Decompositions for Latin Extended-A
			'Ā' => 'A', 'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ć' => 'C', 'ć' => 'c', 'Ĉ' => 'C',
			'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c', 'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D', 'đ' => 'd',
			'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E', 'ĕ' => 'e', 'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E', 'ę' => 'e', 'Ě' => 'E',
			'ě' => 'e', 'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G', 'ġ' => 'g', 'Ģ' => 'G', 'ģ' => 'g',
			'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ĩ' => 'I', 'ĩ' => 'i', 'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I',
			'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I', 'ı' => 'i', 'Ĳ' => 'IJ', 'ĳ' => 'ij', 'Ĵ' => 'J', 'ĵ' => 'j',
			'Ķ' => 'K', 'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'l', 'Ļ' => 'L', 'ļ' => 'l', 'Ľ' => 'L', 'ľ' => 'l',
			'Ŀ' => 'L', 'ŀ' => 'l', 'Ł' => 'L', 'ł' => 'l', 'Ń' => 'N', 'ń' => 'n', 'Ņ' => 'N', 'ņ' => 'n', 'Ň' => 'N',
			'ň' => 'n', 'ŉ' => 'n', 'Ŋ' => 'N', 'ŋ' => 'n', 'Ō' => 'O', 'ō' => 'o', 'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O',
			'ő' => 'o', 'Œ' => 'OE', 'œ' => 'oe', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r', 'Ř' => 'R', 'ř' => 'r',
			'Ś' => 'S', 'ś' => 's', 'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's', 'Š' => 'S', 'š' => 's', 'Ţ' => 'T',
			'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't', 'Ũ' => 'U', 'ũ' => 'u', 'Ū' => 'U', 'ū' => 'u',
			'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u', 'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W',
			'ŵ' => 'w', 'Ŷ' => 'Y', 'ŷ' => 'y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z',
			'ž' => 'z', 'ſ' => 's',
			// Decompositions for Latin Extended-B
			'Ș' => 'S', 'ș' => 's', 'Ț' => 'T', 'ț' => 't',
			// Vowels with diacritic (Vietnamese)
			// unmarked
			'Ơ' => 'O', 'ơ' => 'o', 'Ư' => 'U', 'ư' => 'u',
			// grave accent
			'Ầ' => 'A', 'ầ' => 'a', 'Ằ' => 'A', 'ằ' => 'a', 'Ề' => 'E', 'ề' => 'e', 'Ồ' => 'O', 'ồ' => 'o', 'Ờ' => 'O',
			'ờ' => 'o', 'Ừ' => 'U', 'ừ' => 'u', 'Ỳ' => 'Y', 'ỳ' => 'y',
			// hook
			'Ả' => 'A', 'ả' => 'a', 'Ẩ' => 'A', 'ẩ' => 'a', 'Ẳ' => 'A', 'ẳ' => 'a', 'Ẻ' => 'E', 'ẻ' => 'e', 'Ể' => 'E',
			'ể' => 'e', 'Ỉ' => 'I', 'ỉ' => 'i', 'Ỏ' => 'O', 'ỏ' => 'o', 'Ổ' => 'O', 'ổ' => 'o', 'Ở' => 'O', 'ở' => 'o',
			'Ủ' => 'U', 'ủ' => 'u', 'Ử' => 'U', 'ử' => 'u', 'Ỷ' => 'Y', 'ỷ' => 'y',
			// tilde
			'Ẫ' => 'A', 'ẫ' => 'a', 'Ẵ' => 'A', 'ẵ' => 'a', 'Ẽ' => 'E', 'ẽ' => 'e', 'Ễ' => 'E', 'ễ' => 'e', 'Ỗ' => 'O',
			'ỗ' => 'o', 'Ỡ' => 'O', 'ỡ' => 'o', 'Ữ' => 'U', 'ữ' => 'u', 'Ỹ' => 'Y', 'ỹ' => 'y',
			// acute accent
			'Ấ' => 'A', 'ấ' => 'a', 'Ắ' => 'A', 'ắ' => 'a', 'Ế' => 'E', 'ế' => 'e', 'Ố' => 'O', 'ố' => 'o', 'Ớ' => 'O',
			'ớ' => 'o', 'Ứ' => 'U', 'ứ' => 'u',
			// dot below
			'Ạ' => 'A', 'ạ' => 'a', 'Ậ' => 'A', 'ậ' => 'a', 'Ặ' => 'A', 'ặ' => 'a', 'Ẹ' => 'E', 'ẹ' => 'e', 'Ệ' => 'E',
			'ệ' => 'e', 'Ị' => 'I', 'ị' => 'i', 'Ọ' => 'O', 'ọ' => 'o', 'Ộ' => 'O', 'ộ' => 'o', 'Ợ' => 'O', 'ợ' => 'o',
			'Ụ' => 'U', 'ụ' => 'u', 'Ự' => 'U', 'ự' => 'u', 'Ỵ' => 'Y', 'ỵ' => 'y',
			// Vowels with diacritic (Chinese, Hanyu Pinyin)
			'ɑ' => 'a',
			// macron
			'Ǖ' => 'U', 'ǖ' => 'u',
			// acute accent
			'Ǘ' => 'U', 'ǘ' => 'u',
			// caron
			'Ǎ' => 'A', 'ǎ' => 'a', 'Ǐ' => 'I', 'ǐ' => 'i', 'Ǒ' => 'O', 'ǒ' => 'o', 'Ǔ' => 'U', 'ǔ' => 'u', 'Ǚ' => 'U',
			'ǚ' => 'u',
			// grave accent
			'Ǜ' => 'U', 'ǜ' => 'u'
		];

		if (in_array($locale, ['de_DE', 'de_CH'])) {
			$chars['Ä'] = 'Ae';
			$chars['ä'] = 'ae';
			$chars['Ö'] = 'Oe';
			$chars['ö'] = 'oe';
			$chars['Ü'] = 'Ue';
			$chars['ü'] = 'ue';
			$chars['ß'] = 'ss';
		}
		else if ($locale == 'da_DK') {
			$chars['Æ'] = 'Ae';
			$chars['æ'] = 'ae';
			$chars['Ø'] = 'Oe';
			$chars['ø'] = 'oe';
			$chars['Å'] = 'Aa';
			$chars['å'] = 'aa';
		}
		else if ($locale == 'ca') {
			$chars['l·l'] = 'll';
		}
		else if (in_array($locale, ['sr_RS', 'bs_BA'])) {
			$chars['Đ'] = 'DJ';
			$chars['đ'] = 'dj';
		}

		$data = mb_strtolower((preg_match('/[\x80-\xff]/', $data) === 1) ? strtr($data, $chars) : $data);
		return trim(preg_replace('#[^\w\-]#', '', str_replace(['/', ' '], '-', $data)), '-');
	}
}