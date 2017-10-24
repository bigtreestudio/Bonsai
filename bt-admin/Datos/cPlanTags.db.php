<?php 
abstract class cPlanTagsdb
{

	function __construct(){}
	function __destruct(){}
	protected function plan_tags_categoriasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_plan_tags_categorias_combo_plantagcatnombre";
		$sparam=array(
		);
		return true;
	}


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_tags_xplantagcod";
		$sparam=array(
			'pplantagcod'=> $datos['plantagcod']
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
		$spnombre="sel_plan_tags_busqueda_avanzada";
		$sparam=array(
			'pxplantagcod'=> $datos['xplantagcod'],
			'pplantagcod'=> $datos['plantagcod'],
			'pxplantagnombre'=> $datos['xplantagnombre'],
			'pplantagnombre'=> $datos['plantagnombre'],
			'pxplantagcatcod'=> $datos['xplantagcatcod'],
			'pplantagcatcod'=> $datos['plantagcatcod'],
			'pxplanejecod'=> $datos['xplanejecod'],
			'pplanejecod'=> $datos['planejecod'],
			'pxplantagestado'=> $datos['xplantagestado'],
			'pplantagestado'=> $datos['plantagestado'],
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
		$spnombre="ins_plan_tags";
		$sparam=array(
			'pplantagnombre'=> $datos['plantagnombre'],
			'pplantagcatcod'=> $datos['plantagcatcod'],
			'pplantagestado'=> $datos['plantagestado'],
			'pplantagcolor'=> $datos['plantagcolor'],
			'pplantagclass'=> $datos['plantagclass'],
			'pplanejecod'=> $datos['planejecod'],
			'pplantagorden'=> $datos['plantagorden'],
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
		$spnombre="upd_plan_tags_xplantagcod";
		$sparam=array(
			'pplantagnombre'=> $datos['plantagnombre'],
			'pplantagcatcod'=> $datos['plantagcatcod'],
			'pplantagcolor'=> $datos['plantagcolor'],
			'pplantagclass'=> $datos['plantagclass'],
			'pplanejecod'=> $datos['planejecod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplantagcod'=> $datos['plantagcod']
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
		$spnombre="del_plan_tags_xplantagcod";
		$sparam=array(
			'pplantagcod'=> $datos['plantagcod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function BuscarUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_tags_max_orden";
		$sparam=array();
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el maximo orden. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function ModificarOrden($datos)
	{
		$spnombre="upd_plan_tags_plantagorden_xplantagcod";
		$sparam=array(
			'pplantagorden'=> $datos['plantagorden'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplantagcod'=> $datos['plantagcod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function ModificarEstado($datos)
	{
		$spnombre="upd_plan_tags_plantagestado_xplantagcod";
		$sparam=array(
			'pplantagestado'=> $datos['plantagestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplantagcod'=> $datos['plantagcod']
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