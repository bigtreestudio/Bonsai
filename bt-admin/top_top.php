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
$_SESSION['volver'] ="ban_top.php"; 
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$oTop= new cTop($conexion);
$datos['orderby'] = "topcod asc";
$toptipocod="";
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/top_top/js/top_top.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	listartop();			
});
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Top</h2>
</div>


<div class="form">    
<form action="top_top_am.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <div class="ancho_10">
        <div class="ancho_3">
            <div class="ancho_4">
                <label>Nombre:</label>
            </div>
            <div class="ancho_6">
               <input name="topdesc" id="topdesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        
        <div class="ancho_05">&nbsp;</div>
        
        <div class="ancho_3">
            <div class="ancho_2">
                <label>Tipo:</label>
            </div>
            <div class="ancho_8">
                <?php  
                    $oTop->BusquedaTopTipoSP($spnombre,$sparam);
                    FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","toptipocod","toptipocod","toptipodesc",$toptipocod,"Todos",$regactual,$seleccionado,1,"doSearch(arguments[0]||event)","width: 200px",false,false);
                ?>
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_3">&nbsp;</div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="top_top_am.php">Crear nuevo top</a></li>
		<li><a class="left boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar B&uacute;squeda</a></li>
    
    </ul>    
</div>

         		
<div id="LstTop" style="width:100%;">
    <table id="ListadoTop"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>