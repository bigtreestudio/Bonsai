<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cNoticiasCategorias.db.php");

class cNoticiasCategorias extends cNoticiasCategoriasdb	
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
// Retorna una consulta con las noticias relacionadas de una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarCategoriasxNoticia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarCategoriasxNoticia($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con la noticia relacionada de una noticia y su relacion

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			catcod = codigo de la categoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarCategoriasxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarCategoriasxCodigo($datos,$resultado,$numfilas))
			return false;

		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si se pudo ejecutar con exito

// Parámetros de Entrada:
//		datos: arreglo de datos
//			categorias = arreglo con las categorias a insertar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Actualizar($datos)
	{
		$datosdevueltos = array();
		foreach ($datos['catcodrel'] as $catcod) 
			$datosdevueltos[$catcod]=$catcod;
			
		if(count($datosdevueltos)>0)
		{
				$oCategorias = new cCategorias($this->conexion,$this->formato);
				$datosbuscar['catcod'] = implode(",",$datosdevueltos);
				$datosbuscar['catestado'] = ELIMINADO;
				if(!$oCategorias->BusquedaAvanzada($datosbuscar,$resultadoCatEliminado,$numfilasCatEliminado))
					return false;
				if($numfilasCatEliminado>0)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error hay categorias que fueron Eliminadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
				
				$datosbuscar['catestado'] = NOACTIVO;
				if(!$oCategorias->BusquedaAvanzada($datosbuscar,$resultadoCatNoActivo,$numfilasCatNoActivo))
					return false;
				if($numfilasCatNoActivo>0)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error hay categorias que estan No Activas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
					
		}	

		if(!$this->BuscarCategoriasxNoticia($datos,$resultado,$numfilas))
			return false;
			
		$arregloinsertados = array();
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloinsertados[$fila['catcod']] = $fila['catcod'];
		
		$arregloeliminar = array_diff($arregloinsertados,$datosdevueltos);
		$arregloinsertar = array_diff($datosdevueltos,$arregloinsertados);
		
		$datoseliminar['noticiacod'] = $datosinsertar['noticiacod'] = $datos['noticiacod'];
		foreach ($arregloinsertar as $catcod)
		{
			$datosinsertar['catcod'] = $catcod;
			if(!$this->Insertar($datosinsertar))
				return false;
		}

		foreach ($arregloeliminar as $catcod)
		{
			$datoseliminar['catcod'] = $catcod;
			if(!$this->Eliminar($datoseliminar))
				return false;
		}

		return true;
	}



	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['notcatfalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}


	public function InsertarDuplicar($datos)
	{

		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['notcatfalta'] = date("Y/m/d H:i:s");
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

		$oCategorias = new cCategorias($this->conexion,$this->formato);
		if (!$oCategorias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Categoria inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$filacat = $this->conexion->ObtenerSiguienteRegistro($resultado);
		if($filacat['catestado'] == ELIMINADO)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error categoria fue eliminada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if($filacat['catestado'] == NOACTIVO)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error categoria no esta activa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->BuscarCategoriasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas==1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La categoria ya se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarCategoriasxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La categoria no se encuentra relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}




}//FIN CLASE

?>