<?php 
abstract class cPlanEjesdb
{

	function __construct(){}
	function __destruct(){}
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_ejes_xplanejecod";
		$sparam=array(
			'pplanejecod'=> $datos['planejecod']
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
		$spnombre="sel_plan_ejes_busqueda_avanzada";
		$sparam=array(
			'pxplanejecod'=> $datos['xplanejecod'],
			'pplanejecod'=> $datos['planejecod'],
			'pxplanejenombre'=> $datos['xplanejenombre'],
			'pplanejenombre'=> $datos['planejenombre'],
			'pxplanejeconstante'=> $datos['xplanejeconstante'],
			'pplanejeconstante'=> $datos['planejeconstante'],
			'pxplanejeestado'=> $datos['xplanejeestado'],
			'pplanejeestado'=> $datos['planejeestado'],
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
		$spnombre="ins_plan_ejes";
		$sparam=array(
			'pplanejenombre'=> $datos['planejenombre'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pplanejeconstante'=> $datos['planejeconstante'],
			'pplanejedescripcion'=> $datos['planejedescripcion'],
			'pplanejecolor'=> $datos['planejecolor'],
			'pplanejeestado'=> $datos['planejeestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
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
		$spnombre="upd_plan_ejes_xplanejecod";
		$sparam=array(
			'pplanejenombre'=> $datos['planejenombre'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pplanejeconstante'=> $datos['planejeconstante'],
			'pplanejedescripcion'=> $datos['planejedescripcion'],
			'pplanejecolor'=> $datos['planejecolor'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanejecod'=> $datos['planejecod']
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
		$spnombre="del_plan_ejes_xplanejecod";
		$sparam=array(
			'pplanejecod'=> $datos['planejecod']
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
		$spnombre="upd_plan_ejes_planejeestado_xplanejecod";
		$sparam=array(
			'pplanejeestado'=> $datos['planejeestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanejecod'=> $datos['planejecod']
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