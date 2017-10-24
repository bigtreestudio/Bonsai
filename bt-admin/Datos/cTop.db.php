<?php  
abstract class cTopdb
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

//trae los datos del baner seleccionado
//me trae la informacion de un top por el codigo que le estoy pasando.
	
	protected function BuscarTopxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_top_top_xtopcod";
		$sparam=array(
			'ptopcod'=> $datos['topcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

//hace la busqueda por descripcion y por tipo 
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_top_top_busqueda";
		$sparam=array(
			'pxtoptipocod'=> $datos['xtoptipocod'],
			'ptoptipocod'=> $datos['toptipocod'],
			'pxtopdesc'=> $datos['xtopdesc'],
			'ptopdesc'=> $datos['topdesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	


// arma el combo para desplegar los nombres de los tipos de top
	function BusquedaTopTipoSP (&$spnombre,&$sparam)
	{
		
				$spnombre="sel_top_tops_tipos";
				$sparam=array(
					'porderby'=> "toptipocod desc"
					);

		return true;
	}
	
	function BusquedaTopTipos (&$spnombre,&$sparam)
	{
			$spnombre="sel_top_tops_tipos";
			$sparam=array(
				'porderby'=> "toptipocod desc"
				);
				
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}

	protected function Insertar ($datos,&$codigoinsertado)
	{
		$spnombre="ins_top_top";
		$sparam=array(
			'ptoptipocod'=> $datos['toptipocod'],
			'ptopdesc'=> $datos['topdesc'],
			'ptopdesclarga'=> $datos['topdesclarga'],
			'ptopurl'=> $datos['topurl'],
			'ptoptarget'=> $datos['toptarget'],
			'ptopestado'=> $datos['topestado'],
			'ptoporden'=> $datos['toporden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);		
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	protected function Modificar ($datos)
	{
		$spnombre="upd_top_top_xtopcod";
		$sparam=array(
			'ptopdesc'=> $datos['topdesc'],
			'ptopdesclarga'=> $datos['topdesclarga'],
			'ptopurl'=> $datos['topurl'],
			'ptoptarget'=> $datos['toptarget'],
			'ptoporden'=> $datos['toporden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptopcod'=> $datos['topcod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	


//Modifica el nombre del archivo
	
	protected function ModificarDatosArchivo ($datos)
	{
		$spnombre="upd_top_top_archivo_xtopcod";
		$sparam=array(
			'ptoparchubic'=> $datos['toparchubic'],
			'ptoparchnombre'=> $datos['toparchnombre'],
			'ptoparchsize'=> $datos['toparchsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptopcod'=> $datos['topcod']
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar los datos del archivo del top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}

//Pasa el estado del top a Eliminado, pero no se elimina fisicamente de la tabla 
//no tiene ningun campo para validar si coincide con otra tabla	
	
	protected function ModificarEstado ($datos)
	{
		$spnombre="upd_top_top_cambioestado";
		$sparam=array(
			'ptopestado'=> $datos['topestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptopcod'=> $datos['topcod']
			);
				
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el top. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	
		



}
?>