<?php  

$cantidad = 5;
$spnombre="sel_not_noticias_publicadas_seccion_dinamica";
$noticiacodNot = "-1";
$color="";

if ($vars['noticias']!="")
	$noticiacodNot = $vars['noticias'];

if (isset($vars['color']) && $vars['color']!="")
	$color = $vars['color'];

$sparam=array(
	'pcatcod'=> $vars['catcod'],
	'pnoticiacod'=> $noticiacodNot,
	'porderby'=> "noticiafecha DESC",
	'plimit'=> "LIMIT 0,".$cantidad
	);

$oMultimedia = new Multimedia($vars['conexion'],"");

if(!$vars['conexion']->ejecutarStoredProcedure($spnombre,$sparam,$resultadonoticias,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	return false;
}



$i=1;

while ($fila=$vars['conexion']->ObtenerSiguienteRegistro($resultadonoticias)){ ?>
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
<?php  }?>