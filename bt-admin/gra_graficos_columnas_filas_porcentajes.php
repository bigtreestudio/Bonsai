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
$oGraficosValores = new cGraficosValoresPorcentajes($conexion,"");

//print_r($_POST);
header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_POST;
if (!$oGraficosFilas->BuscarxGrafico ($datos,$resultadofil,$numfilasfil))
	die();


if ($numfilasfil>0)
{
?>
<div style="width:100; overflow-x:auto">
<form action="gra_graficos.php" name="formulario_valores" id="formulario_valores" method="post">
<table class="data">
	<tr>
        <th>Serie</th>
        <th>Valor</th>
    </tr>    

		<?php  

		if (!$oGraficosValores->BuscarxGrafico($datos,$resultadoval,$numfilasval))
			die();
		$arreglofilas = array();
		while($datavalores = $conexion->ObtenerSiguienteRegistro($resultadoval))
			$arreglofilas[$datavalores['filacod']] = $datavalores['valor'];
			
       while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofil))
        {
			$valores = 0;
			if (array_key_exists($fila['filacod'],$arreglofilas))
				$valores = $arreglofilas[$fila['filacod']];
           ?>
			<tr>
                <th>
                    <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['filatitulo'],ENT_QUOTES)?>
                </th>
                <td>
                    <input type="text" name="fila_<?php  echo $fila['filacod']?>" id="fila_<?php  echo $fila['filacod']?>" onchange="return GuardarDatosValores('<?php  echo  $fila['filacod']?>','#fila_<?php  echo $fila['filacod']?>')" value="<?php  echo $valores?>" />
                </td>
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