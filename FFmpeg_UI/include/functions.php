<?php
	////////// Delete special characters from Filename //////////
	function clean($string) {
	    //$string = utf8_decode($string);
		//$string = preg_replace("/[^A-Za-z0-9.äÄüÜöÖß]/"," ", $string);
		//$string = utf8_encode($string);
		//$string = preg_replace("/[^a-zA-Z0-9.\s]/", " ", $string);
		//$string = preg_replace("/[’‘‹›']/", " ", $string);
		
		$string = str_replace(" ", "_", $string);
		$string = preg_replace("/[^.\w_]+/u", "", $string);
		return trim($string, '-');
	}	
?>