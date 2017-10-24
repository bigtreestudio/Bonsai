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


$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = ".php";
$mensajeaccion = "";
$menucod ="";
if(isset($_POST['menucod']) && $_POST['menucod']!="")
	$menucod = $_POST['menucod'];
	
$volver= $_SESSION['volver']; 
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

?>


<script type="text/javascript" src="modulos/tap_tapas/js/tap_menu_dominios_lst.js?v=1.1"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Dominios</h2>
</div>
 
<div class="txt_izq">
     <form action="tap_menu_am.php" method="post" name="formbusqueda" id="formbusqueda">
        <div style="width:100%">
            <label>Buscar por Tipo:</label>
        </div>    
        <div>
           <select name="tipo"  id="tipo" onchange="doSearch(arguments[0]||event)">
                <option value="" >TODOS</option>
                <option value="PORTADA" >PORTADA</option>
                <option value="PAGINA" >PAGINA</option>
                <option value="CATEGORIA NOTICIA" >CATEGORIA NOTICIA</option>
                <option value="GALERIA" >GALERIA</option>
                <option value="ALBUM" >ALBUM</option>
                <option value="FORMULARIOS" >FORMULARIOS</option>

            </select>
            &nbsp;&nbsp;

        </div>    
        <input type="hidden" name="menucod" id="menucod" value="<?php  echo $menucod?>" />
    </form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div id="LstDominios_<?php  echo $menucod;?>" style="width:100%;">
    <table id="ListarDominios_<?php  echo $menucod;?>"></table>
    <div id="pager2"></div>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)"  onclick="DialogCloseDominio()">Cerrar</a></li>
    </ul>
</div>
<?php 
$_SESSION['msgactualizacion']="";
?>