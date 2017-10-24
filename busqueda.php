<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cBusqueda.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

if (!isset($_GET['search']))
	$_GET['search'] = "";
	
if(strlen($_GET['search'])>200)
	die();

$q = "";
if (isset($_GET['search']))
	$q = $_GET['search'];
	
	
$oEncabezados->setTitle( FuncionesPHPLocal::HtmlspecialcharsBigtree($q,ENT_QUOTES));
$oEncabezados->setOgTitle( FuncionesPHPLocal::HtmlspecialcharsBigtree($q,ENT_QUOTES));
$oEncabezados->EncabezadoMenuEmergente();	
?>
<div id="DetalleBusqueda">
	<div id="NoticiasLst" class="clearfix">
		<div class="leftcolumn">
			<h1>B&uacute;squeda</h1>
			<div class="cantidadResultados">
				<span class="fondoiconos iconobuscador">&nbsp;</span>
                Se han encontrado los siguientes resultados que contienen el(los) t&eacute;rmino(s) <b><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($q,ENT_QUOTES)?></b>
			</div>
            <div class="noticias_lst">
				<?php 
                   if (strlen(trim($q))>0)
					   include("busqueda_lst_ajax.php");
				   else
				   {
						?>
                        	<div class="sindatos">Debe ingresar al menos un t&eacute;mino de b&uacute;squeda</div>
                        <?php     
				   }   
                ?>
            </div>
		</div>
		<div class="rightcolumn">
			<?php  include("caja_agenda.php");?>
		</div>
	</div>
</div>
<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>