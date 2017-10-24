<?php 
class GaleriasData {

	private $galeriacod;
	private $multimediaconjuntocod;
	private $albumcod;
	private $galeriatitulo;
	private $galeriadesc;
	private $galeriaestadocod;
	private $galeriaorden;
	private $galeriaimportante;
	private $dominio;
	private $multimedias;
	private $imagengaleria;
	private $galeriatipo;
	private $menucod;	
	private $mentipocod;
	
	function __construct(){
		}


	//GETTERS Y SETTERS DE LAS GALERIAS//
	
	public function getCodigo(){
		return $this->galeriacod;
	}

	public function setCodigo($galeriacod){
		$this->galeriacod = $galeriacod;	
	}
	
	public function getMultimediaConjuntoCodigo(){
		return $this->multimediaconjuntocod;
	}

	public function setMultimediaConjuntoCodigo($multimediaconjuntocod){
		$this->multimediaconjuntocod = $multimediaconjuntocod;	
	}

	public function getAlbumCodigo(){
		return $this->albumcod;
	}
	
	public function setAlbumCodigo($albumcod){
		$this->albumcod = $albumcod;	
	}

	public function getTitulo(){
		return $this->galeriatitulo;
	}
	
	public function setTitulo($galeriatitulo){
		$this->galeriatitulo = $galeriatitulo;	
	}

	public function getDescripcion(){		
		return $this->galeriadesc;
	}
	
	public function setDescripcion($galeriadesc){
		$this->galeriadesc = $galeriadesc;	
	}

	public function geEstado(){
		return $this->galeriaestadocod;
	}

	public function setEstado($galeriaestadocod){
		$this->galeriaestadocod = $galeriaestadocod;	
	}
	
	public function getOrden(){
		return $this->galeriaorden;
	}

	public function setOrden($galeriaorden){
		$this->galeriaorden = $galeriaorden;	
	}
	
	public function getGaleriaImportante(){
		return $this->galeriaimportante;
	}

	public function setGaleriaImportante($galeriaimportante){
		$this->galeriaimportante = $galeriaimportante;	
	}

	public function getMultimedia(){
		return $this->multimedias;
	}

	public function setMultimedia($multimedias){
		$this->multimedias = $multimedias;	
	}
	
	public function getDominio(){
		return $this->dominio;
	}

	public function setDominio($dominio){
		$this->dominio= $dominio;	
	}
	
	public function getImagenGaleria(){
		return $this->imagengaleria;
	}

	public function setImagenGaleria($imagengaleria){
		$this->imagengaleria= $imagengaleria;	
	}
	
	public function getGaleriaTipo(){
		return $this->galeriatipos;
	}

	public function setGaleriaTipo($galeriatipo){
		$this->galeriatipo= $galeriatipo;	
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
