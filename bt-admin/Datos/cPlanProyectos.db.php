<?php 
abstract class cPlanProyectosdb     
{


	function __construct(){}

	function __destruct(){}

	protected function plan_objetivosSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_plan_objetivos_combo_planobjetivonombre";
		$sparam=array(
		);
		return true;
	}



	protected function plan_jurisdiccionesSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_plan_jurisdicciones_combo_planjurisdiccionnombre";
		$sparam=array(
		);
		return true;
	}



	protected function plan_proyectos_estadosSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_plan_proyectos_estados_combo_planproyectoestadonombre";
		$sparam=array(
		);
		return true;
	}



	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_plan_proyectos_xplanproyectocod";
		$sparam=array(
			'pplanproyectocod'=> $datos['planproyectocod']
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
		$spnombre="sel_plan_proyectos_busqueda_avanzada";
		$sparam=array(
			'pxplanproyectocod'=> $datos['xplanproyectocod'],
			'pplanproyectocod'=> $datos['planproyectocod'],
			'pxplanproyectonombre'=> $datos['xplanproyectonombre'],
			'pplanproyectonombre'=> $datos['planproyectonombre'],
			'pxplanobjetivocod'=> $datos['xplanobjetivocod'],
			'pplanobjetivocod'=> $datos['planobjetivocod'],
			'pxplanjurisdiccioncod'=> $datos['xplanjurisdiccioncod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod'],
			'pxplanproyectoestadocod'=> $datos['xplanproyectoestadocod'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod'],
			'pxplanproyectoestado'=> $datos['xplanproyectoestado'],
			'pplanproyectoestado'=> $datos['planproyectoestado'],
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
		$spnombre="ins_plan_proyectos";
		$sparam=array(
			'pplanproyectonombre'=> $datos['planproyectonombre'],
			'pplanproyectodescripcion'=> $datos['planproyectodescripcion'],
			'pplanproyectoobjetivo'=> $datos['planproyectoobjetivo'],
			'pplanobjetivocod'=> $datos['planobjetivocod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod'],
			'pplanproyectofdesde'=> $datos['planproyectofdesde'],
			'pplanproyectofhasta'=> $datos['planproyectofhasta'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod'],
			'pplanproyectoestado'=> $datos['planproyectoestado'],
			'pplanproyectofalta'=> date("Y/m/d H:i:s"),
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
		$spnombre="upd_plan_proyectos_xplanproyectocod";
		$sparam=array(
			'pplanproyectonombre'=> $datos['planproyectonombre'],
			'pplanproyectodescripcion'=> $datos['planproyectodescripcion'],
			'pplanproyectoobjetivo'=> $datos['planproyectoobjetivo'],
			'pplanobjetivocod'=> $datos['planobjetivocod'],
			'pplanjurisdiccioncod'=> $datos['planjurisdiccioncod'],
			'pplanproyectofdesde'=> $datos['planproyectofdesde'],
			'pplanproyectofhasta'=> $datos['planproyectofhasta'],
			'pplanproyectoestadocod'=> $datos['planproyectoestadocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanproyectocod'=> $datos['planproyectocod']
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
		$spnombre="del_plan_proyectos_xplanproyectocod";
		$sparam=array(
			'pplanproyectocod'=> $datos['planproyectocod']
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
		$spnombre="upd_plan_proyectos_planproyectoestado_xplanproyectocod";
		$sparam=array(
			'pplanproyectoestado'=> $datos['planproyectoestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pplanproyectocod'=> $datos['planproyectocod']
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