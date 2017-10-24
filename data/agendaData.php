<?php 
class AgendaData {

	private $agendacod;
	private $catcod;
	private $agendatitulo;
	private $agendabajada;
	private $agendaobservaciones;
	private $agendafdesde;
	private $agendafhasta;
	private $horainicio;
	private $horafin;
	private $agendaestadocod;
	private $agendadiasemana;
	private $agendafalta;
	private $agendadominio;
	private $imagenes;
	private $videos;
	private $audios;
	private $agendadominiocompartir;
	private $agendaestadodesc;

	function __construct(){
		}


	//GETTERS Y SETTERS DE LAS AGENDAS//
	public function getCodigo(){
		return $this->agendacod;
	}

	public function setCodigo($agendacod){
		$this->agendacod = $agendacod;	
	}
	
	public function getCodigoCategoria(){
		return $this->catcod;
	}

	public function setCodigoCategoria($catcod){
		$this->catcod = $catcod;	
	}


	public function getTitulo(){
		return $this->agendatitulo;
	}
	
	public function setTitulo($agendatitulo){
		$this->agendatitulo = $agendatitulo;	
	}

	public function getObservaciones(){
		return $this->agendaobservaciones;
	}
	
	public function setObservaciones($agendaobservaciones){
		$this->agendaobservaciones = $agendaobservaciones;	
	}

	public function getFechaDesdesinFormato(){		
		return $this->agendafdesde;
	}

	public function getFechaDesde($formato= "l d \d\e F \d\e Y"){
		$texto = date($formato,strtotime($this->agendafdesde));
		$noticiafecha = strtolower(FuncionesPHPLocal::ReemplazarTextoFechas($texto));
		return $noticiafecha;
	}
	
	public function setFechaDesde($agendafdesde){
		$this->agendafdesde = $agendafdesde;	
	}

	public function getFechaHastasinFormato(){
		return $this->agendafhasta;
	}
	
	public function getFechaHasta($formato= "l d \d\e F \d\e Y"){
		$texto = date($formato,strtotime($this->agendafhasta));
		$noticiafecha = strtolower(FuncionesPHPLocal::ReemplazarTextoFechas($texto));
		return $noticiafecha;
	}
	

	public function setFechaHasta($agendafhasta){
		$this->agendafhasta = $agendafhasta;	
	}

	public function getHoraInicio(){
		return $this->horainicio;
	}

	public function setHoraInicio($horainicio){
		$this->horainicio = $horainicio;	
	}
	
	public function getHoraFin(){
		return $this->horafin;
	}

	public function setHoraFin($horafin){
		$this->horafin = $horafin;	
	}	
	
	public function getEstado(){
		return $this->agendaestadocod;
	}

	public function setEstado($agendaestadocod){
		$this->agendaestadocod = $agendaestadocod;	
	}

	public function getDiaSemana(){
		return $this->agendadiasemana;
	}

	public function setDiaSemana($agendadiasemana){
		$this->agendadiasemana = $agendadiasemana;	
	}

	public function getFechaAlta(){
		return $this->agendafalta;
	}

	public function setFechaAlta($agendafalta){
		$this->agendafalta = $agendafalta;	
	}	

	public function getBajada(){
		return $this->agendabajada;
	}

	public function setBajada($agendabajada){
		$this->agendabajada = $agendabajada;	
	}	


	public function getDominio(){
		return $this->agendadominio;
	}

	public function setDominio($agendadominio){
		$this->agendadominio = $agendadominio;	
	}	

	public function getDominioCompartir(){
		return $this->agendadominiocompartir;
	}

	public function setDominioCompartir($agendadominiocompartir){
		$this->agendadominiocompartir = $agendadominiocompartir;	
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
						
	public function getImagenes(){
		return $this->imagenes;
	}

	public function setImagenes($imagenes){
		$this->imagenes = $imagenes;	
	}

	public function getEstadoDesc(){
		return $this->agendaestadodesc;
	}

	public function setEstadoDesc($agendaestadodesc){
		$this->agendaestadodesc = $agendaestadodesc;	
	}

						
}
?>
