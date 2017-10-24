<?php 
abstract class cGcbaBarriosdb
{




	{
		$spnombre="sel_gcba_barrios_xbarriocod";
		$sparam=array(
			'pbarriocod'=> $datos['barriocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar al buscar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

	}



	{
		$spnombre="sel_gcba_barrios_busqueda_avanzada";
		$sparam=array(
			'pxbarriocod'=> $datos['xbarriocod'],
			'pbarriocod'=> $datos['barriocod'],
			'pxbarrionombre'=> $datos['xbarrionombre'],
			'pbarrionombre'=> $datos['barrionombre'],
			'pxbarrioestado'=> $datos['xbarrioestado'],
			'pbarrioestado'=> $datos['barrioestado'],
			'plimit'=> $datos['limit'],
			'porderby'=> $datos['orderby']
		);

		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al realizar la búsqueda avanzada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

	}



	{
		$spnombre="ins_gcba_barrios";
		$sparam=array(
			'pbarrionombre'=> $datos['barrionombre'],
			'pbarriodesc'=> $datos['barriodesc'],
			'pbarrioestado'=> $datos['barrioestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


	}



	{
		$spnombre="upd_gcba_barrios_xbarriocod";
		$sparam=array(
			'pbarrionombre'=> $datos['barrionombre'],
			'pbarriodesc'=> $datos['barriodesc'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pbarriocod'=> $datos['barriocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

	}



	{
		$spnombre="del_gcba_barrios_xbarriocod";
		$sparam=array(
			'pbarriocod'=> $datos['barriocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

	}



	{
		$spnombre="upd_gcba_barrios_barrioestado_xbarriocod";
		$sparam=array(
			'pbarrioestado'=> $datos['barrioestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pbarriocod'=> $datos['barriocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

	}





?>