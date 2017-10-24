<?php 
include(DIR_DATA."agendaData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las paginas

class cAgenda
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function SetData(&$AgendaData,$datosevento)
	{
		$AgendaData->setCodigo($datosevento['agendacod']);
		$AgendaData->setCodigoCategoria($datosevento['catcod']);
		$AgendaData->setTitulo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datosevento['agendatitulo'],ENT_QUOTES));
		$AgendaData->setObservaciones(FuncionesPHPLocal::ProcesarHtmlCuerpoEditor($this->conexion,$datosevento['agendaobservaciones']));
		$AgendaData->setBajada(FuncionesPHPLocal::ProcesarHtmlCuerpoEditor($this->conexion,$datosevento['agendabajada']));
		$AgendaData->setFechaDesde($datosevento['agendafdesde']);
		$AgendaData->setFechaHasta($datosevento['agendafhasta']);
		$AgendaData->setHoraInicio($datosevento['horainicio']);
		$AgendaData->setHoraFin($datosevento['horafin']);
		$AgendaData->setEstado($datosevento['agendaestadocod']);
		if (isset($datosevento['agendaestadodesc']) && $datosevento['agendaestadodesc']!="")
			$AgendaData->setEstadoDesc($datosevento['agendaestadodesc']);
		
		$AgendaData->setDiaSemana($datosevento['agendadiasemana']);
		$AgendaData->setFechaAlta($datosevento['agendafalta']);

		$dominio="";
		$dominioform = FuncionesPHPLocal::EscapearCaracteres($datosevento['agendatitulo']);
		$dominioform=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioform));
		$dominioform=str_replace(' ', '-', trim($dominioform))."_e".$datosevento['agendacod'];
		$AgendaData->setDominio("agenda/".$dominioform);
		
		if (isset($datosevento['agendacod']))
		{
			//$dominiocompartir = DOMINIOWEB."ev".$datosnoticia['noticiacod'];
			$AgendaData->setDominioCompartir($dominioform);
		}
		
		return true;
	}
	
	
	public function BuscarEvento($datos)
	{
		$spnombre="sel_age_agenda_xagendacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datosevento = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oAgendaData = new AgendaData();
		$this->SetData($oAgendaData,$datosevento);
		
		return $oAgendaData;	
	}




	public function BuscarEventos($datos,&$CantidadTotal,$limit="")
	{

		$spnombre="sel_age_agenda_busqueda";
		$sparam=array(
			'pfields'=> "COUNT(a.agendacod) as total",		
			'pxagendafdesde'=> 0,
			'pagendafdesde'=> "",
			'pxcatcod'=> 0,
			'pcatcod'=> "",
			'pxfecha'=> 0,
			'pfechainicio'=> "",
			'pfechafin'=> "",
			'pxagendaestadocod'=> 0,
			'pagendaestadocod'=> "",
			'porderby'=> "agendafdesde DESC, horainicio DESC, agendafhasta DESC, horafin DESC",
			'plimit'=> ""
			);

				
		if (isset($datos['agendafdesde']) && $datos['agendafdesde']!="")
		{

			$sparam['porderby']="agendafdesde ASC";
			$sparam['pxagendafdesde'] = 1;
			$sparam['pagendafdesde'] = $datos['agendafdesde'];
		}

		if (isset ($datos['fechainicio']) && $datos['fechainicio']!="" && isset ($datos['fechafin']) && $datos['fechafin']!="")
		{	
			$sparam['porderby']="agendafdesde DESC";
			$sparam['pfechainicio']= $datos['fechainicio'];
			$sparam['pfechafin']= $datos['fechafin'];
			$sparam['pxfecha']= 1;
		}
		if (isset($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['pxcatcod'] = 1;
			$sparam['pcatcod'] = $datos['catcod'];
		}
		if (isset($datos['agendaestadocod']) && $datos['agendaestadocod']!="")
		{
			$sparam['pxagendaestadocod'] = 1;
			$sparam['pagendaestadocod'] = $datos['agendaestadocod'];
		}

		if (isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['porderby'] = $datos['orderby'];
		if (isset($datos['limit']) && $datos['limit']!="")
			$sparam['plimit'] = $datos['limit'];


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$CantidadTotal = 0;

		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
			
		}

		$sparam['pfields'] = "*";
		if ($limit!="")
			$sparam['plimit'] = $limit;		

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los eventos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		
		$ArregloEventos = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oAgendaData = new AgendaData();
			$this->SetData($oAgendaData,$fila);
			$ArregloEventos[] = $oAgendaData;
			unset($oAgendaData);
		}
		
		
		return $ArregloEventos;	
	
	}



	public function CargarImagenes(&$oData,$cantidad=NULL)
	{
			
		$spnombre="sel_age_agenda_mul_multimedia_xagendacod";
		$sparam=array(
			'pagendacod'=> $oData->getCodigo(),
			'pmultimediaconjuntocod'=> FOTOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las imagenes del evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$imagenes = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$imagenes[] = $oMultimedia;
			unset($oMultimedia);
		}
		unset($oMultimediaService);
		$oData->setImagenes($imagenes);

		return true;	
	}

	public function CargarVideos(&$oData,$cantidad=NULL)
	{
			
		$spnombre="sel_age_agenda_mul_multimedia_xagendacod";
		$sparam=array(
			'pagendacod'=> $oData->getCodigo(),
			'pmultimediaconjuntocod'=> VIDEOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los videos del evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$videos = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$videos[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oData->setVideos($videos);


		return true;	
	}


	public function CargarAudios(&$oData,$cantidad=NULL)
	{
			
		$spnombre="sel_age_agenda_mul_multimedia_xagendacod";
		$sparam=array(
			'pagendacod'=> $oData->getCodigo(),
			'pmultimediaconjuntocod'=> AUDIOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los audios del evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$audios = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$audios[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oData->setAudios($audios);


		return true;	
	}



}//FIN CLASE
?>