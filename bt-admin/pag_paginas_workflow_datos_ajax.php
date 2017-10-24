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

$oWorkflowRoles = new cPaginasWorkflowRoles($conexion,"");
$oWorkflow = new cPaginasWorkflow($conexion,"");
header('Content-Type: text/html; charset=iso-8859-1');
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return InsertarWorkflow();";

switch($_POST['tipo'])
{
	case 1:
			$oEstados=new cPaginasEstados($conexion);
			$oEstados->PaginasEstadosSP($spnombre,$spparam);
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"","pagestadocod","pagestadocod","pagestadodesc","","Seleccione un estado",$regnousar,$selecnousar,1,"CargarAcciones()","","",false);
		break;	
	case 2:
			
			if (!$oWorkflow->ObtenerAccionesEstadoInicial($_POST,$resultadoacciones,$numfilasacciones))
				return false;
			?>
                 <div class="ancho_10">
				 <?php  
                   while($filasacciones = $conexion->ObtenerSiguienteRegistro($resultadoacciones)) 
                   {
							$arregloroles = array();
							if(!$oWorkflowRoles->ObtenerWorkflowCodxRol($_POST,$resultadoworkfow,$numfilasworkflow))
								return false;

							while($fila=$conexion->ObtenerSiguienteRegistro($resultadoworkfow))
								$arreglocheckaccioncod[$fila['paginaworkflowcod']] = $fila['paginaworkflowcod'];
							$checked = '';
							if ($numfilasworkflow>0){
								if (array_key_exists($filasacciones['paginaworkflowcod'],$arreglocheckaccioncod))
									$checked='checked="checked"';                           
							}
								?>
								<input type="checkbox" class="chk" <?php  echo $checked?>  id="paginaworkflowcod_<?php  echo $filasacciones['paginaworkflowcod']?>" name="paginaworkflowcod_<?php  echo $filasacciones['paginaworkflowcod']?>" value="<?php  echo $filasacciones['paginaworkflowcod']?>" />
								<label for="paginaworkflowcod_<?php  echo $filasacciones['paginaworkflowcod']?>" class="bold">
								<?php    
									   echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filasacciones['paginaaccion'],ENT_QUOTES);
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