<? 
abstract class cPaginasdb
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
		$spnombre="sel_pag_paginas_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la página. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


	public function BuscarHermanoseHijos($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_pag_paginas_publicadas_arbol_xpagcodsuperior_catcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pxpagcodsuperior'=> 0,
			'ppagcodsuperior'=> ""
			);
		if ($datos['pagcodsuperior']!="")
		{
			$sparam['pxpagcodsuperior'] = 1;
			$sparam['ppagcodsuperior'] = $datos['pagcodsuperior'];
		}	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;	
	}

	
	protected function BuscarPaginasxPaginaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_xpagcodsuperior";
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
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_busqueda_avanzada";
		$sparam=array(
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'pxpagcopiacod'=> $datos['xpagcopiacod'],
			'pxpagestadocod'=> $datos['xpagestadocod'],
			'ppagestadocod'=> $datos['pagestadocod'],
			'pxpagcodsuperior'=> $datos['xpagcodsuperior'],
			'ppagcodsuperior'=> $datos['pagcodsuperior'],
			'pxpagtitulo'=> $datos['xpagtitulo'],
			'ppagtitulo'=> $datos['pagtitulo'],
			'prolcod'=> $datos['rolcod'],
			'ppagestadocodbaja'=> $datos['pagestadocodbaja'],
			'pxpaginaestadobaja'=> $datos['xpaginaestadobaja'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la página por página superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaPaginaRaiz(&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_xpagcodsuperiornull";
		$sparam=array(
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la página por página superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_pag_paginas";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'ppagtitulo'=> $datos['pagtitulo'],
			'ppagsubtitulo'=> $datos['pagsubtitulo'],
			'ppagtitulocorto'=> $datos['pagtitulocorto'],
			'ppagcopete'=> $datos['pagcopete'],
			'ppagcuerpo'=> $datos['pagcuerpo'],
			'ppagcuerpoprocesado'=> $datos['pagcuerpoprocesado'],
			'ppagestadocod'=> $datos['pagestadocod'],
			'ppagcodsuperior'=> $datos['pagcodsuperior'],
			'ppagorden'=> $datos['pagorden'],
			'ppagcopiacodorig'=> $datos['pagcopiacodorig'],
			'ppagcopiacod'=> $datos['pagcopiacod'],
			'pmuestramenu'=> $datos['muestramenu'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'ppagfalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la página. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_pag_paginas_xpagcod";
		$sparam=array(
			'ppagtitulo'=> $datos['pagtitulo'],
			'pcatcod'=> $datos['catcod'],
			'ppagsubtitulo'=> $datos['pagsubtitulo'],
			'ppagtitulocorto'=> $datos['pagtitulocorto'],
			'ppagcodsuperior'=> $datos['pagcodsuperior'],
			'ppagcopete'=> $datos['pagcopete'],
			'ppagcuerpo'=> $datos['pagcuerpo'],
			'ppagcuerpoprocesado'=> $datos['pagcuerpoprocesado'],
			'pmuestramenu'=> $datos['muestramenu'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la página. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstadoPagina($datos)
	{
		$spnombre="upd_pag_paginas_xpagestadocod";
		$sparam=array(
			'ppagestadocod'=> $datos['pagestadocod'],
			'ppagfbaja'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la página. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	
	protected function Eliminar($datos)
	{
		$spnombre="del_pag_paginas_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la página. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	

	protected function BuscarPaginaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_xmaxorden_xpagcodsuperior";
		$sparam=array(
			'pxpagcodsuperior'=> $datos['xpagcodsuperior'],
			'pagcodsuperior'=> $datos['pagcodsuperior']
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
		$spnombre="upd_pag_paginas_orden_xpagcod";
		$sparam=array(
			'ppagorden'=> $datos['pagorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las páginas relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}


	protected function ActualizarEstado($datos)
	{
		$spnombre="upd_pag_paginas_estado_xpagcod";
		$sparam=array(
			'ppagestadocod'=> $datos['pagestadocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	

	protected function ActualizarCopiaOriginal($datos,$codigopagcopia)
	{

		$spnombre="upd_pag_paginas_xpagcod_pagcopia";
		$sparam=array(
			'ppagcodcopia'=> $codigopagcopia,
			'ppagcod'=> $datos['pagcod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


}


?>