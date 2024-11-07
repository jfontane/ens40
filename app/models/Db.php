<?php

class Db {

	private $host;
	private $db;
	private $user;
	private $pass;
	public $conection;

	public function __construct($host='localhost',$db_name='uiakkdaq_escuela_1',$db_user='admin',$db_pass='usuario') {		

		$this->host = $host;
		$this->db = $db_name;
		$this->user = $db_user;
		$this->pass = $db_pass;

		try {
           $this->conection = new PDO('mysql:host='.$this->host.'; dbname='.$this->db.';charset=UTF8', $this->user, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }

	}

}