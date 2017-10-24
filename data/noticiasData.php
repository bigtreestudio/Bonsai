<?php 
class NoticiasData {

	private $noticiacod;
	private $catcod;
	private $catdominio;
	private $catnom;
	private $noticiadominio;
	private $noticiatitulo;
	private $noticiatitulocorto;
	private $noticiahrefexterno;
	private $noticiacopete;
	private $noticiacuerpo;
	private $noticiavolanta;
	private $noticiaautor;
	private $noticiatags;
	private $noticiafecha;
	private $noticiaimportante;
	private $imagenes;
	private $videos;
	private $audios;
	private $relacionadas;
	private $galerias;

	private $noticialat;
	private $noticialng;
	private $noticiazoom;
	private $noticiatype;
	private $noticiadireccion;
	private $noticiamuestramapa;
	private $noticiadominiocompartir;

	private $noticiacomentarios;
	
	function __construct(){
		}


	//GETTERS Y SETTERS DE LA NOTICIA//
	
	public function getCodigo(){
		return $this->noticiacod;
	}

	public function setCodigo($noticiacod){
		$this->noticiacod = $noticiacod;	
	}	

	public function getCodigoCategoria(){
		return $this->catcod;
	}

	public function setCodigoCategoria($catcod){
		$this->catcod = $catcod;	
	}

	public function getDominioCategoria(){
		return $this->catdominio;
	}
	
	public function setDominioCategoria($catdominio){
		$this->catdominio = $catdominio;	
	}

	public function getNombreCategoria(){		
		return $this->catnom;
	}
	
	public function setNombreCategoria($catnom){
		$this->catnom = $catnom;	
	}


	public function getTitulo(){
		return $this->noticiatitulo;
	}
	
	public function setTitulo($noticiatitulo){
		$this->noticiatitulo = $noticiatitulo;	
	}
	

	public function getDominio(){
		return $this->noticiadominio;
	}

	public function setDominio($noticiadominio){
		$this->noticiadominio = $noticiadominio;	
	}

	public function getTituloCorto(){
		return $this->noticiatitulocorto;
	}

	public function setTituloCorto($noticiatitulocorto){
		$this->noticiatitulocorto = $noticiatitulocorto;	
	}

	public function getHrefExterno(){
		return $this->noticiahrefexterno;
	}

	public function setHrefExterno($noticiahrefexterno){
		$this->noticiahrefexterno = $noticiahrefexterno;	
	}

	public function getCopete(){
		return $this->noticiacopete;
	}

	public function setCopete($noticiacopete){
		$this->noticiacopete = $noticiacopete;	
	}

	public function getCuerpo(){
		return $this->noticiacuerpo;
	}

	public function setCuerpo($noticiacuerpo){
		$this->noticiacuerpo = $noticiacuerpo;	
	}	

	public function getVolanta(){
		return $this->noticiavolanta;
	}

	public function setVolanta($noticiavolanta){
		$this->noticiavolanta = $noticiavolanta;	
	}
	
	public function getAutor(){
		return $this->noticiaautor;
	}

	public function setAutor($noticiaautor){
		$this->noticiaautor = $noticiaautor;	
	}


	public function getNoticiaImportante(){
		return $this->noticiaimportante;
	}

	public function setNoticiaImportante($noticiaimportante){
		$this->noticiaimportante = $noticiaimportante;	
	}
	
	public function getTags(){
		return $this->noticiatags;
	}

	public function getTagsArray(){ 
		if ($this->noticiatags!="")
			return explode(", ",$this->noticiatags);
		else
			return array();	
	}	


	public function setTags($noticiatags){
		$this->noticiatags = $noticiatags;	
	}		

	public function getFechaSinFormato(){
		return $this->noticiafecha;
	}

	public function getFecha($formato= "l d \d\e F \d\e Y"){
		$texto = date($formato,strtotime($this->noticiafecha));
		$noticiafecha = strtolower(FuncionesPHPLocal::ReemplazarTextoFechas($texto));
		return $noticiafecha;
	}

	public function setFecha($noticiafecha){
		$this->noticiafecha = $noticiafecha;	
	}
						
	public function getImagenes(){
		return $this->imagenes;
	}

	public function setImagenes($imagenes){
		$this->imagenes = $imagenes;	
	}
						
	public function getRelacionadas(){
		return $this->relacionadas;
	}

	public function setRelacionadas($relacionadas){
		$this->relacionadas = $relacionadas;	
	}


	public function getGalerias(){
		return $this->galerias;
	}

	public function setGalerias($galerias){
		$this->galerias = $galerias;	
	}

	
	public function getLatitud(){
		return $this->noticialat;
	}

	public function setLatitud($noticialat){
		$this->noticialat = $noticialat;	
	}

	public function getLongitud(){
		return $this->noticialng;
	}

	public function setLongitud($noticialng){
		$this->noticialng = $noticialng;	
	}

	public function getMapaZoom(){
		return $this->noticiazoom;
	}

	public function setMapaZoom($noticiazoom){
		$this->noticiazoom = $noticiazoom;	
	}

	public function getMapaTipo(){
		return $this->noticiatype;
	}

	public function setMapaTipo($noticiatype){
		$this->noticiatype = $noticiatype;	
	}

	public function getDireccion(){
		return $this->noticiadireccion;
	}

	public function setDireccion($noticiadireccion){
		$this->noticiadireccion = $noticiadireccion;	
	}

	public function getMuestraMapa(){
		return $this->noticiamuestramapa;
	}

	public function setMuestraMapa($noticiamuestramapa){
		$this->noticiamuestramapa = $noticiamuestramapa;	
	}

	public function getDominioCompartir(){
		return $this->noticiadominiocompartir;
	}

	public function setDominioCompartir($noticiadominiocompartir){
		$this->noticiadominiocompartir = $noticiadominiocompartir;	
	}
						
	public function getVideos(){
		return $this->videos;
	}

	public function setVideos($videos){
		$this->videos = $videos;	
	}
						
	public function getAudios(){
		return $this->audios;
	}

	public function setAudios($audios){
		$this->audios = $audios;	
	}
						
	public function getTieneComentarios(){
		return $this->noticiacomentarios;
	}

	public function setTieneComentarios($noticiacomentarios){
		$this->noticiacomentarios = $noticiacomentarios;	
	}
						

						
}
?>
