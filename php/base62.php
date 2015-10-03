<?php

	// construct base62 string given an integer id
	function base62_encode($id) {
		if ($id < 0) {
			die('Invalid id.');
		}

   		$alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   		$base = 62;
   		$str_62 = '';

   		// gets LSB / associated character, adds character to str_62, rightshifts number (division)
		while ($id > 0) {
			$remainder = $id % $base;
			$str_62 = $alphabet[$remainder] . $str_62;
			$id = (int) ($id/$base);
		}

		return $str_62;
	}

	// construct decimal integer id given base62 string
	function base62_decode($str) {
   		$alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   		$base = 62;
   		// iterate through string
   		for($i = 0; $i < strlen($str); $i++) {
   			$value = strpos($alphabet, $str[$i]); // get index of current character
   			$current = $value * pow($base, strlen($str) - ($i + 1)); // number times base^(strlength - i - 1), gets total from current digit
   			$id += $current; // add value from current digit to total
   		}

   		return $id;
	}
?>