<?php

function dataFromTitan($src) {
	$source = simplexml_load_file($src);
	
	$costumes = array();
	$costume_data = $source->costumes;
	
	foreach ($costume_data->costume as $c) {
		$str = "0   !EN!  COSTUME ". $c->bodytype ." ".
			$c->skincolor ." ". $c->scales ."\r\n";
		$parts = $c->parts;
		foreach ($parts->part as $p) {
			$str .= "0   !EN!  PARTSNAME $p\r\n";
		}
		$costumes[] = $str;
	}
	
	return $costumes;
}
