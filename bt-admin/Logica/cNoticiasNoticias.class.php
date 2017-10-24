<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cNoticiasNoticias.db.php");

class cNoticiasNoticias extends cNoticiasNoticiasdb	
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
// Retorna verdadero o falso si puede o no agregar noticias relacionadas

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function PuedeEditarNoticiasRelacionadas($datos)
	{
		$oNoticias = new cNoticias($this->conexion,$this->formato);
		$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($this->conexion,$this->formato);
		if (!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datonoticia =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		
		switch ($datonoticia['noticiaestadocod'])
		{
			case NOTPUBLICADA:
			case NOTELIMINADA:
				return false;
		}
		$datonoticia['rolcod'] = $datos['rolcod'];
		if(!$oNoticiasWorkflowRoles->ObtenerAccionesRol($datonoticia,$resultado,$numfilas))
			return false;
		
		if ($numfilas>0)
			return true;
			
		return false;	
	}
	
	

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con las noticias relacionadas de una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiasRelacionadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$datosenviar=array(
			'noticiacod'=> $datos['noticiacod'],
			'xnoticiaestadocod'=> 0,
			'noticiaestadocod'=> ""
			);		
		
		if (isset($datos['noticiaestadocod']) && $datos['noticiaestadocod']!="")
		{
			$datosenviar['noticiaestadocod'] = $datos['noticiaestadocod'];	
			$datosenviar['xnoticiaestadocod'] = 1;	
		}
		if (!parent::BuscarNoticiasRelacionadasxNoticia($datosenviar,$resultado,$numfilas))
			return false;

		return true;
	}
	
	public function BuscarNoticiasRelacionadasPublicadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$datosenviar=array(
			'noticiacod'=> $datos['noticiacod'],
			);		
		

		if (!parent::BuscarNoticiasRelacionadasPublicadasxNoticia($datosenviar,$resultado,$numfilas))
			return false;

		return true;
	}	
//----------------------------------------------------------------------------------------- 
// Retorna una consulta con las noticias relacionadas de una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiasRelacionadasxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarNoticiasRelacionadasxCodigoNoticia($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con la noticia relacionada de una noticia y su relacion

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			noticiacodrel = codigo de la noticia relacionada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiasRelacionadasxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarNoticiasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}



	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['noticiaimportante'] = 0;
		$datos['noticiaorden'] = $proxorden;
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['noticiafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}


	public function InsertarDuplicar($datos)
	{

		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['noticiafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}





	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
		return true;	
	}

	public function EliminarCompletoxNoticiacod($datos)
	{

		if (!parent::EliminarCompletoxNoticiacod($datos))
			return false;
		return true;	
	}


	public function DestacarNoticia($datos)
	{
		$datos['noticiaimportante']=1;
		if(!$this->ModificarImportancia($datos))
			return false;
		return true;
	}

	public function NoDestacarNoticia($datos)
	{
		$datos['noticiaimportante']=0;
		if(!$this->ModificarImportancia($datos))
			return false;
		return true;
	}

	public function ModificarImportancia($datos)
	{
		if (!$this->_ValidarModificarImportancia($datos))
			return false;
			
		if (!parent::ModificarImportancia($datos))
			return false;
		return true;	
	}


	public function ModificarOrden($datos)
	{
		if($this->PuedeEditarNoticiasRelacionadas($datos))
		{
			$datosmodif['noticiaorden'] = 1;
			$datosmodif['noticiacod'] = $datos['noticiacod'];
			$arreglonoticias = explode(",",$datos['noticia']);
			foreach ($arreglonoticias as $noticiacodrel)
			{
				$datosmodif['noticiacodrel'] = $noticiacodrel;
				if (!parent::ModificarOrden($datosmodif))
					return false;
				$datosmodif['noticiaorden']++;
			}
		}else
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para ordenar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _ValidarInsertar($datos)
	{
		$ArregloDatos['noticiacod']=$datos['noticiacod'];
		$oNoticias = new cNoticias($this->conexion,$this->formato);
		if (!$oNoticias->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$ArregloDatos['noticiacod']=$datos['noticiacodrel'];
		if (!$oNoticias->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->BuscarNoticiasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas==1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La noticia ya se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->PuedeEditarNoticiasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para agregar una noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarNoticiasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La noticia no se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->PuedeEditarNoticiasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para eliminar una noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _ValidarModificarImportancia($datos)
	{
		if (!$this->BuscarNoticiasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La noticia no se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->PuedeEditarNoticiasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para modificar una noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarNoticiaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}

}//FIN CLASE

?>