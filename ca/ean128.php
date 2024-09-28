<?php
class EAN128{
	var $barcode = array();
	var $codeset = array(array(' ',' ','00','212222'),
						 array('!','!','01','222122'),
						 array('\"','\"','02','222221'),
						 array('#','#','03','121223'),
						 array('$','$','04','121322'),
						 array('%','%','05','131222'),
						 array('&','&','06','122213'),
						 array('\'','\'','07','122312'),
						 array('(','(','08','132212'),
						 array(')',')','09','221213'),
						 array('*','*','10','221312'),
						 array('+','+','11','231212'),
						 array(',',',','12','112232'),
						 array('-','-','13','122132'),
						 array('.','.','14','122231'),
						 array('/','/','15','113222'),
						 array('0','0','16','123122'),
						 array('1','1','17','123221'),
						 array('2','2','18','223211'),
						 array('3','3','19','221132'),
						 array('4','4','20','221231'),
						 array('5','5','21','213212'),
						 array('6','6','22','223112'),
						 array('7','7','23','312131'),
						 array('8','8','24','311222'),
						 array('9','9','25','321122'),
						 array(':',':','26','321221'),
						 array(';',';','27','312212'),
						 array('<','<','28','322112'),
						 array('=','=','29','322211'),
						 array('>','>','30','212123'),
						 array('?','?','31','212321'),
						 array('@','@','32','232121'),
						 array('A','A','33','111323'),
						 array('B','B','34','131123'),
						 array('C','C','35','131321'),
						 array('D','D','36','112313'),
						 array('E','E','37','132113'),
						 array('F','F','38','132311'),
						 array('G','G','39','211313'),
						 array('H','H','40','231113'),
						 array('I','I','41','231311'),
						 array('J','J','42','112133'),
						 array('K','K','43','112331'),
						 array('L','L','44','132131'),
						 array('M','M','45','113123'),
						 array('N','N','46','113321'),
						 array('O','O','47','133121'),
						 array('P','P','48','313121'),
						 array('Q','Q','49','211331'),
						 array('R','R','50','231131'),
						 array('S','S','51','213113'),
						 array('T','T','52','213311'),
						 array('U','U','53','213131'),
						 array('V','V','54','311123'),
						 array('W','W','55','311321'),
						 array('X','X','56','331121'),
						 array('Y','Y','57','312113'),
						 array('Z','Z','58','312311'),
						 array('array(','array(','59','332111'),
						 array('\\','\\','60','314111'),
						 array('),','),','61','221411'),
						 array('^','^','62','431111'),
						 array('_','_','63','111224'),
						 array('NUL','`','64','111422'),
						 array('SOH','a','65','121124'),
						 array('STX','b','66','121421'),
						 array('ETX','c','67','141122'),
						 array('EOT','d','68','141221'),
						 array('ENQ','e','69','112214'),
						 array('ACK','f','70','112412'),
						 array('BEL','g','71','122114'),
						 array('BS','h','72','122411'),
						 array('HT','i','73','142112'),
						 array('LF','j','74','142211'),
						 array('VT','k','75','241211'),
						 array('FF','l','76','221114'),
						 array('CR','m','77','413111'),
						 array('SO','n','78','241112'),
						 array('SI','o','79','134111'),
						 array('DLE','p','80','111242'),
						 array('DC1','q','81','121142'),
						 array('DC2','r','82','121241'),
						 array('DC3','s','83','114212'),
						 array('DC4','t','84','124112'),
						 array('NAK','u','85','124211'),
						 array('SYN','v','86','411212'),
						 array('ETB','w','87','421112'),
						 array('CAN','x','88','421211'),
						 array('EM','y','89','212141'),
						 array('SUB','z','90','214121'),
						 array('ESC','{','91','412121'),
						 array('FS','|','92','111143'),
						 array('GS','}','93','111341'),
						 array('RS','~','94','131141'),
						 array('US','DEL','95','114113'),
						 array('FNC 3','FNC 3','96','114311'),
						 array('FNC 2','FNC 2','97','411113'),
						 array('SHIFT','SHIFT','98','411311'),
						 array('CODE C','CODE C','99','113141'),
						 array('CODE B','FNC 4','CODE B','114131'),
						 array('FNC 4','CODE A','CODE A','311141'),
						 array('FNC 1','FNC 1','FNC 1','411131'),
						 array('Start A','Start A','Start A','211412'),
						 array('Start B','Start B','Start B','211214'),
						 array('Start C','Start C','Start C','211232'),
						 array('Stop','Stop','Stop','2331112'),
						 ) ;
	var $edge=array(0,0,0,0,0,0,0,0,0,0);
	var $start=array(1,1,0,0,0,0,0,0,0,0,0);
    var $stop=array(1,1,0,0,0,0,0,0,0,0,0,0,0);

	function EAN128($code='', $codetype='A'){
		$this->SetCode($code, $codetype);
	}

	function SetCode($code='', $codetype='A'){	
		$this->code=$code;
		$this->codetype=$codetype;
		
	}
	
	function GetPattern($codestr){
		$codetype = $this->codetype;
		$codeset = $this->codeset;
		//print_r ($codeset);
		if ($codetype=='A')$key = 0;
        else if ($codetype=='B')$key = 1;
		else $key = 2;

		$tmp=array();
     
		for($i=0;$i<count($codeset);$i++){
				if($codeset[$i][$key]==$codestr){ 
					$tmp[$i]=$codeset[$i][3];
					return $tmp;
				}
		}
	}

	
	function GetBarcode($code){
		$code = strtoupper($code);
		//print $code;
		$codetype=$this->codetype;
		$codeset = $this->codeset;
		$this->barcode=array();
		$this->barcode = array_merge($this->barcode, $this->edge);	

		if ($codetype== 'A') {
			$this->barcode = array_merge($this->barcode,$this->ConvPattern($codeset[103][3]));
			$this->checksum=103;	
		}
		else if($codetype=='B'){
			$this->barcode = array_merge($this->barcode,$this->ConvPattern($codeset[104][3]));
			$this->checksum=104;	
		}
		else{
			$this->barcode = array_merge($this->barcode,$this->ConvPattern($codeset[105][3]));
			$this->checksum=105;	
		}

		$checksum=$this->checksum;
		$range = strlen($code);

		for($i=0; $i<$range;$i++){
			if($codetype != 'C') {
				$key = array_keys($this->GetPattern( $code{$i} ));
				$value = array_values($this->GetPattern( $code{$i} ));
				$this->barcode = array_merge($this->barcode,$this->ConvPattern($value[0]));
				$checksum = $checksum + (($i+1)*$key[0]);
			}else{
				 if (($i%2)==0) {
                    $keystr = $code{$i};
				 }else{
                    $keystr = $keystr.$code{$i};
					
					$key = array_keys($this->GetPattern( $keystr ));
					$value = array_values($this->GetPattern( $keystr ));
					$this->barcode = array_merge($this->barcode,$this->ConvPattern($value[0]));
                    $checksum = $checksum + (($i+1)/(2*$key[0]));
				  }
			}

		}

		$checkdigit= $checksum % 103;
		$tmp = $codeset[$checkdigit][3];
		
		$this->barcode = array_merge($this->barcode, $this->ConvPattern($tmp));
		$this->barcode = array_merge($this->barcode, $this->ConvPattern($codeset[106][3]));//stop code
	    $this->barcode = array_merge($this->barcode, $this->edge);
		
		return $this->barcode;
	}

	function ConvPattern($pattern){
		$black=True;
		$tmp=array();
		for($i=0; $i<strlen($pattern);$i++){
			$flag=(int)$pattern{$i};
			for($j=0;$j<$flag;$j++){
				if ($black) array_push($tmp,1); 
				else array_push($tmp,0);
			}
			if($black == True) $black = False;
			else $black = True;
		}
	return $tmp;
	}
}


$height = 50;
$width = 300;
$thick = 1;

$ean = new EAN128();
$arr = $ean->GetBarcode($_GET[bc]);

$cnt = count($arr);


header("Content-type: image/png");
$im = imagecreate($width, $height);
$black = imagecolorallocate($im, 0, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $white);

;
for ($i=0;$i<$cnt;$i++) {
	if ($arr[$i]>0) imagefilledrectangle ($im, $i*$thick, 0, (($i+1)*$thick)-1, $height, $black);
	else imagefilledrectangle ($im, $i*$thick, 0, (($i+1)*$thick)-1, $height, $white);
}

imagepng($im);
imagedestroy($im);

?>