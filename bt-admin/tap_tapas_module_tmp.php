<?php  
ob_start();
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

header('Content-Type: text/html; charset=iso-8859-1'); 

$oModulosTmp= new cTapasZonasModulosTmp($conexion);
if(!$oModulosTmp->BuscarModulosTmp($_POST,$resultado,$numfilas))
	return false;
	
?>
<div class="zona_tmp_data" id="zona_temporal" style="margin-top:20px">
<?php  
while ($datosModuloTmp = $conexion->ObtenerSiguienteRegistro($resultado))
   { ?>
    <div class="zona_tmp cuadros" id="modulotmp_<?php  echo  $datosModuloTmp['modulotmpcod']?>">
		<?php  echo utf8_decode(($datosModuloTmp['modulonombre']!="")? FuncionesPHPLocal::HtmlspecialcharsBigtree($datosModuloTmp['modulonombre'],ENT_QUOTES): FuncionesPHPLocal::HtmlspecialcharsBigtree($datosModuloTmp['modulodesc'],ENT_QUOTES));?>
        <div class="eliminartmp">
            <a href="javascript:void(0)" style="display:block" onclick="EliminarTmp(<?php  echo $datosModuloTmp['modulotmpcod']?>)" title="Eliminar">&nbsp;</a>
        </div>
    </div>
<?php  } ?>
</div>
<?php 
ob_end_flush();
?>