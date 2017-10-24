<?php 
class EncuestaData {

	private $encuestacod;
	private $encuestapregunta;
	private $encuestaestado;
	private $dominio;
	private $opciones;

	function __construct(){
		}


	//GETTERS Y SETTERS DE LOS ENCUESTAS//
	
	public function getCodigo(){
		return $this->encuestacod;
	}

	public function setCodigo($encuestacod){
		$this->encuestacod = $encuestacod;	
	}
	
	public function getPregunta(){
		return $this->encuestapregunta;
	}

	public function setPregunta($encuestapregunta){
		$this->encuestapregunta = $encuestapregunta;	
	}

	public function getEstado(){
		return $this->encuestaestado;
	}

	public function setEstado($encuestaestado){
		$this->encuestaestado = $encuestaestado;	
	}

	public function getDominio(){
		return $this->dominio;
	}

	public function setDominio($dominio){
		$this->dominio = $dominio;	
	}

	public function getOpciones(){
		return $this->opciones;
	}

	public function setOpciones($opciones){
		$this->opciones = $opciones;	
	}



}
?>
