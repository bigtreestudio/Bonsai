<?php 
/**
 * @clase logica de la agenda
 * 
 * @author      <Bigtree Studio SRL>
 * @descripcion  <Clase con la lógica para el manejo de los eventos de la agenda>
 * @extend  <cAgendadb - Clase con los accesos a los stored_procedures de cAgenda>
*/

include(DIR_CLASES_DB."cAgenda.db.php");

class cAgenda extends cAgendadb
{
	protected $conexion;
	protected $formato;

	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	





	
//-----------------------------------------------------------------------------------------
//							FUNCIONES PUBLICAS	
//----------------------------------------------------------------------------------------- 


// Retorna una consulta con los datos completos del evento

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo de la agenda

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;	
	}


//------------------------------------------------------------------------------------	
// Retorna en un query todos los eventos de una categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCategoria ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCategoria($datos,$resultado,$numfilas))
			return false;
		
		return true;	
	}


//------------------------------------------------------------------------------------------	
// Retorna un conjunto de registros con la busqueda sobre la agenda

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos
//		agendacod = codigo de la agenda
//		fechainicio = fecha de inicio del evento
//		fechafin = fecha de finalización del evento
//		agendatitulo = titulo del evento (busca con la condición like)
//		agendaestadocod = estado de la agenda (busca con la condición in - se puede pasar mas de un parametro, deben ser separados con coma)
//		catcod = codigo de la categoria
//		orderby = Campo para el ordenamiento de los resultados
//		limit = Campo para filtrar por cantidad de resultados - se debe pasar el siguiente formato - LIMIT 0,10.

// Retorna:
//		resultado= Arreglo con todos los eventos encontrados.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function BuscarAgendaBusquedaAvanzanda($ArregloDatos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xagendacod'=> 0,
			'agendacod'=> "",
			'xfecha'=> 0,
			'fechainicio'=> "",
			'fechafin'=> "",
			'xagendatitulo'=> 0,
			'agendatitulo'=> "",
			'xagendaestadocod'=> 0,
			'agendaestadocod'=> "-1",
			'xcatcod'=> 0,
			'catcod'=> "",
			'orderby'=> "agendafdesde ASC, horainicio ASC",
			'limit'=> ""
			);

		if (isset ($ArregloDatos['agendacod']) && $ArregloDatos['agendacod']!="")
		{	
			$sparam['agendacod']= $ArregloDatos['agendacod'];
			$sparam['xagendacod']= 1;
		}

		if (isset ($ArregloDatos['agendatitulo']) && $ArregloDatos['agendatitulo']!="")
		{	
			$sparam['agendatitulo']= $ArregloDatos['agendatitulo'];
			$sparam['xagendatitulo']= 1;
		}
		if (isset ($ArregloDatos['fechainicio']) && $ArregloDatos['fechainicio']!="" && isset ($ArregloDatos['fechafin']) && $ArregloDatos['fechafin']!="")
		{	
			$sparam['fechainicio']= $ArregloDatos['fechainicio'];
			$sparam['fechafin']= $ArregloDatos['fechafin'];
			$sparam['xfecha']= 1;
		}

		if (isset ($ArregloDatos['agendaestadocod']) && $ArregloDatos['agendaestadocod']!="")
		{	
			$sparam['agendaestadocod']= $ArregloDatos['agendaestadocod'];
			$sparam['xagendaestadocod']= 1;
		}

		if (isset ($ArregloDatos['catcod']) && $ArregloDatos['catcod']!="")
		{	
			$sparam['catcod']= $ArregloDatos['catcod'];
			$sparam['xcatcod']= 1;
		}

		if (isset ($ArregloDatos['orderby']) && $ArregloDatos['orderby']!="")
			$sparam['orderby']= $ArregloDatos['orderby'];
			
		if (isset ($ArregloDatos['limit']) && $ArregloDatos['limit']!="")
			$sparam['limit']= $ArregloDatos['limit'];

		if (!parent::BuscarAgendaBusquedaAvanzanda($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}



//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un categoria 

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function BuscarAgendaBusquedaFechaMayor($ArregloDatos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xfecha'=> 0,
			'fechainicio'=> "",
			'xagendaestadocod'=> 0,
			'agendaestadocod'=> "-1",
			'orderby'=> "agendafdesde ASC, horainicio ASC",
			'limit'=> ""
			);

		
		if (isset ($ArregloDatos['fechainicio']) && $ArregloDatos['fechainicio']!="")
		{	
			$sparam['fechainicio']= $ArregloDatos['fechainicio'];
			$sparam['xfecha']= 1;
		}

		if (isset ($ArregloDatos['agendaestadocod']) && $ArregloDatos['agendaestadocod']!="")
		{	
			$sparam['agendaestadocod']= $ArregloDatos['agendaestadocod'];
			$sparam['xagendaestadocod']= 1;
		}

		if (isset ($ArregloDatos['orderby']) && $ArregloDatos['orderby']!="")
			$sparam['orderby']= $ArregloDatos['orderby'];
			
		if (isset ($ArregloDatos['limit']) && $ArregloDatos['limit']!="")
			$sparam['limit']= $ArregloDatos['limit'];


		if (!parent::BuscarAgendaBusquedaFechaMayor($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}


//------------------------------------------------------------------------------------------	
// Retorna un conjunto de eventos por estado

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos
//		agendaestadocod = estado de la agenda (busca con la condición in - se puede pasar mas de un parametro, deben ser separados con coma)

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	public function BuscarAgendaxEstado ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAgendaxEstado($datos,$resultado,$numfilas))
			return false;
		return true;
	}
	
	
//------------------------------------------------------------------------------------------	
// Retorna los eventos de la agenda por un rango de fechas

// Parámetros de Entrada:
//	fecha1: fecha inicial a buscar
//	fecha2: fecha final a buscar

// Retorna:
//		resultado= query con los resultados encontrados.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function TraerAgendaxRango($datos,&$resultado,&$numfilas)
	{
		if(!parent::TraerAgendaxRango($datos,$resultado,$numfilas))
			return false;
		
		return true;	
	}
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Inserta un evento nuevo en la agenda.

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	agendaestadocod = estado de la agenda
//	horariofdesde  = fecha de inicio de la agenda
//	horariofhasta = fecha de fin de la agenda
//	agendaobservaciones = observaciones de la agenda
//	agendatitulo = titulo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar ($datos, &$codigoinsertado)
	{
		
		if (!$this->ValidarInsertar($datos))
			return false;
		
		$datosinsertar["agendadiasemana"]=FuncionesPHPLocal::ReemplazarDiasSemanaBase(date("D",strtotime($datos["horariofdesde"])));
		$datosinsertar['horainicio'] = $datos['horariodesde'].":01";
		$datosinsertar['horafin'] = $datos['horariohasta'];
		$datosinsertar['agendaestadocod'] = $datos['agendaestadocod'];
		$datosinsertar['agendafdesde'] = $datos['horariofdesde'];
		$datosinsertar['agendafhasta'] = $datos['horariofhasta'];
		$datosinsertar['agendatitulo'] = $datos['agendatitulo'];
		$datosinsertar['agendaobservaciones'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['agendaobservaciones']);
		$datosinsertar['agendabajada'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['agendabajada']);
		$datosinsertar['catcod'] = $datos['catcod'];

		if (!parent::Insertar($datosinsertar,$codigoinsertado))
			return false;

		return true;
	}



//----------------------------------------------------------------------------------------- 
// Modifica los datos de un evento de la agenda

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	agendaestadocod = estado de la agenda
//	horariofdesde  = fecha de inicio de la agenda
//	horariofhasta = fecha de fin de la agenda
//	agendaobservaciones = observaciones de la agenda
//	agendatitulo = titulo de la agenda
//	agendacod = codigo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar ($datos)
	{
		if (!$this->ValidarModificar($datos))
			return false;
        
		$datosmodificar["agendadiasemana"]=FuncionesPHPLocal::ReemplazarDiasSemanaBase(date("D",strtotime($datos["horariofdesde"])));
		$datosmodificar['horainicio'] = $datos['horariodesde'].":01";
		$datosmodificar['horafin'] = $datos['horariohasta'];
		$datosmodificar['agendaestadocod'] = $datos['agendaestadocod'];
		$datosmodificar['agendafdesde'] = $datos['horariofdesde'];
		$datosmodificar['agendafhasta'] = $datos['horariofhasta'];
		$datosmodificar['agendaobservaciones'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['agendaobservaciones']);
		$datosmodificar['agendabajada'] = preg_replace("/<p[^>]*><\\/p[^>]*>/",'<div class="space">&nbsp;</div>',$datos['agendabajada']);
		$datosmodificar['agendatitulo'] =  $datos['agendatitulo'];
		$datosmodificar['agendacod'] = $datos['agendacod'];
		$datosmodificar['catcod'] = $datos['catcod'];

		if (!parent::Modificar($datosmodificar))
			return false;
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica el horario de un evento de la agenda

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	'horariofdesde  = fecha de inicio de la agenda
//	'horariofhasta = fecha de fin de la agenda
//	agendacod = codigo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarHorarioAgenda ($datos)
	{
		
		if (!$this->BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no existe en la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!$this->ValidarHorarioAgenda($datos))
			return false;


		$datosmodificar['agendafdesde'] = $datos['horariofdesde'];
		$datosmodificar['agendafhasta'] = $datos['horariofhasta'];
		$datosmodificar['agendacod'] = $datos['agendacod'];
		$datosmodificar['horainicio'] = $datos['horariodesde'].":01";
		$datosmodificar['horafin'] = $datos['horariohasta'];
		
        
		if (!parent::ModificarHorarioAgenda($datosmodificar))
			return false;
		
		return true;
	}
	
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica el estado de un evento de la agenda

// Parámetros de Entrada:
//		agendacod = codigo de agenda.
//      agendaestadocod = nuevo estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarEstadoAgenda ($datos)
	{
		if (!$this->ValidarModificarEstadoAgenda($datos))
			return false;
        
		$datosmodificar['agendaestadocod'] = $datos['agendaestadocod'];
		$datosmodificar['agendacod'] = $datos['agendacod'];

		if (!parent::ModificarEstadoAgenda($datosmodificar))
			return false;
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Elimina un evento de la agenda

// Parámetros de Entrada:
//		agendacod = codigo de la agenda eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar ($datos)
	{

		if (!$this->ValidarEliminar($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;

		$oAgendaMultimedia = new cAgendaMultimedia($this->conexion,$this->formato);
		if(!$oAgendaMultimedia->EliminarCompletoxEventocod($datos))
			return false;

		return true;
	}
	
	




//-----------------------------------------------------------------------------------------
//							FUNCIONES PRIVADAS	
//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna True o false si algunos de los campos esta vacio o mal ingresado

// Parámetros de Entrada:
//		agendatitulo = Titulo del evento, verifica que al menos lo haya ingresado.
//		agendaestadocod = Estado del evento, verifica que al menos lo haya ingresado.
//		catcod = Categoria del evento, verifica que al menos lo haya ingresado y que exista.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function ValidarDatosVacios($datos)
	{

		if (!isset ($datos['agendatitulo']) || ($datos['agendatitulo']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un titulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['agendaestadocod']) || ($datos['agendaestadocod']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['catcod']) || ($datos['catcod']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oAgendaCategoria = new cAgendaCategorias($this->conexion,$this->formato);
		if(!$oAgendaCategoria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;	
	}
	
	
	
//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//
// Funcion que verifica si la hora de inicio es mayor a la hora de finalización (utilizando la fecha tambien).

// Parámetros de Entrada:
//		datos = array de datos con los horarios y fechas a verificar .
//			horainicio = hora de inicio del evento.
//			minutosinicio = minutos de inicio del evento.
//			horafin = hora de finalizacion del evento.
//			minutosfin = minutos de finalización del evento.
//			diainicio = dias de inicio del evento.
//			diafin = dia de finalización del evento.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function ValidarHorarioAgenda($datos)
	{
		
		//VALIDO HORA DE INICIO
		if (!isset ($datos['horainicio']) || ($datos['horainicio']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora inicio.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['horainicio'],"NumericoEntero"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora inicio numerica.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['horainicio']<0 || $datos['horainicio']>23)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora inicio valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		//VALIDO MINUTOS DE INICIO
		if (!isset ($datos['minutosinicio']) || ($datos['minutosinicio']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos inicio.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['minutosinicio'],"NumericoEntero"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos inicio numericos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['minutosinicio']<0 || $datos['minutosinicio']>59)

		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos inicio validos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		

		//VALIDO HORARIO FIN
		if (!isset ($datos['horafin']) || ($datos['horafin']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora fin.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['horafin'],"NumericoEntero"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora fin numerica.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['horafin']<0 || $datos['horafin']>23)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora fin valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['minutosfin']) || ($datos['minutosfin']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos fin.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['minutosfin'],"NumericoEntero"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos fin numericos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['minutosfin']<0 || $datos['minutosfin']>59)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar unos minutos fin validos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		//VALIDO QUE LA HORA DE INICIO SEA MENOR QUE LA HORA DE FIN
		$time1 = $datos['horainicio'].":".$datos['minutosinicio'];
		$time2 = $datos['horafin'].":".$datos['minutosfin'];
		if (cDateTime::EsHoraMayor($time1,$time2))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una hora fin mayor que una hora inicio del evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!isset ($datos['agendafdesde']) || ($datos['agendafdesde']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una fecha de inicio.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['agendafdesde'],"FechaDDMMAAAA"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una fecha de inicio valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset ($datos['agendafhasta']) || ($datos['agendafhasta']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una fecha de fin.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['agendafhasta'],"FechaDDMMAAAA"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una fecha de fin valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		//VALIDO QUE LA HORA DE FECHA SEA MENOR QUE LA FECHA DE FIN
		$datos['horariofdesde'] = FuncionesPHPLocal::ConvertirFecha($datos['agendafdesde'],"dd/mm/aaaa","aaaa-mm-dd");
		$datos['horariofhasta'] = FuncionesPHPLocal::ConvertirFecha($datos['agendafhasta'],"dd/mm/aaaa","aaaa-mm-dd");
		if (cDateTime::EsFechaMayor($datos['horariofdesde'],$datos['horariofhasta']))
		{
			$error = "La fecha (fin) debe ser mayor o igual a la fecha inicio.";
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//
// Retorna true o false al dar de alta si algunos de los campos esta vacio y verifica el horario a insertar

// Parámetros de Entrada:
	//datos validados en la funcion ValidarDatosVacios
	//horainicio: Hora de inicio del evento
	//minutosinicio: Minutos de inicio del evento
	//Datos validados en ValidarHorarioAgenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function ValidarInsertar($datos)
	{
		if (!$this->ValidarDatosVacios($datos))
			return false;

		$datos['horariodesde'] = $_POST['horainicio'].":".$_POST['minutosinicio'];
		if (!$this->ValidarHorarioAgenda($datos))
			return false;
		
		return true;	
	}



//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//
// Retorna true o false al dar de alta si algunos de los campos esta vacio y verifica el horario a modificar

// Parámetros de Entrada:
	//datos validados en la funcion ValidarDatosVacios
	//horainicio: Hora de inicio del evento
	//minutosinicio: Minutos de inicio del evento
	//Datos validados en ValidarHorarioAgenda
	
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function ValidarModificar($datos)
	{
		if (!$this->ValidarDatosVacios($datos))
			return false;

		$datos['horariodesde'] = $_POST['horainicio'].":".$_POST['minutosinicio'];
		if (!$this->ValidarHorarioAgenda($datos))
			return false;
		
		return true;	
	}
	
	
//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//
// Retorna true o false verifica si existe el código para poder modificar, ademas verifica que haya ingresado al menos un estado
// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function ValidarModificarEstadoAgenda($datos)
	{
		if (!$this->BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no existe el evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['agendaestadocod']) || ($datos['agendaestadocod']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado del evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



//-------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------//
// Retorna true o false verifica si existe el código para poder eliminar

// Parámetros de Entrada:
//		agendacod = Codigo de la agenda a eliminar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no existe el evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una agenda cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo de la agenda
//			agendaestado: estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarDesactivar ($datos)
	{
		
		if (!$this->_ValidarActivarDesactivar($datos))
			return false;
	
		if (!parent::ActivarDesactivar($datos))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar una agenda cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo de la agenda
//			agendaestado: estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	public function Activar($datos)
	{
		$datos['agendaestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar una agenda cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo de la agenda
//			agendaestado: estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['agendaestado'] = NOACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 

    
	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, evento inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}
	
}

?>