<?php  

$cantidad = 8;
$noticiacodNot = "-1";
if ($dataPostSend['noticias']!="")
	$noticiacodNot = $dataPostSend['noticias'];

$catdominio = $dataPostSend['dominio'];

$spnombre="sel_not_noticias_publicadas_seccion_dinamica_cantidad";
$sparam=array('pcatcod'=> $dataPostSend['catcod'], 'pnoticiacod'=> $noticiacodNot);
if(!$conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	die();
}
$cantidaTotal = 0;
if ($numfilas>0)
{
	$datosTotales = $conexion->ObtenerSiguienteRegistro($resultado);
	$cantidaTotal = $datosTotales['total'];			
}

$color="";
if (isset($dataPostSend['color']) && $dataPostSend['color']!="")
	$color = $dataPostSend['color'];

$spnombre="sel_not_noticias_publicadas_seccion_dinamica";
$catcod = $dataPostSend['catcod'];
$sparam=array(
	'pcatcod'=> $dataPostSend['catcod'],
	'pnoticiacod'=> $noticiacodNot,
	'porderby'=> "noticiafecha DESC",
	'plimit'=> "LIMIT 0,".$cantidad
	);

if(!$conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	die();
}

?><div class="noticias_lst"><?php 
$i=1;
while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){ ?>
	<?php        
	$tieneimg = false;
	if ($fila["video"]==1)
	{
		//cargo un objeto multimedia
		$tieneimg = true;
		$oMultimedia= new cMultimedia($conexion, $fila["multimediacatcarpeta"],"");
		$imagen=$oMultimedia->ArmarImagenVideo($fila["multimediatipocod"],$fila["multimediaidexterno"]);
	}
	else
	{
		if ($fila["foto"]==1 && $fila['multimediaubic']!="")
		{
			$tieneimg = true;
			$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(165, 0, $fila['multimediacatcarpeta']."/N/".$fila['multimediaubic']);
		}
	}
	$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);
    ?>
    <div class="notaDinamica clearfix">
    	<?php  if ($tieneimg){?>
            <div class="foto">
                <div class="imagen"><img src="<?php  echo $imagen;?>" alt="<?php  echo $alt?>" /></div>
                <?php  if ($fila["video"]==1){?>
	                <span class="video"></span>
                <?php  }?>
            </div>
        <?php  }?>
        <div class="fototitulo">
           <?php  if ($fila['noticiavolanta']!=""){?>
               <div class="volanta">
                    <span <?php  if ($color!=""){ ?>style="color:<?php  echo $color?>" <?php  }?> >
					<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiavolanta'],ENT_QUOTES)?>
                    </span>
               </div>
           <?php  }?>
           <h2 class="conFoto">
                <a href="<?php  echo DOMINIORAIZSITE?><?php  echo $fila['catdominio']."/".$fila['noticiadominio'];?>" title="Ir a la noticia <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>">
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)?>
                </a>
           </h2>
           <div class="copete">
               <p>
                <?php  echo strip_tags($fila['noticiacopete'],"<br><p><strong><b>")?>
               </p>
           </div>
          <div class="clearboth">&nbsp;</div>
        </div>   
    </div>            
<?php  }
   
if ($cantidaTotal>5){?>
	<div class="paginado clearfix" id="Paginado">
		<div class="clearboth">&nbsp;</div>
		<div class="cargandoresultados">
			<div class="cargandoicono">&nbsp;</div>
			<div class="textocargando">Cargando...</div>
			<div class="clearboth">&nbsp;</div>
		</div>
		<div class="botoninferior">
			<a href="<?php  echo DOMINIORAIZSITE?><?php  echo $catdominio?>/2" title="ver m&aacute;s noticias"  onclick="MasResultadosAjax('<?php  echo DOMINIORAIZSITE?><?php  echo $catdominio?>','<?php  echo $catcod?>','<?php  echo $noticiacodNot?>',2,'<?php  echo $color?>'); return false;">VER M&Aacute;S NOTICIAS</a>
		</div>    
	</div>    
<?php  }?>

</div>