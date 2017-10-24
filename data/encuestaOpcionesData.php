<?php 
class EncuestaOpcionesData {

	private $encuestacod;
	private $opciocod;
	private $opcionombre;
	private $opcioncantvotos;

	function __construct(){
		}


	//GETTERS Y SETTERS DE LOS ENCUESTAS//
	
	public function getCodigo(){
		return $this->opciocod;
	}

	public function setCodigo($opciocod){
		$this->opciocod = $opciocod;	
	}
	
	public function getOpcion(){
		return $this->opcionombre;
	}

	public function setOpcion($opcionombre){
		$this->opcionombre = $opcionombre;	
	}

	public function getEncuesta(){
		return $this->encuestacod;
	}

	public function setEncuesta($encuestacod){
		$this->encuestacod = $encuestacod;	
	}


	public function getCantidadVotos(){
		return $this->opcioncantvotos;
	}

	public function setCantidadVotos($opcioncantvotos){
		$this->opcioncantvotos = $opcioncantvotos;	
	}



}
?>
