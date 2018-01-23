<?php 
require_once 'koneksi.php';
	
	$kode = $_GET['code'];
	$result = $proses->check_code($kode);
	echo json_encode(array('result' => $result,));

 ?>