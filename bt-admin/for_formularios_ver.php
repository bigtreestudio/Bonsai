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
$oFormularios= new cFormularios($conexion);

if (!$oFormularios->BuscarDatosxCodigo($_POST,$resultado,$numfilas))
	die();
		
$datosmensaje = $conexion->ObtenerSiguienteRegistro($resultado);
?>

   
    <div class="form">
        <div>
            <label>Formulario:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div >
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['formulariotipotitulo'],ENT_QUOTES)?>
        </div>
        <div class="clearboth" style="height:5px;">&nbsp;</div>
        <div>
            <label>Nombre:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div >
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['formularionombre'],ENT_QUOTES)?>
        </div>
        <div class="clearboth" style="height:5px;">&nbsp;</div>
        <div>
            <label>Email:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div>
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['formulariomail'],ENT_QUOTES)?>
        </div>
        <div class="clearboth" style="height:5px;">&nbsp;</div>
        <div>
            <label>Provincia:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div>
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['provinciadesc'],ENT_QUOTES)?>
        </div>
        <div class="clearboth" style="height:5px;">&nbsp;</div>
        <div>
            <label>Localidad:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div>
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['departamentodesc'],ENT_QUOTES)?>
        </div>
        <div class="clearboth" style="height:5px;">&nbsp;</div>
        <div>
            <label>Mensaje:</label>
        </div>
        <div class="clearboth" style="height:1px;">&nbsp;</div>
        <div>
            <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmensaje['formulariocomentario'],ENT_QUOTES)?>
        </div>                    
        <div class="clearboth" style="height:5px;">&nbsp;</div>
    	<?php  
        if (trim($datosmensaje['formulariodatosjson'])!="")	
		{	
			$dataJson = json_decode($datosmensaje['formulariodatosjson']);
			foreach($dataJson as $clave=>$dato)
			{
				?>
                    <div class="clearboth" style="height:5px;">&nbsp;</div>
                    <div>
                        <label><?php  echo $clave?>:</label>
                    </div>
                    <div class="clearboth" style="height:1px;">&nbsp;</div>
                    <div>
                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($dato,ENT_QUOTES)?>
                    </div>                    
                <?php  
			}
		}
		?>
    </div>
    <div class="menubarra" align="right"  style="width:100%; text-align:right; margin-top:40px; ">
        <ul>
            <li><a class="boton base" s href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
        </ul>
    </div>  				




