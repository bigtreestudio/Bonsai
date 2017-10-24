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


$oAgenda = new cAgenda($conexion);


$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = $_SESSION['msgactualizacion'];
//----------------------------------------------------------------------------------------- 	


function CargarCategorias($catcod,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option value="<?php  echo $fila['catcod']?>" <?php  if ($catcod==$fila['catcod']) echo 'selected="selected"'?>><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($catcod,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}

$oCategorias = new cAgendaCategorias($conexion);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();

$titulo = "Nuevo";

$agendacod="";
$agendatitulo = "";
$agendaobservaciones = "";
$agendabajada = "";
$boton = "Agregar";
$agendaestadocod = "";
$accion=1;
$esmodif= false;
$puedeeditar=true;
$catcod = "";
$agendafhasta=date("d/m/Y");
$agendafdesde=date("d/m/Y");
$boton="InsertarEvento()";
if (isset($_GET['agendacod'])) 
{
	$boton="ModificarEvento()";
	$titulo="Modificar";
	$agendacod=$_GET['agendacod'];
	$accion = 2;
	$esmodif = true;
	if (!$oAgenda->BuscarxCodigo ($_GET,$resultado,$numfilas))
		return false;
	
	$datosevento = $conexion->ObtenerSiguienteRegistro($resultado);
	$horainicio=$datosevento['agendafdesde']." ".$datosevento['horainicio'];
	$horafin=$datosevento['agendafhasta']." ".$datosevento['horafin'];
	$horainicio = date('D M j Y H:i:s',strtotime($horainicio));
	$horafin = date('D M j Y H:i:s',strtotime($horafin));
	
	$agendatitulo = $datosevento['agendatitulo'];
	$agendaestadocod = $datosevento['agendaestadocod'];
	$agendabajada = str_replace('<div class="space">&nbsp;</div>','<p></p>',$datosevento['agendabajada']);
	$agendaobservaciones = str_replace('<div class="space">&nbsp;</div>','<p></p>',$datosevento['agendaobservaciones']);
	$catcod = $datosevento['catcod'];
	$agendafdesde  = FuncionesPHPLocal::ConvertirFecha($datosevento['agendafdesde'],"aaaa-mm-dd","dd/mm/aaaa");
    $agendafhasta  = FuncionesPHPLocal::ConvertirFecha($datosevento['agendafhasta'],"aaaa-mm-dd","dd/mm/aaaa");
}else //nuevo turno
{
	$horainicio="";
	$horafin="";
	
}


?>
<link href="modulos/age_agenda/css/agenda.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="modulos/age_agenda/js/age_agenda_am.js"></script>

<div class="form" style="text-align:left;">

        <div class="ancho_5">
        <form  method="post" class="general_form" name="formnuevoevento" id="formnuevoevento">
                <div>
                     <h3 style="font-size:14px; font-weight:bold">Horario del Evento</h3>
                </div>
                <div class="ancho_10" style="margin-top:10px;">
                    <div class="ancho_5">
                        <div class="ancho_3">
                                <label>Fecha inicio:</label>
                        </div>
                        <div class="ancho_4">
                            <input type="text" name="agendafdesde" id="agendafdesde"  value="<?php  echo $agendafdesde?>" size="10" maxlength="10">
                            <span class='textonombredatos' style="font-size:8px;">&nbsp;(DD/MM/AAAA) </span>
                        </div>
                    </div>
                    <div class="ancho_5">
                        <div class="ancho_3">
                                <label>Fecha fin:</label>
                        </div>
                        <div class="ancho_4">
                            <input type="text" name="agendafhasta" id="agendafhasta"  value="<?php  echo $agendafhasta?>" size="10" maxlength="10">
                            <span class='textonombredatos' style="font-size:8px;">&nbsp;(DD/MM/AAAA) </span>
                        </div>
                    </div>
                    <div style="clear:both; height:1px;">&nbsp;</div>
                <div class="ancho_10" style="margin-top:10px;">
                    <div class="ancho_5">
                        <div class="ancho_3">
                                <label>Hora de inicio:</label>
                        </div>
                        <div class="ancho_2" >
                                <select name="horainicio" id="horainicio" onchange="ModificarHorario(this)">
                                    <?php  for ($i=0; $i<24; $i++){ 
                                    $hora = str_pad($i,2,0,STR_PAD_LEFT);
                                    ?>
                                        <option <?php  if (date("H",strtotime($horainicio))==$hora) echo 'selected="selected"'?>  name="<?php  echo $hora?>"><?php  echo $hora?></option>
                                    <?php  }?>
                                </select>
                        </div>
                        <div class="ancho_2">
                                <select name="minutosinicio" id="minutosinicio" onchange="ModificarHorario(this)">
                                    <?php  for ($i=0; $i<60; $i++){ 
                                    $minutos = str_pad($i,2,0,STR_PAD_LEFT);
                                    ?>
                                        <option <?php  if (date("i",strtotime($horainicio))==$minutos) echo 'selected="selected"'?>  name="<?php  echo $minutos?>"><?php  echo $minutos?></option>
                                    <?php  }?>
                                </select>Hs.
                        </div>
                    </div>
                    <div class="ancho_5">
                        <div class="ancho_3">
                                <label>Hora de fin:</label>
                        </div>
                        <div class="ancho_2">
                                <select name="horafin" id="horafin" >
                                    <?php  for ($i=0; $i<24; $i++){ 
                                    $hora = str_pad($i,2,0,STR_PAD_LEFT);
                                    ?>
                                        <option <?php  if (date("H",strtotime($horafin))==$hora) echo 'selected="selected"'?>  name="<?php  echo $hora?>"><?php  echo $hora?></option>
                                    <?php  }?>
                                </select>
                        </div>
                        <div class="ancho_2">
                                <select name="minutosfin" id="minutosfin">
                                    <?php  for ($i=0; $i<60; $i++){ 
                                    $minutos = str_pad($i,2,0,STR_PAD_LEFT);
                                    ?>
                                        <option <?php  if (date("i",strtotime($horafin))==$minutos) echo 'selected="selected"'?>  name="<?php  echo $minutos?>"><?php  echo $minutos?></option>
                                    <?php  }?>
                                </select>Hs.
                        </div>
                    </div>
                    <div style="clear:both; height:1px;">&nbsp;</div>
                    <div class="ancho_10" style="margin-top:10px;">
                        <div class="ancho_5">
                            <div>
                                <label>Categoria:</label>
                            </div>
                            <div style="clear:both; height:5px;">&nbsp;</div>
                            <div class="ancho_4">
                                <select name="catcod" id="catcod" >
                                    <option value="">Seleccione una categoria</option>
                                <?php 
                                    foreach($arbol as $fila)
                                    {
                                        ?>
                                        <option value="<?php  echo $fila['catcod']?>" <?php  if ($catcod==$fila['catcod']) echo 'selected="selected"'?> ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                                        <?php  
                                        if (isset($fila['subarbol']))
                                        {
                                            $nivel = "---";
                                            CargarCategorias($catcod,$fila['subarbol'],$nivel);
                                        }
                                    }
                                    ?>
                                 </select>
                            </div>
                         </div>
                         <div class="ancho_5">
                                <div>
                                        <label>Estado:</label>
                                </div>
                                <div style="clear:both; height:5px;">&nbsp;</div>
                                <div>
                                      <?php   
                                       $sparam=array("pagendaagendaestado"=>ACTIVO);
                                       FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_age_agenda_estados_activos",$sparam,"formulario","agendaestadocod","agendaestadocod","agendaestadodesc",$agendaestadocod,"Seleccione un estado...",$regactual,$seleccionado,1,"","width: 200px",false,false);
                                      ?>
                                </div>
                         </div>   
                    </div>    
                    <div style="clear:both; height:1px;">&nbsp;</div>
                    <div class="ancho_10" style="margin-top:10px;">
                        <div>
                                <label>Titulo:</label>
                        </div>
	                    <div style="clear:both; height:5px;">&nbsp;</div>
                        <div>
                                 <input type="text" name="agendatitulo" id="agendatitulo" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($agendatitulo,ENT_QUOTES)?>" class="full"  maxlength="255" size="30"  />
                        </div>
	                    <div style="clear:both; height:10px;">&nbsp;</div>
                        <div>
                            <label>Bajada:</label>
                        </div>
	                    <div style="clear:both; height:5px;">&nbsp;</div>
                        <div>
                            <textarea name="agendabajada"  id="agendabajada" class="textarea full rich-text" rows="13"><?php  echo $agendabajada?></textarea>
                        </div>
	                    <div style="clear:both; height:10px;">&nbsp;</div>
                        <div>
                            <label>Descripci&oacute;n:</label>
                        </div>
	                    <div style="clear:both; height:5px;">&nbsp;</div>
                        <div>
                            <textarea name="agendaobservaciones"  id="agendaobservaciones" class="textarea full rich-text" rows="13"><?php  echo $agendaobservaciones?></textarea>
                        </div>
                    </div>    
                </div>
                <div style="clear:both; height:10px;">&nbsp;</div>
                <div class="ancho_10">
                    <div class="menubarra">
                         <ul>
                            <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $boton?>">Guardar</a></li>
                    		<li class="states botonesaccion"><a class="left boton base" href="age_agenda.php">Volver sin Guardar</a></li>
                        </ul>
                    </div>
                </div>   
                <div class="clearboth">&nbsp;</div>
              </div> 
             <input type="hidden" name="agendacod" id="agendacod" value="<?php  echo $agendacod?>"  />
             <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>"  />
       		 <input type="hidden" name="agendaedit" id="agendaedit" value="<?php  echo $esmodif?>" />
         </form>  
         
    </div>  
	<div class="ancho_05">&nbsp;</div>
    <div class="ancho_4">
     <div class="datosextrapagina">
        <?php  if ($puedeeditar){ 
            $oMultimediaFormulario = new cMultimediaFormulario($conexion,"AGE",$agendacod);
            echo $oMultimediaFormulario->CargarBotonera();
          } ?> 

        <div class="multimedia">
            <?php  echo $oMultimediaFormulario->CargarListado();?>
        </div>
      </div>
   </div>

 
    <div class="clearboth" style="height:1px; font-size:1px;">&nbsp;</div>
</div>

<?php  
 $oEncabezados->PieMenuEmergente();
?>
