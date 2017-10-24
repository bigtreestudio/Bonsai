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


$formulariocod = $datosenviarform['formulariocod'] = $_GET["codigo"];
$oFormularios = $oFormulariosService->BuscarFormulario($datosenviarform);

if ($oFormularios===false)
	die();



if ($oFormularios->getMenuCodigo()!="")
{
	$oEncabezados->setMenu($oFormularios->getMenuCodigo());
	$oMenu = new cMenu($conexion);
	$datosMenuBusqueda['menucod'] = $oFormularios->getMenuCodigo();
	$datosMenuBusqueda['menutipocod'] = $oFormularios->getMenuTipoCodigo();
	$oMenu->BuscarxCodigo($datosMenuBusqueda,$resultado,$numfilas);
	if ($numfilas>0)
	{
		$datosMenu = $conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMenu['menucodsup']!="")
			$oEncabezados->setMenu($datosMenu['menucodsup']);
	}
}
$oEncabezados->setPlantilla(PLANTILLAINTERNA);
$oEncabezados->setTitle($oFormularios->getTitulo());
$oEncabezados->setOgTitle($oFormularios->getTitulo());
$oEncabezados->EncabezadoMenuEmergente();


$resp = null;
$error = null;
$formulariotipocod=$oFormularios->getTipoCodigo();

$mostrarmapa = false;
if ($oFormularios->getLatitud()!="" && $oFormularios->getLongitud()!="" && $oFormularios->getMapaTipo()!="")
	$mostrarmapa = true;
	
$formularionombre	= "";
$formulariomail	= "";
$provinciacod = "";
$departamentocod="";
$formularioubic="";
$formulariocomentario="";
if (isset($_SESSION['formulario']))
{
	if (isset($_SESSION['formulario']['formularionombre']))
		$formularionombre	= $_SESSION['formulario']['formularionombre'];
	if (isset($_SESSION['formulario']['formulariomail']))
		$formulariomail	= $_SESSION['formulario']['formulariomail'];
	if (isset($_SESSION['formulario']['provinciacod']))
		$provinciacod = $_SESSION['formulario']['provinciacod'];
	if (isset($_SESSION['formulario']['departamentocod']))
		$departamentocod=$_SESSION['formulario']['departamentocod'];
	if (isset($_SESSION['formulario']['formularioubic']))
		$formularioubic=$_SESSION['formulario']['formularioubic'];
	if (isset($_SESSION['formulario']['formulariocomentario']))
		$formulariocomentario=$_SESSION['formulario']['formulariocomentario'];
}


?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="<?php echo DOMINIORAIZSITE?>js/formulario.js"></script>
<script type="text/javascript" src="/js/block.js"></script>
<section>
        <div class="container">
          <h2 class="h1 text-center">Complet&aacute; tus datos</h2>
          <br>
          <form class="col-md-8 col-md-offset-2" id="formulario_contacto" action="<?php  echo DOMINIORAIZSITE?>formulario/upd" method="post">
      
            <div class="form-group">
              
              <div class="row">
              
                <div class="col-xs-6">
                	<label for="Nombre">Nombre</label>
                	<input class="form-control input-lg" type="text" placeholder="Nombre" id="formularionombre" name="formularionombre"<? /* required=""*/?>></div>
                <div class="col-xs-6">
                	<label for="Apellido">Apellido</label>
                	<input class="form-control input-lg" type="text" placeholder="Apellido" id="formularioapellido" name="formularioapellido" <? /* required=""*/?>></div>
              </div>
            </div>
            <br>

            <div class="form-group">
              <label for="Documento">Tel&eacute;fono</label>
             <input class="form-control input-lg" type="text" placeholder="Telefono" id="formulariotelefono" name="formulariotelefono" <? /* required=""*/?>>
            </div>

            <br>

            <div class="form-group">
              <label for="Correo">Correo electr&oacute;nico</label>
              <input class="form-control input-lg" type="text" placeholder="Correo electr&oacute;nico" id="formulariomail" name="formulariomail" <? /* required=""*/?>>
            </div>
            <br />

            <div class="form-group">
              <label for="Aclaraciones">Mensaje</label>
              <textarea class="form-control input-lg" rows="3" placeholder="Mensaje" id="formulariocomentario" name="formulariocomentario" <? /* required=""*/?>></textarea>
            </div>
            <br>
            <div class="form-group">
	            <div class="g-recaptcha" data-sitekey="<? echo PUBLICKEYCAPTCHA?>"></div>
            </div>
            <br>
            <p class="text-center">
              <button class="btn btn-primary btn-lg btn_submit" href="#">Envianos tu mensaje <img src="/public/gcba/bastrap3/ba-btn-default.png" class="glyphicon glyphicon-ba" /></button>
            </p>
            <br>
           <input type="hidden" name="formulariocod" id="formulariocod" value="<? echo $formulariocod?>"/>
          </form>
          
        </div>
        <div id="formulario_ok">
          		<h3>Gracias por contactarse. Su consulta ser&aacute; enviada al &aacute;rea correspondiente.</h3>
          </div>
</section>
<?php  
$oEncabezados->PieMenuEmergente();
?>