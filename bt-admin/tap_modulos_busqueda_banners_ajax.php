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

$oBanners = new cBanners($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_POST;
$datos['bannerestado'] = PUBLICADO;
$datos['limit'] = "LIMIT 0,20";
if(!$oBanners->BusquedaAvanzada($datos,$resultadobanners,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadobanners))
	{
	?>
        <tr>
            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannercod'],ENT_QUOTES); ?></td>
            <td style="text-align:left" id="bannerdesc_<?php  echo $fila['bannercod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['bannerdesc'],ENT_QUOTES); ?></td>
                             <td style="text-align:left" id="bannerdesc_<?php  echo $fila['bannercod']?>"><img style="width:50px; height:30px" src="<?php  echo  DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$fila['bannerarchubic']?>"/> </img> &nbsp;</td>               
            <td style="text-align:center; font-weight:bold;">
               <a class="left add_image" href="javascript:void(0)" onclick="AgregarBanner(<?php  echo $fila['bannercod']?>)">&nbsp;</a>
            </td>
        </tr> 
	<?php 
	}
}
?>