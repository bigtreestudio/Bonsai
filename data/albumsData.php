<?php 
class AlbumsData {

	private $albumcod;
	private $albumsuperior;
	private $albumtitulo;
	private $albumorden;
	private $albumfalta;
	private $albumestadocod;
	private $dominio;
	private $galerias;
	private $menucod;	
	private $mentipocod;

function __construct(){
		}


	//GETTERS Y SETTERS DE LOS ALBUMES//
	
	public function getCodigo(){
		return $this->albumcod;
	}

	public function setCodigo($albumcod){
		$this->albumcod = $albumcod;	
	}
	
	public function getCodigoPadre(){
		return $this->albumsuperior;
	}

	public function setCodigoPadre($albumsuperior){
		$this->albumsuperior = $albumsuperior;	
	}

	public function getTitulo(){
		return $this->albumtitulo;
	}
	
	public function setTitulo($albumtitulo){
		$this->albumtitulo = $albumtitulo;	
	}

	public function geEstado(){
		return $this->albumestadocod;
	}

	public function setEstado($albumestadocod){
		$this->albumestadocod = $albumestadocod;	
	}
	
	public function getOrden(){
		return $this->albumorden;
	}

	public function setOrden($albumorden){
		$this->albumorden = $albumorden;	
	}
	
	public function getGaleriaImportante(){
		return $this->galeriaimportante;
	}

	public function setGaleriaImportante($galeriaimportante){
		$this->galeriaimportante = $galeriaimportante;	
	}

	public function getGalerias(){
		return $this->galerias;
	}

	public function setGalerias($galerias){
		$this->galerias = $galerias;	
	}
	
	public function getDominio(){
		return $this->dominio;
	}

	public function setDominio($dominio){
		$this->dominio= $dominio;	
	}


	public function getMenuCodigo(){
		return $this->menucod;
	}

	public function setMenuCodigo($menucod){
		$this->menucod = $menucod;	
	}
	
	public function getMenuTipoCodigo(){
		return $this->menutipocod;
	}

	public function setMenuTipoCodigo($menutipocod){
		$this->menutipocod = $menutipocod;	
	}
}
?>
