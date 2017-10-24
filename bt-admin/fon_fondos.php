<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

?>
<link rel="stylesheet" type="text/css" href="modulos/fon_fondos/css/fon_fondos.css" />
<script type="text/javascript" src="modulos/fon_fondos/js/fon_fondos.js"></script>
<form action="fon_fondos.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2>Fondos</h2>
    </div>
    <div class="ancho_10">
    						<div class="ancho_1">&nbsp;</div>
					<div class="ancho_3">
						<div class="ancho_4">
							<label>Nombre:</label>
						</div>
						<div class="ancho_6">
						   <input name="fondodesc" id="fondodesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="255" size="60" value="" />
						</div>
					</div>
					<div class="ancho_1">&nbsp;</div>
					</div>
</form>    


<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
    	<!--<li><a class="left" href="fon_fondos_am.php">Crear nuevo Fondos</a></li>-->
    	<li><a class="left" href="javascript:void(0)" onclick="Resetear()">Limpiar</a></li>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstDatos" style="width:100%;">
       <table id="listarDatos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();

?>