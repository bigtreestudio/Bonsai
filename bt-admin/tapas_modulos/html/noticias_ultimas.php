<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);

$CantidadTotal = 5;
$catcod="";
$Titulo = "Noticias";

if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->CantidadTotal) && is_int((int)$objDataModel->CantidadTotal))
		$CantidadTotal = $objDataModel->CantidadTotal;
		
	if (isset($objDataModel->catcod) && is_int((int)$objDataModel->catcod))
		$catcod = $objDataModel->catcod;

	if (isset($objDataModel->Titulo))
		$Titulo = utf8_decode($objDataModel->Titulo);
}
if ($catcod=="")//muestro las ultimas noticias de todas las secciones
{
	$archivo="/xmlmobile/ultimasnoticias.xml";
}
else
{
	//busco el dominio de la categoria seleccionada
	$oCategorias=new cCategorias($vars['conexion']);
	$datosP=array('catcod'=>$catcod);
	if (!$oCategorias->BuscarxCodigo($datosP,$resultadoP,$numfilasP))
	{
		die("Error");
	}
	$filaCat = $vars['conexion']->ObtenerSiguienteRegistro($resultadoP);
	$catnom=$filaCat["catnom"];
	//obtengo el nombre del archivo rss
	$nombrecat = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), utf8_encode($catnom)));
	$nombrecat=preg_replace('/[^a-zA-Z0-9-_ ]/', '', trim($nombrecat));
	$nombrecat=str_replace(' ', '', trim($nombrecat));
	$archivo="/xmlmobile/".strtolower($nombrecat).".xml";
}
?>
<div class="moduloultimasnoticias tap_modules clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="pestanias">
    	<ul>
        	<li class="masleidas seleccionada">MAS LEIDAS</li>
        </ul>
    </div>
    <div class="cuerpo">
        <div class="tituloultimasnoticias">
            <h2><?php  echo $Titulo?>&nbsp;<span class="icononoticias"></span></h2>
        </div>
        <div class="clearboth brisa"></div>
        <div class="ultimasnoticiasSite clearfix" id="ultimasnoticias_<?php  echo $vars['zonamodulocod']?>">

        </div>
     </div>
	<script language="javascript">
        $("#ultimasnoticias_<?php  echo $vars['zonamodulocod']?>").LoadNoticias({file: '<?php  echo $archivo?>', cantidad:'<?php  echo $CantidadTotal?>'});
    </script>
     
</div>
<?php  ?>