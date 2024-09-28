<?php
class FormUtil {

	function fillSelectBox($returns, $name, $id, $value, $default, $class="", $options="") {
		$out = "<select name=\"$name\" class=\"$class\" $options>";
		for ($i=0;$i<count($returns);$i++) {
			$out .= "<option value=\"";
			$out .= $returns[$i][$id]."\"";
			if ($returns[$i][$id] == $default) $out .= " selected";
			$out .= ">";
			$out .= $returns[$i][$value];
			$out .= "</option>\n";
		}
		$out .= "</select>";
		return $out;
	}
	
	function fillSelectBoxWithAll($returns, $name, $id, $value, $default, $options="") {
		$out = "<select name=\"$name\">";
		$out .= "<option value=\"all\">All</option>\n";
		for ($i=0;$i<count($returns);$i++) {
			$out .= "<option value=\"";
			$out .= $returns[$i][$id]."\"";
			if ($returns[$i][$id] == $default) $out .= " selected";
			$out .= ">";
			$out .= $returns[$i][$value];
			$out .= "</option>\n";
		}
		$out .= "</select>";
		return $out;
	}

	function fillSelectBoxWithAllRefresh($returns, $name, $id, $value, $default, $options="") {
		$out = "<select name=\"$name\" onChange=\"javascript:updateForm()\">";
		$out .= "<option value=\"all\">All</option>\n";
		for ($i=0;$i<count($returns);$i++) {
			$out .= "<option value=\"";
			$out .= $returns[$i][$id]."\"";
			if ($returns[$i][$id] == $default) $out .= " selected";
			$out .= ">";
			$out .= $returns[$i][$value];
			$out .= "</option>\n";
		}
		$out .= "</select>";
		return $out;
	}

	function fillSelectBoxWithBlank($returns, $name, $id, $value, $default, $class="", $options="") {
		$out = "<select name=\"$name\" class=\"$class\">";
		$out .= "<option value=\"\"></option>\n";
		for ($i=0;$i<count($returns);$i++) {
			$out .= "<option value=\"";
			$out .= $returns[$i][$id]."\"";
			if ($returns[$i][$id] == $default) $out .= " selected";
			$out .= ">";
			$out .= $returns[$i][$value];
			$out .= "</option>\n";
		}
		$out .= "</select>";
		return $out;
	}
	
	function fillSelectBoxRefresh($returns, $name, $id, $value, $default, $class="", $options="") {
		$out = "<select name=\"$name\" class=\"$class\" onChange=\"updateForm('$name')\">";
		for ($i=0;$i<count($returns);$i++) {
			$out .= "<option value=\"";
			$out .= $returns[$i][$id]."\"";
			if ($returns[$i][$id] == $default) $out .= " selected";
			$out .= ">";
			$out .= $returns[$i][$value];
			$out .= "</option>\n";
		}
		$out .= "</select>";
		return $out;
	}

	function fillCheckBox($name, $value="f", $class="", $class="", $options="") {
		$out = "<input class=\"$class\" type=\"checkbox\" name=\"$name\" value=\"t\" ";
		if ($value=="t") $out .= " checked";
		$out .= ">\n";
		return $out;
	}


	function fillTextBox($name, $value, $size=10, $maxlength=20, $class="", $options="") {
		$out = "<input class=\"$class\" type=\"text\" name=\"$name\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" $options >";
		return $out;
	}

	function fillTextBoxTab($name, $value, $size=10, $maxlength=20, $class="", $options="", $tabindex="9") {
		$out = "<input class=\"$class\" type=\"text\" name=\"$name\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" tabindex=\"$tabindex\" $options >";
		return $out;
	}


	function fillTextBoxRO($name, $value, $size=10, $maxlength=20, $class="", $options="") {
		$out = "<input class=\"$class\" type=\"text\" name=\"$name\" size=\"$size\" maxlength=\"$maxlength\" value=\"$value\" $options readonly>";
		return $out;
	}

	function fillPasswordBox($name, $value, $size=10, $maxlength=20, $class="", $options="") {
		$out = "<input class=\"$class\" type=\"password\" name=\"$name\" size=\"$size\"  maxlength=\"$maxlength\" value=\"$value\" $options >";
		return $out;
	}

	function fillTextBoxRefresh($name, $value, $size=10, $maxlength=20, $class="", $options="") {
		$out = "<input type=\"text\" name=\"$name\" size=\"$size\"  maxlength=\"$maxlength\" value=\"$value\" $options onChange=\"updateForm('$name')\" class=\"$class\" $options>";
		return $out;
	}

	function fillHidden($name, $value) {
		$out = "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
		return $out;
	}

	function fillTextareaBox($name, $value, $cols=50, $rows=5, $class="") {
		$out = "<textarea name=\"$name\" cols=\"$cols\" rows=\"$rows\" class=\"$class\">$value</textarea>";
		return $out;
	}

	function prepareDifferences($before, $after) {
		$diff = array();
		foreach($before as $key => $value) {
			if (empty($after[$key]) && $before[$key] == "t") $after[$key] = "f";
			if ($after[$key] != $value) $diff[$key] = $after[$key];
		}
		return $diff;
	}

	function setHelpOption($onhelp) {
		return "onFocus=\"this.className='on_focus';setHelp('$onhelp');\"  onBlur=\"this.className='out_focus';setHelp('');\"";
	}
}	
?>