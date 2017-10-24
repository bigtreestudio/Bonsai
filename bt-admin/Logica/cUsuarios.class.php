<?php  
include(DIR_CLASES_DB."cUsuarios.db.php");

class cUsuarios extends cUsuariosdb
{
	protected $conexion;
	protected $formato;
	
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	



//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
	public function BuscarUsuarioxMail ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarioxMail ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

	public function BuscarUsuarioxDocumento ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarioxDocumento ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	public function BuscarUsuarioxTipoDocumento ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarioxTipoDocumento ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	public function BuscarUsuarioxDocumentoxFechaNacimiento ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarioxDocumentoxFechaNacimiento ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	public function BuscarUsuarioxMatricula ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarioxMatricula ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

	public function BuscarUsuarios ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarUsuarios ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

	public function BusquedaUsuariosEdicion ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BusquedaUsuariosEdicion ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}


	function ObtenerEstadoUsuario($estado,$estilos=false)
	{

		$class = "";
		switch($estado)
		{
			case USUARIONUEVO:
				$txt = "Nuevo";
				$class = "estadonuevo";
				break;
			case USUARIOACT:	
				$txt = "Activo";
				$class = "estadoactivo";
				break;
			case USUARIOBAJA:	
				$txt = "Baja";
				$class = "estadobaja";
				break;
			default:
				return "Sin Asignar";
			
		}

		$mostrarmsgclass = "";
		if ($estilos)
			$mostrarmsgclass = "class='".$class."'";
			
		$txt = "<span ".$mostrarmsgclass.">".$txt."</span>";
		return $txt;
		
	}





	function CambiarPwd($usuariocod,$claveactual,$clavenueva,$claveconf)
	{
		$ArregloDatos['usuariocod']=$usuariocod;
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
			return false;
		
		$filausuario=$this->conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
		if(md5($claveactual)!=$filausuario["usuariopassword"] || $clavenueva!=$claveconf)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Los datos ingresados son erróneos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if(!FuncionesPHPLocal::ValidarPassword($clavenueva,$claveactual,$filausuario["usuarioemail"],8))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La nueva contraseña no es válida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!parent::CambiarPassword ($filausuario,$clavenueva))
			return false;
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Function que modifica el pass de un usuario

// Parámetros de Entrada:
//		usuariocod: Codigo del usuario
//		usuariopassword: Pass del usuario
//		usuariopasswordconfirm: Confirmación del pass del usuario

// Retorna:
//		nuevapwd: la clave nueva generada por el sistema
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function CambiarPwdUsuario($datos)
	{
		$ArregloDatos['usuariocod']=$datos['usuariocod'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
			return false;
		
		$filausuario=$this->conexion->ObtenerSiguienteRegistro($resultadousuarios);
	
		if($datos['usuariopassword']!=$datos['usuariopasswordconfirm'])
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La confirmación de la contraseña es diferente a la misma. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if(!FuncionesPHPLocal::ValidarPassword($datos['usuariopassword'],$filausuario["usuarioemail"],$filausuario["usuarioemail"],8))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La nueva contraseña no es válida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!parent::CambiarPassword ($filausuario,$datos['usuariopassword']))
			return false;
			
		$datos['usuarioestado'] = USUARIONUEVO;
		$datos['usuariofbaja'] = "NULL";
		if (!parent::ModificarEstadoUsuario($datos))
			return false;
		
		return true;
	}
	
	public function ModificarEstado($datos)
	{
		$datos['usuariofbaja'] = "NULL";
		if (!parent::ModificarEstadoUsuario($datos))
			return false;
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Genera una password nueva para ser enviada al usuario

// Parámetros de Entrada:
//		usuariocuit/usuariomail: datos a buscar en la base

// Retorna:
//		nuevapwd: la clave nueva generada por el sistema
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	function ReenviarContrasenia($datos)
	{		
		$ArregloDatos['usuarioemail']=$datos['usuarioemail'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error al buscar el usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$usuario=$this->conexion->ObtenerSiguienteRegistro($resultadousuarios);
		if($usuario["usuarioestado"]>USUARIOACT)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"No se pudo enviar una contraseña nueva. Su usuario no se encuentra activo - '". FuncionesPHPLocal::HtmlspecialcharsBigtree($usuarioemail,ENT_QUOTES)."'",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			
			$nuevapwd=FuncionesPHPLocal::GenerarPassword(8);
			$datos['usuariopassword'] = $nuevapwd;
			$datos['usuarioestado'] = USUARIONUEVO;//$usuario['usuarioestado'];
			$datos['usuariocod'] = $usuario['usuariocod'];

			if(!parent::ModificarContraseniaUsuario($datos))
				return false;
			$datos = $usuario;
			$datos['usuariopassword'] = $nuevapwd;
			$oMails = new cMails($this->conexion,$this->formato);
			if (!$oMails->MailReenvioContrasenia($datos))
				return false;
		}
		

		return true;

	}


//----------------------------------------------------------------------------------------- 
// Retorna si el rol elegido es válido

// Parámetros de Entrada:
//		usuariocod
//		rolcod

// Retorna:
//		datosvalidados: retorna el rol asignado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	
	function ValidarDatosElegirRol($usuariocod,$rolcod,&$datosvalidados)
	{
		$roles=new cRoles($this->conexion);

		$roles->RolesDeUnUsuarioSP($usuariocod,$spnombre,$spparam);
		$arraybusq=array("rolcod"=>$rolcod);
	
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filaret,$numfilasmatcheo,$errno))
		{
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error BD en selección de roles. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		elseif($numfilasmatcheo!=1)
		{
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error filas en selección de roles. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$datosvalidados["rolcod"]=$filaret["rolcod"];
		
		
		return true;
	}
	
	function AltaUsuarioInterno($datos,&$usuariocod)
	{
		$oUsuarios_Roles = new cUsuariosRoles($this->conexion);
		
		//valido los datos a insertar
		if (!$this->ValidarDatosAlta($datos))
			return false;
			

		if($datos['usuariopassword']!=$datos['usuariopasswordconfirm'])
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Los datos ingresados son erróneos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		//valido los roles a insertar
		if (!$this->_ValidarRoles($datos))
			return false;			
		
		
		$datos['imgubic'] = "NULL";

		$datos['usuarioestado']=USUARIONUEVO;
		$datos['imgubic'] = "NULL";
		$datos['usuariofcambiopass'] = date("Y/m/d H:i:s");
		$datos['usuariofalta'] = date("Y/m/d H:i:s");
		$datos['usuariofbaja'] = "NULL";
		$datos['usuariocodigoactivacion'] = "NULL";
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		if(isset($datos["usuariofnacimiento"]) && $datos["usuariofnacimiento"]!="")
			$datos["usuariofnacimiento"]=FuncionesPHPLocal::ConvertirFecha($datos["usuariofnacimiento"],'dd/mm/aaaa','aaaa-mm-dd');
		
		$this->SetearValoresNull($datos);
		if (!parent::AltaUsuarioCompleto($datos,$usuariocod))
			return false;
		
		if(!FuncionesPHPLocal::ValidarPassword($datos['usuariopassword'],"",$datos["usuarioemail"],8))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La nueva contraseña no es válida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['usuariocod'] = $usuariocod;
		if (!parent::CambiarPassword ($datos,$datos['usuariopassword']))
			return false;
		
		//inserto los roles
		$datos["usuariocod"]=$usuariocod;
		if (!$oUsuarios_Roles->ActualizarRolesUsuario($datos))
			return false;

		return true;
	}
	
	
	function AltaUsuarioExterno($datos,&$usuariocod,&$usuariocodigoactivacion)
	{
		//valido los datos a insertar
		if (!$this->ValidarDatosAlta($datos))
			return false;
			
		$datos["usuariofnacimiento"]=FuncionesPHPLocal::ConvertirFecha($datos["usuariofnacimiento"],'dd/mm/aaaa','aaaa-mm-dd');
		$datos['usuarioestado']=USUPENDACT;
		if (!parent::AltaUsuario($datos,$usuariocod))
			return false;
			
		$datos['usuariocod']=$usuariocod;
		$usuariocodigoactivacion = $datos['usuariocodigoactivacion']=md5($usuariocod.$datos["usuarioemail"]);

		//armo md5 con usuariocod y el mail para activacion
		if (!parent::AsignarCodigoActivacionUsuario($datos))
			return false;
					
			
		return true;
	}
	
	public function Insertar($datos,&$usuariocod)
	{	
	
		 if(!$this->ValidarDatosAlta($datos,false))
		 	return false;
	
		$datos['usuariofnacimiento'] = FuncionesPHPLocal::ConvertirFecha($datos['usuariofnacimiento'],"dd/mm/aaaa","aaaa-mm-dd");			
		$datos['usuarioestado']=USUARIOACT;
		$this->SetearValoresNull($datos);

		
		$datos['imgubic'] = "NULL";
		if (!isset($datos['empresacelcod']) || $datos['empresacelcod']=="")
			$datos['empresacelcod']="NULL";
			
		$datos['imgubic'] = "NULL";
		$datos['usuariofcambiopass'] = date("Y/m/d H:i:s");
		$datos['usuariofalta'] = date("Y/m/d H:i:s");
		$datos['usuariofbaja'] = "NULL";
		$datos['usuariocodigoactivacion'] = "NULL";
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		if (!parent::AltaUsuarioCompleto($datos,$usuariocod))	
			return false;
		
		$datos['usuariocod'] = $usuariocod;
		if (isset($datos['imagen']) && $datos['imagen']!="")
		{
			if(!$this->SubirFotoUsuario($datos))
			 	return false;
			if ($datos['imagen']!="")
				$datos['imgubic'] = "usuario_".$datos['usuariocod'].'.jpg';
			if(!$this->ModificarFotoUsuario($datos))
			 	return false;
		}
			
		return true;
	}
	
	
	
	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificarUsuario($datos,false))	
			return false;
		if(isset($datos['usuariofnacimiento']) && $datos['usuariofnacimiento']!="")
			$datos['usuariofnacimiento'] = FuncionesPHPLocal::ConvertirFecha($datos['usuariofnacimiento'],"dd/mm/aaaa","aaaa-mm-dd");			
		
		$this->SetearValoresNull($datos);
		
		if (isset($datos['imagen']) && $datos['imagen']!="")
			if(!$this->SubirFotoUsuario($datos))
			 	return false;
		if (!isset($datos['empresacelcod']) || $datos['empresacelcod']=="")
			$datos['empresacelcod']="NULL";
		if (!isset($datos['imagen']) || $datos['imagen']!="")
			$datos['imgubic'] = "usuario_".$datos['usuariocod'].'.jpg';

		if (!parent::ModificarUsuario($datos))	
			return false;
			
		return true;
	}
	

	
	
	
	function SubirFotoUsuario($datos)
	{

		$data = str_replace("data:image/png;base64,","",$datos['imagen']);
		$imageStr = base64_decode($data);
		$image = imagecreatefromstring($imageStr);
		if ($image === false) {
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al subir la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$img = 'imgtmp/usuario_'.$datos['usuariocod'].date("Ymdhis").'.jpg';
		if(!imagepng($image, $img))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al subir la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if(($tamanio=getimagesize($img))===false)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"El archivo ingresado no es una imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$archnom=$img;
		$random = substr(md5(uniqid(rand())),0,6);
		$partes_ruta = pathinfo($archnom);
		$archubic = date("YmdHis").$random.".".$partes_ruta['extension'];
	
		$datosvalidados['fototitulo'] = $img;
		$datosvalidados["fotoubic"]=$archubic;

		$ancho = 320;
		$calidad = 100;
		$forma = "X"; 
		$image_new = FuncionesPHPLocal::Guardafoto($img, $ancho, $calidad, $forma);
		$savePath = IMAGENESUSUARIOS;
		if (!imagejpeg($image_new, $savePath."usuario_".$datos['usuariocod'].'.jpg', $calidad))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error guardar la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		unlink($img);
		return true;
	}
	
	
	
	public function GuardarImagen($datos)
	{	
	
		if(isset($datos['size']) && $datos['size']!="" && isset($datos['name']) && $datos['name']!="" && isset($datos['file']) && $datos['file']!="")
		{
			if(!$this->InsertarImgDesdeTemporal($datos))
				return false;

			if(!$this->ModificarFotoUsuario($datos))
			 	return false;
				
			if($datos["usuariocod"]==$_SESSION['usuariocod'])			
				$_SESSION['avatar']=$datos["imgubic"];
			
		}
		return true;
	}	
	
	public function InsertarImgDesdeTemporal(&$datos)
	{

		
		$pathinfo = pathinfo($datos['name']);
		$extension = strtolower($pathinfo['extension']);
		
		switch($extension)
		{
			case "jpg":
			case "gif":
			case "png":
				break;
			default:
				FuncionesPHPLocal::MostrarMensaje($this->conexion,"Formato de archivo no permitido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
				break;
		}
		
		if(!is_dir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR)){ 
			@mkdir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR);
		}

		//Subir imagenes
		$nombrearchivo = "usuario_".$datos['usuariocod'].".jpg";//.$extension;
		$carpetaorigen = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/"."tmp/".$datos['file'];

		$ancho = TAMANIOAVATARL;
		$calidad = 100;
		$forma = "T"; 
		$image_new = FuncionesPHPLocal::Guardafoto($carpetaorigen, $ancho, $calidad, $forma);
		$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L;
		if (!imagejpeg($image_new, $savePath."usuario_".$datos['usuariocod'].'.jpg', $calidad))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error guardar la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$ancho = TAMANIOAVATARM;
		$calidad = 100;
		$forma = "T"; 
		$image_new = FuncionesPHPLocal::Guardafoto($carpetaorigen, $ancho, $calidad, $forma);
		$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_M;
		if (!imagejpeg($image_new, $savePath."usuario_".$datos['usuariocod'].'.jpg', $calidad))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error guardar la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$ancho = TAMANIOAVATARS;
		$calidad = 100;
		$forma = "T"; 
		$image_new = FuncionesPHPLocal::Guardafoto($carpetaorigen, $ancho, $calidad, $forma);
		$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_S;
		if (!imagejpeg($image_new, $savePath."usuario_".$datos['usuariocod'].'.jpg', $calidad))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error guardar la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		@unlink($carpetaorigen);
		
		$datos['imgubic']=$nombrearchivo;
		return true;
	
	}

	
	function ActivarCuentaUsuario($datos)
	{
		if (!$this->_ValidarActivarCuenta($datos))	
			return false;
		
		$datos['usuarioestado'] = USUARIOACT;
		if (!parent::ModificarEstadoUsuario($datos))
			return false;
			
		return true;
	}
	
	
	function ModificarUsuarioInterno($datos)
	{	
		if (!$this->_ValidarModificarUsuario($datos))	
			return false;
		
		if (isset($datos['usuariofnacimiento']) && $datos['usuariofnacimiento']!="")
			$datos['usuariofnacimiento'] = FuncionesPHPLocal::ConvertirFecha($datos['usuariofnacimiento'],"dd/mm/aaaa","aaaa-mm-dd");			
		if (isset($datos['imagen']) && $datos['imagen']!="")
			if(!$this->SubirFotoUsuario($datos))
			 	return false;
		if (!isset($datos['empresacelcod']) || $datos['empresacelcod']=="")
			$datos['empresacelcod']="NULL";
		if (!isset($datos['imagen']) || $datos['imagen']!="")
			$datos['imgubic'] = "usuario_".$datos['usuariocod'].'.jpg';
		else
			$datos['imgubic'] = "NULL";
		$this->SetearValoresNull($datos);
		if (!parent::ModificarUsuario($datos))	
			return false;
	
		$oUsuarios_Roles = new cUsuariosRoles($this->conexion);
		if (!$oUsuarios_Roles->ActualizarRolesUsuario($datos))
			return false;
			
			
		if (isset($datos['usuariopassword']) && trim($datos['usuariopassword'])!="")
		{
			if(!$this->CambiarPwdUsuario($datos))
				return false;
		}	

		return true;
	}
	
	
	function EliminarUsuario($datos)
	{
		if (!$this->_ValidarEliminarUsuario($datos))
			return false;
		
		if (!parent::EliminarUsuario($datos))	
			return false;
		
		return true;
	}
	
	function BloqueaUsuario($datos)
	{
		
		$ArregloDatos['usuariocod']=$datos['usuariocod'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
			return false;
			
		if($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, usuario inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		$filausuario=$this->conexion->ObtenerSiguienteRegistro($resultadousuarios);
			
			$filausuario["usuarioestado"] = USUARIOBAJA;
			$filausuario['usuariofbaja'] = date("Y/m/d H:i:s");
			if (!parent::ModificarEstadoUsuario($filausuario))	
				return false;
		
		return true;
	}
	
	function RehabilitaUsuario($datos)
	{
		
		$ArregloDatos['usuariocod']=$datos['usuariocod'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
			return false;
			
		if($numfilas!=1)
		{
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, usuario inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		$filausuario=$this->conexion->ObtenerSiguienteRegistro($resultadousuarios);
			
		if($filausuario["usuarioestado"]!=USUARIOBAJA)
		{
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"El usuario no se encuentra para rehabilitar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		else
		{ // se puede generar la nueva contraseña
			$filausuario["usuarioestado"] = USUARIOACT;
			$filausuario['usuariofbaja'] ="NULL";
			if (!parent::ModificarEstadoUsuario($filausuario))	
				return false;
		}
		
		return true;
	}
	function _ValidarRoles($datos)
	{
		

		//recojo los roles de los check de roles
		$oUsuarios_Roles = new cUsuariosRoles($this->conexion);
		if (!$oUsuarios_Roles->ObtenerDatosCheckRoles($datos,$arrayfinal))
			return false;
			
		//si no hay al menos un rol muestro error
		if (count($arrayfinal)<1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Debe seleccionar al menos un rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	function _ValidarAcciones($datos)
	{

		return true;
	}	
	
	function _ValidarEstados($datos)
	{

		return true;
	}		
	
	function ValidarDatosAlta($datos,$validaemail = true)
	{
		if (!$this->_ValidarDatosVacios($datos,$validaemail))
			return false;
	
		
		

		if ($validaemail && $datos['usuarioemail'])
		{
			if (!$this->BuscarUsuarioxMail ($datos,$queryusuario,$numfilas))
				return false;
				
			if ($numfilas>=1)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, el e-mail ingresado no esta disponible en el sistema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		
		if ($datos['usuariopassword']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, debe ingresar una contraseña. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if ($datos['usuariopasswordconfirm']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, debe confirmar la contraseña. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if ($datos['usuariopassword']!=$datos['usuariopasswordconfirm'])
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, la contraseña no es la misma que la confirmada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (strlen($datos['usuariopassword'])<8)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La contraseña debe contener minimo 8 caracteres. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if(!FuncionesPHPLocal::ValidarPassword($datos['usuariopassword'],"",$datos["usuariodocumento"],8))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"La contraseña no es válida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	function _ValidarModificarUsuario($datos,$validaemail = true)
	{

		if (!$this->_ValidarDatosVacios($datos,$validaemail))
			return false;
			
		$ArregloDatos['usuariocod']=$datos['usuariocod'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Usuario inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($validaemail || $datos['usuarioemail']!="")
		{
			/* VALIDO EXISTENCIA DE EMAIL*/
			$ArregloDatosEmail['usuarioemail']=$datos['usuarioemail'];
			if (!$this->BuscarUsuarios ($ArregloDatosEmail,$queryusuario,$numfilas_email))
				return false;
			if ($numfilas_email==1)
			{
				$datosusuario = $this->conexion->ObtenerSiguienteRegistro($queryusuario);
				if ($datosusuario['usuariocod']!=$datos['usuariocod'])
				{	
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, el email ya existe y lo esta utilizando otro usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}	
			}
		}
		
		return true;
	}
	
function _ValidarDatosVacios($datos,$validaemail = true)
	{
		$datosvalidados=array();
		
		/*if ($datos['usuariosexo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe seleccionar el sexo del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if ($datos['tipodocumentocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe seleccionar el tipo de documento de identidad del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['usuariodoc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar el numero de documento del usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['usuariodoc'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un numero de documento valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		if ($datos['usuarionombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['usuarioapellido']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un apellido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($validaemail || $datos['usuarioemail']!="")
		{
			if ($datos['usuarioemail']=="")
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
			if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['usuarioemail'],"Email"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un email valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}

*/

		return true;
	}
		
	function _ValidarEliminarUsuario($datos)
	{
		/*
		$oRoles=new cRoles($this->conexion);
		
		if (!$oRoles->RolesDeUnUsuario($datos['usuariocod'],$numfilas,$resultadoroles))
			return false;

		if (!$oRoles->TraerRolesActualizar($_SESSION,$resultado,$numfilas))
			return false;
		$arregloroles = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloroles[] = $fila['rolcodactualizado'];
		
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoroles)) {
			if (!in_array($fila['rolcod'],$arregloroles))
			{
				FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el rol contiene un rol superior que no puede dar de baja. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}*/
			
		return true;
	}
	

	function _ValidarActivarCuenta($datos)
	{
		
		$ArregloDatos['usuariocod']=$datos['usuariocod'];
		if (!$this->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
		{	
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, usuario inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datosusuario = $this->conexion->ObtenerSiguienteRegistro($resultadousuarios);	
		if ($datosusuario['usuarioestado']!=USUPENDACT)
		{
			FuncionesPHPLocalPortal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, estado del usuario no pendiente de activacion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
		
	}
	
	
	private function SetearValoresNull(&$datos)
	{
		if (!isset($datos['usuariodireccion']) || $datos['usuariodireccion']=="")
			$datos['usuariodireccion'] = "NULL";
			
		if (!isset($datos['usuariodirnumero']) || $datos['usuariodirnumero']=="")
			$datos['usuariodirnumero'] = "NULL";
			
		if (!isset($datos['usuariodirpiso']) || $datos['usuariodirpiso']=="")
			$datos['usuariodirpiso'] = "NULL";
			
		if (!isset($datos['usuariodirdpto']) || $datos['usuariodirdpto']=="")
			$datos['usuariodirdpto'] = "NULL";
			
		if (!isset($datos['usuariotel']) || $datos['usuariotel']=="")
			$datos['usuariotel'] = "NULL";
			
		if (!isset($datos['usuariocel']) || $datos['usuariocel']=="")
			$datos['usuariocel'] = "NULL";
			
		if (!isset($datos['empresacelcod']) || $datos['empresacelcod']=="")
			$datos['empresacelcod']="NULL";
			
		if (!isset($datos['usuariotwitter']) || $datos['usuariotwitter']=="")
			$datos['usuariotwitter'] = "NULL";
			
		if (!isset($datos['usuariofacebook']) || $datos['usuariofacebook']=="")
			$datos['usuariofacebook'] = "NULL";
			
		if (!isset($datos['departamentocod']) || $datos['departamentocod']=="")
			$datos['departamentocod'] = "NULL";
			
		if (!isset($datos['provinciacod']) || $datos['provinciacod']=="")
			$datos['provinciacod'] = "NULL";
			
		if (!isset($datos['usuariocp']) || $datos['usuariocp']=="")
			$datos['usuariocp'] = "NULL";
			
		if (!isset($datos['usuarioemail']) || $datos['usuarioemail']=="")
			$datos['usuarioemail']="NULL";
			
		if (!isset($datos['usuariofnacimiento']) || $datos['usuariofnacimiento']=="")
			$datos['usuariofnacimiento']="NULL";
			
		
	}

	
	public function ModificarFotoUsuario($datos)
	{

		if (!parent::ModificarFotoUsuario ($datos))
			return false;
		

		return true;	
	}
	
	public function EliminarFotoUsuario($datos)
	{
		
		if(!$this->BorrarFotoUsuario($datos))
			return false;
				
		if(!$this->ModificarFotoUsuario($datos))
			return false;
				
		return true;

	}

	function BorrarFotoUsuario(&$datos)
	{

		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datosusuario= $this->conexion->ObtenerSiguienteRegistro($resultado);

		if(isset($datosusuario['imgubic']) && $datosusuario['imgubic']!="")
		{
			$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L."usuario_".$datos['usuariocod'].'.jpg';
			unlink($savePath);
			
			$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_M."usuario_".$datos['usuariocod'].'.jpg';
			unlink($savePath);
			
			$savePath = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_S."usuario_".$datos['usuariocod'].'.jpg';
			unlink($savePath);
		}
		
		if($datos["usuariocod"]==$_SESSION['usuariocod'])			
			$_SESSION['avatar']="/default.png";
		
		$datos['imgubic']="/default.png";
		return true;
	}	

	public function BuscarxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
}



?>