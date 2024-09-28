<?php
	include_once("class/register_globals.php");
  include_once("class/class.dbconfig.php");

if ($_GET[cmd]=="catalog_select") {
  if (!is_array($_SESSION["selections"])) $_SESSION["selections"]=array();
  $found = false;
  for ($i=0;$i<count($_SESSION["selections"]);$i++) {
    if ($_SESSION["selections"][$i]["item_code"]==$_GET["id"]) {
      $found = true;
      break;
    }
  }
  if (!$found) {
    $dbcfg = new DbConfig();
    $conx = odbc_pconnect($dbcfg->dbname,$dbcfg->username,$dbcfg->password);
    odbc_exec($conx, "use ".$dbcfg->dbname);

    $query = "SELECT i.item_code, i.item_desc, i.item_name, i.item_unit, i.item_qty_onhnd, i.item_msrp ";
    $query .= "FROM items i ";
    $query .= "WHERE i.item_code LIKE '".$_GET['id']."' ";
    $query .= "ORDER BY i.item_code ";
    $res = odbc_exec($conx, $query);
    if ($row = odbc_fetch_row($res)) {
      $tmp=array();
      $tmp['item_code']=strtolower(trim($_GET['id']));
      $tmp['item_desc']=trim(odbc_result($res, "item_desc"));
      $tmp['item_name']=trim(odbc_result($res, "item_name"));
      $tmp['item_qty_onhnd']=odbc_result($res, "item_qty_onhnd");
      $tmp['item_msrp']=odbc_result($res, "item_msrp");
      $tmp['item_unit']=trim(odbc_result($res, "item_unit"));
      array_push($_SESSION["selections"], $tmp);
    }
    odbc_free_result($res);
    odbc_close($conx);
  }
  $loc = "item_catalog.php?pg=".$_GET["pg"]."&ft=".$_GET["ft"];
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_delete") {
  $numrows = count($_SESSION["selections"]);
  $tmp = array();
  for ($i=0;$i<$numrows;$i++) {
    if (strtolower(trim($_SESSION['selections'][$i]['item_code']))==strtolower(trim($_GET['id']))) continue;
    array_push($tmp,$_SESSION['selections'][$i]);
  }
  $_SESSION["selections"]=$tmp;
  $loc = "item_catalog.php?ty=s";
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_clear") { // clear
  $_SESSION["selections"]=null;
  $loc = "item_catalog.php";
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_up") { // upward
  $numrows = count($_SESSION["selections"]);
  for ($i=0;$i<$numrows;$i++) {
    if ($_SESSION['selections'][$i]['item_code']==$_GET['id']) {
      if ($i==0) break;
      $prev = $i-1;
      $tmp = $_SESSION['selections'][$i];
      $_SESSION['selections'][$i]=$_SESSION['selections'][$prev];
      $_SESSION['selections'][$prev]=$tmp;
      break;
    }
  }
  $loc = "item_catalog.php?ty=s";
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_down") { // downward
  $numrows = count($_SESSION["selections"]);
  for ($i=0;$i<$numrows;$i++) {
    if ($_SESSION['selections'][$i]['item_code']==$_GET['id']) {
      if ($i==$numrows-1) break;
      $next = $i+1;
      $tmp = $_SESSION['selections'][$i];
      $_SESSION['selections'][$i]=$_SESSION['selections'][$next];
      $_SESSION['selections'][$next]=$tmp;
      break;
    }
  }
  $loc = "item_catalog.php?ty=s";
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_page") { // insert items in a page
  if (!is_array($_SESSION["selections"])) $_SESSION["selections"]=array();
  $ft = $_GET["ft"];
  $numpage = $_GET[c]*$_GET[r]+0;
  $startpos = $numpage*$_GET["pg"]-$numpage;
  $dbcfg = new DbConfig();
  $conx = odbc_pconnect($dbcfg->dbname,$dbcfg->username,$dbcfg->password);
  odbc_exec($conx, "use ".$dbcfg->dbname);
  $query = "SELECT i.item_code, i.item_desc, i.item_name, i.item_unit, i.item_qty_onhnd, i.item_msrp ";
  $query .= "FROM items i ";
  $query .= "WHERE item_active<>'f' AND i.item_code LIKE '$ft%' ";
  $query .= "ORDER BY i.item_code ";
  $res = odbc_exec($conx, $query);
  $i = 0;
  while ($row = odbc_fetch_row($res)) {
    if ($i>$startpos+$numpage) {
      break;
    } else if ($i>=$startpos && $i<$startpos+$numpage) {
      $item_code = strtolower(trim(odbc_result($res, "item_code")));
      $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($_POST["id"])).".jpg";

      if (file_exists($imgfile)) {
        $found = false;
        for ($j=0;$j<count($_SESSION["selections"]);$j++) {
          if ($_SESSION["selections"][$j]["item_code"]==$item_code) {
            $found = true;
            break;
          }
        }
        if (!$found) {
          $tmp=array();
          $tmp['item_code']=$item_code;
          $tmp['item_desc']=trim(odbc_result($res, "item_desc"));
          $tmp['item_name']=trim(odbc_result($res, "item_name"));
          $tmp['item_qty_onhnd']=odbc_result($res, "item_qty_onhnd");
          $tmp['item_msrp']=odbc_result($res, "item_msrp");
          $tmp['item_unit']=trim(odbc_result($res, "item_unit"));
          array_push($_SESSION["selections"], $tmp);
        }
      }
    }
    $i += 1;
  }
  odbc_free_result($res);
  odbc_close($conx);
  $loc = "item_catalog.php?pg=".$_GET["pg"]."&ft=$ft";
  header("Location: ".$loc);
  exit;

} else if ($_GET[cmd]=="catalog_filter") { // insert filtered items
  if (!is_array($_SESSION["selections"])) $_SESSION["selections"]=array();
  $ft = $_GET["ft"];
  $dbcfg = new DbConfig();
  $conx = odbc_pconnect($dbcfg->dbname,$dbcfg->username,$dbcfg->password);
  odbc_exec($conx, "use ".$dbcfg->dbname);
  $query = "SELECT i.item_code, i.item_desc, i.item_name, i.item_unit, i.item_qty_onhnd, i.item_msrp ";
  $query .= "FROM items i ";
  $query .= "WHERE item_active<>'f' AND i.item_code LIKE '$ft%' ";
  $query .= "ORDER BY i.item_code ";
  $res = odbc_exec($conx, $query);
  while ($row = odbc_fetch_row($res)) {
    $item_code = strtolower(trim(odbc_result($res, "item_code")));
    $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($item_code)).".jpg";
    if (file_exists($imgfile)) {
      $found = false;
      for ($j=0;$j<count($_SESSION["selections"]);$j++) {
        if ($_SESSION["selections"][$j]["item_code"]==$item_code) {
          $found = true;
          break;
        }
      }
      if (!$found) {
        $tmp=array();
        $tmp['item_code']=$item_code;
        $tmp['item_desc']=trim(odbc_result($res, "item_desc"));
        $tmp['item_name']=trim(odbc_result($res, "item_name"));
        $tmp['item_qty_onhnd']=odbc_result($res, "item_qty_onhnd");
        $tmp['item_msrp']=odbc_result($res, "item_msrp");
        $tmp['item_unit']=trim(odbc_result($res, "item_unit"));
        array_push($_SESSION["selections"], $tmp);
      }
    }
  }
  odbc_free_result($res);
  odbc_close($conx);
  $loc = "item_catalog.php?pg=".$_GET["pg"]."&ft=".$_GET["ft"];
  header("Location: ".$loc);
  exit;

} else if ($_POST[cmd]=="catalog_picture") { // image upload
  //name,type,size,tmp_name,error
  $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($_POST["id"])).".jpg";
  if (move_uploaded_file($_FILES['upload']['tmp_name'], $imgfile)) {
    chmod($imgfile, 0777);
  }

  $loc = "item_catalog.php?pg=".$_POST["pg"]."&ft=".$_POST["ft"]."&ty=v&id=".$_POST["id"];
  header("Location: ".$loc);
  exit;

} else if ($_POST[cmd]=="catalog_checked") {
  if (!is_array($_SESSION["selections"])) $_SESSION["selections"]=array();
  $instr = "";
  $first=true;
  for ($i=0;$i<count($_POST[cboxes]);$i++) {
    $item_code = strtolower(trim($_POST[cboxes][$i]));
    $imgfile = $_SERVER["DOCUMENT_ROOT"]."item_images/".strtolower(trim($item_code)).".jpg";

    $found = false;
    if (file_exists($imgfile)) {
      for ($j=0;$j<count($_SESSION["selections"]);$j++) {
        //echo strtolower($_SESSION["selections"][$j]["item_code"])."/".$item_code."<br>";
        //if (strcmp(strtolower($_SESSION["selections"][$j]["item_code"]),$item_code)) {
        if (strtolower($_SESSION["selections"][$j]["item_code"])==$item_code) {
          $found = true;
          break;
        }
      }
      if (!$found) {
        if ($first) $first = false;
        else $instr .=",";
        $instr .= "'".str_pad($item_code,20," ",STR_PAD_RIGHT)."'";
      }
    }
  }

  if (strlen($instr)>0) {
    $dbcfg = new DbConfig();
    $conx = odbc_pconnect($dbcfg->dbname,$dbcfg->username,$dbcfg->password);
    odbc_exec($conx, "use ".$dbcfg->dbname);

    $query = "SELECT i.item_code, i.item_desc, i.item_name, i.item_unit, i.item_qty_onhnd, i.item_msrp ";
    $query .= "FROM items i ";
    $query .= "WHERE i.item_code in ($instr) ";
    $query .= "ORDER BY i.item_code ";

    $res = odbc_exec($conx, $query);

    while ($row = odbc_fetch_row($res)) {
      $tmp=array();
      $tmp['item_code']=odbc_result($res, "item_code");
      $tmp['item_desc']=trim(odbc_result($res, "item_desc"));
      $tmp['item_name']=trim(odbc_result($res, "item_name"));
      $tmp['item_qty_onhnd']=odbc_result($res, "item_qty_onhnd");
      $tmp['item_msrp']=odbc_result($res, "item_msrp");
      $tmp['item_unit']=trim(odbc_result($res, "item_unit"));
      array_push($_SESSION["selections"], $tmp);
    }
    odbc_free_result($res);
    odbc_close($conx);
  }
  $loc = "item_catalog.php?pg=".$_POST["pg"]."&ft=".$_POST["ft"];
  header("Location: ".$loc);
  exit;

} else {
  header("Location: item_catalog.php");
  exit;
}
?>
