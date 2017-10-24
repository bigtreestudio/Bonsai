<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oCategorias = new cCategorias($conexion);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();
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
?>


<link rel="stylesheet" type="text/css" href="modulos/not_noticias/css/noticias_relacionadas.css" />

<script type="text/javascript" src="modulos/not_noticias/js/noticias_relacionadas_buscar_popup.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	ListarNoticiasRelacionadasPopup();	
});

</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Noticias</h2>
</div>
<div class="clear fixalto">&nbsp;</div>

<form action="gal_albums_gal_galerias.php" method="post" name="formbusquedanoticiarelacionada"  class="general_form" id="formbusquedanoticiarelacionada" >
    <div class="ancho_10">
        <div class="ancho_10">
            <div class="ancho_2">
                <label>Titulo:</label>
            </div>
            <div class="ancho_8">
               <input name="noticiatitulo" id="noticiatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="120" value="" />
            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
       <div class="ancho_10">
            <div class="ancho_2">
                <label>Categoria:</label>
            </div>
            <div class="ancho_6">
               <select name="catcod" id="catcod" onchange="doSearch(arguments[0]||event)">
                        <option value="">Seleccionar una categoria...</option>
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
       <div class="clear fixalto">&nbsp;</div>
    </div>
    <input type="hidden" name="albumcod" id="albumcod" value="" />
</form>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstNoticiasRelacionadas" style="width:100%;">
    <table id="ListarNoticiasRelacionadas"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
?>
