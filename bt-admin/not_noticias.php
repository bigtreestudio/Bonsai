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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oNoticias = new cNoticias($conexion);

$_SESSION['volveratras'] = "noticias_lst.php";


$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$noticiacod = "";
$noticiatitulo = "";
$catcod = "";
$noticiafecha = "";
$noticiaestadocod = NOTBORRADOR;
if (isset($_SESSION['datosbusquedafiltro']) && count($_SESSION['datosbusquedafiltro'])>0)
{
	$noticiaestadocod = $_SESSION['datosbusquedafiltro']['noticiaestadocod'];
	$noticiacod = $_SESSION['datosbusquedafiltro']['noticiacod'];
	$noticiatitulo = $_SESSION['datosbusquedafiltro']['noticiatitulo'];
	$noticiafecha = $_SESSION['datosbusquedafiltro']['noticiafecha'];
	$catcod = $_SESSION['datosbusquedafiltro']['catcod'];
}

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


$oNoticiasEstados = new cNoticiasEstados($conexion);
$datos['usuariocod'] = $_SESSION['usuariocod'];
if(!$oNoticiasEstados->ObtenerEstadosCantidadesxUsuario($datos,$resultadopermisos,$numfilaspermisos))
	return false;
	
?>
<script type="text/javascript" src="modulos/not_noticias/js/noticias.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Noticias</h2>
</div>
<div class="form">
    <form action="noticias_lst.php" method="post" name="formbusqueda" id="formbusqueda">
		<div class="ancho_10">
            <div class="ancho_3">
                <div class="ancho_4">
    	            <label>ID/COD:</label>
                </div>
                <div class="ancho_6">
	                <input type="text" name="noticiacod" id="noticiacod" class="full" onkeydown="doSearch(arguments[0]||event)"  maxlength="10"  value="<?php  echo $noticiacod?>" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
    	            <label>Categorias:</label>
                </div>
                <div class="ancho_6">
                    <select name="catcod" id="catcod" onchange="doSearch(arguments[0]||event)">
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
    	            <label>Fecha:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="noticiafecha" id="noticiafecha" class="large" onkeydown="doSearch(arguments[0]||event)" maxlength="100" value="<?php  echo $noticiafecha?>" />
                </div>
            </div>
            <div class="clear brisa">&nbsp;</div>
             <div class="ancho_6">
                <div class="ancho_2">
    	            <label>Titulo:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="noticiatitulo" id="noticiatitulo" class="full" onkeydown="doSearch(arguments[0]||event)" maxlength="100" value="<?php  echo $noticiatitulo?>" />
                </div>
            </div>
        </div>
        <div class="clear fixalto">&nbsp;</div>
		<div class="ancho_10">
            
		</div>
        <div class="clear fixalto">&nbsp;</div>
        <input type="hidden" name="noticiaestadocod" value="<?php  echo  $noticiaestadocod?>" id="noticiaestadocod" />
	</form>
</div>

<div class="clear brisa">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class=" boton verde" href="not_noticias_am.php">Crear nueva noticia</a></li>
        <li><a class="left boton base" style="margin:0 5px 0 0px" href="javascript:void(0)" onclick="FilterStates(this,'');">Todos</a></li>
        <?php  
		$i=1;
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultadopermisos)) {
			$class="middle";
			if ($i==$numfilaspermisos)
				$class="";
			
			$selected = "";
			if ($fila['noticiaestadocod']==$noticiaestadocod)
				$selected = " selected";
			
			$cantidad="";
			if ($fila['noticiaestadomuestracantidad'])
				$cantidad = " <span class='negro'>(".$fila['total'].")</span>";
			
			?>
	        <li><a class=" boton base <?php  echo $selected?> states " href="javascript:void(0)" onclick="FilterStates(this,<?php  echo  $fila['noticiaestadocod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaestadodesc'],ENT_QUOTES).$cantidad?></a></li>
        <?php  
			$i++;
			
		}?>
    </ul>
</div>
    <div class="clearboth">&nbsp;</div>

<div id="LstNoticias" style="width:100%;">
    <table id="ListadoNoticias"></table>
    <div id="pager2"></div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>