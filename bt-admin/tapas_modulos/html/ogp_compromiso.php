<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$compromisoTitulo="";
$Texto="";
$compromisocod="";
$multimediacod="";
$imgurl="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->compromisoTitulo))
		$compromisoTitulo = $objDataModel->compromisoTitulo;

	if (isset($objDataModel->Texto))
		$Texto = $objDataModel->Texto;
		
	if (isset($objDataModel->compromisocod))
		$compromisocod = $objDataModel->compromisocod;
	
	if ($compromisocod!=""){
		$oCompromisos= new cOgpCompromisos($vars['conexion']);
		$datos["compromisocod"]=$compromisocod;
		if($oCompromisos->BuscarxCodigo($datos,$resultadoCompromisos,$numfilas)) {
			if ($numfilas>0){
				$filaC=$vars['conexion']->obtenerSiguienteRegistro($resultadoCompromisos);
				if ($filaC["multimediaubic"]!="")
				{
					$oMultimedia = new Multimedia($conexion,"");
					$imgurl=DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(428, 170, "noticias/N/".$filaC['multimediaubic'], true, 1);
				}
				
				$dominio="";
				$dominio = FuncionesPHPLocal::EscapearCaracteres(utf8_decode(substr($filaC["compromisotitulo"],0,50)));
				$dominio=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominio));
				$dominio=str_replace(' ', '-', trim($dominio))."/comp".$filaC['compromisocod'];
			}
		}
	}
}
?>
<div class="compromiso_home_box wow zoomIn" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="foto_compromiso col-md-5 col-sm-5 col-xs-8">
         <img src="<? echo $imgurl?>"/>
    	  <div class="pie_imagen"></div>
     </div>
     <div class="foto_compromiso col-md-1 col-sm-1 col-xs-4">
           <div class="iconotema <? echo $filaC["temadominio"]?>">&nbsp;</div>
     </div>
     <div class="texto_compromiso col-md-6 col-sm-6 col-xs-12">
          <h3><a href="/ogp/<? echo $dominio?>" title="Ir a compromiso <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($compromisoTitulo),ENT_QUOTES)?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($compromisoTitulo),ENT_QUOTES)?></a></h3>
           <p><? echo utf8_decode($Texto)?></p>
     </div>
     <div class="clearboth nada">&nbsp;</div>
</div>
<?php  