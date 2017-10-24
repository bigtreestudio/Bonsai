<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cNoticiasGalerias.db.php");

class cNoticiasGalerias extends cNoticiasGaleriasdb	
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
// Retorna verdadero o falso si puede o no editar una galeria relacionada

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function PuedeEditarGaleriasRelacionadas($datos)
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
// Retorna una consulta con las galerias relacionadas de una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarGaleriasRelacionadasxNoticia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarGaleriasRelacionadasxNoticia($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con la galeria relacionada de una noticia y su relacion

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			galeriacod = codigo de la galeria relacionada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarGaleriasRelacionadasxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarGaleriasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}



	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['galeriaimportante'] = 0;
		$datos['galeriaorden'] = $proxorden;
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['galeriafalta'] = date("Y/m/d H:i:s");
		
		
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Insertar Galerias a una noticia //NO VALIDA DATOS

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			galeriacod = codigo de la galeria relacionada

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function InsertarDuplicar($datos)
	{
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['galeriafalta'] = date("Y/m/d H:i:s");
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

	public function DestacarGaleria($datos)
	{
		$datos['galeriaimportante']=1;
		if(!$this->ModificarImportancia($datos))
			return false;
		return true;
	}

	public function NoDestacarGaleria($datos)
	{
		$datos['galeriaimportante']=0;
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
		if($this->PuedeEditarGaleriasRelacionadas($datos))
		{
			$datosmodif['galeriaorden'] = 1;
			$datosmodif['noticiacod'] = $datos['noticiacod'];
			$arreglogalerias = explode(",",$datos['galeria']);
	
			foreach ($arreglogalerias as $galeriacod)
			{
				$datosmodif['galeriacod'] = $galeriacod;
				if (!parent::ModificarOrden($datosmodif))
					return false;
				$datosmodif['galeriaorden']++;
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
		$oGalerias = new cGalerias($this->conexion,$this->formato);
		if (!$oNoticias->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$ArregloDatos['galeriacod']=$datos['galeriacod'];
		if (!$oGalerias->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
			
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Galeria inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!$this->BuscarGaleriasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas==1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La galeria ya se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if(!$this->PuedeEditarGaleriasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para agregar una galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarGaleriasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La galeria no se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->PuedeEditarGaleriasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para eliminar una galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _ValidarModificarImportancia($datos)
	{
		if (!$this->BuscarGaleriasRelacionadasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La galeria no se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(!$this->PuedeEditarGaleriasRelacionadas($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no tiene permisos para modificar una galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}




	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarGaleriaUltimoOrden($datos,$resultado,$numfilas))
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