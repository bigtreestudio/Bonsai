<?php  
require('./config/include.php');
require('./Librerias/cGoogleAnalytics.php');

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

$oNoticiasEstados = new cNoticiasEstados($conexion);
$datos['usuariocod'] = $_SESSION['usuariocod'];
if(!$oNoticiasEstados->ObtenerEstadosCantidadesxUsuario($datos,$resultadopermisos,$numfilaspermisos))
	return false;

$oUsuariosAccesos = new cUsuariosAccesos($conexion);
if(!$oUsuariosAccesos->BuscarMiUltimoAcceso ($resultultacceso,$numfilasultacceso))
	return false;

$oArchivos = new cArchivos($conexion);
if(!$oArchivos->ObtenerEspacioEnDisco ($datosEspacio))
	return false;

$tieneultacceso = false;
if ($numfilasultacceso>0)
{
	$datosultimoacceso = $conexion->ObtenerSiguienteRegistro($resultultacceso);
	$tieneultacceso = true;
}



?>

        <link href="css/ui.dashboard.css" rel="stylesheet" title="style" media="all" />


        <div class="inner-page-title"> 
          <h2>Panel de control</h2>
          <span>Bienvenido <?php  echo $_SESSION['usuarionombre']?> <?php  echo $_SESSION['usuarioapellido']?>!</span> 
        </div>		

        <div id="dashboard-buttons">
            <div class="clear">&nbsp;</div>    
                <div class="col-md-8" style="margin-top:40px">
                        <div class="white-panel-widget clearfix m-sidebar m-bot-30 no-pad">
                            <div class="average-statistics-wrapper row no-margin">
                                <h3>Noticias</h3>
                            </div>
                        	<div class="wrapper-circle-charts">
                                <div class="wrp row no-margin">
                                <?php  
                                    while ($fila = $conexion->ObtenerSiguienteRegistro($resultadopermisos)) {
    
                                       // if ($fila['noticiaestadomuestracantidad'])
                                        {	
                                            $cantidad = $fila['total'];
											$color="";
                                            if($fila["noticiaestadocod"]==30){
												$color="red";
											}
											?>
                                            <a href="not_noticias.php" title="Ir a las noticias">
                                                <div class="chart <?php  echo $color; ?>"  data-percent="75" data-barcolor="#3498DB" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                                        <span class="percent" ><?php  echo $cantidad;?></span>
                                                        <p><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaestadodesc'],ENT_QUOTES)?></p>
                                                        <canvas height="150" width="150">
                                                 </div>
                                             </a>
                                            <?php 
                                        }
                                    }
                                ?>
                                </div> <!--  CIERRE wrp row no-margin -->
                            </div><!--  CIERRE wrapper-circle-charts -->
                    </div><!--  CIERRE white-panel-widget clearfix m-sidebar m-bot-30 no-pad -->
                </div><!--  CIERRE col-md-10 -->
                
                
                <div class="col-md-4" style="margin-top:40px" >
                    <div class="widget-wrapper">
                        <div class="panel-widget">                        
                       	 <span>Cuando acced&iacute;?</span>
                        <div class="content extra clearfix">
                            <?php  if ($tieneultacceso){?>
                                <ul class="wrapper-items">
                                    <li style="font-weight:bold">&Uacute;ltimo Acceso:&nbsp;&nbsp;</b><span style="font-style:italic"><?php  echo FuncionesPHPLocal::ConvertirFecha($datosultimoacceso['usuariofecha'],"aaaa-mm-dd","dd/mm/aaaa");?>&nbsp;<?php  echo substr($datosultimoacceso['usuariofecha'],11,5)."Hs.";?></span>
                                    <li style="font-weight:bold">Desde IP:</b> <?php  echo $datosultimoacceso['usuarioip'];?>
                                </ul>
                            <?php  }?>
                        </div>
                    </div>
                </div>
               </div>

                 <div class="col-md-4" style="margin-top:20px"  >
                        <div class="white-panel-widget clearfix m-sidebar m-bot-30 no-pad">
                            <div class="average-statistics-wrapper row no-margin">
                                <h3>Espacio en Disco</h3>
                            </div>
                            <div class="chart <?php  echo $color; ?>" style=" margin:8% 0 5% 25%"  data-percent="<? echo $datosEspacio["porcentaje"]?>" data-barcolor="<? echo $datosEspacio["msje"]; ?>" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                    <span style="font-size:1.6em; color:<? echo $datosEspacio["msje"]; ?>" class="percent" ><?php  echo sprintf('%1.2f' ,$datosEspacio["porcentaje"]);?> %</span>
                                    <p>Espacio Utilizado</p>
                                    <canvas height="200" width="200">
<div class="clear"></div>
                             </div>
                         </div>
                     </div>
                   <div class="clear"></div>

            </div>               
            <div class="clear"></div>

            </div>



</div>
<div class="clear"></div>    

<script type="text/javascript" src="js/easycharts.js"></script>

<script type="text/javascript">

$(function(){
	init_easypiechart(<?php  echo "1"?>);
});




</script>
<?php 

 $oEncabezados->PieMenuEmergente();
?>
