<?php  
abstract class cBannersdb
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
//me trae la informacion de un banner por el codigo que le estoy pasando.
	
	protected function BuscarBannerxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_ban_banners_xbannercod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

//hace la busqueda por descripcion y por tipo 
// en ban_banners.php me trae de la base de datos todos los banners que hay creados.
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_ban_banners_busqueda";
		$sparam=array(
			'pxbannertipocod'=> $datos['xbannertipocod'],
			'pbannertipocod'=> $datos['bannertipocod'],
			'pxbannerdesc'=> $datos['xbannerdesc'],
			'pbannerdesc'=> $datos['bannerdesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	


// arma el combo para desplegar los nombres de los tipos de banners	
	function BusquedaBannerTipoSP (&$spnombre,&$sparam)
	{
		
		$spnombre="sel_ban_banners_tipos";
		$sparam=array(
			'porderby'=> "bannertipocod desc"
			);

		return true;
	}
	
	function BusquedaBannerTipos (&$spnombre,&$sparam)
	{
			$spnombre="sel_ban_banners_tipos";
			$sparam=array(
				'porderby'=> "bannertipocod desc"
				);
				
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}

	protected function Insertar ($datos,&$codigoinsertado)
	{
		$spnombre="ins_ban_banners";
		$sparam=array(
			'pbannertipocod'=> $datos['bannertipocod'],
			'pbannerdesc'=> $datos['bannerdesc'],
			'pbannerdesclarga'=> $datos['bannerdesclarga'],
			'pbannerurl'=> $datos['bannerurl'],
			'pbannertarget'=> $datos['bannertarget'],
			'pbannerestado'=> $datos['bannerestado'],
			'pbannerorden'=> $datos['bannerorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);		
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	protected function Modificar ($datos)
	{
		$spnombre="upd_ban_banners_xbannercod";
		$sparam=array(
			'pbannerdesc'=> $datos['bannerdesc'],
			'pbannerdesclarga'=> $datos['bannerdesclarga'],
			'pbannerurl'=> $datos['bannerurl'],
			'pbannertarget'=> $datos['bannertarget'],
			'pbannerorden'=> $datos['bannerorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pbannercod'=> $datos['bannercod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	


//Modifica el nombre del archivo
	
	protected function ModificarDatosArchivo ($datos)
	{
		$spnombre="upd_ban_banners_archivo_xbannercod";
		$sparam=array(
			'pbannerarchubic'=> $datos['bannerarchubic'],
			'pbannerarchnombre'=> $datos['bannerarchnombre'],
			'pbannerarchsize'=> $datos['bannerarchsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pbannercod'=> $datos['bannercod']
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar los datos del archivo del banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}

//Pasa el estado del banner a Eliminado, pero no se elimina fisicamente de la tabla 
//no tiene ningun campo para validar si coincide con otra tabla	
	
	protected function ModificarEstado ($datos)
	{
		$spnombre="upd_ban_banners_cambioestado";
		$sparam=array(
			'pbannerestado'=> $datos['bannerestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pbannercod'=> $datos['bannercod']
			);
				
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	
		



}
?>