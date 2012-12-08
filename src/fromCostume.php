<?php

require_once 'costumeClasses.php';

function dataFromCostume($src) {
	$source = file_get_contents($src);
	
	$costume = new costumeData();
	
	$scaleSplit = strpos($source, 'CostumePart');
	$scaleData = substr($source, 0, $scaleSplit);
	$scaleData = explode("\n", $scaleData);
	
	// Massage and split the data a bit
	for ($i=0; $i<count($scaleData); $i++) {
		$scaleData[$i] = str_replace("  ", " ", $scaleData[$i]);
		$scaleData[$i] = str_replace(",", "", $scaleData[$i]);
		$scaleData[$i] = explode(" ", $scaleData[$i]);
	}
	
	// populate the costume object
	for ($i=0; $i<count($scaleData); $i++) {
		switch (strtolower($scaleData[$i][0])) {
			case 'scale':
				$costume->setScale($scaleData[$i][1]);
				break;
			case 'bonescale':
				$costume->setBoneScale($scaleData[$i][1]);
				break;
			case 'shoulderscale':
				$costume->setShoulderScale($scaleData[$i][1]);
				break;
			case 'chestscale':
				$costume->setChestScale($scaleData[$i][1]);
				break;
			case 'waistscale':
				$costume->setWaistScale($scaleData[$i][1]);
				break;
			case 'hipscale':
				$costume->setHipScale($scaleData[$i][1]);
				break;
			case 'legscale':
				$costume->setLegScale($scaleData[$i][1]);
				break;
			case 'headscales':
				$costume->setHeadScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'browscales':
				$costume->setBrowScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'cheekscales':
				$costume->setCheekScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'chinscales':
				$costume->setChinScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'craniumscales':
				$costume->setCraniumScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'jawscales':
				$costume->setJawScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'nosescales':
				$costume->setNoseScales($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'skincolor':
				$costume->setSkinColor($scaleData[$i][1], $scaleData[$i][2], $scaleData[$i][3]);
				break;
			case 'bodytype':
				$costume->setBodyType($scaleData[$i][1]);
				break;
		}
	}

	$parts = array();
	preg_match_all('/CostumePart ""\r\n\{(.*?)\r\n\}/s', $source, $parts);
	
	foreach ($parts[1] as $p) {
		$part = new costumePart();
		
		$bits = explode("\r\n", $p);

		foreach ($bits as $bit) {
			$bit = str_replace("  ", " ", $bit);
			$bit = str_replace(",", "", $bit);
			$bit = explode(" ", $bit);
			
			switch (trim($bit[0])) {
				case 'Geometry':
					$part->setGeometry($bit[1]);
					break;
				case 'Texture1':
					$part->setTexture1($bit[1]);
					break;
				case 'Texture2':
					$part->setTexture2($bit[1]);
					break;
				case 'Color1':
					$part->setColor1($bit[1], $bit[2], $bit[3]);
					break;
				case 'Color2':
					$part->setColor2($bit[1], $bit[2], $bit[3]);
					break;
				case 'Color3':
					$part->setColor3($bit[1], $bit[2], $bit[3]);
					break;
				case 'Color4':
					$part->setColor4($bit[1], $bit[2], $bit[3]);
					break;
				case 'Fx':
					if ($bit[1] != trim('none')) $part->setFx($bit[1]);
					break;
			}
			
		}
		
		$costume->addPart($part->getPartString());
		
	}
	
	// returns as an array because the Titan exporter does
	return array($costume->getCostumeString());
	
}
