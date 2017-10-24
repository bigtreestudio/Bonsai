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

$oGraficosFilas = new cGraficosFilas($conexion,"");
$oGraficosColumnas = new cGraficosColumnas($conexion,"");
$oGraficosValores = new cGraficosValores($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_POST;
if (!$oGraficosFilas->BuscarxGrafico ($datos,$resultadofil,$numfilasfil))
	die();


if (!$oGraficosColumnas->BuscarxGrafico ($datos,$resultadocol,$numfilascol))
	die();


if ($numfilasfil>0 && $numfilascol>0)
{
?>
<div style="width:100; overflow-x:auto">
<form action="gra_graficos.php" name="formulario_valores" id="formulario_valores" method="post">
<table class="data">
	<tr>
        <th>&nbsp;
            
        </th>
		<?php  
		$arreglodatos = array();
        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadocol))
        {
            ?>
                <th>
                	<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['columnatitulo'],ENT_QUOTES)?>
                </th>
            <?php  	
			$arreglodatos[] = $fila;
        }
        ?>
    </tr>    

		<?php  
        while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofil))
        {
			if (!$oGraficosValores->BuscarxGraficoxFila($fila,$resultadoval,$numfilasval))
				die();
			$arreglofilas = array();
			while($datavalores = $conexion->ObtenerSiguienteRegistro($resultadoval))
				$arreglofilas[$datavalores['columnacod']] = $datavalores['valor'];
			
            ?>
			<tr>
                <th>
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['filatitulo'],ENT_QUOTES)?>
                </th>
            <?php  	
			foreach($arreglodatos as $datos)
			{
				$valores = 0;
				if (array_key_exists($datos['columnacod'],$arreglofilas))
					$valores = $arreglofilas[$datos['columnacod']];
				?>
                    <td>
                        <input type="text" name="fila_<?php  echo $fila['filacod']."_".$datos['columnacod']?>" id="fila_<?php  echo $fila['filacod']."_".$datos['columnacod']?>" onchange="return GuardarDatosValores('<?php  echo  $fila['filacod']?>','<?php  echo  $datos['columnacod']?>','#fila_<?php  echo $fila['filacod']."_".$datos['columnacod']?>')" value="<?php  echo $valores?>" />
                    </td>
                <?php  	
			}?>
		    </tr><?php     
        }
        ?>
</table>
</form>
<br />
</div>
<div class="clearboth aire_menor">&nbsp;</div>
<div class="menubarra" >
     <ul>
        <li><a class="left" href="javascript:void(0)" onclick="PrevisualizarGrafico()">Previsualizar G&aacute;fico</a></li>
    </ul>
</div>
<div class="clearboth aire_menor">&nbsp;</div>

<div class="clear aire_vertical">&nbsp;</div>
<?php  
}
?>