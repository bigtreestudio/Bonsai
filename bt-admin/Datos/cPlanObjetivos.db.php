<?php 
abstract class cPlanObjetivosdb
{

	function __construct(){}
	function __destruct(){}
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_objetivos_xplanobjetivocod";
		$sparam=array(
			'pplanobjetivocod'=> $datos['planobjetivocod']
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
		$spnombre="sel_plan_objetivos_busqueda_avanzada";
		$sparam=array(
			'pxplanobjetivocod'=> $datos['xplanobjetivocod'],
			'pplanobjetivocod'=> $datos['planobjetivocod'],
			'pxplanobjetivonombre'=> $datos['xplanobjetivonombre'],
			'pplanobjetivonombre'=> $datos['planobjetivonombre'],
			'pxplanobjetivoestado'=> $datos['xplanobjetivoestado'],
			'pplanobjetivoestado'=> $datos['planobjetivoestado'],
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
		$spnombre="ins_plan_objetivos";
		$sparam=array(
			'pplanobjetivonombre'=> $datos['planobjetivonombre'],
			'pplanobjetivodescripcion'=> $datos['planobjetivodescripcion'],
			'pplanobjetivoestado'=> $datos['planobjetivoestado'],
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
		$spnombre="upd_plan_objetivos_xplanobjetivocod";
		$sparam=array(
			'pplanobjetivonombre'=> $datos['planobjetivonombre'],
			'pplanobjetivodescripcion'=> $datos['planobjetivodescripcion'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanobjetivocod'=> $datos['planobjetivocod']
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
		$spnombre="del_plan_objetivos_xplanobjetivocod";
		$sparam=array(
			'pplanobjetivocod'=> $datos['planobjetivocod']
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
		$spnombre="upd_plan_objetivos_planobjetivoestado_xplanobjetivocod";
		$sparam=array(
			'pplanobjetivoestado'=> $datos['planobjetivoestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanobjetivocod'=> $datos['planobjetivocod']
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