<?php  
abstract class cBannersMultimediadb
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

	protected function BuscarBannerxCodigoBannerxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_ban_banners_mul_multimedia_xbannercod_multimediacod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de banner y codigo de multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarBannersxCodigoBanner($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_ban_banners_mul_multimedia_xbannercod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod']
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia del banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function Insertar($datos)
	{

		$spnombre="ins_ban_banners_mul_multimedia";
		$sparam=array(
			'pmultimediacod'=> $datos['multimediacod'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pbannermultimediafalta'=> $datos['bannermultimediafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el multimedia al banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_ban_banners_mul_multimedia_xbannercod_multimediacod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia del banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}





}
?>