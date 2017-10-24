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


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$ArregloDatos['usuariocod']=$_GET['usuariocod'];
if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Usuario inexistente, por favor seleccionelo nuevamente",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
		
$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
FuncionesPHPLocal::ArmarLinkMD5("usuarios_cargar_acciones_upd.php",array("usuariocod"=>$filausuario['usuariocod']),$get_upd,$md5_upd);

if ($filausuario['usuariocod']==$_SESSION['usuariocod']  && $_SESSION['rolcod']!=ADMISITE)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Usted no puede editar sus propias acciones.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	?>
    <div style="text-align:center">
        <a href="usuarios_buscar.php" title="Volver al listado">
            Volver al listado de usuarios
        </a>
    </div>    
    <?php  
	die();
}

$oModulosAcciones = new cModulosAcciones($conexion);
$datos['usuariocod'] = $_GET['usuariocod'];
$datos['rolcodactualiza'] = $_SESSION['rolcod'];
if(!$oModulosAcciones->BuscarModulosAccionesxUsuarioxRolcodActualiza ($datos,$resultadoModulos,$numfilasModulos))
	return false;


$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($conexion);
if(!$oUsuariosModulosAcciones->BuscarAccionesxUsuario ($datos,$resultado,$numfilas))
	return false;

if(!$oUsuariosModulosAcciones->TienePermisosAccion("000110"))
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, no tiene permisos para agregar acciones",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}
	
	
$acciones = array();
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	$acciones[$fila['accioncodigo']] = $fila['accioncodigo'];

?>
<script type="text/javascript" language="javascript">
function Actualizar()
{
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando acciones...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize(); 
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "usuarios_cargar_acciones_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   
			alert(msg.Msg);
			$("#MsgGuardando").hide();
			$.unblockUI();
	   }
	});
	return true;
}
</script>





<div class="onecolumn">
    <div class="header">
        <span>Acciones del usuario <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarioapellido']." ".$filausuario['usuarionombre'],ENT_QUOTES); ?></span>
    </div>
    <div class="content base clearfix">
        <div style="margin:5px 0; font-size:1.083em;">Desde esta pantalla podr&aacute; actualizar las acciones disponibles sobre diferentes m&oacute;dulos del sistema</div>
        <div class="clearboth">&nbsp;</div>
        <form action="usuarios_buscar.php" class="general_form" method="post" name="formulario" id="formulario">

                 <div class="ancho_10" style="font-size:1.083em;">
                 	<?php  
					$i = 1;
					$mitad = round($numfilasModulos/2);
					?>
                    <div class="ancho_5">
                    <?php 
					while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoModulos))
					{
						?>
                        <div class="ancho_10">
                            <h3 style="font-weight:bold; font-size:1.133em"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['modulodesc'],ENT_QUOTES)?></h3>
                            <ul style="margin:5px 0; margin-left:10px;">
                            <?php  
							$fila['usuariocod']=$_SESSION['usuariocod'];
							if ($oModulosAcciones->PuedeEditarTodasAcciones())
								$fila['usuariocod']="";
								
                            if(!$oModulosAcciones->BuscarAccionesxModulos ($fila,$resultadoAcciones,$numfilasAcciones))
                                return false;
                            while ($filaAcciones = $conexion->ObtenerSiguienteRegistro($resultadoAcciones))
                            {
                                $checked = "";
                                if (array_key_exists($filaAcciones['accioncodigo'],$acciones))
                                    $checked = 'checked="checked"';
                                ?>
                                <li style="padding:2px 0; margin:0;">
                                    <div style="float:left; width:20px;">
                                        <input <?php  echo $checked?> type="checkbox" value="<?php  echo $filaAcciones['accioncodigo']?>" name="accioncodigo_<?php  echo $filaAcciones['accioncodigo']?>" id="accioncodigo_<?php  echo $filaAcciones['accioncodigo']?>"  />
                                    </div>
                                    <div style="float:left; width:300px;">
                                        <label for="accioncodigo_<?php  echo $filaAcciones['accioncodigo']?>" style="font-size:1.083em"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaAcciones['acciondesc'],ENT_QUOTES);?></label>
                                    </div>
                                    <div style="clear:both; height:1px; font-size:1px;">&nbsp;</div>
                                </li>
                                <?php  
                            }
                            ?>
                            </ul>
                            <div style="height:10px; font-size:10px;">&nbsp;</div>
                        </div>
                        
                        <?php  
						if ($i==$mitad)
						{
							echo "</div>";
							echo "<div class='ancho_5'>";	
						}
						$i++;
					}
					?>
                    </div>
                    <div class="clearboth aire">&nbsp;</div>
                    <div class="ancho_6">
                       <div class="ancho_4">
                       <input type="hidden" value="<?php  echo $md5_upd?>" name="md5" />
                       <input type="hidden" value="<?php  echo $filausuario['usuariocod']?>" name="usuariocod" />
                       		<input type="button" name="btActualizar" class="boton" value="Actualizar acciones"  onclick="return Actualizar()" />
                       </div>
                        <div class="ancho_2">&nbsp;</div>
                        <div class="ancho_4">
                            <input type="submit" name="btVolver" class="boton" value="Volver al listado de usuarios"  />
                        </div>
                    </div>
                    <div class="ancho_1">&nbsp;</div>
                    <div class="clearfix aire">&nbsp;</div>
               </div>
        </form>
   
    </div>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>
