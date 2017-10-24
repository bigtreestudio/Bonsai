<?php  
abstract class cFormulariosTiposdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 


	protected function TiposFormulariosSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_con_formulario_tipos_stored";
		$sparam=array(
			);
		return true;
	}

	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_tipos_busqueda";
		$sparam=array(
			'pxformulariotipodesc'=> $datos['xformulariotipodesc'],
			'pformulariotipodesc'=> $datos['formulariotipodesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los tipos de formularios.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_tipos_xformulariotipocod";
		$sparam=array(
			'pformulariotipocod'=> $datos['formulariotipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de formualrio por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	


	protected function Eliminar($datos)
	{
		$spnombre="del_con_formulario_tipos_xformulariotipocod";
		$sparam=array(
			'pformulariotipocod'=> $datos['formulariotipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tipo de formulario por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function Modificar($datos)
	{
		$spnombre="upd_con_formulario_tipos_xformulariotipocod";
		$sparam=array(
			'pformulariotipodesc'=> $datos['formulariotipodesc'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenucod'=> $datos['menucod'],			
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pformulariotipocod'=> $datos['formulariotipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tipo de formulario por código. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_con_formulario_tipos";
		$sparam=array(
			'pformulariotipodesc'=> $datos['formulariotipodesc'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenucod'=> $datos['menucod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
						
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un nuevo tipo de formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		};
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}


}
?>