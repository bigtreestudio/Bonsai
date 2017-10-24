<?php 
$oMultimedia = new cMultimedia($vars['conexion'],"");
$Multimedia = new Multimedia($vars['conexion'],"");


$tituloFoto = $titulo = "";
$numfilas = 0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	$oFotosDia = new cFotosDia($vars['conexion']);
	$fotodiacod = $objDataModel->fotodiacod;
	$datosbusqueda['fotodiacod'] = $fotodiacod;
	if (!$oFotosDia->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
		return false;
		
	//guardo datos del multimedia elegido
	$fotoDia =  $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	$titulo =  FuncionesPHPLocal::HtmlspecialcharsBigtree($fotoDia['fotodiatitulo'],ENT_QUOTES);
}

$anchoImg = 300;
if (isset($vars['width']) && $vars['width']!="")
	$anchoImg = $vars['width'];


?>
<div class="fotodeldia tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <h2><span class="icon icon-multimedia">&nbsp;</span><?php  echo utf8_decode( $objDataModel->fotodeldiatitulo)?></h2>
    <div class="clearboth brisa">&nbsp;</div>
    <div class="fotoimg">
    	<?php  if ($numfilas>0){?>
            <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$Multimedia->GetImagenStatic($anchoImg, 0, $fotoDia['multimediacatcarpeta']."N/".$fotoDia['multimediaubic']);?>" alt="Foto del dia - <?php  echo $titulo?>" />
			<?php  if ($titulo!=""){?>
                <div class="titulo">&nbsp;&nbsp;&nbsp;<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES);?></div>
            <?php  }?>
        <?php  }?>
    </div>
</div>