<?php 
abstract class cPlanProyectosComunasdb
{


	function __construct(){}

	function __destruct(){}

	protected function gcba_comunasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_gcba_comunas_combo_comunanumero";
		$sparam=array(
		);
		return true;
	}



	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_proyectos_comunas_xplanproyectocod_comunacod";
		$sparam=array(
			'pplanproyectocod'=> $datos['planproyectocod'],
			'pcomunacod'=> $datos['comunacod']
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
		$spnombre="sel_plan_proyectos_comunas_busqueda_avanzada";
		$sparam=array(
			'pxplanproyectocod'=> $datos['xplanproyectocod'],
			'pplanproyectocod'=> $datos['planproyectocod'],
			'pxcomunacod'=> $datos['xcomunacod'],
			'pcomunacod'=> $datos['comunacod'],
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



	protected function Insertar($datos)
	{
		$spnombre="ins_plan_proyectos_comunas";
		$sparam=array(
			'pplanproyectocod'=> $datos['planproyectocod'],
			'pcomunacod'=> $datos['comunacod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_plan_proyectos_comunas_xplanproyectocod_comunacod";
		$sparam=array(
			'pplanproyectocod'=> $datos['planproyectocod'],
			'pcomunacod'=> $datos['comunacod'],
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}





}
?>