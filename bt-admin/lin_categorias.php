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

$oLinks = new cLinks($conexion,"");

$_SESSION['msgactualizacion'] = "";

?>
<link rel="stylesheet" type="text/css" href="modulos/lin_links/css/lin_links.css" />
<script type="text/javascript" src="modulos/lin_links/js/lin_categorias.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Categorias - Links</h2>
</div>
 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="AltaCategorias()">Crear nueva Categoria</a></li>
    </ul>
</div>

<div class="txt_izq">
    <form action="lin_categorias.php" method="post" name="formbusquedacategoria" id="formbusquedacategoria">
               <input type="hidden" name="catcod" id="catcod" />
   	</form>
</div>


<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstCategorias" style="width:100%;">
       <table id="listarCategorias"></table>
    <div id="pager2"></div>
</div>

<?php  
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>