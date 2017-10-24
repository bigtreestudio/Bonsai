<?php  
abstract class cGaleriasdb
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

//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:
//		galeriacod=ccodigo de la galeria

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_xgaleriacod";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod']
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


	protected function GenerarDominio($datos)
	{
		$spnombre="upd_gal_galeria_xgaleriacod_galeriadominio";
		$sparam=array(
			'pgaleriadominio'=> $datos['galeriadominio'],
			'pgaleriacod'=> $datos['galeriacod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el dominio de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


	
	protected function BuscaGaleriasxEstado($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_xgaleriaestadocod";
		$sparam=array(
			'pgaleriaestadocod'=> $datos['galeriaestadocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar galerias por código de estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	

	
	
	
	
	protected function BuscaGaleriasNombreRaiz($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_xgaleriatitulo";
		$sparam=array(
			'pgaleriatitulo'=> $datos['galeriatitulo']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la galeria por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarGaleriaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_maxorden";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_gal_galerias";
		$sparam=array(
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod'],
			'pcatcod'=> $datos['catcod'],
			'palbumcod'=> $datos['albumcod'],
			'pgaleriatitulo'=> $datos['galeriatitulo'],
			'pgaleriadesc'=> $datos['galeriadesc'],
			'pgaleriaestadocod'=> $datos['galeriaestadocod'],
			'pgaleriaorden'=> $datos['galeriaorden'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pgaleriafalta'=> date("Y/m/d H:i:s"),
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_gal_galerias_xgaleriacod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pgaleriatitulo'=> $datos['galeriatitulo'],
			'pgaleriadesc'=> $datos['galeriadesc'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pgaleriacod'=> $datos['galeriacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstadoGaleria($datos)
	{
		$spnombre="upd_gal_galerias_galeriaestadocod_xgaleriacod";
		$sparam=array(
			'pgaleriaestadocod'=> $datos['galeriaestadocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pgaleriacod'=> $datos['galeriacod']
			);		

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	
	protected function Eliminar($datos)
	{

		$spnombre="del_gal_galerias_xgaleriacod";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function EliminarGaleriaAlbum($datos)
	{

		$spnombre="del_gal_albums_gal_galerias_realcionadas";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria del album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function EliminarGaleriaNoticia($datos)
	{

		$spnombre="del_not_noticias_gal_galerias_realcionadas";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	
	protected function EliminarGaleriaMultimedias($datos)
	{

		$spnombre="del_gal_galerias_mul_multimedia_realcionadas";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
		
	protected function ModificarOrden($datos)
	{

		$spnombre="upd_gal_galerias_galeriaorden_xgaleriacod";
		$sparam=array(
			'pgaleriaorden'=> $datos['galeriaorden'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pgaleriacod'=> $datos['galeriacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarAvanzadaxGaleria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_busqueda";
		$sparam=array(
			'pxgaleriatitulo'=> $datos['pxgaleriatitulo'],
			'pgaleriatitulo'=> $datos['pgaleriatitulo'],
			'pxgaleriaestadocod'=> $datos['pxgaleriaestadocod'],
			'pgaleriaestadocod'=> $datos['pgaleriaestadocod'],
			'pxmultimediaconjuntocod'=> $datos['pxmultimediaconjuntocod'],
			'pmultimediaconjuntocod'=> $datos['pmultimediaconjuntocod'],
			'pxcatcod'=> $datos['pxcatcod'],
			'pcatcod'=> $datos['pcatcod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		

}


?>