<?php  
abstract class cUsuariosdb
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


	protected function BuscarUsuarioxMail($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_xusuarioemail";
		$sparam=array(
			'pusuarioemail'=> $datos['usuarioemail']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas>1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	
	// Retorna una consulta con todos los usuarios que tienen ese nro de doc, tipo de documento y sexo
	protected function BuscarUsuarioxDocumento($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_xdocumento";
		$sparam=array(
			'pusuariodoc'=> $datos['usuariodoc'],
			'ptipodocumentocod'=> $datos['tipodocumentocod'],
			'pusuariosexo'=> $datos['usuariosexo']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas>1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	
	protected function BuscarUsuarioxTipoDocumento($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_usuarios_xtipodocumento";
		$sparam=array(
			'ptipodocumentocod'=> $datos['tipodocumentocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por tipo de documento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	
	// Retorna una consulta con todos los usuarios que tienen ese nro de doc, tipo de documento, sexo y fecha de nacimiento
	protected function BuscarUsuarioxDocumentoxFechaNacimiento($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_xdocumento_fechanacimiento";
		$sparam=array(
			'pusuariodoc'=> $datos['usuariodoc'],
			'ptipodocumentocod'=> $datos['tipodocumentocod'],
			'pusuariosexo'=> $datos['usuariosexo'],
			'pusuariofnacimiento'=> $datos['usuariofnacimiento']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas>1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}
	
	// Retorna una consulta con todos los usuarios que tienen ese nro de doc, tipo de documento y sexo
	protected function BuscarUsuarioxMatricula($datos,&$resultado,&$numfilas)
	{
		$medicoestado=ACTIVO.",".NOACTIVO;
		$spnombre="sel_usuarios_xmatricula";
		$sparam=array(
			'ptipomatriculacod'=> $datos['tipomatriculacod'],
			'pmedicomatricula'=> $datos['medicomatricula'],
			'pmedicoestadocod'=> $medicoestado
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas>1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por matricula. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;	
	}



// Retorna una consulta con todos los usuarios que cumplan con las condiciones

// Parámetros de Entrada:
//		datosbuscar: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarUsuarios ($ArregloDatos,&$resultado,&$numfilas)
	{
		$sparam=array('pestadocod' =>0);
		$sparam+=array('pestadopass' =>0);
		$sparam+=array('pestadonom' =>0);
		$sparam+=array('pestadoape' =>0);
		$sparam+=array('pestadoemail' =>0);
		$sparam+=array('pestadoestado' =>0);
		
		$sparam+=array('pusuariocod' =>"");
		$sparam+=array('pusuariopassword' =>"");
		$sparam+=array('pusuarionombre' =>"");
		$sparam+=array('pusuarioapellido' =>"");
		$sparam+=array('pusuarioemail' =>"");
		$sparam+=array('pusuarioestado' =>"");



		if (isset ($ArregloDatos['usuariocod']))
		{
			if ($ArregloDatos['usuariocod']!="")
			{	
				$sparam['pusuariocod']= $ArregloDatos['usuariocod'];
				$sparam['pestadocod']= 1;
			}
		}	
		if (isset ($ArregloDatos['usuariopassword']))
		{
			if ($ArregloDatos['usuariopassword']!="")
			{	
				$sparam['pusuariopassword']= $ArregloDatos['usuariopassword'];
				$sparam['pestadopass']= 1;
			}
		}	
		if (isset ($ArregloDatos['usuarionombre']))
		{
			if ($ArregloDatos['usuarionombre']!="")
			{	
				$sparam['pusuarionombre']= $ArregloDatos['usuarionombre'];
				$sparam['pestadonom']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioapellido']))
		{
			if ($ArregloDatos['usuarioapellido']!="")
			{	
				$sparam['pusuarioapellido']= $ArregloDatos['usuarioapellido'];
				$sparam['pestadoape']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioemail']))
		{
			if ($ArregloDatos['usuarioemail']!="")
			{	
				$sparam['pusuarioemail']= $ArregloDatos['usuarioemail'];
				$sparam['pestadoemail']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioestado']))
		{
			if ($ArregloDatos['usuarioestado']!="")
			{	
				$sparam['pusuarioestado']= $ArregloDatos['usuarioestado'];
				$sparam['pestadoestado']= 1;
			}
		}
		

		$spnombre="sel_usuarios";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al realizar una busqueda de usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna una consulta con todos los usuarios que cumplan con las condiciones

// Parámetros de Entrada:
//		datosbuscar: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BusquedaUsuariosEdicion ($ArregloDatos,&$resultado,&$numfilas)
	{
		$sparam=array('pestadocod' =>0);
		$sparam+=array('pestadopass' =>0);
		$sparam+=array('pestadonom' =>0);
		$sparam+=array('pestadoape' =>0);
		$sparam+=array('pestadoemail' =>0);
		$sparam+=array('pestadoestado' =>0);
		$sparam+=array('pestadorolcod' =>0);
		$sparam+=array('pestadousuariodoc' =>0);
		
		$sparam+=array('pusuariocod' =>"");
		$sparam+=array('pusuariopassword' =>"");
		$sparam+=array('pusuarionombre' =>"");
		$sparam+=array('pusuarioapellido' =>"");
		$sparam+=array('pusuarioemail' =>"");
		$sparam+=array('pusuarioestado' =>"-1");
		$sparam+=array('pusuariodoc' =>"");
		
		$sparam+=array('prolcod' =>"-1");
		$sparam+=array('porderby' =>"usuarioapellido");
		$sparam+=array('plimit'=>"");

		if (isset ($ArregloDatos['usuariocod']))
		{
			if ($ArregloDatos['usuariocod']!="")
			{	
				$sparam['pusuariocod']= $ArregloDatos['usuariocod'];
				$sparam['pestadocod']= 1;
			}
		}	
		if (isset ($ArregloDatos['usuariopassword']))
		{
			if ($ArregloDatos['usuariopassword']!="")
			{	
				$sparam['pusuariopassword']= $ArregloDatos['usuariopassword'];
				$sparam['pestadopass']= 1;
			}
		}	
		if (isset ($ArregloDatos['usuarionombre']))
		{
			if ($ArregloDatos['usuarionombre']!="")
			{	
				$sparam['pusuarionombre']= $ArregloDatos['usuarionombre'];
				$sparam['pestadonom']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioapellido']))
		{
			if ($ArregloDatos['usuarioapellido']!="")
			{	
				$sparam['pusuarioapellido']= $ArregloDatos['usuarioapellido'];
				$sparam['pestadoape']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioemail']))
		{
			if ($ArregloDatos['usuarioemail']!="")
			{	
				$sparam['pusuarioemail']= $ArregloDatos['usuarioemail'];
				$sparam['pestadoemail']= 1;
			}
		}
		if (isset ($ArregloDatos['usuarioestado']))
		{
			if ($ArregloDatos['usuarioestado']!="")
			{	
				$sparam['pusuarioestado']= $ArregloDatos['usuarioestado'];
				$sparam['pestadoestado']= 1;
			}
		}
		
		if (isset ($ArregloDatos['usuariodoc']))
		{
			if ($ArregloDatos['usuariodoc']!="")
			{	
				$sparam['pusuariodoc']= $ArregloDatos['usuariodoc'];
				$sparam['pestadousuariodoc']= 1;
			}
		}

		if (isset ($ArregloDatos['rolcod']))
		{
			if ($ArregloDatos['rolcod']!="")
			{	
				$sparam['prolcod']= $ArregloDatos['rolcod'];
				$sparam['pestadorolcod']= 1;
			}
		}	

		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
			{	
				$sparam['porderby']= $ArregloDatos['orderby'];
			}
		}	

		if (isset ($ArregloDatos['limit']))
		{
			if ($ArregloDatos['limit']!="")
			{	
				$sparam['plimit']= $ArregloDatos['limit'];
			}
		}		

		$spnombre="sel_usuarios_busqueda";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al realizar una busqueda de usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		return true;
	}



	protected function CambiarPassword($datosusuario,$clavenueva)
	{
		
		$spnombre="upd_usuariopassword_xusuariocod";
		$sparam=array(
			'pusuariopassword'=> md5($clavenueva),
			'pusuarioestado'=> $datosusuario['usuarioestado'],
			'pultmodusuario'=>$_SESSION['usuariocod'],
			'pultmodfecha'=>date('Y/m/d H:i:s'),
			'pusuariocod'=>$datosusuario['usuariocod']
			);
		//print_r($sparam);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$nousar,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al actualizar la contraseña del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
		
		return true;
	}
	
	protected function AltaUsuario($datos,&$codigoinsertado)
	{
		$usuariodioalta = $_SESSION['usuariocod'];
		$ultmodusuario = $_SESSION['usuariocod'];
		
		$spnombre="ins_usuarios";
		$sparam=array(
			'pusuarioemail'=> $datos['usuarioemail'],
			'pusuariopassword'=> md5($datos['usuariopassword']),
			'pusuarionombre'=> $datos['usuarionombre'],
			'pusuarioapellido'=> $datos['usuarioapellido'],
			'pusuarioestado'=> $datos['usuarioestado'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pusuariodireccion'=> $datos['usuariodireccion'],
			'pusuariotel'=> $datos['usuariotel'],
			'pusuariofnacimiento'=> $datos['usuariofnacimiento'],
			'pusuariofalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptipodocumentocod'=> $datos['tipodocumentocod'],
			'pusuariodoc'=> $datos['usuariodoc'],
			'pusuariosexo'=> $datos['usuariosexo'],
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $datos['provinciacod'],
			'pusuariocp'=> $datos['usuariocp'],
			'pimgubic'=> $datos['imgubic']
			);
					
					

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al dar de alta el usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	
	}
	


	protected function AltaUsuarioCompleto($datos,&$codigoinsertado)
	{
		$usuariodioalta = $_SESSION['usuariocod'];
		$ultmodusuario = $_SESSION['usuariocod'];
		$datos['usuariodoc'] = $datos['usuariodocumento'];

		
		$spnombre="ins_usuarios_completo";
		$sparam=array(
			'pusuarioemail'=> $datos['usuarioemail'],
			'pusuariopassword'=> $datos['usuariopassword'],
			'pusuarionombre'=> $datos['usuarionombre'],
			'pusuarioapellido'=> $datos['usuarioapellido'],
			'pusuariodoc'=> $datos['usuariodoc'],
			'ptipodocumentocod'=> $datos['tipodocumentocod'],
			'pusuariosexo'=> $datos['usuariosexo'],
			'pusuarioestado'=> $datos['usuarioestado'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pusuariodireccion'=> $datos['usuariodireccion'],
			'pusuariodirnumero'=> $datos['usuariodirnumero'],
			'pusuariodirpiso'=> $datos['usuariodirpiso'],
			'pusuariodirdpto'=> $datos['usuariodirdpto'],
			'pusuariotel'=> $datos['usuariotel'],
			'pusuariocel'=> $datos['usuariocel'],
			'pempresacelcod'=> $datos['empresacelcod'],
			'pusuariofnacimiento'=> $datos['usuariofnacimiento'],
			'pusuariotwitter'=> $datos['usuariotwitter'],
			'pusuariofacebook'=> $datos['usuariofacebook'],
			'pusuariofcambiopass'=> $datos['usuariofcambiopass'],
			'pusuariofalta'=> $datos['usuariofalta'],
			'pusuariofbaja'=> $datos['usuariofbaja'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $datos['provinciacod'],
			'pusuariocp'=> $datos['usuariocp'],
			'pimgubic'=> $datos['imgubic'],
			'pusuariocodigoactivacion'=> $datos['usuariocodigoactivacion']
			);				
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al dar de alta el usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	
	}
	


	protected function ModificarUsuario($datos)
	{
		$spnombre="upd_usuarios_xusuariocod_usuariocod";
		$sparam=array(
			'pusuarionombre'=> $datos['usuarionombre'],
			'pusuarioapellido'=> $datos['usuarioapellido'],
			'pusuariodireccion'=> $datos['usuariodireccion'],
			'pusuariodirnumero'=> $datos['usuariodirnumero'],
			'pusuariodirpiso'=> $datos['usuariodirpiso'],
			'pusuariodirdpto'=> $datos['usuariodirdpto'],
			'pusuariotel'=> $datos['usuariotel'],
			'pusuariocel'=> $datos['usuariocel'],
			'pempresacelcod'=> $datos['empresacelcod'],
			'pusuariofnacimiento'=> $datos['usuariofnacimiento'],
			'pusuariotwitter'=> $datos['usuariotwitter'],
			'pusuariofacebook'=> $datos['usuariofacebook'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptipodocumentocod'=> $datos['tipodocumentocod'],
			'pusuariodoc'=> $datos['usuariodoc'],
			'pusuariosexo'=> $datos['usuariosexo'],
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $datos['provinciacod'],
			'pusuariocp'=> $datos['usuariocp'],
			'pusuariocod'=> $datos['usuariocod'],
			'pusuarioemail'=> $datos['usuarioemail'],
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron modificar los datos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;
	}
	
	
	
	
	protected function ModificarFotoUsuario($datos)
	{		

		$spnombre="upd_usuarios_foto_xusuariocod";
		$sparam=array(
			'pimgubic'=> $datos['imgubic'],
			'pusuariocod'=> $datos['usuariocod']
			);

	

		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modifasdsadsicar la foto del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}
	
	
	protected function BorrarUsuario($datos)
	{		
		$spnombre="upd_usuarios_usuarioestado_usuariofbaja_xusuariocod";
		$sparam=array(
			'pusuarioestado'=> USUARIOBAJA,
			'pusuariofbaja'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pusuariocod'=> $datos['usuariocod']
			);
			
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al dar de baja el usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}
	protected function AsignarCodigoActivacionUsuario($datos)
	{
		$spnombre="upd_usuarios_codigoactivacion";
		$sparam=array(
			'pusuariocodigoactivacion'=> $datos['usuariocodigoactivacion'],
			'pusuariocod'=> $datos['usuariocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudo modificar el codigo de activacion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;
	}


	
	
	protected function ModificarEstadoUsuario($datos)
	{
		
			$spnombre="upd_usuarios_usuarioestado_usuariofbaja_xusuariocod";
			$sparam=array(
				'pusuarioestado'=> $datos['usuarioestado'],
				'pusuariofbaja'=> $datos['usuariofbaja'],
				'pultmodusuario'=> $_SESSION['usuariocod'],
				'pultmodfecha'=> date("Y/m/d H:i:s"),
				'pusuariocod'=> $datos['usuariocod']
				);
				
			
			if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}

		return true;	
	}



	protected function ModificarContraseniaUsuario($datos)
	{
		$spnombreusu='upd_usuariopassword_xusuariocod';
		$spparamusu=array(
			'pusuariopassword'=>md5($datos['usuariopassword']),
			'pusuarioestado'=>$datos['usuarioestado'],
			'pultmodusuario'=>0,
			'pultmodfecha'=>date('Y/m/d H:i:s'),
			'pusuariocod'=>$datos["usuariocod"]
		);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombreusu,$spparamusu,$nousar,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error BD en selección de usuarios. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
		
		return true;	
	}
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_usuarios_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas>1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar usuario por codigo<!---->. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

}


?>