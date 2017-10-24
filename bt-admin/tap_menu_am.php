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

$oMenu = new cTapasMenu($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 



if (isset($_POST['menucod']) && $_POST['menucod'])
{
	$esmodif = true;
	if (!$oMenu->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosmenu = $conexion->ObtenerSiguienteRegistro($resultado);
	$menutipocod = $datosmenu['menutipocod'];
}else
	$menutipocod = $_POST['menutipocod'];


$botonejecuta = "BtAlta";
$boton = "Alta";
$menucod = "";
$menudesc = "";
$menuclass = "";
$menuclassli = "";
$menutitle = "";
$menulink = "";
$menuaccesskey = "";
$menutarget = "_self";
$onclick = "return InsertarMenu();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$menucod = $datosmenu['menucod'];
	$menudesc = $datosmenu['menudesc'];
	$menutitle = $datosmenu['menutitle'];
	$menulink = $datosmenu['menulink'];
	$menuaccesskey = $datosmenu['menuaccesskey'];
	$menutarget = $datosmenu['menutarget'];
	$menuclass = $datosmenu['menuclass'];
	$menuclassli = $datosmenu['menuclassli'];
	$onclick = "return ModificarMenu();";
}


?>
<div style="text-align:left">
	<div class="form">
		<form action="tap_macros_am.php" method="post" name="formmenuam" id="formmenuam" >
			<div class="datosgenerales">
				<div>
    				<label>Nombre:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menudesc"  id="menudesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menudesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Link:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menulink"   id="menulink" maxlength="80" class="medium" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menulink,ENT_QUOTES)?>"/>
                    <label class="titulodominio" style="width:20%; float:left; margin-top:15px">
                       <input type="checkbox" style="width:20%; float:left" id="checklink" name="checklink"  onclick="DesactivarTextoLink();" value="Sin dominio"  /> 
                   		Sin link
                    </label>
                    <div class="menubarra">
                        <ul>       
                           <li> 
                           		<a class="boton azul" href="javascript:void(0)" onclick="return AgregarDominioMenu(<?php  echo $menucod;?>)" title="Agregar Dominio Existente">
                               	 Agregar link Existente
                                </a>
                           </li>
                         </ul>
                     </div>     
				</div>
              
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Titulo al ubicar el mouse por encima del link:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menutitle"  id="menutitle" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menutitle,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Codigo Acceso teclado num&eacute;rico:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menuaccesskey"  id="menuaccesskey" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menuaccesskey,ENT_QUOTES)?>"/>
				</div>
                <div class="clearboth aire_menor">&nbsp;</div>
 				<div>
    				<label>Class Link:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menuclass"  id="menuclass" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menuclass,ENT_QUOTES)?>"/>
				</div>
                 <div class="clearboth aire_menor">&nbsp;</div>
 				<div>
    				<label>Class Item:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="menuclassli"  id="menuclassli" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($menuclassli,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Ventana:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<select name="menutarget" id="menutarget">
                    	<option value="_self" <?php  if ($menutarget=="_self"){ echo 'selected="selected"';}?>>Abre en la misma ventana</option>
                    	<option value="_blank" <?php  if ($menutarget=="_blank"){ echo 'selected="selected"';}?>>Abre en nueva ventana</option>
                    </select>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
        				<li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $menucod?>" name="menucod" id="menucod" />
            <input type="hidden" value="<?php  echo $menutipocod?>" name="menutipocod" id="menutipocod" />
		</form>
	</div>
</div>
<div id="PopupDetalleDominioMenu_<?php  echo $menucod?>"></div>
