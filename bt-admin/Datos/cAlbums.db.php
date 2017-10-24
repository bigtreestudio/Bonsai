<?php  
abstract class cAlbumsdb
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

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_xalbumcod";
		$sparam=array(
			'palbumcod'=> $datos['albumcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarGaleriasAlbumsxalbumcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_gal_galerias_xalbumcod";
		$sparam=array(
			'palbumcod'=> $datos['albumcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscaAlbumRaiz(&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_xalbumsuperiornull";
		$sparam=array(
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria por categoria superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	
		
	protected function BuscarAlbumxAlbumSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_xalbumsuperior";
		$sparam=array(
			'palbumsuperior'=> $datos['albumsuperior']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el album por album superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarAvanzadaxAlbumSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_busqueda_xalbumsuperiornull";
		$sparam=array(
			'pxalbumsuperior'=> $datos['xalbumsuperior'],
			'pxalbumsuperior1'=> $datos['xalbumsuperior1'],
			'palbumsuperior1'=> $datos['albumsuperior1'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el album por album superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscaAlbumNombreRaiz($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_gal_albums_xalbumtitulo_xalbumsuperiornull";
		$sparam=array(
			'palbumtitulo'=> $datos['albumtitulo']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la categoria por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscaAlbumTituloxAlbumSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_xalbumtitulo_xalbumsuperior";
		$sparam=array(
			'palbumtitulo'=> $datos['albumtitulo'],
			'palbumsuperior'=> $datos['albumsuperior']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el album por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_gal_albums";
		$sparam=array(
			'palbumsuperior'=> $datos['albumsuperior'],
			'palbumtitulo'=> $datos['albumtitulo'],
			'palbumorden'=> $datos['albumorden'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'palbumfalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'palbumestadocod'=> $datos['albumestadocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	protected function GenerarDominio($datos)
	{
		$spnombre="upd_gal_albums_xalbumcod_albumdominio";
		$sparam=array(
			'palbumdominio'=> $datos['albumdominio'],
			'palbumcod'=> $datos['albumcod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el dominio de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_gal_albums_xalbumcod";
		$sparam=array(
			'palbumsuperior'=> $datos['albumsuperior'],
			'palbumtitulo'=> $datos['albumtitulo'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'palbumestadocod'=> $datos['albumestadocod'],
			'palbumcod'=> $datos['albumcod']
			);
			
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function Eliminar($datos)
	{

		$spnombre="del_gal_albums_xalbumcod";
		$sparam=array(
			'palbumcod'=> $datos['albumcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function BuscarAlbumUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_maxorden_xalbumsuperior";
		$sparam=array(
			'pxalbumsuperior'=> $datos['xalbumsuperior'],
			'palbumsuperior'=> $datos['albumsuperior']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function ModificarOrden($datos)
	{
		$spnombre="upd_gal_albums_orden_xalbumcod";
		$sparam=array(
			'palbumorden'=> $datos['albumorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'palbumcod'=> $datos['albumcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los albumes relacionados. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}

}


?>