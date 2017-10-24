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
$_SESSION['volver'] ="age_agenda.php"; 

$oAgenda= new cAgenda($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla


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

$oCategorias = new cAgendaCategorias($conexion);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();

?>
<link rel="stylesheet" type="text/css" href="modulos/age_agenda/css/agenda.css" />
<script type="text/javascript" src="modulos/age_agenda/js/agenda.js"></script>
<?php 

$_SESSION['msgactualizacion'] = "";
$agendatitulo="";
$agendacod="";
?>
<div class="form">

<form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2>Agenda</h2>
    </div>
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Título:</label>
                </div>
                <div class="ancho_6">
                   <input name="agendatitulo" id="agendatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
                </div>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
    
                <div class="ancho_4">
                <label>Categoria:</label>
                </div>
                  <div class="ancho_6">
    
                <select name="catcod" id="catcod" style="width:90%" onchange="doSearch(arguments[0]||event)">
                    <option value="">Todas...</option>
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
    
        <div class="clearboth" style="height:1px;">&nbsp;</div>    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>


   	</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
    	<li><a class="left boton verde" href="age_agenda_alta.php">Crear nuevo Evento</a></li>
    </ul>
</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstEvento" style="width:100%;">
       <table id="listarEvento"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>