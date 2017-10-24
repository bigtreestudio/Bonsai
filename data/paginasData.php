<?php 
class PaginasData {

	private $pagcod;
	private $catcod;
	private $pagtitulo;
	private $pagsubtitulo;
	private $pagdominio;
	private $pagtitulocorto;
	private $pagcopete;
	private $pagcuerpo;
	private $pagestadocod;
	private $pagcodsuperior;
	private $pagorden;
	private $pagcopiacodorig;
	private $pagcopiacod;
	private $muestramenu;	
	private $planthtmlcod;
	private $pagfalta;		
	private $pagfbaja;		
	private $imagenes;
	private $videos;
	private $audios;	
	private $menucod;	
	private $mentipocod;	

	private $subarbol;		
	
	
	function __construct(){
		}


	//GETTERS Y SETTERS DE LAS PAGINAS//

	public function getCodigo(){
		return $this->pagcod;
	}

	public function setCodigo($pagcod){
		$this->pagcod = $pagcod;	
	}
	public function getMuestraMenu(){
		return $this->muestramenu;
	}

	public function setMuestraMenu($muestramenu){
		$this->muestramenu = $muestramenu;	
	}	
	
	public function getCategoria(){
		return $this->categoria;
	}

	public function setCategoria($categoria){
		$this->categoria = $categoria;	
	}
	
	
	public function getCodigoCategoria(){
		return $this->catcod;
	}

	public function setCodigoCategoria($catcod){
		$this->catcod = $catcod;	
	}
	
	public function getTitulo(){
		return $this->pagtitulo;
	}
	
	public function setTitulo($pagtitulo){
		$this->pagtitulo = $pagtitulo;	
	}

	public function getSubtitulo(){
		return $this->pagsubtitulo;
	}
	
	public function setSubtitulo($pagsubtitulo){
		$this->pagsubtitulo = $pagsubtitulo;	
	}

	public function getDominio(){
		return $this->pagdominio;
	}
	
	public function setDominio($pagdominio){
		$this->pagdominio = $pagdominio;	
	}

	public function getTituloCorto(){		
		return $this->pagtitulocorto;
	}
	
	public function setTituloCorto($pagtitulocorto){
		$this->pagtitulocorto = $pagtitulocorto;	
	}

	public function getCopete(){
		return $this->pagcopete;
	}

	public function setCopete($pagcopete){
		$this->pagcopete = $pagcopete;	
	}

	public function getCuerpo(){
		return $this->pagcuerpo;
	}

	public function setCuerpo($pagcuerpo){
		$this->pagcuerpo = $pagcuerpo;	
	}

	public function getEstado(){
		return $this->pagestadocod;
	}

	public function setEstado($pagestadocod){
		$this->pagestadocod = $pagestadocod;	
	}

	public function getCodigoSuperior(){
		return $this->pagcodsuperior;
	}

	public function setCodigoSuperior($pagcodsuperior){
		$this->pagcodsuperior = $pagcodsuperior;	
	}

	public function getOrden(){
		return $this->pagorden;
	}

	public function setOrden($pagorden){
		$this->pagorden = $pagorden;	
	}	

	public function getCopiaCodigoOriginal(){
		return $this->pagcopiacodorig;
	}

	public function setCopiaCodigoOriginal($pagcopiacodorig){
		$this->pagcopiacodorig = $pagcopiacodorig;	
	}	
	
	public function getCopiaCodigo(){
		return $this->pagcopiacod;
	}

	public function setCopiaCodigo($pagcopiacod){
		$this->pagcopiacod = $pagcopiacod;	
	}			

	public function getFechaAlta(){
		return $this->pagfalta;
	}

	public function setFechaAlta($pagfalta){
		$this->pagfalta = $pagfalta;	
	}

	public function getFechaBaja(){
		return $this->pagfbaja;
	}

	public function setFechaBaja($pagfbaja){
		$this->pagfbaja = $pagfbaja;	
	}


	public function getPlantillaHtmlCodigo(){
		return $this->planthtmlcod;
	}

	public function setPlantillaHtmlCodigo($planthtmlcod){
		$this->planthtmlcod = $planthtmlcod;	
	}
	
	public function getImagenes(){
		return $this->imagenes;
	}

	public function setImagenes($imagenes){
		$this->imagenes = $imagenes;	
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
				

}
?>
