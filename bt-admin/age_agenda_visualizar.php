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

$oAgenda = new cAgenda($conexion);


$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = $_SESSION['msgactualizacion'];
//----------------------------------------------------------------------------------------- 	
header('Content-Type: text/html; charset=iso-8859-1'); 


$datos = $_POST;
$datos['fechainicio'] = date('Y-m-d',strtotime($datos['start']));
$datos['limit'] = "LIMIT 0,20";
if(!$oAgenda->BuscarAgendaBusquedaFechaMayor($datos,$resultado,$numfilas))
	return false;

$fecha = FuncionesPHPLocal::ConvertirFecha($datos['fechainicio'],"aaaa-mm-dd","dd/mm/aaaa");
$fechaanterior = date ( 'r', strtotime ( '-7 day' . $datos['fechainicio'] ) );
$fechasiguiente = date ( 'r', strtotime ( '+7 day' . $datos['fechainicio'] ) );

?>
<h3 style="font-weight:bold; font-size:14px; margin-bottom:5px;">Desde la fecha <?php  echo $fecha;?></h3>
<div class="tableContainer">
<table class="scrollTable" border="0" width="100%" cellspacing="0" cellpadding="0">
	<thead class="fixedHeader">
    <tr  class="alternateRow">
        <th>
        	Evento
        </th>
        <th>
        	Fecha Inicio
        </th>
        <th>
        	Fecha Fin
        </th>
    </tr>
    </thead>
    <tbody class="scrollContent">
    <?php  while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)){?>
        <tr class="normalRow">
            <td><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['agendatitulo'],ENT_QUOTES)?></td>
            <td><?php  echo FuncionesPHPLocal::ConvertirFecha($fila['agendafdesde'],"aaaa-mm-dd","dd/mm/aaaa")?> - <?php  echo $fila['horainicio'];?>Hs.</td>
            <td><?php  echo FuncionesPHPLocal::ConvertirFecha($fila['agendafhasta'],"aaaa-mm-dd","dd/mm/aaaa")?> - <?php  echo $fila['horafin'];?>Hs.</td>
        </tr>
    <?php  }?>
    </tbody>
</table>
</div>
<div style="clear:both;">&nbsp;</div>
<div style="float:left; width:120px; text-align:left">
	<a href="javascript:void(0)" onclick="CargarEventos('<?php  echo $fechaanterior?>')" style="color:#000; font-weight:bold;">&laquo; 7 d&iacute;as menos</a>
</div>
<div style="float:right; width:120px; text-align:right;">
	<a href="javascript:void(0)" onclick="CargarEventos('<?php  echo $fechasiguiente?>')" style="color:#000; font-weight:bold;">7 d&iacute;as m&aacute;s &raquo;</a>
</div>
<div style="clear:both;">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
//$oEncabezados->PieModal();
?>