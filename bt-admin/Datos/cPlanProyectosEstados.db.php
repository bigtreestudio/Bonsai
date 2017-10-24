<?php 
abstract class cPlanProyectosEstadosdb
{

	function __construct(){}
	function __destruct(){}
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_proyectos_estados_xplanproyectoestadocod";
		$sparam=array(
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod']
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
		$spnombre="sel_plan_proyectos_estados_busqueda_avanzada";
		$sparam=array(
			'pxplanproyectoestadocod'=> $datos['xplanproyectoestadocod'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod'],
			'pxplanproyectoestadonombre'=> $datos['xplanproyectoestadonombre'],
			'pplanproyectoestadonombre'=> $datos['planproyectoestadonombre'],
			'pxplanproyectoestadoestado'=> $datos['xplanproyectoestadoestado'],
			'pplanproyectoestadoestado'=> $datos['planproyectoestadoestado'],
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
		$spnombre="ins_plan_proyectos_estados";
		$sparam=array(
			'pplanproyectoestadonombre'=> $datos['planproyectoestadonombre'],
			'pplanproyectoestadoestado'=> $datos['planproyectoestadoestado'],
			'pplanproyectoestadocolor'=> $datos['planproyectoestadocolor'],
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
		$spnombre="upd_plan_proyectos_estados_xplanproyectoestadocod";
		$sparam=array(
			'pplanproyectoestadonombre'=> $datos['planproyectoestadonombre'],
			'pplanproyectoestadocolor'=> $datos['planproyectoestadocolor'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod']
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
		$spnombre="del_plan_proyectos_estados_xplanproyectoestadocod";
		$sparam=array(
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod']
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
		$spnombre="upd_plan_proyectos_estados_planproyectoestadoestado_xplanproyectoestadocod";
		$sparam=array(
			'pplanproyectoestadoestado'=> $datos['planproyectoestadoestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod']
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