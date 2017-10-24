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

$oMenu= new cTapasMenu($conexion);

$oMenuTipo = new cTapasMenuTipos($conexion);
if(!$oMenuTipo->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}	
	
$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);


if(!$oMenu->BuscarxTipo($_GET,$resultado,$numfilas))
	return false;

$_SESSION['msgactualizacion'] = "";
$menutipocod = $_GET['menutipocod'];
$nivel = 1;
function CargarSubMenu($arbol,$nivel)
{
	$margen = $nivel *10; 
	?>
    <ol>
    <?php  
	foreach($arbol as $fila)
	{
		?>
            <li id="menu_<?php  echo $fila['menucod']?>" class="clearfix">
               <div class="menuseleccionado clearfix">
                    <div style="float:left; width:70%">
                    <div class="menudesc"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);?></div>
                    <div style="font-size:10px;">Acces Key: <?php  echo $fila['menuaccesskey']?></div>
                    </div>
                    <div style="float:left; width:30%">
                        <a href="javascript:void(0)" class="menuhandle" title="Mover Menu">
                            <img src="images/move.png" alt="Mover Menu" />
                        </a>
                        &nbsp;
                        <a href="javascript:void(0)" onclick="EditarMenu(<?php  echo $fila['menucod']?>)" title="Editar Menu">
                            <img src="images/edit_action.gif" alt="Editar Menu" />
                        </a>
                        &nbsp;
                        <a href="javascript:void(0)" onclick="EliminarMenu(<?php  echo $fila['menucod']?>)" title="Eliminar Menu">
                            <img src="images/cross.png" alt="Eliminar Menu" />
                        </a>
                    </div>
              </div>        
              <div class="clearboth" style="height:1px; font-size:1px;">&nbsp;</div>
					<?php  
					if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
          </li>      
		<?php 	
	}
	?>
    	</ol>
    <?php  
}

$oMenu-> ArmarArbol($datostipo,"",$arbol);

?>
<link rel="stylesheet" type="text/css" href="modulos/tap_tapas/css/tap_menu.css" />
<script type="text/javascript" src="modulos/tap_tapas/js/tap_menu.js?v=1.1"></script>
<script type="text/javascript" src="modulos/tap_tapas/js/tap_menu_dominios.js?v=1.1"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Men&uacute; <?php  echo $datostipo['menutipodesc']?></h2>
</div>

<div class="clearboth">&nbsp;</div>
<div id="MenuCarga" style="width:500px;">
    <ol class="sortable">
    <?php  
		foreach($arbol as $fila)
		{
            ?>
                <li id="menu_<?php  echo $fila['menucod']?>"  class="clearfix">
                   <div class="menuseleccionado clearfix">
                        <div style="float:left; width:70%">
                        <div class="menudesc"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);?></div>
                        <div style="font-size:10px;">Acces Key: <?php  echo $fila['menuaccesskey']?></div>
                        </div>
                        <div style="float:left; width:30%">
                            <a href="javascript:void(0)" class="menuhandle" title="Mover Menu">
                                <img src="images/move.png" alt="Mover Menu" />
                            </a>
                            &nbsp;
                            <a href="javascript:void(0)" onclick="EditarMenu(<?php  echo $fila['menucod']?>)" title="Editar Menu">
                                <img src="images/edit_action.gif" alt="Editar Menu" />
                            </a>
                            &nbsp;
                            <a href="javascript:void(0)" onclick="EliminarMenu(<?php  echo $fila['menucod']?>)" title="Eliminar Menu">
                                <img src="images/cross.png" alt="Eliminar Menu" />
                            </a>
                        </div>
                 	</div>
					<?php  
                    if (isset($fila['subarbol']))
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
                            
                </li>    
            <?php  
        }
    
    ?>
    </ol>
</div>    
<div class="clearboth">&nbsp;</div>
<div class="form">
	<form action="tap_menu_upd.php" method="post" name="menusuperior">
        <div class="clearboth aire_menor">&nbsp;</div>      
        <div class="menubarra">
            <ul>
                <li><a class="boton verde" name="AgregarMenu" title="Agregar Menu" href="javascript:void(0)"  onclick="AgregarMenu()">Agregar Men&uacute;</a></li>
                <li><a class="boton verde left" name="Publicar" title="Publicar" href="javascript:void(0)"  onclick="publicarMenu()">Publicar Men&uacute;</a></li>
                <li><a class="boton base left" href="tap_menu_tipos.php" title="Volver">Volver</a></li>
            </ul>
        </div>
        <div class="clearboth aire_menor">&nbsp;</div>      
        <input type="hidden" value="<?php  echo $menutipocod?>" name="menutipocod" id="menutipocod" />
    </form>
</div>
<div class="clearboth">&nbsp;</div>
<div id="PopupDetalleMenu"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>