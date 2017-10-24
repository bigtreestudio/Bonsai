<?php  
abstract class cTapasZonasModulosTmpdb
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


	function Insertar($datos)
	{
		$spnombre="ins_tap_tapas_zonas_modulos_tmp";
		$sparam=array(
			'ptapacod'=> $datos['tapacod'],
			'pmodulocod'=> $datos['modulocod'],
			'pmodulodata'=> $datos['modulodata'],
			'pmodulonombre'=> $datos['modulonombre'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	
	
	function BuscarModulosTmp($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_modulos_tmp_tap_modulos";
		$sparam=array(
			"ptapacod"=>$datos['tapacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos temporales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}	
	
	
	function BuscarModulosTmpxModuloTmpcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_modulos_tmp_xmodulotmpcod";
		$sparam=array(
			'pmodulotmpcod'=> $datos['modulotmpcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos temporales por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	
	function Eliminar($datos)
	{
		$spnombre="del_tap_tapas_zonas_modulos_tmp_xmodulotmpcod";
		$sparam=array(
			'pmodulotmpcod'=> $datos['modulotmpcod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
}
?>