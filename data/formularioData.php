<?php 
class FormularioData {

	private $formulariocod;
	private $formulariotipocod;
	private $formulariotipotitulo;
	private $formulariodireccion;
	private $formulariotelefono1;
	private $formulariotelefono2;
	private $formulariocelular;
	private $formulariomail;
	private $formularioweb;
	private $formulariotwitter;
	private $formulariofacebook;
	private $formulariolatitud;
	private $formulariolongitud;
	private $formulariomapazoom;
	private $formulariomapatipo;
	private $formulariociudad;
	private $formulariocp;
	private $formulariopiso;	
	private $provinciacod;	
	private $paiscod;
	private $formulariojson;
	private $formulariotexto;
	private $formulariomaildesde;	
	private $formularioestado;
	private $formulariodominio;
	private $formulariodisclaimer;
	private $menucod;	
	private $mentipocod;	


	function __construct(){
		}


	//GETTERS Y SETTERS DE LOS FORMULARIOS//
	
	public function getCodigo(){
		return $this->formulariocod;
	}

	public function setCodigo($formulariocod){
		$this->formulariocod = $formulariocod;	
	}
	
	public function getTipoCodigo(){
		return $this->formulariotipocod;
	}

	public function setTipoCodigo($formulariotipocod){
		$this->formulariotipocod = $formulariotipocod;	
	}

	public function getTitulo(){
		return $this->formulariotipotitulo;
	}
	public function getCodigoPostal(){
		return $this->formulariocp;
	}
	
	public function setCodigoPostal($formulariocp){
		$this->formulariocp = $formulariocp;	
	}
	
	public function getPiso(){
		return $this->formulariopiso;
	}		
	
	public function setPiso($formulariopiso){
		$this->formulariopiso = $formulariopiso;
	} 
		
	
	public function setTitulo($formulariotipotitulo){
		$this->formulariotipotitulo = $formulariotipotitulo;	
	}

	public function getDireccion(){
		return $this->formulariodireccion;
	}
	
	public function setDireccion($formulariodireccion){
		$this->formulariodireccion = $formulariodireccion;	
	}
	public function getTelefono1(){		
		return $this->formulariotelefono1;
	}
	public function setTelefono1($formulariotelefono1){
		$this->formulariotelefono1 = $formulariotelefono1;	
	}
	public function getTelefono2(){		
		return $this->formulariotelefono2;
	}
	public function setTelefono2($formulariotelefono2){
		$this->formulariotelefono2 = $formulariotelefono2;	
	}	
	public function getCelular(){		
		return $this->formulariocelular;
	}
	public function setCelular($formulariocelular){
		$this->formulariocelular = $formulariocelular;	
	}
	public function getMail(){		
		return $this->formulariomail;
	}
	public function setMail($formulariomail){
		$this->formulariomail = $formulariomail;	
	}
	public function getWeb(){		
		return $this->formularioweb;
	}
	public function setWeb($formularioweb){
		$this->formularioweb = $formularioweb;	
	}

	public function getTwitter(){		
		return $this->formulariotwitter;
	}
	public function setTwitter($formulariotwitter){
		$this->formulariotwitter = $formulariotwitter;	
	}
	public function getFacebook(){		
		return $this->formulariofacebook;
	}
	public function setFacebook($formulariofacebook){
		$this->formulariofacebook = $formulariofacebook;	
	}	
	public function getLatitud(){		
		return $this->formulariolatitud;
	}
	public function setLatitud($formulariolatitud){
		$this->formulariolatitud = $formulariolatitud;	
	}	
	public function getLongitud(){		
		return $this->formulariolongitud;
	}
	public function setLongitud($formulariolongitud){
		$this->formulariolongitud = $formulariolongitud;	
	}	
	public function getMapaZoom(){		
		return $this->formulariomapazoom;
	}
	public function setMapaZoom($formulariomapazoom){
		$this->formulariomapazoom = $formulariomapazoom;	
	}	

	public function getMapaTipo(){		
		return $this->formulariomapatipo;
	}
	public function setMapaTipo($formulariomapatipo){
		$this->formulariomapatipo = $formulariomapatipo;	
	}	


	public function getCiudad(){		
		return $this->formulariociudad;
	}
	public function setCiudad($formulariociudad){
		$this->formulariociudad = $formulariociudad;	
	}	
	public function getProvinciaCodigo(){		
		return $this->provinciacod;
	}
	public function setProvinciaCodigo($provinciacod){
		$this->provinciacod = $provinciacod;	
	}		
	public function getPaisCodigo(){		
		return $this->paiscod;
	}
	public function setPaisCodigo($paiscod){
		$this->paiscod = $paiscod;	
	}	
	public function getDatosExtra(){		
		return $this->formulariojson;
	}
	public function setDatosExtra($formulariojson){
		$this->formulariojson = $formulariojson;	
	}	
	public function getDisclaimer(){		
		return $this->formulariodisclaimer;
	}
	public function setDisclaimer($formulariodisclaimer){
		$this->formulariodisclaimer = $formulariodisclaimer;	
	}	

	public function getTexto(){		
		return $this->formulariotexto;
	}
	public function setTexto($formulariotexto){
		$this->formulariotexto = $formulariotexto;	
	}	


	public function getMailDesde(){		
		return $this->formulariomaildesde;
	}
	public function setMailDesde($formulariomaildesde){
		$this->formulariomaildesde = $formulariomaildesde;	
	}
	public function getEstado(){		
		return $this->formularioestado;
	}
	public function setEstado($formularioestado){
		$this->formularioestado = $formularioestado;	
	}

	public function getDominio(){		
		return $this->formulariodominio;
	}
	public function setDominio($formulariodominio){
		$this->formulariodominio = $formulariodominio;	
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
