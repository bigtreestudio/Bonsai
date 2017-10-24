<?php  
session_start();
include("./config/include.php");
include(DIR_CLASES."cProvincias.class.php");
include(DIR_CLASES."cDepartamentos.class.php");
include(DIR_CLASES."cFormularios.class.php");
include(DIR_LIBRERIAS."recaptchalib.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);
     

$oFormulariosService=new cFormularios($conexion);

if (!isset($_GET['codigo']) || $_GET['codigo']=='')
	die();
	
if(strlen($_GET['codigo'])>10)
	die();
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['codigo'],"NumericoEntero"))
	die();


$datosenviarform['formulariocod'] = $_GET["codigo"];
$oFormularios = $oFormulariosService->BuscarFormulario($datosenviarform);

if ($oFormularios===false)
{	
	ob_clean();
	FuncionesPHPLocal::Error404();
	die();
}

$oEncabezados->setTitle($oFormularios->getTitulo());
$oEncabezados->setOgTitle($oFormularios->getTitulo());
$oEncabezados->EncabezadoMenuEmergente();

$resp = null;
$error = null;
$formulariotipocod=$oFormularios->getTipoCodigo();

$mostrarmapa = false;
if ($oFormularios->getLatitud()!="" && $oFormularios->getLongitud()!="" && $oFormularios->getMapaTipo()!="")
	$mostrarmapa = true;
	

$_SESSION['formulario'] = array();
$_SESSION['error'] = "";
$_SESSION['accionmsg'] = "";
?>
<div id="DetalleContacto" class="contactoForm">
	<div class="leftcolumn">
        <div class="verde_oscuro txt_destacado">
			<?php  if ($oFormularios->getTexto()!=""){?>
	            <div class="txt_destacado_contacto">
            		<?php  echo $oFormularios->getTexto();?>
                </div>
            <?php  }?>
        </div>
        <div class="BordeInferior">&nbsp;</div>  <!-- Cierre BordeInferior-->  
        <p class="msg_mail">Su mensaje ha sido enviado correctamente.</p>
    </div><!-- Cierre leftcolumn-->

   	<div class="rightcolumn">
        <?php  include("col_derecha.php")?>
        <?php  include("col_derecha_bottom.php")?>
  	</div> <!-- Cierre rightcolumn--> 
	<div class="clearboth"></div>
</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>