<?php 
class MultimediaData {

	private $multimediacod;
	private $multimediacatcod;
	private $multimediacatcarpeta;
	private $multimediatitulo;
	private $multimediadesc;
	private $multimedianombre;
	private $multimediaubic;
	private $multimediaubicurl;
	private $multimediaidexterno;
	private $multimediatipocod;
	private $multimediaestadocod;
	
	function __construct(){
		}


	//GETTERS Y SETTERS DE LOS MULTIMEDIA//
	
	

	public function getCodigo(){
		return $this->multimediacod;
	}

	public function setCodigo($multimediacod){
		$this->multimediacod = $multimediacod;	
	}
	
	public function getCodigoCategoria(){
		return $this->multimediacatcod;
	}

	public function setCodigoCategoria($multimediacatcod){
		$this->multimediacatcod = $multimediacatcod;	
	}

	public function getCarpetaCategoria(){
		return $this->multimediacatcarpeta;
	}

	public function setCarpetaCategoria($multimediacatcarpeta){
		$this->multimediacatcarpeta = $multimediacatcarpeta;	
	}

	public function getTitulo(){
		return $this->multimediatitulo;
	}
	
	public function setTitulo($multimediatitulo){
		$this->multimediatitulo = $multimediatitulo;	
	}

	public function getDescripcion(){
		return $this->multimediadesc;
	}
	
	public function setDescripcion($multimediadesc){
		$this->multimediadesc = $multimediadesc;	
	}
	public function getNombre(){		
		return $this->multimedianombre;
	}
	
	public function setNombre($multimedianombre){
		$this->multimedianombre = $multimedianombre;	
	}

	public function getUbicacion(){
		return $this->multimediaubic;
	}

	public function setUbicacion($multimediaubic){
		$this->multimediaubic = $multimediaubic;	
	}
	
	public function getIdExterno(){
		return $this->multimediaidexterno;
	}

	public function setIdExterno($multimediaidexterno){
		$this->multimediaidexterno = $multimediaidexterno;	
	}

	public function getTipoCodigo(){
		return $this->multimediatipocod;
	}

	public function setTipoCodigo($multimediatipocod){
		$this->multimediatipocod = $multimediatipocod;	
	}

	public function geEstado(){
		return $this->multimediaestadocod;
	}

	public function setEstado($multimediaestadocod){
		$this->multimediaestadocod = $multimediaestadocod;	
	}


	public function getUbicacionURL($tamanio=CARPETA_SERVIDOR_MULTIMEDIA_THUMBS)
	{
		return $this->getCarpetaCategoria().$tamanio.$this->getUbicacion();
	}
	
	public function getImgVideo()
	{
		return DOMINIO_SERVIDOR_MULTIMEDIA.$this->getCarpetaCategoria().$tamanio.$this->getUbicacion();
	}
	
	public function getUbicacionAudio()
	{
		return DOMINIO_SERVIDOR_MULTIMEDIA.$this->getCarpetaCategoria()."audios/".$this->getUbicacion();
	}



}
?>
