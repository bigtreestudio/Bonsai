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

$oPaginasEstados = new cPaginasEstados($conexion);

$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 


if (isset($_POST['pagestadocod']) && $_POST['pagestadocod'])
{
	$esmodif = true;
	if (!$oPaginasEstados->BuscarxCodigo($_POST,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Código inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	$datosestado = $conexion->ObtenerSiguienteRegistro($resultado);
}
$botonejecuta = "BtAlta";
$boton = "Alta";
$pagestadocod = "";
$pagestadodesc = "";
$pagestadocte= "";
$pagestadomuestracantidad= "";
$pagestadosemuestra= "";
$onclick = "return InsertarPaginaEstado();";
if ($esmodif)
{
	$botonejecuta = "BtModificar";
	$boton = "Modificar";
	$pagestadocod = $datosestado['pagestadocod'];
	$pagestadodesc = $datosestado['pagestadodesc'];
	$pagestadocte= $datosestado['pagestadocte'];
	$pagestadomuestracantidad= $datosestado['pagestadomuestracantidad'];
	$pagestadosemuestra= $datosestado['pagestadosemuestra'];
	$onclick = "return ModificarPaginaEstado();";
}

?>
<script type="text/javascript" language="javascript">
</script>
<div style="text-align:left">
	<div class="form">
		<form action="mul_multimedia_formatos.php" method="post" name="formpaginaestado" id="formpaginaestado" >
			<div class="datosgenerales">
            	<div>
    				<label>C&oacute;digo:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="pagestadocod"  id="pagestadocod" maxlength="2" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagestadocod,ENT_QUOTES)?>" <?php  if($esmodif) echo 'disabled="disabled"'?> />
				</div>
                <div class="clearboth aire_menor">&nbsp;</div>
				<div>
    				<label>Descripci&oacute;n:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="pagestadodesc"  id="pagestadodesc" maxlength="55" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagestadodesc,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>
    			<div>	
                    <label>Constante:</label>
				</div>
   				<div class="clearboth brisa_vertical">&nbsp;</div>
				<div>
    				<input type="text"  name="pagestadocte"  id="pagestadocte" maxlength="140" class="full" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagestadocte,ENT_QUOTES)?>"/>
				</div>
				<div class="clearboth aire_menor">&nbsp;</div>

				<div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
    				<label>Muestra Cantidad:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<select name="pagestadomuestracantidad" id="pagestadomuestracantidad">
                            <option value="1" <?php  if ($pagestadomuestracantidad=="1") echo 'selected="selected"'?> >SI</option>
                            <option value="0" <?php  if ($pagestadomuestracantidad=="0") echo 'selected="selected"'?>>NO</option>
                        </select>
    			</div>
				<div class="clearboth aire_menor">&nbsp;</div> 
                <div>
    				<label>Se Muestra:</label>
				</div>
				<div class="clearboth brisa_vertical">&nbsp;</div>
     			<div>
         			<select name="pagestadosemuestra" id="pagestadosemuestra">
                            <option value="1" <?php  if ($pagestadosemuestra=="1") echo 'selected="selected"'?> >SI</option>
                            <option value="0" <?php  if ($pagestadosemuestra=="0") echo 'selected="selected"'?>>NO</option>
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
           <?php  if($esmodif) {?>
             <input  type="hidden"  name="pagestadocod"  id="pagestadocod" maxlength="2" class="mini" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagestadocod,ENT_QUOTES)?>" />
			<?php  }?>	

		</form>
	</div>
</div>
