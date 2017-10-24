<?php 
abstract class cGcbaComunaBarriosdb
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


	protected function gcba_barriosSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_gcba_barrios_combo_barrionombre";
		$sparam=array(
		);
		return true;
	}


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gcba_comunas_barrios_xcomunabarriocod";
		$sparam=array(
			'pcomunabarriocod'=> $datos['comunabarriocod']
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
		$spnombre="sel_gcba_comunas_barrios_busqueda_avanzada";
		$sparam=array(
			'pxcomunabarriocod'=> $datos['xcomunabarriocod'],
			'pcomunabarriocod'=> $datos['comunabarriocod'],
			'pxcomunacod'=> $datos['xcomunacod'],
			'pcomunacod'=> $datos['comunacod'],
			'pxbarriocod'=> $datos['xbarriocod'],
			'pbarriocod'=> $datos['barriocod'],
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
		$spnombre="ins_gcba_comunas_barrios";
		$sparam=array(
			'pcomunacod'=> $datos['comunacod'],
			'pbarriocod'=> $datos['barriocod'],
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
		$spnombre="upd_gcba_comunas_barrios_xcomunabarriocod";
		$sparam=array(
			'pcomunacod'=> $datos['comunacod'],
			'pbarriocod'=> $datos['barriocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pcomunabarriocod'=> $datos['comunabarriocod']
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
		$spnombre="del_gcba_comunas_barrios_xcomunabarriocod";
		$sparam=array(
			'pcomunabarriocod'=> $datos['comunabarriocod']
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