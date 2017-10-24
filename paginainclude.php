<?
include(DIR_LIBRERIAS."cProcesarHTML.php");
$codigo = $oPaginas['pagcod'];

$class="onecolumn";
if ($oPaginas['muestramenu']==1)
	$class="leftcolumn";
$oEncabezados->setTitle($oPaginas['pagtitulo']);
$oEncabezados->setDescription(strip_tags($oPaginas['pagcopete']));
$oEncabezados->setOgTitle($oPaginas['pagtitulo']);
$oEncabezados->setPlantilla($oPaginas['planthtmlcod']);
$oEncabezados->EncabezadoMenuEmergente();


function CargarArbolMenu($codigo,$arbol)
{
	?><ul><?php  
		foreach ($arbol as $datospaginamenu){ 
		?>
			<li>
				<a <?php  if (array_key_exists($codigo,$datospaginamenu['hijos']) || $codigo == $datospaginamenu['datos']->getCodigo()) echo 'class="seleccionado"'?> href="<?php  echo DOMINIORAIZSITE?><?php  echo $datospaginamenu['datos']->getDominio();?>" title="Ir a <?php  echo $datospaginamenu['datos']->getTitulo();?>"><?php  echo $datospaginamenu['datos']->getTitulo();?></a>
				<?php  
				if (isset($datospaginamenu['subarbol']) && count($datospaginamenu['subarbol'])>0 && (array_key_exists($codigo,$datospaginamenu['hijos']) || $codigo == $datospaginamenu['datos']->getCodigo()))
					CargarArbolMenu($codigo,$datospaginamenu['subarbol']);
				?>
			</li>
			<?php  
		}
	?></ul><?php  
}

?>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/pagina.js"></script>
<div id="DetallePagina">
	<div class="<?php  echo $class?>">
        <h1><?php  echo $oPaginas['pagtitulo'];?></h1>
        <div class="copete">
        	<?php  if (trim($oPaginas['pagcopete'])!=""){?>
            	<?php  echo $oPaginas['pagcopete'];?>
                <div class="separadorcopete">&nbsp;</div>
            <?php  }?>
         </div>
         <div class="cuerpo">
            <?php  echo $oPaginas['pagcuerpoprocesado'];?>
        </div>    
    </div>
	<div class="rightcolumn">
            <div class="fondogris">
				<?php  
					$archivo = PUBLICA."paginas/pagina_".$codigo.".html";
					if (file_exists($archivo))
					{
						$oProcesarElementosDinamicosHTML = new cProcesarElementosDinamicosHTML($conexion);
						$html=file_get_contents($archivo);
						$oProcesarElementosDinamicosHTML->Procesar($html,$htmlprocesado);
						echo $htmlprocesado;
					}
                ?>
           </div>
    </div>    
    <div class="clearboth">&nbsp;</div>
</div>
<?
$oEncabezados->PieMenuEmergente();
?>