<?php
class Sessions {

	var $sid;

	function Sessions($sid="") {
		$this->sid = $sid;
	}

	function setUserCode($user_code) {
		$_SESSION["user_code"] = $user_code;

		setcookie ("usercookie", $user_code);
	}

	function getUserCode() {
		return $_SESSION["user_code"];
	}

	function unsetUserCode($user_code) {
		session_unregister("user_code");
	}

	function setSessArray($aname, $arr) {
		$$aname = base64_encode(serialize($arr));
		$_SESSION[$aname] = $$aname;
	}

	function delSessArray($aname) {
		if (session_is_registered($aname)) {
			session_unregister($aname);
		}
	}

	function getSessArray($aname, $del="f") {
		if (session_is_registered($aname)) {
			$$aname = unserialize(base64_decode($aname));
			if ($del == "t") $_SESSION[$aname] = $$aname;
		}
		return $$aname;
	}


}
?>