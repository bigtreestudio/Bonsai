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

$usuarios = new cUsuarios($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 
$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = $_SESSION['msgactualizacion'];
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
	$ArregloDatos['usuariocod']=$_POST['usuariocod'];
	if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
	{	
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	
	$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
	$usuariotelefono=$filausuario["usuariotel"];
	
?>
<script type="text/javascript" language="javascript">

jQuery(document).ready(function(){
		$( "#usuariofnacimiento" ).datepicker( {dateFormat:"dd/mm/yy",changeMonth: true,changeYear: true, yearRange: "-120:+0",});
	});

</script>
<link href="modulos/us_usuarios/css/usuarios.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="modulos/us_usuarios/js/usuarios_img.js"></script>


<div class="col-md-3 text-center">
	<div id="avatarImg">
	<?php  if (isset($filausuario['imgubic'] ) && $filausuario['imgubic']!=""){  ?>
		<img src="<?php  echo CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L.$filausuario['imgubic']."";?>" />
	<?php  }else{ ?>
		<img  src="<?php  echo CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L.'default.png' ?>" id="avatarImg" style="border:1px solid; color:#999999;"/>
	<?php  }?>
   </div>
	<div style=" margin-bottom:8px">&nbsp;&nbsp;</div>
  </div>
<div class="col-md-2" style="float:left">	
	<div style=" margin-bottom:6px">&nbsp;&nbsp;</div>
	<span>La imagen se guarda automaticamente</span>
	<div style=" margin-bottom:6px">&nbsp;&nbsp;</div>
	
	<div id="btn_subirImgMostrar" style="float:left" ></div>                  
	<input type="hidden" name="imagen" id="imagen" value="" />
	<input type="hidden" name="size" id="size"  value="" />
	<input type="hidden" name="name" id="name"  value="" />
	<input type="hidden" name="file" id="file"  value="" />
	<div class="clearboth brisa_vertical">&nbsp;</div>
	<div class="menubarra">
		<ul>
			<li><div class="ancho boton_aire"><a class="boton rojo" href="javascript:void(0)" id="Eliminar" onclick="EliminarFotoUsuario()"><i class="fa fa-times"></i>&nbsp;Eliminar Avatar</a></div></li>
		</ul>
	</div>

</div>
	  
<div class="col-md-7">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				Datos del usuario
			</h4>
		</div>
		<div class="panel-collapse collapse in">
			<div class="panel-body"> 
				<div class="col-md-12">
						<div class="form-group">
							<div  class="col-md-3">
								<label for="usuarioemail">Email <span class="obligatorio">(*)</span></label> 
							</div>
							<div class="col-md-9">
								<input type="text" name="usuarioemail"  tabindex="1" id="usuarioemail" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarioemail'],ENT_QUOTES); ?>" class="form-control input-md"  maxlength="255" onKeyUp="BuscarEmail();" />
							</div>
							<!--<div class="col-md-3">
								<span id="ChkEmail"></span>
							</div>-->
							<div class="clearboth"></div>
						</div>
						<div class="form-group">
							<label class="col-md-3" for="usuarionombre">Nombre <span class="obligatorio">(*)</span></label>
							<div class="col-md-9">
									<input type="text" name="usuarionombre"  tabindex="2" id="usuarionombre"  value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarionombre'],ENT_QUOTES); ?>"  class="form-control input-md" maxlength="100" size="80"  />
							</div>
							<div class="clearboth"></div>
						</div>
	
						<div class="form-group">
							<label class="col-md-3" for="usuarioapellido">Apellido <span class="obligatorio">(*)</span></label>
							<div class="col-md-9">
								  <input type="text" name="usuarioapellido"  tabindex="3" id="usuarioapellido"  value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarioapellido'],ENT_QUOTES); ?>"  class="form-control input-md" maxlength="100" size="80"/>
							</div>
							<div class="clearboth"></div>
						</div>
						<div class="form-group">
							<label class="col-md-3" for="usuariosexo">Sexo: <span class="obligatorio">(*)</span></label>
						
							<?php  
							$checkedm="";
							$checkedf="";
							if($filausuario['usuariosexo']=="M" )
								$checkedm='checked="checked"'; 
							if($filausuario['usuariosexo']=="F" )
								$checkedf='checked="checked"'; 							
							?>
							
							<div class="col-md-9">
								<label class="radio_label">Masculino</label>
								<input type="radio" tabindex="4" class="radio" name="usuariosexo" id="usuariosexo_M" value="M" <?php  echo $checkedm ?>/>
								<label class="radio_label">Femenino</label>
								<input type="radio" name="usuariosexo" class="radio" id="usuariosexo_F" value="F"<?php  echo $checkedf ?>/>
							</div>  
							<div class="clearboth"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3" for="tipodocumentocod">
								<label>Tipo de documento:</label>
							</div>   
							<div class="col-md-9">
								<select class="form-control input-md" name="tipodocumentocod" id="tipodocumentocod" tabindex="5">	
									<option <?php  if ($filausuario['tipodocumentocod']==1) echo "selected='selected'"; ?>  value="1">DNI</option>
									<option <?php  if ($filausuario['tipodocumentocod']==2) echo "selected='selected'"; ?> value="2">LE</option>
									<option <?php  if ($filausuario['tipodocumentocod']==3) echo "selected='selected'"; ?> value="3">LC</option>
									<option <?php  if ($filausuario['tipodocumentocod']==4) echo "selected='selected'"; ?> value="4">Pasaporte</option>
								</select>
							</div>
							<div class="clearboth"></div>
						</div> 
	
						<div class="form-group">
							<label class="col-md-3" for="usuariodoc">N&uacute;mero de documento</label>
							<div class="col-md-9">
								  <input type="text" name="usuariodoc"  tabindex="6" id="usuariodoc"  value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuariodoc'],ENT_QUOTES); ?>"  class="form-control input-md" maxlength="8" size="80" />
							</div>
							<div class="clearboth"></div>
						</div>
						
	
						<div class="form-group">
							<label class="col-md-3" for="usuariotel">Tel&eacute;fono</label>
							<div class="col-md-9">
								  <input type="text" name="usuariotel"  tabindex="7" id="usuariotel"  value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuariotel'],ENT_QUOTES); ?>"  class="form-control input-md" maxlength="100" size="80"/>
							</div>
							<div class="clearboth"></div>
						</div>
	
	
						<div class="clearboth brisa_vertical">&nbsp;</div>
					</div>
				</div>
			</div>
		</div>                        
</div>	
<div class="clearboth">&nbsp;</div>
<div class="clearboth aire">&nbsp;</div>

  
<?php 
$_SESSION['msgactualizacion']="";
?>