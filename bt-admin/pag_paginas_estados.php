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
$oNoticiasWorkflowRoles = new cNoticiasWorkflowRoles($conexion,"");
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/pag_paginas/js/pag_paginas_estados.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Estados de P&aacute;ginas  </h2>
</div>
    
<div class="form">
<form action="pag_paginas_estados.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_5">
            <div class="ancho_2">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="pagestadodesc" id="pagestadodesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
		<div class="ancho_3">
            <div class="ancho_3">
                <label>Constante:</label>
            </div>
            <div class="ancho_6">
               <input name="pagestadocte" id="pagestadocte" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="ancho_10">
		<div class="ancho_5">
            <div class="ancho_3">
                <label>Muestra Cantidad:</label>
            </div>
            <div class="ancho_5">
         			<select name="pagestadomuestracantidad" id="pagestadomuestracantidad" onchange="doSearch(arguments[0]||event)">
                           <option value="" >Seleccione una opci&oacute;n</option>
                            <option value="1" >SI</option>
                            <option value="0" >NO</option>
                   </select>
            </div>
        </div>
		<div class="ancho_5">
            <div class="ancho_2">
                <label>Se Muestra:</label>
            </div>
            <div class="ancho_5">
         			<select name="pagestadosemuestra" id="pagestadosemuestra" onchange="doSearch(arguments[0]||event)">
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
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaPaginaEstado()">Agregar Estado</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></div></li>
    </ul>    
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstPaginaEstado" style="width:100%;">
    <table id="ListarPaginaEstado"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>