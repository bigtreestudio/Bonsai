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
$_SESSION['volver'] ="feriados.php"; 
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oFeriados = new cFeriados($conexion);
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/archivos/feriados.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Feriados</h2>
</div>
 <div class="form">
 <form action="feriados.php" method="post" class="general_form" name="formbusqueda" id="formbusqueda" style="margin:0;">
    <div class="ancho_5">
            <div class="ancho_1">
                <label>Mes:</label>
            </div>
            <div class="ancho_2">
                <select name="feriadosmes" id="feriadosmes" class="full" onchange="doSearch(arguments[0]||event)" >
                            <option selected="selected" value = ""> Todos</option>
                            <option value = "01"> Enero </option>
                            <option value = "02"> Febrero </option>
                            <option value = "03"> Marzo </option>
                            <option value = "04"> Abril </option>
                            <option value = "05"> Mayo </option>
                            <option value = "06"> Junio </option>
                            <option value = "07"> Julio </option>
                            <option value = "08"> Agosto </option>
                            <option value = "09"> Septiembre</option>
                            <option value = "10"> Octubre </option>
                            <option value = "11"> Noviembre </option>
                            <option value = "12"> Diciembre </option>
               </select>
            </div>
         	<div class="ancho_05">&nbsp;</div>
            <div class="ancho_1">
                <label>A&ntilde;o:</label>
            </div>
            <div class="ancho_1">
               <input name="feriadosano" id="feriadosano" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="4" size="20" value="" />
            </div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
 </div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaFeriados()">Nuevo feriado</a></div></li>
		<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar B&uacute;squeda</a></div></li>
    
    </ul>    
</div>
<div style="margin-top:10px">&nbsp;</div>

<div id="Popup"></div>         		
<div id="LstFeriados" style="width:100%;">
    <table id="ListarFeriados"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>