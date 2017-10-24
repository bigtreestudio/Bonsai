<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$usuarios=new cUsuarios($conexion);
$oRoles=new cRoles($conexion);


FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_GET['usuariocod']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}
$datosgerencias=array();

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
	$ArregloDatos['usuariocod']=$_GET['usuariocod'];
	if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
	{	
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Usuario inexistente, por favor seleccionelo nuevamente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
		
	$mensajeaccion = "";
	if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
		$mensajeaccion = $_SESSION['msgactualizacion'];

	$filausuario = $conexion->ObtenerSiguienteRegistro($resultadousuarios);


	$esbaja = false;
	if ($filausuario['usuarioestado']==USUARIOBAJA)
		$esbaja = true;


	FuncionesPHPLocal::ArmarLinkMD5("usuarios_modificar_datos_upd.php",array("usuariocod"=>$ArregloDatos['usuariocod']),$get_md5,$md5_upd);



	if($filausuario['usuariofnacimiento']!=""){
		$usuariofnacimiento = FuncionesPHPLocal::ConvertirFecha($filausuario['usuariofnacimiento'],"aaaa-mm-dd","dd/mm/aaaa");	}
	
	$usuarioprovincia = $filausuario['provinciacod'];
	$usuariociudad = $filausuario['departamentocod'];
	$usuariocp = $filausuario['usuariocp'];
	$usuariotipodocumento = $filausuario['tipodocumentocod'];
	$usuariodocumento = $filausuario['usuariodoc'];
	$usuariosexo = $filausuario['usuariosexo'];
	$usuariodireccion = $filausuario['usuariodireccion'];
	$usuariodirnumero = $filausuario['usuariodirnumero'];
	$usuariodirpiso = $filausuario['usuariodirpiso'];
	$usuariodirdpto = $filausuario['usuariodirdpto'];
	$usuariocp = $filausuario['usuariocp'];
	$usuariotelefono = $filausuario['usuariotel'];
	$usuariocel = $filausuario['usuariocel'];
	$usuariotwitter = $filausuario['usuariotwitter'];
	$usuariofacebook = $filausuario['usuariofacebook'];

	$modificacion=1;
	
?>

<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<link href="modulos/us_usuarios/css/usuarios.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="modulos/us_usuarios/js/usuarios_am.js?v=1.0"></script>
<script type="text/javascript" language="javascript">
	var mailusuario	= "<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarioemail'],ENT_QUOTES);?>";
	var usuariocod = "<?php echo $_GET['usuariocod'];?>";
</script>


<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Datos del usuario</h2>
</div>
<div class="form clearfix">
	<form action="usuarios_modificar_datos_upd.php" class="general_form" method="post" name="formulario" id="formulario">
		<div id="DatosPersonales"></div> 
		<div class="clearboth">&nbsp;</div>
		<div class="col-md-5">
			<h2>Modificar Contrase&ntilde;a</h2>
			<div class="form-group">
				<label for="usuariopassword">Contrase&ntilde;a Nueva</label>
				<input type="password"  class="form-control input-md" tabindex="8" value="" autocomplete="off" id="usuariopassword" name="usuariopassword" />
			</div>
			<div class="form-group">
				<label for="usuariopasswordconfirm">Confirmar Contrase&ntilde;a</label>
				<input type="password"  class="form-control input-md" tabindex="9" value="" autocomplete="off" id="usuariopasswordconfirm" name="usuariopasswordconfirm" />
			</div>
		</div>
		<div class="col-md-7">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h4 class="panel-title">
						<i class="fa fa-exclamation-triangle"></i>&nbsp;La contrase&ntilde;a nueva debe cumplir los siguientes requerimientos
					</h4>
				</div>
				<div id="accordion1_1" class="panel-collapse collapse in" aria-expanded="true">
					<div class="panel-body"> 
						- Debe contener al menos una may&uacute;scula.<br>
						- Debe contener al menos un n&uacute;mero o caracter especial.<br>
						- Como m&iacute;nimo debe tener un largo de 8 car&aacute;cteres.<br>
						- No puede tener el mismo car&aacute;cter consecutivo mas de 4 veces.<br>
						- No puede ser parecida a la contrase&ntilde;a anterior.<br>
					</div>
				</div>
			</div>                        
		</div>
		<div class="clearboth"></div>    

		<?php 
		$oRol = new cRoles($conexion);
		if (!$oRol->TraerRolesActualizar($_SESSION,$resultadoroles,$numfilasroles))
			return false;
		
		
		if (!$oRoles->RolesDeUnUsuario($_GET['usuariocod'],$numfilasrolesusuarios,$resultadorolesusuarios))
			return false;
	
		$arreglorolesusuarios = array();
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultadorolesusuarios))
			$arreglorolesusuarios[$fila['rolcod']] = $fila['rolcod'];

		$tabindex = 10;
		if ($numfilasroles>0)
		{
			
			?>
			 
		 <div class="aire clearboth">&nbsp;</div>
		 <div class="col-md-12">
			<h2>Roles Posibles a Asignar:</h2>
		 </div>    
		<div class="clearboth brisa_vertical">&nbsp;</div>
		<div class="col-md-12">
		<?php  while($fila_roles = $conexion->ObtenerSiguienteRegistro($resultadoroles)) 
		   { 
				//print_r($arreglorolesusuarios);
				$checked = '';
				if ($numfilasrolesusuarios>0){
					if (array_key_exists($fila_roles['rolcodactualizado'],$arreglorolesusuarios))
						$checked='checked="checked"';                           
				}
			?>
			<div class="col-md-3">
				<input type="checkbox" class="chk" tabindex="<?php  echo $tabindex?>" id="rolcod_<?php  echo $fila_roles['rolcodactualizado']?>" name="rolcod_<?php  echo $fila_roles['rolcodactualizado']?>" value="<?php  echo $fila_roles['rolcodactualizado']?>" <?php  echo $checked?> />
				<label for="rolcod_<?php  echo $fila_roles['rolcodactualizado']?>" class="bold">
				<?php    
					if($conexion->TraerCampo("roles","roldesc",array("rolcod='",$fila_roles['rolcodactualizado'],"'"),$roldesc,$numfilas,$errno))
					   echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($roldesc,ENT_QUOTES);
				?>
				</label>
			</div>
				<?php  
				$tabindex++;
			}
			?>					
		</div><?php
		}?>
		<div class="clearboth brisa_vertical">&nbsp;</div>
				 
		<?php  $tabindex++;?>
		<input type="hidden" name="modif" id="modif" value="<?php  echo $modificacion?>" />
		<input type="hidden" value="<?php  echo $filausuario['usuariocod']?>" name="usuariocod" id="usuariocod" />
		<input type="hidden" value="<?php  echo $md5_upd?>" name="md5" />
		<div class="clearfix aire">&nbsp;</div>
		<div class="row">
			<span class="obligatorio">(*)</span> Campos Obligatorios.
		</div>
	   
		<?php  if ($mensajeaccion!=""){?>
		<div class="clearfix aire">&nbsp;</div>
		<div class="col-md-12">
			<?php  FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$mensajeaccion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));?>
		</div>
		<?php  }?>
		<div class="clearboth">&nbsp;</div>
		<div class="menubarraInferior">
			<div class="menubarra">
				<ul>
					<li><div class="ancho_boton aire"><input type="submit" name="botonmodif"  class="boton verde" tabindex="<?php  echo $tabindex?>" value="Modificar" onClick="return Validar(formulario)" /></div></li>
					<?php  $tabindex++;?>
					<li><div class="ancho_boton aire"> <input type="button" name="botonvolver" class="boton base" tabindex="<?php  echo $tabindex?>"  value="Volver" onClick="return window.location='usuarios_buscar.php';" /></div></li>
					<?php  $tabindex++;?>
.					<?php if($filausuario['usuarioestado']==USUARIOACT /*|| $filausuario['usuarioestado']==USUARIOPASSBLOQUEADO */|| $filausuario['usuarioestado']==USUARIONUEVO){?>
						<li><div class="ancho_boton aire"> <input type="submit" name="botoneliminar" class="boton rojo" tabindex="<?php  echo $tabindex?>"  value="Bloquear"  onClick="return confirm('¿Está seguro de que desea bloquear el usuario?');" /></div></li>
						<?php  $tabindex++;?>
					<?php }
					else {?>
						<li><div class="ancho_boton aire"> <input type="submit" name="botonrehabilitar" class="boton base" tabindex="<?php  echo $tabindex?>" value="Rehabilitar" /></div></li>	
						<?php  $tabindex++;?>
					<?php } 
					?>
				</ul>
				<div class="clearboth">&nbsp;</div>
			</div>
		</div>
	</form>
	<div class="clearboth">&nbsp;</div>

</div>
   
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>
