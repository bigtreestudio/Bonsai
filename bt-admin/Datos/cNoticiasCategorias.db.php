<?php  
abstract class cNoticiasCategoriasdb
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

	protected function BuscarCategoriasxNoticia($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_not_categorias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las categorias de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function BuscarCategoriasxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_categorias_xnoticiacod_catcod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}





	protected function Insertar($datos)
	{

		$spnombre="ins_not_noticias_not_categorias";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcatcod'=> $datos['catcod'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pnotcatfalta'=> $datos['notcatfalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_not_categorias_xnoticiacod_catcod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function EliminarCompletoxNoticiacod($datos)
	{
		$spnombre="del_not_noticias_not_categorias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



}
?>