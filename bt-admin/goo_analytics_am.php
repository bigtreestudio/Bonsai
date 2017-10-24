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
$_SESSION['volver'] ="ingreso.php"; 
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla		
$oGoogle = new cGoogle($conexion);

//codigo de analytics
$datos['googlecod'] = $googlecod =1;


if (!$oGoogle ->Buscar($datos,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$datosgoogle = $conexion->ObtenerSiguienteRegistro($resultado);

$googletitulo=$datosgoogle['googletitulo'];
$googleuser=$datosgoogle['googleuser'];
$googlecodanalytics=$datosgoogle['googlecodanalytics'];
$cargoprofile = false;
if ($datosgoogle['googleprofile']!="")
	$cargoprofile = true;
?>
<script type="text/javascript" src="modulos/goo_google/js/goo_analytics_am.js"></script>
<script type="text/javascript">
	<?php  if ($cargoprofile){?>
		jQuery(document).ready(function(){
			CargarProfiles()
		});
	<?php  }?>
</script>

<div style="text-align:left">
    <div class="form ">
        <form action="goo_analytics_am.php" method="post" name="formanalytics" id="formanalytics" >
            <div class="datosgenerales">
                <div class="inner-page-title" style="padding-bottom:2px;">
                    <h2>Datos:</h2>
                </div>
                 <div class="ancho_1">
                    <label>Titulo:</label>
                </div>
                
                <div class="clearboth brisa_vertical">&nbsp;</div>
                
                 <div class="ancho_4">
                    <input type="text" value="<?php  echo   FuncionesPHPLocal::HtmlspecialcharsBigtree($googletitulo,ENT_QUOTES)?>" name="googletitulo" size="80" id="googletitulo" class="medium" />
                </div>
                
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_1">
                    <label>Codigo Analytics:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
               <div class="ancho_4">
              		<textarea name="googlecodigoanalytics" id="googlecodigoanalytics"  cols="89" rows="5" /><?php  echo   FuncionesPHPLocal::HtmlspecialcharsBigtree($googlecodanalytics,ENT_QUOTES)?></textarea>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>  
               <div class="ancho_1">
                    <label>Usuario:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
               <div class="ancho_4">
                     <input type="text" name="googleuser" id="googleuser" size="20" maxlength="50" class="medium" value="<?php  echo   FuncionesPHPLocal::HtmlspecialcharsBigtree($googleuser,ENT_QUOTES)?>"/>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>  
                <div class="ancho_1">
                    <label>Password:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div class="ancho_4">
                     <input type="password" name="googlepass" id="googlepass" size="20" maxlength="50" class="medium" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div> 
                <div class="ancho_1">
                    <label>Confirmar Password:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
               <div class="ancho_4">
                     <input type="password" name="googlepassconfirm" id="googlepassconfirm" size="20" maxlength="50" class="medium" />
                </div>
                <div class="clearboth aire_menor">&nbsp;</div> 
                
                <input type="hidden" name="accion" id="accion" value="1" />
                <input type="hidden" name="googlecod" id="googlecod" value="<?php  echo $googlecod?>" />
                            
                <div class="menubarra">
                    <ul>
                     <li><a class="left" name="guardardatos"  href="javascript:void(0)" onclick="ActualizarDatos()">Guardar Datos</a></li>
                    </ul>
                </div>
                 
             </div>
         </form>
    </div>
    <div class="clearboth brisa_vertical">&nbsp;</div>
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2>Perfiles:</h2>
    </div>
    <div id="profiles">
    </div>

</div>
               
        

<?php  
$oEncabezados->PieMenuEmergente();
?>