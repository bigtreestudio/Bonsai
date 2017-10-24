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
header('Content-Type: text/html; charset=iso-8859-1'); 

$oMenu= new cTapasMenu($conexion);

$oMenuTipo = new cTapasMenuTipos($conexion);
if(!$oMenuTipo->BuscarxCodigo($_POST,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}	
	
$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);


if(!$oMenu->BuscarxTipo($_POST,$resultado,$numfilas))
	return false;

$_SESSION['msgactualizacion'] = "";
$menutipocod = $_POST['menutipocod'];
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
    <?php  
}

$oMenu-> ArmarArbol($_POST,"",$arbol);

?>
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
