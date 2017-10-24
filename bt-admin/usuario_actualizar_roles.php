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

//----------------------------------------------------------------------------------------- 	
?>
<script type="text/javascript">
function doExpand(nomObj,imagen) {
	var objMostrado=document.getElementById(nomObj);         
	if (objMostrado.style.display=="none") {
		objMostrado.style.display=""; 
		imagen.src="minus.gif"
  	} else  {
		objMostrado.style.display="none";
		imagen.src="plus.gif"
	}
}

// --------------------------------------------------------- 
function seccion_CambiarEstado(TheTR,estado)	{
	// Muestra o Oculta un grupo de elementos. (div)
	// TheTR: corresponde al nombre del objeto.
	var DataTR = eval('document.getElementById("' + TheTR + '")');
	if (estado=="abierto") 
		DataTR.style.display="";
	else 
		DataTR.style.display="none";
}
//------------------------------------------------------------
function Validar(formulario,operacion) 	{

	if(formulario.usuarioCUIT.value=="" || !ValidarContenido(formulario.usuarioCUIT.value,"CUIT"))
	{
		alert("El CUIT/CUIL/CDI no es válido");
		formulario.usuarioCUIT.focus();
		return false;	
	}

	switch (operacion){
		case "I": 		
			return ValidarDatosRolUsuarioJS(formulario);	
			break;
		case "B": 
			return true;
			break;
		case "M": 
			return ValidarDatosRolUsuarioJS(formulario);	
			break;
		default : false;
	}
}
//-----------------------------------------------------------------------------------------------------	
function ValidarDatosRolUsuarioJS(formulario)	{

	if(formulario.rolcod.value=="") 	{
		alert("Debe ingresar el rol");
		formulario.rolcod.focus();
		return false;	
	}

	return true;	
}
</script>
<?php 
//--------------------------------------------------------------------------------------------------
function MostrarGrillaSeleccion($conexion, $cuit) {

	$spnombre='sel_usuarios_roles_actualizables_xrolcod_cuit';
	$spparam = array('pusuariocuit'=>$cuit, 
 					 'pusuarioestado'=>USUARIOACT.",".USUARIONUEVO,
					 'prolcodactualiza'=>$_SESSION['rolcod'],				
					 'porderby'=>"roldesc"
		 			);

	if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$result_usu,$num_roles_usu,$errno)) {	
		$txt_sys_mail="\nusuariocuit=".$cuit."\nusuarioestado=".USUARIOACT."\nrolcodactualiza=".$_SESSION['rolcod'];
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar usuarios.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO),$txt_sys_mail);
		return false;			
	}

	$opciones=array(
		"FilasPagina"=>$num_roles_usu,
	);
	
	$oGrilla = new cGrilla($opciones);
	$oGrilla->SetearTituloGrilla('Roles actualizables');
	$oGrilla->SetearAnchoGrilla('100%');
	
	$miColumna1 = $oGrilla->AddColumna('txt');
	$oGrilla->Setear('titulo_columna',$miColumna1,'Rol');
	$oGrilla->Setear('campo_grilla',$miColumna1,'roldesc');
	$oGrilla->Setear('align_columna',$miColumna1,'left');


	$miColumna4 = $oGrilla->AddColumna('linkimg');
	$oGrilla->Setear('titulo_columna',$miColumna4,'Eliminar');
	$oGrilla->Setear('ancho_columna',$miColumna4,'60');
	$oGrilla->Setear('url',$miColumna4,'usuario_actualizar_roles.php');
	$oGrilla->Setear('url_ancla',$miColumna4,'Edicion');
	$oGrilla->Setear('imagen',$miColumna4,'images/borrar.jpg');
	
	$oGrilla->Setear('altImagen',$miColumna4,'Eliminar');
	$oGrilla->AddParametrosURL($miColumna4,"usuariocod","usuariocod");
	$oGrilla->AddParametrosURL($miColumna4,"rolcod","rolcod");
	$oGrilla->AddParametrosURL($miColumna4,"operacion","B","cte");

	if (!$oGrilla->CargarGrilla($result_usu))
		return false;
		
	$oGrilla->MostrarGrilla();

	return true;
} 		

//--------------------------------------------------------------------------------------------------
// Inicio de pantalla
?>
<br />
<div>
	  <h1>Actualizar Roles de Usuario</h1>
</div>
<br />
<?php 
$result = true;

$oUsuario=new cUsuarios($conexion);
$oRol = new cRoles($conexion);

$filausuario=array();
$filausuario['usuarioCUIT']=(isset($_POST['usuarioCUIT']))?$_POST['usuarioCUIT']:"";
$filausuario['usuariocod']=(isset($_GET['usuariocod']))?$_GET['usuariocod']:"";

if (isset($_POST['usuarioCUIT']) ) {
	if($conexion->TraerCampo("usuarios","usuariocod",array("usuariocuit='",$_POST['usuarioCUIT'],"'"),$usuariocod,$numfilas,$errno))
	 	$filausuario['usuariocod']= FuncionesPHPLocal::HtmlspecialcharsBigtree($usuariocod,ENT_QUOTES);
}
if (isset($_GET['usuariocod']) ) {
	if($conexion->TraerCampo("usuarios","usuariocuit",array("usuariocod='",$_GET['usuariocod'],"'"),$usuariocuit,$numfilas,$errno))
	 	$filausuario['usuarioCUIT']= FuncionesPHPLocal::HtmlspecialcharsBigtree($usuariocuit,ENT_QUOTES);
}
?>
<form action="usuario_actualizar_roles.php" method="post" name="formulario_cuit" id="formulario_cuit">
      <fieldset>
		<legend>Usuario</legend>

		<?php  $oUsuario->PedirCuit($filausuario['usuarioCUIT'],'formulario_cuit','usuarioCUIT','botoningresoCUIT'); ?>			 
       </fieldset>
</form>
<?php  
//------- DATOS BASICOS DEL USUARIO
if ($filausuario['usuariocod']=="") {

	if (isset($_GET['usuariocod']) || isset($_POST['usuarioCUIT']))
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"El usuario no se encuentra en el sistema.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));

} else { 

	$oUsuario->TraerDatosUsuario($filausuario['usuariocod'],$filausuario,false);
?>
				<?php 	$oUsuario->MostrarDatosBasicosUsuarios($filausuario); ?>

				<?php 	 
					if(!($filausuario['usuarioestado'] == USUARIOACT  || $filausuario['usuarioestado'] == USUARIONUEVO))  {
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"<br />El usuario no esta activo en el sistema.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));				
						$result=false;
					}	
				?>
<?php  
//------- 
if ($result)	{  

 	// Se define la acción
	if (!isset($_GET['operacion'])) {
	
			$leyenda_operacion = "Agregar";
			$boton_operacion= "Agregar";
			$operacion = "I";
			if (!$oRol->RolesPosiblesAsignar($_SESSION['rolcod'],$filausuario['usuariocod'],$numfilas,$roles_sin_asignar)) {
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al obtener los roles posibles de asignar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				$result=false;	
			} 
	} else {
		switch ($_GET['operacion']) {		
		case "M" :
				$leyenda_operacion = "Actualizar";	
				$boton_operacion = "Actualizar";	 	
				$operacion = "M";				
				if(!$oRol->TienePermisoModificarAsignacionRol($_SESSION["rolcod"],$_GET['rolcod'])) 
					$result=false;	
				break;
		case "B" :
				$leyenda_operacion = "Eliminar";		
				$boton_operacion = "Eliminar";	 	
				$operacion = "B";				
				if(!$oRol->TienePermisoDesasignarRol($_SESSION["rolcod"],$_GET['rolcod'])) 
					$result=false;	
				break;
		default:
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error de operación.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$result=false;
		}
	}

	if ($result)  {
		//------- GRILLA DE ROLES ?>
        <div class="centrado">
				<?php 	if (!MostrarGrillaSeleccion($conexion,$filausuario['usuarioCUIT']))
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar los roles asignados.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				?>
       </div>         
		<br />
<?php 	}

	if ($operacion=="I"  && count($roles_sin_asignar)==0) {
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"El usuario ya tiene todos los roles que Ud. puede agregar.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$result = false;
	}	
		
	if ($result) { 	
	
		$filausuariorol=array();						//  NUEVO
		if ($operacion == "M" || $operacion == "B") {	// BAJA o MODIFICACION
			if( !$oUsuario->BuscarUsuariosXUsuarioRol($filausuario['usuariocod'],$_GET['rolcod'],$numfilas,$query_usurol)) 
				$result=false;
			elseif ($numfilas==0)  {
					$txt_sys_mail="Error no existe el rol en usuario_rol_jurisdiccion_inap \n"."\n usuariocod=".$filausuario['usuariocod']."\n rolcod= ".$_GET['rolcod'];
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando rol del usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO),$txt_sys_mail);
					$result=false;
				} else
					$filausuariorol=$conexion->ObtenerSiguienteRegistro($query_usurol);
					
		} // Obtencion de datos del rol
		// ---- CARGA DE DATOS DEL ROL (Corresponde si no es modificación o es modificación y es el Coordinador Técnico)		
?>  

<?php 	if ( $result && ($operacion != "M" || ($operacion == "M"))) {  ?>
		<a name="Edicion"></a>
		<table width="100%"  border="0" align="center" cellspacing="0" cellpadding="0">			
			<!-- DATOS DEL USUARIO -->
			<tr> 
				<td>
					<fieldset class="bordeDetalleRegistro">
					<legend class="textoLeyendaDetalleRegistro">  Detalle del Registro  ( <?php  echo $leyenda_operacion ?>  )</legend>
				
					<form action="usuario_actualizar_roles_upd.php" method="post" name="formulario">
					<table width="100%" class="textoTablaDato" cellpadding="2" cellspacing="0">

							<!-- DATOS DEL ROL   -->
							<tr>
								<td valign="top" width="20%" class="textoEtiqueta">Rol</td>
								<td class="textoEtiquetaDatoResaltado">
								<?php  if ($operacion == "I") { 
										$roles_seleccionar="(".implode(",",$roles_sin_asignar).")";
										$spnombre="sel_roles_in_rolcod";
										$spparam= array('prolcod'=>$roles_seleccionar);
										FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formulario","rolcod","rolcod","roldesc","","Seleccione un Rol",$fila_rol,$selecnousar,1,"");
									} else {
										if($conexion->TraerCampo("roles","roldesc",array("rolcod='",$filausuariorol['rolcod'],"'"),$roldesc,$numfilas,$errno))
											echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($roldesc,ENT_QUOTES);
									}	?>
								</td>
							</tr> 
							<tr>
								<td colspan="2" align="center">
									<input type="submit" name="boton_confirma" value="<?php  echo $boton_operacion; ?>" onclick="return Validar(formulario,'<?php  echo $operacion ?>'); " class="boton_aceptar" />
<!--									<input type="reset" name="boton_cancela" value="Cancelar" class="botones" /> -->
									<input type="hidden" name="operacion" id="operacion" value="<?php  echo $operacion;?>" />
									<input type="hidden" name="usuariocod" id="usuariocod" value="<?php  echo $filausuario['usuariocod']?>" />				  
									<input type="hidden" name="usuarioCUIT" id="usuarioCUIT" value="<?php  echo $filausuario['usuarioCUIT']?>" />				  
									<?php 	if ($operacion == "B" || $operacion == "M") { ?> 
											<input type="hidden" name="rolcod" id="rolcod" value="<?php  echo $filausuariorol['rolcod'] ?>" />				 
									<?php  } ?>	
								</td>
							</tr>					
					</table>
					</form>
					</fieldset> 	
				</td>
				</tr>
			</table>
<?php 			} else { // Fin Hay datos a modificar 
				if ($result)
					FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"El rol seleccionado no tiene datos modificables.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			}
			
		} // Se seteo operación 
	} //  result ok en los datos mostrados 
} //  se ingreso un usuario que existia
	
$oEncabezados->PieMenuEmergente(); 

//--------------------------------------------------------------------------------------------------
function mostrarDetalleRol($conexion,$filausuariorol,$es_modif) { ?>
	<!-- Domicilio,Teléfono,Fax (SOLO Coordinador Técnico) -->
	<tr>
		<td valign="top" class="textoEtiqueta" width="20%" >Domicilio</td>
		<td class="textoEtiquetaDatoResaltado">
			<textarea cols="50" <?php  echo (!$es_modif)?"readonly class='inputReadonly'":"class='textoInput'" ?> name="usuroljur_domicilio" id="usuroljur_domicilio" rows="2" style="width:350px" ><?php  echo isset($filausuariorol['usuroljur_domicilio'])? FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuariorol['usuroljur_domicilio'],ENT_QUOTES):"" ?></textarea>
		</td>
	</tr>
	<tr>
		<td valign="top" class="textoEtiqueta">Teléfono</td>
		<td class="textoEtiquetaDatoResaltado">
			<input type="text" name="usuroljur_telefono" size="22"  <?php  echo (!$es_modif)?"readonly class='inputReadonly'":"class='textoInput'" ?> maxlength="20" value = "<?php  echo isset($filausuariorol['usuroljur_telefono'])? FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuariorol['usuroljur_telefono'],ENT_QUOTES):"" ?>">
		</td>
	</tr>
	<tr>
		<td valign="top" class="textoEtiqueta">Fax</td>
		<td class="textoEtiquetaDatoResaltado">
			<input type="text" name="usuroljur_fax" size="22"  maxlength="20" <?php  echo (!$es_modif)?"readonly class='inputReadonly'":"class='textoInput'" ?> value = "<?php  echo isset($filausuariorol['usuroljur_fax'])? FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuariorol['usuroljur_fax'],ENT_QUOTES):"" ?>">
		</td>
	</tr> 
<?php  } 
//--------------------------------------------------------------------------------------------------
?>