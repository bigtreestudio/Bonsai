<?php  
abstract class cAlbumsGaleriasdb
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
	
	protected function BuscarAlbumGaleriaxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_gal_galerias_xalbumcod_galeriacod";
		$sparam=array(
			'palbumcod'=> $datos['albumcod'],
			'pgaleriacod'=> $datos['galeriacod']
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
		
		$spnombre="sel_gal_albums_gal_galerias_busqueda";
		$sparam=array(
			'pxalbumcod'=> $datos['xalbumcod'],
			'palbumcod'=> $datos['albumcod'],
			'pxalbumtitulo'=> $datos['xalbumtitulo'],
			'palbumtitulo'=> $datos['albumtitulo'],
			'pxgaleriatitulo'=> $datos['xgaleriatitulo'],
			'pgaleriatitulo'=> $datos['galeriatitulo'],
			'pxgaleriaestadocod'=> $datos['xgaleriaestadocod'],
			'pgaleriaestadocod'=> $datos['galeriaestadocod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el album - galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	

	protected function Insertar ($datos)
	{
		$spnombre="ins_gal_albums_gal_galerias";
		$sparam=array(
			'palbumcod'=> $datos['albumcod'],
			'pgaleriacod'=> $datos['galeriacod'],
			'palbumgaleriaorden'=> $datos['albumgaleriaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el banner. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}
	
	
	protected function Eliminar($datos)
	{

		$spnombre="del_gal_albums_gal_galerias_xalbumcod_galeriacod";
		$sparam=array(
			'palbumcod'=> $datos['albumcod'],
			'pgaleriacod'=> $datos['galeriacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	protected function ModificarOrden($datos)
	{
		$spnombre="upd_gal_albums_gal_galerias_xalbumcod_galeriacod";
		$sparam=array(
			'palbumgaleriaorden'=> $datos['albumgaleriaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'palbumcod'=> $datos['albumcod'],
			'pgaleriacod'=> $datos['galeriacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los albumes relacionados. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarAlbumUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_albums_gal_galerias_maxorden";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
}

?>