<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticia = new cNoticias($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$datosbusqueda['noticiacod'] = $objDataModel->noticiacod;
if(!$oNoticia->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
	return false;

$datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);

$dominiocompartir = DOMINIOWEB."cn".$objDataModel->noticiacod;

$titulo = $datosnoticia['noticiatitulo'];
$titulobase = $datosnoticia['noticiatitulo'];
$catdominio = $datosnoticia["catdominio"];
$volanta = $datosnoticia["noticiavolanta"];

$dominio = DOMINIORAIZSITE.$catdominio."/".$datosnoticia["noticiadominio"];
$target = "_self";
if ($datosnoticia['noticiahrefexterno']!="")
{
	$dominio = $datosnoticia['noticiahrefexterno'];
	$target = "_blank";
}
$catnom = $datosnoticia["catnom"];
$catcod = $datosnoticia["catcod"];
$catcolor = $datosnoticia["catcolor"];
$hora = substr($datosnoticia["noticiafecha"],11,5);

$oMultimedia = new cMultimedia($vars['conexion'],"");

$Top=0;
if (isset($objDataModel->Top) && $objDataModel->Top!=0)
	$Top = $objDataModel->Top;
	
if (isset($objDataModel->Titulo) && $objDataModel->Titulo!="")
	$titulo = utf8_decode($objDataModel->Titulo);

$conCategoria = false;
if (isset($objDataModel->conCategoria) && $objDataModel->conCategoria=="on")
	$conCategoria = true;

$multimediacod = "";
if (isset($objDataModel->multimediacod) && $objDataModel->multimediacod!="")
	$multimediacod = $objDataModel->multimediacod;

$conVolanta = false;
if (isset($objDataModel->conVolanta) && $objDataModel->conVolanta=="on")
	$conVolanta = true;

$conPais = false;
if (isset($objDataModel->conPais) && $objDataModel->conPais=="on")
	$conPais = true;

$conCuerpo = false;
if (isset($objDataModel->conCuerpo) && $objDataModel->conCuerpo=="on")
	$conCuerpo = true;	

$conHora = false;
if (isset($objDataModel->conHora) && $objDataModel->conHora=="on")
	$conHora = true;

$conAudio = false;
if (isset($objDataModel->conAudio) && $objDataModel->conAudio=="on")
	$conAudio = true;

$conRedesSociales = false;
if (isset($objDataModel->conRedesSociales) && $objDataModel->conRedesSociales=="on")
	$conRedesSociales = true;

$noticiaTipo = 1;
if (isset($objDataModel->noticiaTipo))	
	$noticiaTipo = $objDataModel->noticiaTipo;


$IconoVideo = false;
if (isset($objDataModel->IconoVideo) && $objDataModel->IconoVideo=="on")
	$IconoVideo = true;	

$conVideo = false;
if (isset($objDataModel->conVideo) && $objDataModel->conVideo=="on")
	$conVideo = true;	

/*
$ColorTitulo = "";
if (isset($objDataModel->ColorTitulo) && $objDataModel->ColorTitulo!="")
	$ColorTitulo = $objDataModel->ColorTitulo;

$TamTitulo = "";
if (isset($objDataModel->TamTitulo) && $objDataModel->TamTitulo!="")
	$TamTitulo = $objDataModel->TamTitulo;
*/

$FondoTitulo = "";
if (isset($objDataModel->FondoTitulo) && $objDataModel->FondoTitulo!="")
	$FondoTitulo = $objDataModel->FondoTitulo;

$conFoto = false;
$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");
if (isset($objDataModel->conFoto) && $objDataModel->conFoto=="on")
{	
	if ($multimediacod!="")
	{
		$datosnoticia['multimediacod'] = $multimediacod;
		if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticiaxCodigoMultimedia($datosnoticia,$resultado,$numfilas))
			die();
			
		if ($numfilas!=0)
		{
			$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
			$conFoto = true;
		}
	}
}

if (isset($objDataModel->conAudio) && $objDataModel->conAudio=="on")
{	
	if(!$oNoticiasMultimedia->BuscarMultimediaAudiosxCodigoNoticia($datosnoticia,$resultado,$numfilas))
		die();
		
	if ($numfilas!=0)
	{
		$datosAudio = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		$conAudio = true;
	}
}

$oMultimedia = new Multimedia($conexion,"");
$conVideo = false;
$urlVideo = "";

if (isset($objDataModel->conVideo) && $objDataModel->conVideo=="on")
{	
	if(!$oNoticiasMultimedia->BuscarMultimediaVideosxCodigoNoticia($datosnoticia,$resultado,$numfilas))
		die();
		
	if ($numfilas!=0)
	{
		$datosVideo = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
		$conVideo = true;

		switch($datosVideo['multimediatipocod'])
		{
			case VIM:
				$urlVideo = '<iframe class="MultimediaVideo" src="http://player.vimeo.com/video/'.$datosVideo['multimediaidexterno'].'" frameborder="0" allowfullscreen></iframe>';	
			break;	
			case YOU:
				$urlVideo = '<iframe class="MultimediaVideo" src="http://www.youtube.com/embed/'.$datosVideo['multimediaidexterno'].'" frameborder="0" allowfullscreen></iframe>';	
			break;	
		}
		$conFoto = false;
	}
}


$conBajada = false;
if (isset($objDataModel->conBajada) && $objDataModel->conBajada=="on")
	$conBajada = true;

$conVinculadas = false;
if (isset($objDataModel->conVinculadas) && $objDataModel->conVinculadas=="on")
{	
	$oNoticiasNoticias = new cNoticiasNoticias($vars['conexion'],"");
	if(!$oNoticiasNoticias->BuscarNoticiasRelacionadasPublicadasxNoticia($datosnoticia,$resultadorel,$numfilasrel))
		die();
		
	if ($numfilasrel!=0)
		$conVinculadas = true;
}
$BordeInferior = false;
if (isset($objDataModel->BordeInferior) && $objDataModel->BordeInferior=="on")
	$BordeInferior = true;

unset($oNoticia);


$clase = "notaBomba";
$corteVolanta = 20;	
$anchoImg = 998;
if (isset($vars['width']) && $vars['width']!="")
	$anchoImg = $vars['width'];


?>
<div class="<?php  echo $clase?> tap_modules<?php  if ($BordeInferior) echo " borde_inferior"?> clearfix" id="module_<?php  echo$vars['zonamodulocod']?>" <?php  echo $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>	
        <?php  if ($conFoto){?>
            <div class="foto">
                <a href="<?php  echo $dominio?>"  target="<?php  echo $target?>" title="Ir a <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulobase,ENT_QUOTES)?>">
                    <img src="<?php  echo DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic($anchoImg, 0, $datosFoto['multimediacatcarpeta']."N/".$datosFoto['multimediaubic']);?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>" />
           		</a>
                <?php  if ($IconoVideo){?>
	                <div class="ico-player-home">
                        <a href="<?php  echo $dominio?>"  target="<?php  echo $target?>" title="Ir a <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulobase,ENT_QUOTES)?>">
                            &nbsp;
                        </a>
                    </div>
                <?php  }?>
                <div class="textosobrefoto <?php  echo $FondoTitulo?>">
                	<div class="cuerpo">
                    	<?php  if ( $conVolanta){?>
                            <div class="volanta">
                                	<?php  
									echo $volanta;
									?>
                             </div>
                             <div class="clearboth">&nbsp;</div>
                        <?php  }elseif ($conCategoria){?>
                             <div class="categoria" >
                                 	<?php  
								 	echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES);
									?>
                                    
                             </div>
                            <div class="clearboth">&nbsp;</div>
                        <?php  }?> 
                        <h2>
                            <a href="<?php  echo $dominio?>"  target="<?php  echo$target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulobase,ENT_QUOTES)?>" <?php  /*style=" <?php  if ($ColorTitulo!=""){ echo "color:".$ColorTitulo." !important;";}?><?php  if ($TamTitulo!=""){ echo "font-size:".$TamTitulo." !important;";}?>"*/?>>
                                <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
                            </a>
                        </h2>
                    </div>
                </div>
           </div>
        <?php  }else { //sin foto?>
        	<?php  if ( $conVolanta){?>
                            <div class="volanta">
                                	<?php  
									echo $volanta;
									?>
                             </div>
                             <div class="clearboth">&nbsp;</div>
           <?php  }elseif ($conCategoria){?>
                             <div class="categoria" >
                                 	<?php  
								 	echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($catnom,ENT_QUOTES);
									?>
                                    
                             </div>
                            <div class="clearboth">&nbsp;</div>
           <?php  }?> 
                <h2>
                    <a href="<?php  echo $dominio?>"  target="<?php  echo$target?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulobase,ENT_QUOTES)?>" style=" <?php  if ($ColorTitulo!=""){ echo "color:".$ColorTitulo." !important;";}?><?php  if ($TamTitulo!=""){ echo "font-size:".$TamTitulo." !important;";}?>">
                         <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($titulo,ENT_QUOTES)?>
                    </a>
                </h2>
        <?php  }?>
        	
        <?php  if ($conVinculadas){?>
        	<div class="clearboth">&nbsp;</div>
        	<div class="noticias_relacionadas">
        	<?php  
				$i=0;
				while ($fila = $vars['conexion']->ObtenerSiguienteRegistro($resultadorel))
				{
					if ($i>0)
						echo "&nbsp;&nbsp;&times;&nbsp;&nbsp;";
					$targetrel = "_self";
					$dominiorel = DOMINIORAIZSITE.$fila['catdominio']."/".$fila["noticiadominio"];
					if ($fila['noticiahrefexternorel']!="")
					{
						$dominiorel = $fila['noticiahrefexternorel'];
						$targetrel = "_blank";
					}
					?>
                    	<a href="<?php  echo $dominiorel?>" target="<?php  echo$targetrel?>" title="Ir a <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	<strong>
							<?php  }?>
								<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                        	<?php  if ($fila['noticiaimportante']==1){?>
                            	</strong>
							<?php  }?>
                        </a>
                    <?php  
					$i++;
				}
			?>
            </div>
	<div style="clear:both; height:0px; font-size:0px;"></div>
<?php  }?>    
</div>
