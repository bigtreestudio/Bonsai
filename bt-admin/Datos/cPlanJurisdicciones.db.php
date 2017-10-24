<?php 
abstract class cPlanJurisdiccionesdb
{

	function __construct(){}
	function __destruct(){}
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_jurisdicciones_xplanjurisdiccioncod";
		$sparam=array(
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod']
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
		$spnombre="sel_plan_jurisdicciones_busqueda_avanzada";
		$sparam=array(
			'pxplanjurisdiccioncod'=> $datos['xplanjurisdiccioncod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod'],
			'pxplanjurisdiccionnombre'=> $datos['xplanjurisdiccionnombre'],
			'pplanjurisdiccionnombre'=> $datos['planjurisdiccionnombre'],
			'pxplanjurisdiccionestado'=> $datos['xplanjurisdiccionestado'],
			'pplanjurisdiccionestado'=> $datos['planjurisdiccionestado'],
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
		$spnombre="ins_plan_jurisdicciones";
		$sparam=array(
			'pplanjurisdiccionnombre'=> $datos['planjurisdiccionnombre'],
			'pplanjurisdicciondescripcion'=> $datos['planjurisdicciondescripcion'],
			'pplanjurisdiccionestado'=> $datos['planjurisdiccionestado'],
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
		$spnombre="upd_plan_jurisdicciones_xplanjurisdiccioncod";
		$sparam=array(
			'pplanjurisdiccionnombre'=> $datos['planjurisdiccionnombre'],
			'pplanjurisdicciondescripcion'=> $datos['planjurisdicciondescripcion'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod']
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
		$spnombre="del_plan_jurisdicciones_xplanjurisdiccioncod";
		$sparam=array(
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod']
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
		$spnombre="upd_plan_jurisdicciones_planjurisdiccionestado_xplanjurisdiccioncod";
		$sparam=array(
			'pplanjurisdiccionestado'=> $datos['planjurisdiccionestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod']
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