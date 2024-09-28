<?php

class Common  {

    function getDefaults($arr, $defvalue) {
        $outarr = array();
        foreach ($arr as $k => $v) {
            if (isset($arr[$k])) {
                $outarr[$k] = $v;
            } else {
                $outarr[$k] = $defvalue;
            }
        } 
        return $outarr;
    }

    function setDefaults(&$arr, $defvalue) {
        foreach ($arr as $k => $v) {
            if (isset($arr[$k])) {
                $arr[$k] = $v;
            } else {
                $arr[$k] = $defvalue;
            }
        } 
    }

    function getKeyDefaults($arr, $keyarr, $defvalue) {
        $outarr = array();
        for ($i=0;$i<count($keyarr);$i++) {
            if (isset($arr[$keyarr[$i]])) {
                $outarr[$keyarr[$i]] = $arr[$keyarr[$i]];
            } else {
                $outarr[$keyarr[$i]] = $defvalue;;
            }
        }
        return $outarr;
    }

    function setKeyDefaults(&$arr, $keyarr, $defvalue) {
        for ($i=0;$i<count($keyarr);$i++) {
            if (!isset($arr[$keyarr[$i]])) {
                $arr[$keyarr[$i]] = $defvalue;;
            }
        }
    }

    function getPairDefaults($arr, $pairarr, $defvalue) { // $pairarr = [][2] 0:source,1:target
        $outarr = array();
        for ($i=0;$i<count($pairarr);$i++) {
            if (isset($arr[$pairarr[$i]])) {
                $outarr[$pairarr[$i][1]] = $arr[$pairarr[$i][0]];
            } else {
                $outarr[$pairarr[$i][1]] = $defvalue;
            }
        }
        return $outarr;
    }

}

?>