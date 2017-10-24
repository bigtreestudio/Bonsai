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
$_SESSION['volver'] ="ban_banners.php"; 
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>
<link href="css/elfinder.min.css" rel="stylesheet" title="style" media="all" />
<link href="css/theme.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/elfinder.min.js"></script>
<script type="text/javascript" src="js/elfinder.es.js" charset="utf-8"></script>

<script type="text/javascript">

var mytoolbar = [
		['back', 'forward'],
		['mkdir', 'mkfile', 'upload'],
		['open', 'download', 'getfile'],
		['info'],
		['quicklook'],
		['copy', 'cut', 'paste'],
		['rm'],
		['rename', 'edit'],
		['view']
	];
	
	$().ready(function() {
		var elf = $('#AdminFiles').elfinder({
			url : 'fil_filemanager_connect.php',  // connector URL (REQUIRED)
			uiOptions : {toolbar : mytoolbar},
			"params"    : {
				"uplMaxSize" : "1M"
			},
			handlers : {
					select : function(event, elfinderInstance) {
						var selected = event.data.selected;
						if (selected.length) {
							CargarArchivo(elfinderInstance.path(selected[0]))
						}
					}
				},
			lang: 'es'            // language (OPTIONAL)
		}).elfinder('instance');
});


function CargarArchivo(path)
{
	var pathcompleto = path.replace(/\\/g,'/');
	$("#archnom").val('<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA?>'+pathcompleto);
	
}

function Seleccionar()
{
	$("#archnom").focus();
	$("#archnom").select();
}
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Administrador de archivos</h2>
</div>
<div class="form">
	<form action="javascript:void(0)" name="formarch" id="formarch" method="post">
    	<div class="ancho_1" style="margin-top:7px; text-align:right">
        	<span style="font-weight:bold">Url Archivo:</span>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_6">
            <input type="text" value="" style="background-color:#FFF; color:#000;" id="archnom" name="archnom" class="full" />
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_2" style="text-align:left">
            <input type="button" class="boton verde" value="Seleccionar URL" id="BtCopiar" name="BtCopiar" onclick="Seleccionar()" class="full boton" />
        </div>
        <div style="clear:both">&nbsp;</div>
    </form>
</div>
<div id="AdminFiles"></div>

<div class="clearboth">&nbsp;</div>

<?php  
$oEncabezados->PieMenuEmergente();
?>