<?php
class Requests {

	function getMaxKeyInArray($arr) {
		if (count($arr) > 0) { 
			$max = -1;
			foreach($arr as $k => $v) if ($max < $k) $max = $k;
			if ($max>0) return $max;
		}
		return false;
	}

	function getTagedArray($parr, $tag) {
		$out = array();
		for ($i=0;$i<count($parr); $i++) {
			list($k,$v) = each($parr);
			if (substr($k,0,strlen($tag)) == $tag) {
				$key = substr($k,strlen($tag));
				$out[$key] = $v;
			}
		}
		return $out;
	}

	function getTagedArraySimple($parr, $tag) {
		$out = array();
		$j = 0;
		for ($i=0;$i<count($parr); $i++) {
			list($k,$v) = each($parr);
			if (substr($k,0,strlen($tag)) == $tag) {
				$key = substr($k,strlen($tag));
				$out[$j] = $key;
				$j++;
			}
		}
		sort ($out, SORT_NUMERIC);
		return $out;
	}

	function findArrayKey($keyvalue, $anarray) {
		$found = false;
		for ($i=0;$i<count($anarray);$i++) {
			list($key, $value) = each($anarray);
			if ($keyvalue == $key) {
				$found = true;
				break;
			}
		}
		return $found;
	}

	function getDiffKey($before, $after) {
		$diff = array();
		$marray = array_merge($before, $after);
		$x = 0;
		foreach($marray as $k => $v) {
			if(findArrayKey($k, $before)) {
				if(findArrayKey($k, $after)) {
					$diff[$x] = $k;
					$x++;
				}
			}
		}
		return $diff;
	}

	function getMergeKey($before, $after) {
		return array_merge($before, $after);
	}

	function getAlteredArray($oldarr, $newarr) {
		$arr = array();
		foreach ($oldarr as $ok => $ov) {
			foreach ($newarr as $nk => $nv) {
				if ($nk == $ok && $nv != $ov) {
					$arr[$nk] = $nv;
					break;
				}
			}
		}
		return $arr;
	}

	function getConvertArray($simple) {
		$arr = array();
		$num = count($simple);
		for ($i=0;$i<$num;$i++) {
			$k=$simple[$i];
			$arr[$k]="";
		}
		return $arr;
	}
}

?>