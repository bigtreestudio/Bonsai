<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

if (!isset($_POST['codigo']) || $_POST['codigo']=='')
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
	
if(strlen($_POST['codigo'])>10)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['codigo'],"NumericoEntero"))
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

$oGaleriasService= new cMultimedia($conexion);
$databusqueda['multimediacod'] = $_POST['codigo'];	
$oMultimedia = $oGaleriasService->BuscarxCodigo($databusqueda);
header('Content-Type: text/html; charset=ISO-8859-15'); 

?>
<div class="videoplayer_videocontainer">
						<script src="<?php  echo DOMINIORAIZSITE?>js/jwplayer.js" type="text/javascript"></script>
                        <div id="videoplayer_<?php  echo $_POST['codigo']?>" class="videoplayer">&nbsp;</div>
                        <script type="text/javascript">
                            jwplayer('videoplayer_<?php  echo $_POST['codigo']?>').setup({
                                    'id': 'player_<?php  echo $oMultimedia->getCodigo()?>',
                                    'width': '500',
                                    'height': '300',
                                    'file': 'http://www.youtube.com/watch?v=<?php  echo $oMultimedia->getIdExterno()?>',
                                    'controlbar': 'bottom',
                                    'modes': [
                                        {type: 'flash', src: '<?php  echo DOMINIORAIZSITE?>player/player.swf'},
                                        {type: 'html5'},
                                        
                                        {type: 'download'}
                                    ]
                             });
                        </script>
</div>
<div class="clearboth" style="height:10px;">&nbsp;</div>
<span class="videodesc"><?php  echo $oMultimedia->getDescripcion()?></span>
<?php  
ob_end_flush();
?>