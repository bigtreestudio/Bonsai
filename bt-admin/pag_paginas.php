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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oPaginas = new cPaginas($conexion,"");

$pagcod = "";
$pagtitulo = "";
$catcod = "";
$pagestadocod = "";
if (isset($_SESSION['datosbusquedafiltropagina']) && count($_SESSION['datosbusquedafiltropagina'])>0)
{
	$pagestadocod = $_SESSION['datosbusquedafiltropagina']['pagestadocod'];
	$pagcod = $_SESSION['datosbusquedafiltropagina']['pagcod'];
	$pagtitulo = $_SESSION['datosbusquedafiltropagina']['pagtitulo'];
	$catcod = $_SESSION['datosbusquedafiltropagina']['catcod'];
}
	
$oPaginasEstados = new cPaginasEstados($conexion);
$datos = array();
if(!$oPaginasEstados->ObtenerEstadosCantidades($datos,$resultadopermisos,$numfilaspermisos))
	return false;
	
	
function CargarCategorias($catnom,$arreglocategorias,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		 $catnom2 = $fila['catnom'];
		 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
			$catnom2 .="  (".$fila['estadonombre'].")";
		
		?>

        <option <? if (array_key_exists($fila['catcod'],$arreglocategorias)) echo 'selected="selected"'?>  value="<? echo $fila['catcod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom),ENT_QUOTES).$nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>
		<? 
		if (isset($fila['subarbol']) && count ($fila['subarbol'])>0)
		{
			$catnom = $catnom.html_entity_decode(" &raquo;&raquo; ").$catnom;
			CargarCategorias($catnom,$arreglocategorias,$fila['subarbol'],$nivel);
			//$nivel = substr($nivel,0,strlen($nivel)-strlen("&raquo;&raquo;"));
		}
	}
}



$pagcodsuperior="";	
?>
<link rel="stylesheet" type="text/css" href="modulos/pag_paginas/css/paginas.css" />
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/pag_paginas/js/paginas.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de P&aacute;ginas</h2>
</div>
<div class="form">
    <form action="pag_paginas.php" method="post" name="formbusqueda" id="formbusqueda">
		<div class="ancho_10">
            <div class="ancho_2">
                <div class="ancho_4" style="margin-top:5px">
    	            <label>Id de P&aacute;gina:</label>
                </div>
                <div class="ancho_6">
	                <input type="text" name="pagcod" id="pagcod" class="full" onkeydown="doSearch(arguments[0]||event)"  maxlength="10"  value="<?php  echo $pagcod?>" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_2" style="margin-top:5px">
    	            <label>Titulo:</label>
                </div>
                <div class="ancho_8">
	                <input type="text" name="pagtitulo" id="pagtitulo" class="full" onkeydown="doSearch(arguments[0]||event)" maxlength="100" value="<?php  echo $pagtitulo?>" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_4" >
                <div class="ancho_2" style="margin-top:5px">
    	            <label>Categor&iacute;a:</label>
                </div>
                <div class="ancho_8">
                	<?php
                        $oCategorias=new cPaginasCategorias($conexion);
                        $catsuperior="";
						$estadocombocat = "";
						if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol,$estadocombocat))
                            $mostrar=false;
						$arreglocategoriasSeleccionado = array();
						
					?>
                    <select name="catcod" id="catcod" onchange="doSearch(arguments[0]||event)" style="width:100%;">
                        <option value="">Todas</option>
                    <?php 
                        foreach($arbol as $fila)
                        {

							 $catnom2 =  $catnom =$fila['catnom'];
							 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
								$catnom2 .="  (".$fila['estadonombre'].")";	
							?>

                            <option <?php if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php echo $fila['catcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>

                            <?php 
                            if (isset($fila['subarbol']))
                            {
                                $nivel = " &raquo;&raquo; ";
                                CargarCategorias($catnom,$arreglocategoriasSeleccionado,$fila['subarbol'],$nivel);
                            }
                        } ?>
                     </select>
                </div>
            </div>
		</div>
        <div class="clear fixalto">&nbsp;</div>
        <input type="hidden" name="pagestadocod" value="<?php  echo  $pagestadocod?>" id="pagestadocod" />
        <input type="hidden" name="pagcodsuperior" id="pagcodsuperior" value="<?php  echo $pagcodsuperior?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="pag_paginas_am.php" title="Agregar p&aacute;gina">Agregar p&aacute;gina</a></li>
        <li><a class="states boton base <?php  if ($pagestadocod=="") echo "selected"?>" href="javascript:void(0)"  onclick="FilterStates(this,'');">Todos</a></li>
        <?php  
		$i=1;
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultadopermisos)) {
			$class="middle";
			if ($i==$numfilaspermisos)
				$class="right";
			
			$selected = "";
			if ($fila['pagestadocod']==$pagestadocod)
				$selected = " selected";
			
			$cantidad="";
			if ($fila['pagestadomuestracantidad'])
				$cantidad = " <span class='negro'>(".$fila['total'].")</span>";
			
			?>
	        <li><a class="boton base <?php  echo $selected?> states" href="javascript:void(0)" onclick="FilterStates(this,<?php  echo  $fila['pagestadocod']?>)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['pagestadodesc'],ENT_QUOTES).$cantidad?></a></li>
        <?php  
			$i++;
			
		}?>
    </ul>
</div>

<div class="clear aire_vertical">&nbsp;</div>

<div id="LstPaginas" style="width:100%;">
    <table id="ListarPaginas"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
    
<?php  
$oEncabezados->PieMenuEmergente();
?>