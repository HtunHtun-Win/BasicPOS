<?php

	define('HOST','localhost');
	define('USER','root');
	define('PASSWORD','');
	define('DATABASE','basic_pos');

	try{
	$pdo = new PDO(
		"mysql:host=" . HOST . ";dbname=" . DATABASE,
		USER,
		PASSWORD,
		[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]
	);
	}catch(Exception $e){
		echo $e->__toString();
	}
