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

$oMultimediaFormatos = new cMultimediaFormatos($conexion,"");

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['formatocod']) && $_POST['formatocod'])
{
	$esmodif = true;
	if (!$oMultimediaFormatos->BuscarMultimadiaFormatoxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosformato = $conexion->ObtenerSiguienteRegistro($resultado);
}

$botonejecuta = "BtAlta";
$boton = "Alta";
$formatocod = "";
$formatodesc = "";
$formatoancho= "";
$formatoalto= "";
$formatocarpeta= "";
$formatocrop="1";
$onclick = "return InsertarMultFormatos();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$formatocod = $datosformato['formatocod'];
	$formatodesc = $datosformato['formatodesc'];
	$formatoancho= $datosformato['formatoancho'];
	$formatoalto= $datosformato['formatoalto'];
	$formatocarpeta= $datosformato['formatocarpeta'];
	$formatocrop= $datosformato['formatocrop'];
	$onclick = "return ModificarMultFormatos();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formmultformato" id="formmultformato" >
			<div class="datosgenerales">
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="formatodesc"  id="formatodesc" maxlength="80" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formatodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Ancho:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="formatoancho" id="formatoancho" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formatoancho,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div> 
                <div>
    				<label>Alto:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="formatoalto" id="formatoalto" maxlength="80" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formatoalto,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Formato Carpeta:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<input type="text" name="formatocarpeta" id="formatocarpeta"  maxlength="10" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formatocarpeta,ENT_QUOTES)?>"/>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>
                <div>
    				<label>Cropea:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<select name="formatocrop" id="formatocrop">
                            <option value="1" <?php  if ($formatocrop=="1") echo 'selected="selected"'?> >SI</option>
                            <option value="0" <?php  if ($formatocrop=="0") echo 'selected="selected"'?>>NO</option>
                        </select>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div>      
				<div class="menubarra">
    				<ul>
        				<li><div class="ancho_boton aire"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></div></li>
        				<li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
    				</ul>
				</div>
			</div>
			<input type="hidden" value="<?php  echo $formatocod?>" name="formatocod" id="formatocod" />
		</form>
	</div>
</div>
