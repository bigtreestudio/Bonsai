<?php  

require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oGalerias = new cGalerias($conexion,"");




$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';


$galeriacod = "";
$nivelgaldesc = "Inicio";
$galsup = "";
$galsuperior = "";
$galeriatitulo ="";


$_SESSION['msgactualizacion'] = "";
$_SESSION['volver'] = "gal_galerias.php";

function CargarCategorias($arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option value="<?php  echo $fila['catcod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}


$oCategorias = new cCategorias($conexion);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();


?>
<link rel="stylesheet" type="text/css" href="modulos/gal_galerias/css/gal_galerias.css" />
<script type="text/javascript" src="modulos/gal_galerias/js/gal_galerias.js?v=1.1"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Galerias</h2>
</div>
<div class="form">
    <form action="gal_galerias.php" method="post" name="formbusqueda" id="formbusqueda">
        <div class="ancho_10">
        		<div class="ancho_3">
                <div class="ancho_4">
    	            <label>T&iacute;tulo:</label>
                </div>
                <div class="ancho_6">
	                <input type="text" name="galeriatitulo" id="galeriatitulo" class="full" onkeydown="doSearch(arguments[0]||event)" maxlength="255" value="<?php  echo $galeriatitulo?>" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
    	            <label>Categorias:</label>
                </div>
                <div class="ancho_6">
                   <select name="catcod" id="catcod" class="full" onchange="doSearch(arguments[0]||event)" >
                        <option value="">Todas</option>
                    <?php 
                        foreach($arbol as $fila)
                        {
                            ?>
                            <option value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                            <?php  
                            if (isset($fila['subarbol']))
                            {
                                $nivel = "---";
                                CargarCategorias($fila['subarbol'],$nivel);
                            }
                        }
                        ?>
                     </select>
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2">
    	            <label>Tipo:</label>
                </div>
                <div class="ancho_8">
	                <select name="multimediaconjuntocod" id="multimediaconjuntocod" class="full" onchange="doSearch(arguments[0]||event)" >
                        <option value="">Todas</option>
                        <option value="<?php echo FOTOS?>">Fotos</option>
                        <option value="<?php echo VIDEOS?>">Videos</option>
                    </select>    
                </div>
            </div>
            <div class="clear brisa">&nbsp;</div>
        </div>
        <input type="hidden" name="galeriaestadocod" id="galeriaestadocod" value="<?php  echo  ACTIVO.",".NOACTIVO?>"  />
	</form>
</div>
 
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left boton verde" href="gal_galerias_am.php" >Crear nueva Galeria</a></li>
    </ul>
</div>
<?php  
	$oGalerias->MostrarJerarquia($galeriacod,$jerarquia,$nivel);

?>

<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstGalerias" style="width:100%;">
    <table id="listarGalerias"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>