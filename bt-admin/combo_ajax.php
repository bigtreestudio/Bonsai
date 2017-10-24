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
header('Content-Type: text/html; charset=iso-8859-1'); 


$nivel = 1;
function CargarSubMenu($arbol,$nivel)
{
	$margen = $nivel *10; 
	foreach($arbol as $fila)
	{
		?>
        <option  value="<?php  echo $fila['menucod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['menudesc']),ENT_QUOTES)?></option>
		<?php 
        			if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
          
		<?php 	
	}
}

switch($_POST['tipo']){
	case 1:
		//muestro select de productos
		$oProductos= new cProductos($conexion);
		$datos["catcod"] = $_POST['catcod'];
		$datos["xproestado"] = 1;
		$datos['proestado']=ACTIVO;
		if ($oProductos->BusquedaAvanzada ($datos,$resultado,$numfilas))
		{		
			echo '<select name="procod" id="procod">';
			while ($filaProd=$conexion->ObtenerSiguienteRegistro($resultado))
			{
				echo '<option value="'.$filaProd["procod"].'">';
                echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaProd["prodesc"], ENT_QUOTES);
                echo '</option>';
			}
			echo '</select>';
		}
		break;
	case 2:
		$provinciacod = $_POST['provinciacod'];
		$oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");
		$oDepartamentos->DepartamentoSP($spnombre,$sparam);
		$campo = "departamentocod";
		$descripcion = "departamentodesc";
		$seleccione = "Todas las Ciudades";
		$onclick = "";
		if ($provinciacod!="")
		{
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario",$campo,$campo,$descripcion,"",$seleccione,$regnousar,$selecnousar,1,$onclick,"width: 200px",false,false);
		}
		break;
	case 3:
		//cargo deptos para entrega
		$provinciacod = $_POST['provinciacod'];
		$oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");
		$oDepartamentos->DepartamentoSP($spnombre,$sparam);
		$campo = "departamentocod";
		$nombrecombo = "ventaentregadepartamentocod";
		$descripcion = "departamentodesc";
		$seleccione = "Todas las Ciudades";
		$onclick = "";
		if ($provinciacod!="")
		{
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario",$nombrecombo,$campo,$descripcion,"",$seleccione,$regnousar,$selecnousar,1,$onclick,"width: 200px",false,false);
		}
		break;
		
	case 4:
		//cargo deptos para entrega
		$oMenu= new cTapasMenu($conexion);
		$oMenu-> ArmarArbol($_POST,"",$arbol);
		?>

            <select data-placeholder="Todos los menu..." name="menucod" id="menucod" style="width:400px;" class="chzn-select" >
                 <option value="">Sin men&uacute;...</option>
                <?php 
                    foreach($arbol as $fila)
                    {
                       
                       ?>
                        <option value="<?php  echo $fila['menucod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['menudesc']),ENT_QUOTES)?></option>
                        <?php  
                        if (isset($fila['subarbol']))
                        {
                            $nivel = "---";
                            CargarSubMenu($fila['subarbol'],$nivel);
                        }
                    }
                    ?>
             </select>
        
        <?php 
		break;		
	case 5:
		
		//cargo fotos para galeria
		$galeriacod = $_POST['galeriacod'];
		//busco galeria
		$oGaleria = new cGalerias($conexion);
		if (!$oGaleria->BuscarxCodigo($_POST,$resultadoG,$numfilasG))
		{
			die("Error");
		}
		$filaG=$conexion->ObtenerSiguienteRegistro($resultadoG);
		$oGaleriaM = new cGaleriasMultimedia($conexion);
		switch ($filaG["multimediaconjuntocod"])
		{
			case FOTOS:
				$oGaleriaM->BuscarMultimediaFotosxCodigoGaleria($filaG,$resultado,$numfilas);
				break;
			case VIDEOS:
				$oGaleriaM->BuscarMultimediaVideosxCodigoGaleria($filaG,$resultado,$numfilas);
				break;
		}
		?>
        <select name="multimediacod" id="multimediacod" onchange="PrevisualizarFoto()">
            <option value=""> Seleccione una im&aacute;gen</option>
			<?php       
            if ($numfilas>0)
            {
				while ($fila=$conexion->ObtenerSiguienteRegistro($resultado))
				{
					?>
					<option value="<?php  echo $fila["multimediacod"]?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila["galmultimediatitulo"],ENT_QUOTES)?></option>
					<?php  
				}
            }
            ?>
        </select>
        <?php 
		break;
	case 6:
		if(isset($_POST['multimediacod']) && $_POST['multimediacod']!="")
		{
			//cargo fotos para galeria
			$multimediacod = $_POST['multimediacod'];
			//busco galeria
			$oMultimedia = new cMultimedia($conexion,"");
			if (!$oMultimedia->BuscarMultimediaxCodigo($_POST,$resultadoM,$numfilasM) || $numfilasM<=0)
			{
				echo "Multimedia inexistente";
				break;
			}
			$filaM=$conexion->ObtenerSiguienteRegistro($resultadoM);
			$url=$oMultimedia->DevolverDireccionImg($filaM);
			echo $url;
		}
		break;
		case 7:
			$oCategorias = new cCategorias($conexion,"");
			if (!$oCategorias->BusquedaAvanzadaCategorias($_POST,$resultadoCategorias,$numfilas))
				return false;
			if($numfilas>0)
			{
			?>		   
				<label>Categor&iacute;a:</label>
				<select name="catcodrelacion" id="catcodrelacion" class="chzn-select-idioma" style="width:200px" >
                   <option value="">Seleccione una categor&iacute;a...</option>
                    <?php  while($datosCategoria = $conexion->ObtenerSiguienteRegistro($resultadoCategorias)){
                     ?>
                    <option value="<?php  echo $datosCategoria['catcod']?>"><?php  echo $datosCategoria['catnom']?></option>
                    <?php  }?>
				</select>
				<?php 
			}
			else
			{?>
				<label>No existen categorias para idioma seleccionado...</label>
			<?php  }
		break;
}
	
	
?>
<?php  ?>