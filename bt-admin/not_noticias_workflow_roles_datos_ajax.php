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

$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($conexion,"");
$oNoticiasWorkflow = new cNoticiasWorkflow($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1');
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return InsertarWorkflow();";

switch($_POST['tipo'])
{
	case 1:
			$oNoticiasEstados=new cNoticiasEstados($conexion);
			$oNoticiasEstados->NoticiasEstadosSP($spnombre,$spparam);
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"","noticiaestadocod","noticiaestadocod","noticiaestadodesc","","Seleccione un estado",$regnousar,$selecnousar,1,"CargarAcciones()","","",false);
		break;	
	case 2:
			
			if (!$oNoticiasWorkflow->ObtenerAccionesEstadoInicial($_POST,$resultadoacciones,$numfilasacciones))
				return false;
			?>
                 <div class="ancho_10">
				 <?php  
                   while($filasacciones = $conexion->ObtenerSiguienteRegistro($resultadoacciones)) 
                   {
							$arregloroles = array();
							if(!$oNoticiasWorkflowRoles->ObtenerWorkflowCodxRol($_POST,$resultadoworkfow,$numfilasworkflow))
								return false;

							while($fila=$conexion->ObtenerSiguienteRegistro($resultadoworkfow))
								$arreglocheckaccioncod[$fila['noticiaworkflowcod']] = $fila['noticiaworkflowcod'];
							$checked = '';
							if ($numfilasworkflow>0){
								if (array_key_exists($filasacciones['noticiaworkflowcod'],$arreglocheckaccioncod))
									$checked='checked="checked"';                           
							}
								?>
								<input type="checkbox" class="chk" <?php  echo $checked?>  id="noticiaworkflowcod_<?php  echo $filasacciones['noticiaworkflowcod']?>" name="noticiaworkflowcod_<?php  echo $filasacciones['noticiaworkflowcod']?>" value="<?php  echo $filasacciones['noticiaworkflowcod']?>" />
								<label for="noticiaworkflowcod_<?php  echo $filasacciones['noticiaworkflowcod']?>" class="bold">
								<?php    
									   echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasacciones['noticiaaccion'],ENT_QUOTES);
								?>
								</label>
								<div class="clearboth ancho_11 brisa_vertical">&nbsp;</div>
						<?php  
				  

					}
					?>					
                 </div>
                 <div class="aire clearboth">&nbsp;</div>
                  <div class="menubarra">
                        <ul>
                            <li><a class="left" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
         <?php  
	
}


?>