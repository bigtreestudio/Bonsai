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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$volver= "not_noticias_workflow.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/not_noticias/js/not_noticias_estados.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Estados de Noticias  </h2>
</div>

<div class="form">
<form action="not_noticias_estados.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_5">
            <div class="ancho_3">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="noticiaestadodesc" id="noticiaestadodesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
		<div class="ancho_5">
            <div class="ancho_3">
                <label>Constante:</label>
            </div>
            <div class="ancho_6">
               <input name="noticiaestadocte" id="noticiaestadocte" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div class="ancho_10">
		<div class="ancho_5">
            <div class="ancho_3">
                <label>Muestra Cantidad:</label>
            </div>
            <div class="ancho_3">
         			<select name="noticiaestadomuestracantidad" id="noticiaestadomuestracantidad" onchange="doSearch(arguments[0]||event)">
                           <option value="" >Seleccione una opci&oacute;n</option>
                            <option value="1" >SI</option>
                            <option value="0" >NO</option>
                   </select>
            </div>
        </div>
		<div class="ancho_5">
            <div class="ancho_4">
                <label>Se Muestra:</label>
            </div>
            <div class="ancho_3">
         			<select name="noticiaestadosemuestra" id="noticiaestadosemuestra" onchange="doSearch(arguments[0]||event)">
                           <option value="" >Seleccione una opci&oacute;n</option>
                            <option value="1" >SI</option>
                            <option value="0" >NO</option>
                    </select>
            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaNoticiasEstado()">Agregar Estado</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></div></li>
    </ul>    
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstNoticiasEstado" style="width:100%;">
    <table id="ListarNoticiasEstado"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>