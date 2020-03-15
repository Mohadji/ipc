<?php 

	try {
		$bdd = new PDO("mysql:host=localhost;dbname=ipc_stock_db", "root", '');
	} catch (Exception $e) {
		die("Erreur: ".$e->getmessage());
	}
 ?>