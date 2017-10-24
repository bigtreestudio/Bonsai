<?php 
abstract class cGcbaComunasdb
{

	function __construct(){}
	function __destruct(){}
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gcba_comunas_xcomunacod";
		$sparam=array(
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
		$spnombre="sel_gcba_comunas_busqueda_avanzada";
		$sparam=array(
			'pxcomunacod'=> $datos['xcomunacod'],
			'pcomunacod'=> $datos['comunacod'],
			'pxcomunanumero'=> $datos['xcomunanumero'],
			'pcomunanumero'=> $datos['comunanumero'],
			'pxcomunabarrios'=> $datos['xcomunabarrios'],
			'pcomunabarrios'=> $datos['comunabarrios'],
			'pxcomunaestado'=> $datos['xcomunaestado'],
			'pcomunaestado'=> $datos['comunaestado'],
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
		$spnombre="ins_gcba_comunas";
		$sparam=array(
			'pcomunanumero'=> $datos['comunanumero'],
			'pcomunabarrios'=> $datos['comunabarrios'],
			'pcomunaperimetro'=> $datos['comunaperimetro'],
			'pcomunaarea'=> $datos['comunaarea'],
			'pcomunapoligono'=> $datos['comunapoligono'],
			'pcomunaestado'=> $datos['comunaestado'],
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
		$spnombre="upd_gcba_comunas_xcomunacod";
		$sparam=array(
			'pcomunanumero'=> $datos['comunanumero'],
			'pcomunabarrios'=> $datos['comunabarrios'],
			'pcomunaperimetro'=> $datos['comunaperimetro'],
			'pcomunaarea'=> $datos['comunaarea'],
			'pcomunapoligono'=> $datos['comunapoligono'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pcomunacod'=> $datos['comunacod']
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
		$spnombre="del_gcba_comunas_xcomunacod";
		$sparam=array(
			'pcomunacod'=> $datos['comunacod']
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
		$spnombre="upd_gcba_comunas_comunaestado_xcomunacod";
		$sparam=array(
			'pcomunaestado'=> $datos['comunaestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pcomunacod'=> $datos['comunacod']
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