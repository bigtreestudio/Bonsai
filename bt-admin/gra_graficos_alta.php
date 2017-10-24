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

$oGraficos = new cGraficos($conexion);
$conjuntocod = $_POST['conjuntocod'];

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$act_des = "";	
$graficoestado = "";	
$graficotipocod = "";
$graficotitulo = "";

?>
<link href="modulos/gra_graficos/css/graficos_am.css" rel="stylesheet" title="style" media="all" />
<div id="contentedor_modulo">
	<div class="form" id="contenedor_interno">
<form action="gra_graficos_upd.php" method="post" name="formulariografico" id="formulariografico">
    <input type="hidden" name="accion" id="accion" value="1">
    <input type="hidden" name="conjuntocod" id="conjuntocod" value="<?php  echo $conjuntocod?>">
	<div class="ancho_10">
         <div class="ancho_4">
            <div class="datosgenerales">
            	<div class="ancho_10">
                    <div>
                        <label>Tipo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                         <?php  
							$datos['conjuntocod'] = $conjuntocod;
							$oGraficos->BuscarTiposActivos($datos,$spnombre,$sparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formbusqueda","graficotipocod","graficotipocod","graficotipotitulo","","Seleccione un tipo...",$regactual,$seleccionado,1,"",false,false);
                        ?>                     
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>

				<div class="ancho_10">
                    <div>
                        <label>Titulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="graficotitulo" id="graficotitulo" class="full" maxlength="255" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($graficotitulo,ENT_QUOTES);?>" />
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                

               <div class="clearboth aire_menor">&nbsp;</div>

           </div>
        </div>

   		<div class="ancho_05">&nbsp;</div>
        <div class="clearboth aire_menor">&nbsp;</div>
        <div class="msgaccionbanner">&nbsp;</div> 
        <div class="ancho_10">
            <div class="menubarra">
                 <ul>
                    <li><a class="boton verde" href="javascript:void(0)" onclick="AgregarGrafico()">Agregar Gr&aacute;fico</a></li>
                    <li><a class="left boton base" href="javascript:void(0)" onclick="DialogClose()">Cerrar</a></li>
                </ul>
            </div>
	        <div class="clearboth aire_menor">&nbsp;</div>
        </div>
        <div class="clearboth aire_menor">&nbsp;</div>
  </div>
</form>
<?php 
?>