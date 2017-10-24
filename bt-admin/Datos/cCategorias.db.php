<?php  
abstract class cCategoriasdb
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
		$spnombre="sel_not_categorias_xcatcod";
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
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_not_categorias_xbusqueda_avanzada";
		$sparam=array(
			'pxcatcod'=> $datos['xcatcod'],
			'pactcod'=> $datos['catcod'],
			'pxcatnom'=> $datos['xcatnom'],
			'pcatnom'=> $datos['catnom'],
			'pxcatsuperior'=> $datos['xcatsuperior'],
			'pcatsuperior'=> $datos['catsuperior'],
			'pxcatestado'=> $datos['xcatestado'],
			'pcatestado'=> $datos['catestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las categorias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function BuscarCategoriasNoticiasxCatcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_categorias";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	
	}
	
	protected function BuscarNoticiasxCatcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categoria de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	
	}
	
	protected function BuscaCategoriasxEstado($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_categorias_xcatestado";
		$sparam=array(
			'pcatestado'=> $datos["catestado"]
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar categorias por código de estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscarCategoriasxCategoriaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_xcatsuperior";
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
		$spnombre="sel_not_categorias_busqueda_xcatsupnull";
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
		$spnombre="sel_not_categorias_xcatsupnull";
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
		$spnombre="sel_not_categorias_xcatnom_xcatsupnull";
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
		$spnombre="sel_categorias_xcatnom_xsuperior";
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
		$spnombre="ins_not_categorias";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pplanthtmlcod'=> $datos['planthtmlcod'],
			'pcatdominio'=> $datos['catdominio'],
			'pcatdesc'=> $datos['catdesc'],
			'pcatcolor'=> $datos['catcolor'],
			'pcatsuperior'=> $datos['catsuperior'],
			'pcatorden'=> $datos['catorden'],
			'pcatestado'=> $datos['catestado'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],
			'psemuestramenu'=> $datos['semuestramenu'],
			'pfondocod'=> $datos['fondocod'],
			'pcatdatajson'=> $datos['catdatajson'],
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

		$spnombre="upd_not_categorias_xcatcod";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pcatdominio'=> $datos['catdominio'],
			'pplanthtmlcod'=> $datos['planthtmlcod'],
			'pcatdesc'=> $datos['catdesc'],
			'pcatcolor'=> $datos['catcolor'],
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod'],			
			'psemuestramenu'=> $datos['semuestramenu'],
			'pfondocod'=> $datos['fondocod'],
			'pcatcodraiz'=> $datos['catcodraiz'],
			'pcatdatajson'=> $datos['catdatajson'],
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

	protected function ModificarArchivo($datos)
	{

		$spnombre="upd_not_categorias_img_xcatcod";
		$sparam=array(
			'pimgubic'=> $datos['imgubic'],
			'pimgnombre'=> $datos['imgnombre'],
			'pimgsize'=> $datos['imgsize'],
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
		$spnombre="upd_not_categorias_xcatestado";
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


	protected function ModificarCamposHijos($datos)
	{
		$spnombre="upd_not_categorias_cathijos_xcatcod";
		$sparam=array(
			'pcathijos'=> $datos['cathijos'],
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

		$spnombre="del_not_categorias_xcatcod";
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
		$spnombre="sel_not_categorias_maxorden_xcatsuperior";
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
		$spnombre="upd_not_categorias_orden_xcatcod";
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