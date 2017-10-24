<?php  
abstract class cPaginasCategoriasdb
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
	/*protected function CategoriasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_categorias";
		$sparam=array(
			);
		return true;
	}*/
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pxcatestado'=> $datos['xcatestado'],
			'pcatestado'=> $datos['catestado']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	
	protected function BuscarCategoriasxCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xcatsuperior";
		$sparam=array(
			'pcatsuperior'=> $datos['catsuperior'],
			'pxcatestado'=> $datos['xcatestado'],
			'pcatestado'=> $datos['catestado']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria por categoria superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarAvanzadaxCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_busqueda_xcatsuperiornull";
		$sparam=array(
			'pxcatsuperior'=> $datos['xcatsuperior'],
			'pxcatsuperior1'=> $datos['xcatsuperior1'],
			'pcatsuperior1'=> $datos['catsuperior1'],
			'pxcatestado'=> $datos['xcatestado'],
			'pcatestado'=> $datos['catestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria por categoria superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaCategoriasRaiz($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xcatsupnull";
		$sparam=array(
			'pxcatestado'=> $datos['xcatestado'],
			'pcatestado'=> $datos['catestado']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria por categoria superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaCategoriasNombreRaiz($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xcatnom_xcatsupnull";
		$sparam=array(
			'pcatnom'=> $datos['catnom']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la categoria por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscaCategoriasNombrexCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xcatnom_xsuperior";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pcatsuperior'=> $datos['catsuperior']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la categoria por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_pag_categorias";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pplanthtmlcod'=> $datos['planthtmlcod'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pcatdesc'=> $datos['catdesc'],
			'pcatsuperior'=> $datos['catsuperior'],
			'pcatorden'=> $datos['catorden'],
			'pcatestado'=> $datos['catestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_pag_categorias_xcatcod";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pplanthtmlcod'=> $datos['planthtmlcod'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pcatdesc'=> $datos['catdesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstadoCategoria($datos)
	{
		$spnombre="upd_pag_categorias_xcatestado";
		$sparam=array(
			'pcatestado'=> $datos['catestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	
	protected function Eliminar($datos)
	{

		$spnombre="del_pag_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function BuscarNoticiaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_categorias_xmaxorden_xcatsuperior";
		$sparam=array(
			'pcatsuperior'=> $datos['catsuperior']
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
		$spnombre="upd_pag_categorias_orden_xcatcod";
		$sparam=array(
			'pcatorden'=> $datos['catorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}

}


?>