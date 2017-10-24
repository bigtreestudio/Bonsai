<? 
abstract class cPaginasPublicaciondb
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

	protected function BuscarPaginasxPaginaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_publicadas_xpagcodsuperior";
		$sparam=array(
			'ppagcodsuperior'=> $datos['pagcodsuperior']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la página por la página superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function EsPaginaPublicada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_publicadas_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la pagina publicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_publicadas_busqueda_avanzada";
		$sparam=array(
			'pxpagcod'=> $datos['xpagcod'],
			'ppagcod'=> $datos['pagcod'],
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las paginas por busqueda avanzada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function Eliminar($datos)
	{
		$spnombre="del_pag_paginas_publicadas_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la pagina publicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	


	protected function Insertar($datos,&$codigoinsertado)
	{
	
		$spnombre="ins_pag_paginas_publicadas";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			'pcatcod'=> $datos['catcod'],
			'pcatnom'=> $datos['catnom'],
			'ppagdominio'=> $datos['pagdominio'],
			'ppagtitulo'=> $datos['pagtitulo'],
			'ppagsubtitulo'=> $datos['pagsubtitulo'],
			'ppagtitulocorto'=> $datos['pagtitulocorto'],
			'ppagcopete'=> $datos['pagcopete'],
			'ppagcuerpo'=> $datos['pagcuerpo'],
			'ppagcuerpoprocesado'=> $datos['pagcuerpoprocesado'],
			'ppagestadocod'=> $datos['pagestadocod'],
			'ppagcodsuperior'=> $datos['pagcodsuperior'],
			'ppagorden'=> $datos['pagorden'],
			'pmuestramenu'=> $datos['muestramenu'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'ppagfalta'=> $datos['pagfalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al publicar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}



	protected function Modificar($datos)
	{
	
		$spnombre="upd_pag_paginas_publicadas_xpagcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pcatnom'=> $datos['catnom'],
			'ppagtitulo'=> $datos['pagtitulo'],
			'ppagsubtitulo'=> $datos['pagsubtitulo'],
			'ppagtitulocorto'=> $datos['pagtitulocorto'],
			'ppagcopete'=> $datos['pagcopete'],
			'ppagcuerpo'=> $datos['pagcuerpo'],
			'ppagcuerpoprocesado'=> $datos['pagcuerpoprocesado'],
			'ppagestadocod'=> $datos['pagestadocod'],
			'ppagcodsuperior'=> $datos['pagcodsuperior'],
			'ppagorden'=> $datos['pagorden'],
			'pmuestramenu'=> $datos['muestramenu'],			
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar una pagina publicada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



}
?>