<?php 
class DepartamentoData {

	private $provinciacod;
	private $provinciadesc;
	private $departamentocod;
	private $departamentodesc;

	function __construct(){
		}


	//GETTERS Y SETTERS DE LAS AGENDAS//
	public function getProvinciaCodigo(){
		return $this->provinciacod;
	}

	public function setProvinciaCodigo($provinciacod){
		$this->provinciacod = $provinciacod;	
	}
	
	public function getProvinciaDescripcion(){
		return $this->provinciadesc;
	}

	public function setProvinciaDescripcion($provinciadesc){
		$this->provinciadesc = $provinciadesc;	
	}
	
	public function getCodigo(){
		return $this->departamentocod;
	}

	public function setCodigo($departamentocod){
		$this->departamentocod = $departamentocod;	
	}
	
	public function getDescripcion(){
		return $this->departamentodesc;
	}

	public function setDescripcion($departamentodesc){
		$this->departamentodesc = $departamentodesc;	
	}
	

}
?>
