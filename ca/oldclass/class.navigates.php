<?php
include_once("class.sessions.php");

class Navigates {

	var $return_page;
	var $page;
	var $totalpage;

	function getPageName($str) {
		if (preg_match("/(\w+\.php)/i",$str, $matches)) {
			$last = count($matches) - 1;
			$this->return_page = $matches[$last];
		}
		return $return_page;
	}

	function setReturnPage($pname) {
		$this->return_page = $pname;
	}

	function getReturnPage() {
		return $this->return_page;
	}

	function setTotalPage($numrec, $limit) {
		$this->totalpage = ceil($numrec / $limit);
	}

	function getTotalPage() {
		return $this->totalpage;
	}

	function setPage($page=1) {
		if (empty($page)) $page = 1;
		$this->page = $page;
	}

	function getPage() {
		return $this->page;
	}

	function getPrevPage() {
		if ($this->page <= 1) $prevpage = 1;
		else $prevpage = $this->page - 1;
		return $prevpage;
	}

	function getNextPage() {
		if ($this->page >= $this->totalpage) $nextpage = $this->page;
		else $nextpage = $this->page + 1;
		return $nextpage;
	}

}

?>