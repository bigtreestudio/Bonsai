<?php  
abstract class cContactosdb
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



	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_con_formulario_xbusqueda_avanzada";
		$sparam=array(
			'pxformulariotipotitulo'=> $datos['xformulariotipotitulo'],
			'pformulariotipotitulo'=> $datos['formulariotipotitulo'],
			'pxformulariotipocod'=> $datos['xformulariotipocod'],
			'pformulariotipocod'=> $datos['formulariotipocod'],
			'pxformularioestado'=> $datos['xformularioestado'],
			'pformularioestado'=> $datos['formularioestado'],						
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los contactos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function GenerarDominio($datos)
	{
		$spnombre="upd_con_formulario_xformulariocod_formulariodominio";
		$sparam=array(
			'pformulariodominio'=> $datos['formulariodominio'],
			'pformulariocod'=> $datos['formulariocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el dominio del formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_xformlariocod";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el formualrio por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
// arma el combo para desplegar los nombres de los tipos de banners	
	function BusquedaFormularioTipoSP (&$spnombre,&$sparam)
	{
		
			$spnombre="sel_con_formulario_tipos_tipos";
			$sparam=array(
				'porderby'=> "formulariotipocod desc"
				);

		return true;
	}	

// arma el combo para desplegar los nombres de los tipos de banners	
	function BusquedaFormularioTiposxFormulariotipocod (&$spnombre,&$sparam)
	{
		$spnombre="sel_con_formulario_tipos_xformulariotipocod";
		$sparam=array(
			'pformulariotipocod'=> $datos['formulariotipocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}		return true;
	}	

	protected function Buscar(&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas";
		$sparam=array(
			);		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarEncuestasOpciones($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_encuestas_opciones_xencuestacod";
		$sparam=array(
					'pencuestacod'=> $datos['encuestacod'],
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function EliminarFormulario($datos)
	{
		$spnombre="del_con_formulario_xformulariocod";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la encuesta por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function ModificarFormulario($datos)
	{
		$spnombre="upd_con_formulario_xformulariocod";
		$sparam=array(
			'pformulariotipocod'=> $datos['formulariotipocod'],
			'pformulariotipotitulo'=> $datos['formulariotipotitulo'],
			'pformulariodireccion'=> $datos['formulariodireccion'],
			'pformulariotelefono1'=> $datos['formulariotelefono1'],
			'pformulariotelefono2'=> $datos['formulariotelefono2'],
			'pformulariocelular'=> $datos['formulariocelular'],
			'pformulariomail'=> $datos['formulariomail'],
			'pformularioweb'=> $datos['formularioweb'],
			'pformulariotwitter'=> $datos['formulariotwitter'],
			'pformulariofacebook'=> $datos['formulariofacebook'],
			'pformulariolatitud'=> $datos['formulariolatitud'],
			'pformulariolongitud'=> $datos['formulariolongitud'],
			'pformulariomapazoom'=> $datos['formulariomapazoom'],
			'pformulariomapatipo'=> $datos['formulariomapatipo'],
			'pformulariociudad'=> $datos['formulariociudad'],
			'pformulariocp'=> $datos['formulariocp'],
			'pformulariopiso'=> $datos['formulariopiso'],			
			'pprovinciacod'=> $datos['provinciacod'],
			'ppaiscod'=> $datos['paiscod'],
			'pformulariojson'=> $datos['formulariojson'],
			'pformulariotexto'=> $datos['formulariotexto'],
			'pformulariodisclaimer'=>$datos['formulariodisclaimer'],
			'pformulariomaildesde'=> $datos['formulariomaildesde'],
			'pformularioestado'=> $datos['formularioestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pformulariocod'=> $datos['formulariocod']
			);				

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function InsertarFormulario($datos,&$codigoinsertado)
	{			
		$spnombre="ins_con_formulario";
		$sparam=array(
			'pformulariotipocod'=> $datos['formulariotipocod'],
			'pformulariotipotitulo'=> $datos['formulariotipotitulo'],
			'pformulariodireccion'=> $datos['formulariodireccion'],
			'pformulariotelefono1'=> $datos['formulariotelefono1'],
			'pformulariotelefono2'=> $datos['formulariotelefono2'],
			'pformulariocelular'=> $datos['formulariocelular'],
			'pformulariomail'=> $datos['formulariomail'],
			'pformularioweb'=> $datos['formularioweb'],
			'pformulariotwitter'=> $datos['formulariotwitter'],
			'pformulariofacebook'=> $datos['formulariofacebook'],
			'pformulariolatitud'=> $datos['formulariolatitud'],
			'pformulariolongitud'=> $datos['formulariolongitud'],
			'pformulariomapazoom'=> $datos['formulariomapazoom'],
			'pformulariomapatipo'=> $datos['formulariomapatipo'],
			'pformulariociudad'=> $datos['formulariociudad'],
			'pformulariocp'=> $datos['formulariocp'],
			'pformulariopiso'=> $datos['formulariopiso'],			
			'pprovinciacod'=> $datos['provinciacod'],
			'ppaiscod'=> $datos['paiscod'],
			'pformulariojson'=> $datos['formulariojson'],
			'pformulariotexto'=> $datos['formulariotexto'],
			'pformulariodisclaimer'=>$datos['formulariodisclaimer'],
			'pformulariomaildesde'=> $datos['formulariomaildesde'],
			'pformularioestado'=> $datos['formularioestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un nuevo formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

;
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	protected function ModificarEstadoFormulario($datos)
	{

		$spnombre="upd_con_formulario_estado_xencuestacod";
		$sparam=array(
			'pformularioestado'=> $datos['formularioestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pformulariocod'=> $datos['formulariocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado del formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

}
?>