<?php  
abstract class cFormulariosEnviosdb
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



	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		
		$spnombre="sel_con_formulario_envios_busqueda";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los Emails.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_envios_xenviocod";
		$sparam=array(
			'penviocod'=> $datos['enviocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Email por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
// arma el combo para desplegar los nombres de los tipos de banners	

	protected function Eliminar($datos)
	{
		$spnombre="del_con_formulario_envios_xenviocod";
		$sparam=array(
			'penviocod'=> $datos['enviocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el Email por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_con_formulario_envios";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod'],
			'penviomail'=> $datos['enviomail'],
			'penviotipo'=> $datos['enviotipo'],
			'penvioestado'=> 10,//$datos['envioestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
				


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

;
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

}
?>