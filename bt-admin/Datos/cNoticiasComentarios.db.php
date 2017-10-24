<?php 
abstract class cNoticiasComentariosdb
{


	function __construct(){}

	function __destruct(){}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_comentarios_xcomentariocod";
		$sparam=array(
			'pcomentariocod'=> $datos['comentariocod']
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
		$spnombre="sel_not_comentarios_busqueda_avanzada";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pxnoticiacod'=> $datos['xnoticiacod'],
			'pxcomentariocod'=> $datos['xcomentariocod'],
			'pcomentariocod'=> $datos['comentariocod'],
			'pxcomentarionombre'=> $datos['xcomentarionombre'],
			'pcomentarionombre'=> $datos['comentarionombre'],
			'pxnoticiatitulo'=> $datos['xnoticiatitulo'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pxcomentarioemail'=> $datos['xcomentarioemail'],
			'pcomentarioemail'=> $datos['comentarioemail'],
			'pxcomentarioestado'=> $datos['xcomentarioestado'],
			'pcomentarioestado'=> $datos['comentarioestado'],
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
		$spnombre="ins_not_comentarios";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcomentarionombre'=> $datos['comentarionombre'],
			'pcomentarioemail'=> $datos['comentarioemail'],
			'pcomentariodesc'=> $datos['comentariodesc'],
			'pcomentarioestado'=> $datos['comentarioestado'],
			'pcomentariofalta'=> date("Y-m-d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y-m-d H:i:s")
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
		$spnombre="upd_not_comentarios_xcomentariocod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcomentarionombre'=> $datos['comentarionombre'],
			'pcomentarioemail'=> $datos['comentarioemail'],
			'pcomentariodesc'=> $datos['comentariodesc'],
			'pcomentariofalta'=> $datos['comentariofalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcomentariocod'=> $datos['comentariocod']
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
		$spnombre="del_not_comentarios_xcomentariocod";
		$sparam=array(
			'pcomentariocod'=> $datos['comentariocod']
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
		$spnombre="upd_not_comentarios_comentarioestado_xcomentariocod";
		$sparam=array(
			'pcomentarioestado'=> $datos['comentarioestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcomentariocod'=> $datos['comentariocod']
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