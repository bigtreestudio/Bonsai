<?php 
include(DIR_CLASES_DB."cNoticiasComentarios.db.php");

class cNoticiasComentarios extends cNoticiasComentariosdb
{

	protected $conexion;
	protected $formato;

	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}

	function __destruct(){parent::__destruct();}

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}



	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'noticiacod'=> "",
			'xnoticiacod'=> 0,
			'xcomentariocod'=> 0,
			'comentariocod'=> "",
			'xcomentarionombre'=> 0,
			'comentarionombre'=> "",
			'xnoticiatitulo'=> 0,
			'noticiatitulo'=> "",
			'xcomentarioemail'=> 0,
			'comentarioemail'=> "",
			'xcomentarioestado'=> 0,
			'comentarioestado'=> "-1",
			'limit'=> '',
			'orderby'=> "comentariofalta DESC"
		);

		if(isset($datos['noticiacod']) && $datos['noticiacod']!="")
		{
			$sparam['noticiacod']= $datos['noticiacod'];
			$sparam['xnoticiacod']= 1;
		}

		if(isset($datos['comentariocod']) && $datos['comentariocod']!="")
		{
			$sparam['comentariocod']= $datos['comentariocod'];
			$sparam['xcomentariocod']= 1;
		}
		
		if(isset($datos['comentarionombre']) && $datos['comentarionombre']!="")
		{
			$sparam['comentarionombre']= $datos['comentarionombre'];
			$sparam['xcomentarionombre']= 1;
		}
		
		if(isset($datos['noticiatitulo']) && $datos['noticiatitulo']!="")
		{
			$sparam['noticiatitulo']= $datos['noticiatitulo'];
			$sparam['xnoticiatitulo']= 1;
		}
		
		if(isset($datos['comentarioemail']) && $datos['comentarioemail']!="")
		{
			$sparam['comentarioemail']= $datos['comentarioemail'];
			$sparam['xcomentarioemail']= 1;
		}
		if(isset($datos['comentarioestado']) && $datos['comentarioestado']!="")
		{
			$sparam['comentarioestado']= $datos['comentarioestado'];
			$sparam['xcomentarioestado']= 1;
		}

		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];

		if(isset($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;
	}



	public function Insertar($datos,&$codigoinsertado)
	{
		
		if (!$this->_ValidarInsertar($datos))
			return false;

		$datos['comentariofalta']=$datos['comentariofalta'];
		$datos['comentarioestado'] = ACTIVO;
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		return true;
	}



	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
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



	public function ModificarEstado($datos)
	{
		if (!parent::ModificarEstado($datos))
			return false;
		return true;
	}



	public function Activar($datos)
	{
		$datosmodif['comentariocod'] = $datos['comentariocod'];
		$datosmodif['comentarioestado'] = ACTIVO;
		if (!parent::ModificarEstado($datosmodif))
			return false;
		return true;
	}



	public function DesActivar($datos)
	{
		$datosmodif['comentariocod'] = $datos['comentariocod'];
		$datosmodif['comentarioestado'] = NOACTIVO;
		if (!parent::ModificarEstado($datosmodif))
			return false;
		return true;
	}




//-----------------------------------------------------------------------------------------
//FUNCIONES PRIVADAS
//-----------------------------------------------------------------------------------------

	private function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



	private function _ValidarModificar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un código valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un código valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['noticiacod']) || $datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una noticia",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['noticiacod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numérico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$oNoticias = new cNoticias($this->conexion);
		if(!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error no existe la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comentarionombre']) || $datos['comentarionombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comentarioemail']) || $datos['comentarioemail']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un email",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['comentarioemail'],"Email"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un email valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comentariodesc']) || $datos['comentariodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una descripcion",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

/*		if (!isset($datos['comentariofalta']) || $datos['comentariofalta']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una fecha",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['comentariofalta'],"FechaDDMMAAAA"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una fecha valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
*/		return true;
	}





}
?>