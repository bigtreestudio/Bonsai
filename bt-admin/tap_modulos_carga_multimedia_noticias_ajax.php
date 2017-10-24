<?php 
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oNoticias = new cNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$oNoticiasMultimedia = new cNoticiasMultimedia($conexion,"");
if(!$oNoticiasMultimedia->BuscarMultimediaFotosxCodigoNoticia($_POST,$resultado,$numfilas))
	die();
$oMultimedia = new cMultimedia($conexion,"");


if ($numfilas>0)
{
	$multimediacod="";
	if (isset($_POST['multimediacod']) && $_POST['multimediacod']!="")
		$multimediacod = $_POST['multimediacod'];
	else
	{	
		$fila = $conexion->ObtenerSiguienteRegistro($resultado);
		$multimediacod = $fila['multimediacod'];
	}
	?>
    <script type="text/javascript">
		function CambiarImage()
		{
			$("#multimediacod").val($("#multimediacodsel").val());
			$("#imageLoad").html($("#multimediacod_"+$("#multimediacodsel").val()).html());
		}
	</script>
	<?php 
    $conexion->MoverPunteroaPosicion($resultado,0);
    while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
    {
        if ($fila['multimediacod']==$multimediacod)
		{
		?>	
            <div id="imageLoad" style="text-align:center; margin-bottom:5px;"> 
                <img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
            <div style="display:none" id="multimediacod_<?php  echo $fila['multimediacod']?>"> 
                <img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
		<?php  
		}else
		{
	    ?>
            <div style="display:none" id="multimediacod_<?php  echo $fila['multimediacod']?>"> 
                <img src="<?php  echo $oMultimedia->DevolverDireccionImgThumb($fila['multimediacatcarpeta'],$fila['multimediaubic'])?>" alt="Imagen" />
            </div>    
        <?php 
		}
    }
	?>    
    <input type="hidden" name="multimediacod" id="multimediacod" value="<?php  echo $multimediacod?>" />
    <?php  
	if ($numfilas>1)
	{
	?>
    <select name="multimediacodsel" id="multimediacodsel" style="width:100%;" onchange="CambiarImage()">
	<?php 
		$conexion->MoverPunteroaPosicion($resultado,0);
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?>
				<option value="<?php  echo $fila['multimediacod']?>" <?php  if ($fila['multimediacod']==$multimediacod) echo 'selected="selected"'?> >
					<?php  echo ($fila['multimediadesc']!="") ? $fila['multimediadesc']:$fila['multimedianombre'];?>
					<?php  echo ($fila['notmultimediamuestrahome']==1) ? " - Home":"";?>
				</option>  
			<?php  
		}
		?>
	</select>
	<?php  
    }
}else
{
	echo "&nbsp;";	
}
?>