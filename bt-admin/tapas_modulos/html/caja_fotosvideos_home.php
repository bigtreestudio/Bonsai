<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oGalerias= new cGalerias($vars['conexion']);
$oMultimedia= new cMultimedia($vars['conexion'], "");
$Multimedia= new Multimedia($vars['conexion'], "");

$CantidadTotal = 0;
$Titulo = "FOTOS/VIDEOS";


if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->galeriacod) && count($objDataModel->galeriacod)>0)
		$CantidadTotal = count($objDataModel->galeriacod);
	else
		$CantidadTotal=0;

	if (isset($objDataModel->Titulo))
		$Titulo = utf8_decode($objDataModel->Titulo);

}

?>
<div class="modulofotosvideosgalerias tap_modules clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>

    <div class="cuerpo">
        <div class="titulofotosvideosgalerias">
            <h2><span class="iconomultimedia"></span><?php  echo $Titulo?>&nbsp;</h2>
        </div>
        <div class="clearboth brisa"></div>
        
        <div class="clearboth brisa"></div>
        <div class="fotosvideosgalerias clearfix" id="fotosvideosgalerias_<?php  echo $vars['zonamodulocod']?>">
        	<ul>
			<?php 
			foreach ($objDataModel->galeriacod as $galeriacod)
            {
				if (isset($objDataModel->multimediacod->$galeriacod))
					$multimediacod=$objDataModel->multimediacod->$galeriacod;
				
				//BUSSCO LA GALERIA
				$datosG=array('galeriacod'=>$galeriacod);
				if ($oGalerias->BuscarxCodigo($datosG, $resultadoG, $numfilasG)&& $numfilasG>0)
				{
					$filaG = $conexion->ObtenerSiguienteRegistro($resultadoG);
					$galeriatitulo= FuncionesPHPLocal::HtmlspecialcharsBigtree($filaG["galeriatitulo"], ENT_QUOTES);
					//BUSCO EL MULTIMEDIA
					$datosP=array('multimediacod'=>$multimediacod);
						//print_r($datosP);
					if ($oMultimedia->BuscarMultimediaxCodigo($datosP, $resultadoM, $numfilasM))
					{
						$filaM = $conexion->ObtenerSiguienteRegistro($resultadoM);
						if ($filaM['multimediaconjuntocod']==FOTOS)
							$thumbUrl=DOMINIO_SERVIDOR_MULTIMEDIA.$Multimedia->GetImagenStatic(145, 0, $filaM['multimediacatcarpeta']."N/".$filaM['multimediaubic']);
						else
							$thumbUrl=$oMultimedia->ArmarImagenVideo($filaM["multimediatipocod"],$filaM["multimediaidexterno"]);
						?>
						<li>
							<a href="<?php  echo DOMINIOWEB."galeria/".$filaG["galeriadominio"]?>" title="Ver galeria <?php  echo $galeriatitulo?>">
							<img src="<?php  echo $thumbUrl?>" alt="Imagen de galeria <?php  echo $galeriatitulo?>"/>
								<?php  if ($filaG["multimediaconjuntocod"]==VIDEOS){?>
                                    <span class="videoicon">&nbsp;</span>
                                <?php  }?>
							</a>
                            
						</li>
						<?php  
					}
				}
				
			}?>
            </ul>
        </div>
     </div> 
</div>
<?php  ?>