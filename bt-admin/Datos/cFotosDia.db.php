<?php 
abstract class cFotosDiadb
{


	function __construct(){}

	function __destruct(){}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_mul_fotos_dia_xfotodiacod";
		$sparam=array(
			'pfotodiacod'=> $datos['fotodiacod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar al buscar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_mul_fotos_dia_busqueda_avanzada";
		$sparam=array(
			'pxfotodiacod'=> $datos['xfotodiacod'],
			'pfotodiacod'=> $datos['fotodiacod'],
			'pxfotodiatitulo'=> $datos['xfotodiatitulo'],
			'pfotodiatitulo'=> $datos['fotodiatitulo'],
			'pxfotodiaestado'=> $datos['xfotodiaestado'],
			'pfotodiaestado'=> $datos['fotodiaestado'],
			'plimit'=> $datos['limit'],
			'porderby'=> $datos['orderby']
		);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al realizar la búsqueda avanzada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_mul_fotos_dia";
		$sparam=array(
			'pmultimediacod'=> $datos['multimediacod'],
			'pfotodiatitulo'=> $datos['fotodiatitulo'],
			'pfotodiadesc'=> $datos['fotodiadesc'],
			'pfotodiaestado'=> $datos['fotodiaestado'],
			'pfotofecha'=> $datos['fotofecha'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}



	protected function Modificar($datos)
	{
		$spnombre="upd_mul_fotos_dia_xfotodiacod";
		$sparam=array(
			'pmultimediacod'=> $datos['multimediacod'],
			'pfotodiatitulo'=> $datos['fotodiatitulo'],
			'pfotodiadesc'=> $datos['fotodiadesc'],
			'pfotofecha'=> $datos['fotofecha'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pfotodiacod'=> $datos['fotodiacod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Eliminar($datos)
	{
		$spnombre="del_mul_fotos_dia_xfotodiacod";
		$sparam=array(
			'pfotodiacod'=> $datos['fotodiacod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstado($datos)
	{
		$spnombre="upd_mul_fotos_dia_fotodiaestado_xfotodiacod";
		$sparam=array(
			'pfotodiaestado'=> $datos['fotodiaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pfotodiacod'=> $datos['fotodiacod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}





}
?>