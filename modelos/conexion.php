<?php

class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=34.69.55.73;dbname=erp_backend",
			            "root",
			            "admin123");

		$link->exec("set names utf8");

		return $link;

	}

}