<?php  
include("./config/include.php");
include(DIR_CLASES."cGalerias.class.php");
include(DIR_CLASES."cMultimedia.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

//instancio un service para la galeria
$oGalerias = new cGalerias($conexion);

$datos['limit']="";
$oGalerias->CargarGaleriasFotos($datos,$resultado,$numfilas);



$oEncabezados->setTitle("Galerias de Fotos");
$oEncabezados->setDescription("Galerias de Fotos");
$oEncabezados->setOgTitle("Galerias de Fotos");
$oEncabezados->EncabezadoMenuEmergente();

?>
<div id="DetalleSecciones">
	<div class="leftcolumn">

        <div class="naranja txt_destacado">
            <div class="clearboth"></div>
            <h1>
                <a href="/galeria" title="Galer&iacute;as de Fotos">
                    Galer&iacute;as de Fotos
                </a>
            </h1>
        </div>    
        <div class="clearboth brisa_vertical">&nbsp;</div>
        <div class="lst_galeria clearfix">
            <ul>
                <?php  
				while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
				{
					$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['galeriatitulo'],ENT_QUOTES);
					$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.$fila['multimediacatcarpeta']."GALTHUMB/".$fila['multimediaubic'];
                    ?>
                    <li>
                        <a class="imagen_multimedia" href="<?php  echo $fila['galeriadominio']?>" title="<?php  echo $alt?>">
                            <img src="<?php  echo $imagen;?>" alt="<?php  echo $alt?>" />
                            <span class="zoom">&nbsp;</span>
                        </a>
                    </li>
                <?php  
                }?>
            </ul>
        </div>
        <div class="clearboth">&nbsp;</div>
</div>
	<div class="rightcolumn">
        <?php  include("col_derecha.php")?>
        <?php  include("col_derecha_bottom.php")?>
    </div>
    <div class="clearboth"></div>
</div>

<?php  
$oEncabezados->PieMenuEmergente();
?>