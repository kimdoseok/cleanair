<?php
class Langs {

	var $lang;
	var $charset;
	var $lang_arr;

	function Langs() {
		$this->lang = "en";
		$this->charset = "iso-8859-1";
	}

	function setLang($lang) {
		$this->$lang = $lang;
	}
}
?>