<?php 
abstract class cFondosdb
{


	function __construct(){}

	function __destruct(){}

	protected function FondosSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_fon_fondos_stored";
		$sparam=array(
			'porderby'=> "fondodesc"
			);		
		return true;
	}
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_fon_fondos_xfondocod";
		$sparam=array(
			'pfondocod'=> $datos['fondocod']
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
		$spnombre="sel_fon_fondos_busqueda_avanzada";
		$sparam=array(
			'pxfondodesc'=> $datos['xfondodesc'],
			'pfondodesc'=> $datos['fondodesc'],
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
		$spnombre="ins_fon_fondos";
		$sparam=array(
			'pfondodesc'=> $datos['fondodesc'],
			'pfondocte'=> $datos['fondocte'],
			'pfondoimgubic'=> $datos['fondoimgubic'],
			'pfondoimgnombre'=> $datos['fondoimgnombre'],
			'pfondoimgsize'=> $datos['fondoimgsize'],
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
		$spnombre="upd_fon_fondos_xfondocod";
		$sparam=array(
			'pfondodesc'=> $datos['fondodesc'],
			'pfondocte'=> $datos['fondocte'],
			'pfondoimgubic'=> $datos['fondoimgubic'],
			'pfondoimgnombre'=> $datos['fondoimgnombre'],
			'pfondoimgsize'=> $datos['fondoimgsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pfondocod'=> $datos['fondocod']
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
		$spnombre="del_fon_fondos_xfondocod";
		$sparam=array(
			'pfondocod'=> $datos['fondocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	
	public function ModificarFotoLink($datos)
	{
		$spnombre="upd_fon_fondos_imagen_xfondocod";
		$sparam=array(
			'pfondoimgubic'=> $datos['fondoimgubic'],
			'pfondoimgnombre'=> $datos['fondoimgnombre'],
			'pfondoimgsize'=> $datos['fondoimgsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pfondocod'=> $datos['fondocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{

			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error al modificar la imagen del link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;	
	}





}
?>