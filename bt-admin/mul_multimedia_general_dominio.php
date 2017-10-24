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

$oMultimedia = new cMultimedia($conexion,"");

if(!$oMultimedia->BuscarMultimediaxCodigo($_POST,$resultado,$numfilas))
	return false;

$fila = $conexion->ObtenerSiguienteRegistro($resultado);

function DevolverURL($fila)
{
	switch ($fila['multimediaconjuntocod'])
	{
		case 1:
			return DOMINIOWEB."multimedia/".$fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic']; 
			break;
		case 2:
			return DOMINIOWEB."multimedia/".$fila['multimediacatcarpeta']."/video".$fila['multimediaubic']; 
			break;

		case 3:
			return DOMINIOWEB."multimedia/".$fila['multimediacatcarpeta']."/audios".$fila['multimediaubic']; 
			break;
		case 4:
			return DOMINIOWEB."multimedia/".$fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$fila['multimediaubic']; 
			break;
	}
	return true;
}


$dominio = DevolverURL($fila);



?>
<link href="modulos/mul_multimedia/css/popup.css?v=1.1" rel="stylesheet" title="style" media="all" />
<div>
    <div id="mul_multimedia_fotos">
        <div id="mul_multimedia_dominio">
        	<div>
                <form action="noticias_lst.php" method="post" name="form_mul_multimedia_img" id="form_mul_multimedia_img">
                   <div class="ancho_10">
                        <label>URL:</label>
                        <input type="text"  name="dominio" id="dominio" class="full"  value="<?php  echo $dominio ?>"  readonly="readonly"/>
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                </form>        
            </div>
        </div>
   </div>     
</div>