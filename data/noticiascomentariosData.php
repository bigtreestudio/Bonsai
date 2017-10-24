<?php 
class NoticiasComentariosData {

	private $comentariocod;
	private $noticiacod;
	private $comentarionombre;
	private $comentarioemail;
	private $comentariodesc;
	private $comentarioestado;
	private $comentariofalta;
	
	function __construct(){
		}


	//GETTERS Y SETTERS DE LA NOTICIA//
	
	public function getCodigo(){
		return $this->comentariocod;
	}

	public function setCodigo($comentariocod){
		$this->comentariocod = $comentariocod;	
	}	

	public function getCodigoNoticia(){
		return $this->noticiacod;
	}

	public function setCodigoNoticia($noticiacod){
		$this->catcod = $noticiacod;	
	}

	public function getNombre(){		
		return $this->comentarionombre;
	}
	
	public function setNombre($comentarionombre){
		$this->comentarionombre = $comentarionombre;	
	}


	public function getEmail(){
		return $this->comentarioemail;
	}
	
	public function setEmail($comentarioemail){
		$this->comentarioemail = $comentarioemail;	
	}
	
	public function getComentarioDescripcion(){
		return $this->comentariodesc;
	}
	
	public function setComentarioDescripcion($comentariodesc){
		$this->comentariodesc = $comentariodesc;	
	}
	
	
	public function getEstado(){
		return $this->comentarioestado;
	}

	public function setEstado($comentarioestado){
		$this->comentarioestado = $comentarioestado;	
	}

	public function getFechaAlta(){
		return $this->comentariofalta;
	}

	public function setFechaAlta($comentariofalta){
		$this->comentariofalta = $comentariofalta;	
	}

}
?>
