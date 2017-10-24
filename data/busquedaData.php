<?php 
class BusquedaData {

	private $codigo;
	private $titulo;
	private $copete;
	private $dominio;
	

	function __construct(){
		}


	//GETTERS Y SETTERS DE LAS AGENDAS//
	public function getCodigo(){
		return $this->agendacod;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;	
	}
	
	public function getTitulo(){
		return $this->titulo;
	}

	public function setTitulo($titulo){
		$this->titulo = $titulo;	
	}

	public function getCopete(){
		return $this->copete;
	}

	public function setCopete($copete){
		$this->copete = $copete;	
	}


	public function getDominio(){
		return $this->dominio;
	}

	public function setDominio($dominio){
		$this->dominio = $dominio;	
	}



}
?>
