<?php
	/*
	 * Given an id, returns hashed/encoded id.
	 */
	function get_id_hash($id) {
		// hash the id
		$hash = hash('sha256', (string) $id);
		// take first 8 characters, convert to int
		$hash_num = hexdec(substr($hash, 0, 8));
		// base62 encode
		$surl = base62_encode($hash_num);
		return $surl;
	}
?>