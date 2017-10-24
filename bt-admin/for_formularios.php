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

$oFormularios= new cFormularios($conexion);

$_SESSION['msgactualizacion'] = "";
$formulariotipocod="";

?>
<link rel="stylesheet" type="text/css" href="modulos/for_formularios/css/estilos.css" />
<script type="text/javascript" src="modulos/for_formularios/js/for_formularios.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Mensajes Recibidos</h2>
</div>
 
<div class="form">
<form action="for_formularios_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Nombre y apellido</label>
                </div>
                <div class="ancho_6">
                   <input name="formularionombre" id="formularionombre" class="full" type="text"  onkeydown="doSearchFormFormularios(arguments[0]||event)" maxlength="100" size="60" value="" />

                </div>
                
            </div>
	 </div>
            
    <div class="ancho_10">
        <div class="ancho_05">&nbsp;</div>

        <div class="ancho_3">
            <div class="ancho_4" style="float:left; padding:20px 0px  10px 0px">
                <label>Tipo de formulario: </label>
            </div>
            <div class="ancho_6" style="float:left; padding:15px 0px  10px 0px">
               <?php  
			     $oFormularios->BuscarTiposFormulariosSP($_POST,$spnombre,$sparam);
                 FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","formulariotipocod","formulariotipocod","formulariotipodesc",$formulariotipocod,"Todos",$regactual,$seleccionado,1,"return doSearchFormFormularios(arguments[0]||event); ","width: 200px",false,false);
                ?>
            </div>
        </div>             
    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
   	</form>
     </div>
    <div style="text-align:right">
        <a class="excel" href="for_formularios_xls.php" target="_blank" title="Exportar Lista Completa">
            Exportar Lista Completa
        </a>
    </div>

<div class="clear aire_vertical">&nbsp;</div>


<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstFormulario" style="width:100%;">
    <table id="listarFormularios"></table>
    <div id="pager2"></div>
</div>
<div id="Popup">
</div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>