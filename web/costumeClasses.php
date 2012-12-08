<?php

class costumePart {
	private $geometry = "none";
	private $texture1 = "none";
	private $texture2 = "none";
	private $color1 = "00000000";
	private $color2 = "00000000";
	private $color3 = "00000000";
	private $color4 = "00000000";
	private $fx = null;
	
	private function rgbToCode($r, $g=null, $b=null) {
		if (is_array($r)) {
			if (count($r) == 3) {
				return '00' . str_pad((string)dechex($r[2]), 2, '0', STR_PAD_LEFT)
					. str_pad((string)dechex($r[1]), 2, '0', STR_PAD_LEFT)
					. str_pad((string)dechex($r[0]), 2, '0', STR_PAD_LEFT);
			} else {
				return '00000000';
			}
		} else {
			return '00' . str_pad((string)dechex($b), 2, '0', STR_PAD_LEFT)
				. str_pad((string)dechex($g), 2, '0', STR_PAD_LEFT)
				. str_pad((string)dechex($r), 2, '0', STR_PAD_LEFT);
		}
	}
	function setGeometry ($g) {
		$this->geometry = ((string)$g ? : 'none');
	}
	function setTexture1 ($t) {
		$this->texture1 = ((string)$t ? : 'none');
	}
	function setTexture2 ($t) {
		$this->texture2 = ((string)$t ? : 'none');
	}
	function setFx ($fx) {
		$this->fx = ((string)$fx ? : null);
	}
	function setColor1 ($c, $c2=0, $c3=0) {
		$this->color1 = (is_array($c) ? $this->rgbToCode($c) : $this->rgbToCode($c, $c2, $c3));
	}
	function setColor2 ($c, $c2=0, $c3=0) {
		$this->color2 = (is_array($c) ? $this->rgbToCode($c) : $this->rgbToCode($c, $c2, $c3));
	}
	function setColor3 ($c, $c2=0, $c3=0) {
		$this->color3 = (is_array($c) ? $this->rgbToCode($c) : $this->rgbToCode($c, $c2, $c3));
	}
	function setColor4 ($c, $c2=0, $c3=0) {
		$this->color4 = (is_array($c) ? $this->rgbToCode($c) : $this->rgbToCode($c, $c2, $c3));
	}
	function getPartString() {
		$str = $this->geometry .' '. $this->texture1 .' '. $this->texture2
			.' '. $this->color1 .' '. $this->color2;
		if ($this->fx !== null && $this->fx !== 'none') {
			$str .= ' '. $this->color3 .' '. $this->color4 .' '. $this->fx;
		}
		return $str;
	}
}

class costumeData {
	
	private $scale = 0;
	private $boneScale = 0;
	private $shoulderScale = 0;
	private $chestScale = 0;
	private $waistScale = 0;
	private $hipScale = 0;
	private $legScale = 0;
	
	private $headScales = array(0, 0, 0);
	private $browScales = array(0, 0, 0);
	private $cheekScales = array(0, 0, 0);
	private $chinScales = array(0, 0, 0);
	private $craniumScales = array(0, 0, 0);
	private $jawScales = array(0, 0, 0);
	private $noseScales = array(0, 0, 0);
	private $skinColor = array(0, 0, 0); // r g b
	// private $numParts = 0; // doesn't seem demorecords care
	private $bodyType = 0; // 0 = male, 4 = huge, 1 = female
	private $entityNumber = "!EN!";
	
	private $parts = array();

	private function rgbToCode($r, $g=null, $b=null) {
		if (is_array($r)) {
			if (count($r) == 3) {
				return '00' . str_pad((string)dechex($r[2]), 2, '0', STR_PAD_LEFT)
					. str_pad((string)dechex($r[1]), 2, '0', STR_PAD_LEFT)
					. str_pad((string)dechex($r[0]), 2, '0', STR_PAD_LEFT);
			} else {
				return '00000000';
			}
		} else {
			return '00' . str_pad((string)dechex($b), 2, '0', STR_PAD_LEFT)
				. str_pad((string)dechex($g), 2, '0', STR_PAD_LEFT)
				. str_pad((string)dechex($r), 2, '0', STR_PAD_LEFT);
		}
	}
	
	private function scalesToString($s) {
		if (is_array($s)) {
			return (string)(number_format($s[0], 6)) ." ".
				(string)(number_format($s[1], 6)) ." ".
				(string)(number_format($s[2], 6)) ." ";
		} else {
			return (string)(number_format($s, 6)) ." ";
		}
	}
	
	function setScale($s) {
		$this->scale = $s;
	}
	function setBoneScale($s) {
		$this->boneScale = $s;
	}
	function setShoulderScale($s) {
		$this->shoulderScale = $s;
	}
	function setChestScale($s) {
		$this->chestScale = $s;
	}
	function setWaistScale($s) {
		$this->waistScale = $s;
	}
	function setHipScale($s) {
		$this->hipScale = $s;
	}
	function setLegScale($s) {
		$this->legScale = $s;
	}
	function setHeadScales($s, $s2=0, $s3=0) {
		$this->headScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setBrowScales($s, $s2=0, $s3=0) {
		$this->browScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}	
	function setCheekScales($s, $s2=0, $s3=0) {
		$this->cheekScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setChinScales($s, $s2=0, $s3=0) {
		$this->chinScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setCraniumScales($s, $s2=0, $s3=0) {
		$this->craniumScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setJawScales($s, $s2=0, $s3=0) {
		$this->jawScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setNoseScales($s, $s2=0, $s3=0) {
		$this->noseScales = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setSkinColor($s, $s2=0, $s3=0) {
		$this->skinColor = (is_array($s) ? $s : array($s, $s2, $s3));
	}
	function setBodyType($b) {
		$valid = array(0, 1, 4);
		if (in_array($b, $valid)) {
			$this->bodyType = $b;
		}
	}
	function setEntityNumber($n) {
		$this->entityNumber = $n;
	}
	function addPart($p) {
		$this->parts[] = $p;
	}
	function getCostumeString() {
		
		$str = "0   ".$this->entityNumber."  COSTUME ". $this->bodyType ." ".
			$this->rgbToCode($this->skinColor) ." ".
			$this->scalesToString($this->scale) .
			$this->scalesToString($this->boneScale) .
			"0.000000 ".
			$this->scalesToString($this->shoulderScale) .
			$this->scalesToString($this->chestScale) .
			$this->scalesToString($this->waistScale) .
			$this->scalesToString($this->hipScale) .
			$this->scalesToString($this->legScale) .
			"0.000000 ".
			$this->scalesToString($this->headScales) .
			$this->scalesToString($this->browScales) .
			$this->scalesToString($this->cheekScales) .
			$this->scalesToString($this->chinScales) .
			$this->scalesToString($this->craniumScales) .
			$this->scalesToString($this->jawScales) .
			$this->scalesToString($this->noseScales) ."\r\n";
		
		foreach ($this->parts as $p) {
			$str .= "0   ".$this->entityNumber."  PARTSNAME ".$p."\r\n";
		}
		return $str;
		
	}
}
