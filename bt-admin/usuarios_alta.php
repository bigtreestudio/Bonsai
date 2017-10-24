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

$usuarios = new cUsuarios($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = $_SESSION['msgactualizacion'];
	
$oRol = new cRoles($conexion);
if (!$oRol->TraerRolesActualizar($_SESSION,$resultadoroles,$numfilasroles))
	return false;
	

	
$usuarioprovincia="";
$usuariociudad="";
$usuarionombre = "";	
$usuarioapellido = "";	
$usuarioemail = "";	
$usuariofnacimiento = "";	
$resultadoaccionesusuarios="";
$numfilasaccionesusuarios=0;
$resultadoestadoesusuarios="";
$numfilasestadousuarios=0;
$modificacion=0;

$usuariofingreso  = "";
$usuariogerencia = "";
$usuarioarea = "";
$usuariopiso = "";
$usuariocalle = "";
$usuariopuerta = "";
$usuarioprovincia = "";
$usuariociudad = "";
$usuariocp = "";
$usuariotel = "";
$tipodocumentocod = "";
$usuariodoc = "";
$usuariosexo = "";
$usuarioestadocivil = "";
$usuariodireccion="";
$usuariodirnumero="";
$usuariocel="";




if (isset($_SESSION['datosusuario']) && count($_SESSION['datosusuario'])>0)
{
	if(isset($_SESSION['datosusuario']['usuarionombre']) && $_SESSION['datosusuario']['usuarionombre']!="")
		$usuarionombre = $_SESSION['datosusuario']['usuarionombre'];	
	if(isset($_SESSION['datosusuario']['usuarioapellido']) && $_SESSION['datosusuario']['usuarioapellido']!="")
		$usuarioapellido = $_SESSION['datosusuario']['usuarioapellido'];	
	if(isset($_SESSION['datosusuario']['usuarioemail']) && $_SESSION['datosusuario']['usuarioemail']!="")
		$usuarioemail = $_SESSION['datosusuario']['usuarioemail'];	
	//$usuariofnacimiento = $_SESSION['datosusuario']['usuariofnacimiento'];	
	if(isset($_SESSION['datosusuario']['usuariotel']) && $_SESSION['datosusuario']['usuariotel']!="")
		$usuariotel = $_SESSION['datosusuario']['usuariotel'];	
	if(isset($_SESSION['datosusuario']['usuariodoc']) && $_SESSION['datosusuario']['usuariodoc']!="")
		$usuariodoc=$_SESSION['datosusuario']['usuariodoc'];
	if(isset ($_SESSION['datosusuario']['usuariosexo']) && $_SESSION['datosusuario']['usuariosexo']!="")
		$usuariosexo=$_SESSION['datosusuario']['usuariosexo'];
	
	if(isset($_SESSION['datosusuario']['tipodocumentocod']) && $_SESSION['datosusuario']['tipodocumentocod']!="")
		$tipodocumentocod=$_SESSION['datosusuario']['tipodocumentocod'];
}
?>

<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
<link href="modulos/us_usuarios/css/usuarios.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="modulos/us_usuarios/js/usuarios_areas.js"></script>
<script type="text/javascript" src="modulos/us_usuarios/js/usuarios_am.js"></script>
<script type="text/javascript" language="javascript">
var mailusuario	= "<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($usuarioemail,ENT_QUOTES);?>";
</script>


<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Datos del usuario</h2>
</div>
<div class="form">
	<form action="usuarios_alta_upd.php" class="general_form" method="post" name="formulario" id="formulario">
         <div class="col-md-5 text-center">
			<img  src="<?php  echo CARPETA_SERVIDOR_MULTIMEDIA.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR.CARPETA_SERVIDOR_MULTIMEDIA_AVATAR_L.'default.png' ?>" id="avatarImg" style="border:1px solid; color:#999999;"/>
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
										<input type="text" name="usuarioemail"  tabindex="1" id="usuarioemail" value="<?php echo $usuarioemail;?>" placeholder="usuario@dominio.ext" class="form-control input-md"  maxlength="255" onKeyUp="BuscarEmail();" />
									</div>
									<!--<div class="col-md-3">
										<span id="ChkEmail"></span>
									</div>-->
									<div class="clearboth"></div>
								</div>
								<div class="form-group">
									<label class="col-md-3" for="usuarionombre">Nombre <span class="obligatorio">(*)</span></label>
									<div class="col-md-9">
											<input type="text" name="usuarionombre"  tabindex="2" id="usuarionombre"  value="<?php echo $usuarionombre;?>" placeholder="Nombre"  class="form-control input-md" maxlength="100" size="80"  />
									</div>
									<div class="clearboth"></div>
								</div>
			
								<div class="form-group">
									<label class="col-md-3" for="usuarioapellido">Apellido <span class="obligatorio">(*)</span></label>
									<div class="col-md-9">
										  <input type="text" name="usuarioapellido"  tabindex="3" id="usuarioapellido"  value="<?php echo $usuarioapellido;?>" placeholder="Apellido"  class="form-control input-md" maxlength="100" size="80"/>
									</div>
									<div class="clearboth"></div>
								</div>
								<div class="form-group">
									<label class="col-md-3" for="usuariosexo">Sexo: <span class="obligatorio">(*)</span></label>
								
									<?php  
									$checkedm="";
									if($usuariosexo="M")
										$checkedm="checked";
									$checkedf="";		
									if($usuariosexo="F")
										$checkedm="checked";				
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
											<option value="">Elija una opci&oacute;n...</option>
											<option <?php if($tipodocumentocod==1){ echo "selected";}?> value="1">DNI</option>
											<option <?php if($tipodocumentocod==2){ echo "selected";}?> value="2">LE</option>
											<option <?php if($tipodocumentocod==3){ echo "selected";}?> value="3">LC</option>
											<option <?php if($tipodocumentocod==4){ echo "selected";}?> value="4">Pasaporte</option>
										</select>
									</div>
									<div class="clearboth"></div>
								</div> 
			
								<div class="form-group">
									<label class="col-md-3" for="usuariodoc">N&uacute;mero de documento</label>
									<div class="col-md-9">
										  <input type="text" name="usuariodoc"  tabindex="6" id="usuariodoc"  value="<?php echo $usuariodoc;?>" placeholder="Documento"  class="form-control input-md" maxlength="8" size="80" />
									</div>
									<div class="clearboth"></div>
								</div>
								
			
								<div class="form-group">
									<label class="col-md-3" for="usuariotel">Tel&eacute;fono</label>
									<div class="col-md-9">
										  <input type="text" name="usuariotel"  tabindex="7" id="usuariotel"  value="<?php echo $usuariotel;?>" placeholder="Telefono"  class="form-control input-md" maxlength="100" size="80"/>
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
		<div class="col-md-5">
			<h2>Contrase&ntilde;a</h2>
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


		$tabindex =10;
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
					<li><div class="ancho_boton aire"><input type="submit" name="botonmodif"  class="boton verde" tabindex="<?php  echo $tabindex?>" value="Guardar" onclick="return Validar(formulario)" /></div></li>
					<?php  $tabindex++;?>
					<li><div class="ancho_boton aire"> <input type="button" name="botonvolver" class="boton base" tabindex="<?php  echo $tabindex?>"  value="Volver" onclick="return window.location='usuarios_buscar.php';" /></div></li>
				</ul>
				<div class="clearboth">&nbsp;</div>
			</div>
		</div>
	</form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>
