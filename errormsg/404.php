<?php  
include("../config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

$oEncabezados->setTitle("P&aacute;gina no disponible");
$oEncabezados->EncabezadoMenuEmergente();
?>
    <div id="error_404">
    	<h1>P&aacute;gina no disponible</h1>
        <p>
        	La p&aacute;gina a la que intenta acceder no est&aacute; disponible.<br/>
            <img src="/imagenes/404.png" alt="Error 404" />
        </p>
        <p>
        	Para continuar navegando en el sitio haga click AQU&Iacute;: <a href="<?php  echo DOMINIOWEB?>" title="Ir a <?php  echo DOMINIOWEB?>"><?php  echo DOMINIOWEB?></a>
        </p>
    </div>
<?php  
$oEncabezados->PieMenuEmergente();
?>