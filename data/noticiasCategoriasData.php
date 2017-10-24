<?php 
class NoticiasCategoriasData {

	private $catcod;
	private $planthtmlcod;
	private $catnom;
	private $catdominio;
	private $catdesc;
	private $catsuperior;
	private $catorden;
	private $catestado;
	private $semuestramenu;
	private $catdatajson;
	private $menucod;	
	private $mentipocod;
	private $fondocod;
	
	private $noticias;

	function __construct(){
		}


	//GETTERS Y SETTERS DE LA NOTICIA//
	

	public function getCodigo(){
		return $this->catcod;
	}

	public function setCodigo($catcod){
		$this->catcod = $catcod;	
	}

	public function getPlantillaHtmlCodigo(){
		return $this->planthtmlcod;
	}
	
	public function setPlantillaHtmlCodigo($planthtmlcod){
		$this->planthtmlcod = $planthtmlcod;	
	}

	public function getDominio(){
		return $this->catdominio;
	}
	
	public function setDominio($catdominio){
		$this->catdominio = $catdominio;	
	}

	public function getNombre(){		
		return $this->catnom;
	}
	
	public function setNombre($catnom){
		$this->catnom = $catnom;	
	}

	public function getDescripcion(){
		return $this->catdesc;
	}

	public function setDescripcion($catdesc){
		$this->catdesc = $catdesc;	
	}

	public function getCategoriaSuperior(){
		return $this->catsuperior;
	}

	public function setCategoriaSuperior($catsuperior){
		$this->catsuperior = $catsuperior;	
	}

	public function getCategoriaOrden(){
		return $this->catorden;
	}

	public function setCategoriaOrden($catorden){
		$this->catorden = $catorden;	
	}

	public function getEstado(){
		return $this->catestado;
	}

	public function setEstado($catestado){
		$this->catestado = $catestado;	
	}

	public function getSeMuestraMenu(){
		return $this->semuestramenu;
	}

	public function setSeMuestraMenu($semuestramenu){
		$this->semuestramenu = $semuestramenu;	
	}	
						
	public function getDataJson(){
		return json_decode($this->catdatajson);
	}

	public function setDataJson($catdatajson){
		$this->catdatajson = $catdatajson;	
	}	
						

	public function getNoticias(){
		return $this->noticias;
	}

	public function setNoticias($noticias){
		$this->noticias = $noticias;	
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
	
	public function setFondo($fondocod){
		$this->fondocod = $fondocod;	
	}
	
	public function getFondo(){
		return $this->fondocod;
	}

}
?>
