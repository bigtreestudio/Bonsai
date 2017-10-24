<?php  
include("./config/include.php");
include(DIR_CLASES."cNoticias.class.php");
include(DIR_CLASES."cNoticiasCategorias.class.php");
include(DIR_CLASES."cPortadas.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

header('Content-Type: text/html; charset=ISO-8859-15'); 

if (!isset($_GET['catcod']) || $_GET['catcod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['catcod'],"NumericoEntero"))
	die();

$color = "";
if (isset($_POST['color']))
	$color = $_POST['color'];
	
$catcod = $_GET['catcod'];
$noticiacodNot = "-1";	
if (isset($_POST['noticias']) && $_POST['noticias']!="" && $_POST['noticias']!="-1")
{
	$noticias = explode(",",$_POST['noticias']);
	foreach($noticias as $noticia)
	{
		if (!FuncionesPHPLocal::ValidarContenido($conexion,$noticia,"NumericoEntero"))
			die();
	}
	$noticiacodNot = $_POST['noticias'];
}	
$cantidad = 8;
$inicio = 0;

$paginaactual=1;
if (isset($_GET['pagina']) && $_GET['pagina']!='')
{
	if(strlen($_GET['pagina'])>10)
		die();
		
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagina'],"NumericoEntero"))
		die();
		
	$inicio = $cantidad*($_GET['pagina']-1);
	$paginaactual=$_GET['pagina'];
}


$catdominio = $_GET['dominio'];

$spnombre="sel_not_noticias_publicadas_seccion_dinamica_cantidad";
$sparam=array('pcatcod'=> $catcod, 'pnoticiacod'=> $noticiacodNot);
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

$spnombre="sel_not_noticias_publicadas_seccion_dinamica";
$sparam=array(
	'pcatcod'=> $catcod,
	'pnoticiacod'=> $noticiacodNot,
	'porderby'=> "noticiafecha DESC",
	'plimit'=> "LIMIT ".$inicio.", ".$cantidad
	);
if(!$conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultadonoticias,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al cargar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
	return false;
}

$cantpaginas=round($cantidaTotal/$cantidad);
$paginasiguiente=1;

if (isset($_GET['pagina']) && $_GET['pagina']!='')
{
	$paginasiguiente = $paginaactual+1;	
}



while($fila = $conexion->ObtenerSiguienteRegistro($resultadonoticias)){ ?>
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
                <div class="imagen">
                    <img src="<?php  echo $imagen;?>" alt="Im&aacute;gen de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($alt,ENT_QUOTES)?>" />
                </div>
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
                <?php  echo strip_tags($fila['noticiacopete'],"<br><strong><b>")?>
               </p>
           </div>
          <div class="clearboth">&nbsp;</div>
        </div>   
    </div>            
<?php  }
if ($paginaactual<$cantpaginas){?>
	<div class="paginado clearfix" id="Paginado">
		<div class="clearboth">&nbsp;</div>
		<div class="cargandoresultados">
			<div class="cargandoicono">&nbsp;</div>
			<div class="textocargando">Cargando...</div>
			<div class="clearboth">&nbsp;</div>
		</div>
		<div class="botoninferior">
			<a href="<?php  echo DOMINIORAIZSITE?><?php  echo $catdominio?>/<?php  echo $catcod?>/<?php  echo $paginasiguiente?>" title="VER M&Aacute;S NOTICIAS" onclick="MasResultadosAjax('<?php  echo DOMINIORAIZSITE?><?php  echo $catdominio?>','<?php  echo $catcod?>','<?php  echo $noticiacodNot?>',<?php  echo $paginasiguiente?>,'<?php  echo $color?>'); return false;">VER M&Aacute;S NOTICIAS</a>
		</div>    
	</div>    
<?php  }?>