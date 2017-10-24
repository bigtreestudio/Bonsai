<? 
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

$_SESSION['usuariocod']=1;

$oStored=new cStored($conexion);
if (!$oStored->TraerTablas(BASEDATOS,$query))
	$result=false;

$tablas = array();
if (!$oStored->TraerCampos($_POST['tabla'],$resultado))
	$result=false;

while($fila = $conexion->ObtenerSiguienteRegistro($query))
	$tablas[] = $fila;
$Tables="Tables_in_".BASEDATOS;

$tipo = 1;	
if (isset($_POST['tipo']))
	$tipo = $_POST['tipo'];
	
switch ($tipo){
	case 1:
		?>
		<option value="">Seleccione un campo...</option>
		<? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?>	
				<option value="<? echo $fila['Field']?>"><? echo $fila['Field']?><? echo ($fila['Key']=="PRI")?" (PRIMARY)":""?></option>
			<? 
		}
		break;
	case 2:
		?>
            <div class="clearboth" style="margin:2px 0;">&nbsp;</div>
            <div class="col-md-1">
                <label>&nbsp;</label>
            </div>
            <div class="col-md-3">
                <label style="border-bottom:1px solid #000;">Campo</label>
            </div>
            <div class="col-md-4">
                <label style="border-bottom:1px solid #000;">Desc Campo</label>
            </div>
            <div class="col-md-4">
                <label style="border-bottom:1px solid #000;">Tipo</label>
            </div>
            <div class="clearboth">&nbsp;</div>
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?><div class="form-group clearfix">
            <div class="col-md-1">
                <input type="checkbox" name="camposbusquedaavanzada[]" id="camposBusquedaAvanzada_<? echo $fila['Field']?>" value="<? echo $fila['Field']?>" />
            </div>
            <div class="col-md-3">
                <label for="camposBusquedaAvanzada_<? echo $fila['Field']?>"><? echo $fila['Field']?></label>
            </div>
            <div class="col-md-4">
                <input type="text" class="full" name="nombrecampobusqueda_<? echo $fila['Field']?>" value="" /> 
            </div>
            <div class="col-md-4">
                <select name="busquedaavanzada_<? echo $fila['Field']?>"> 
                    <option value="0">Igual</option>
                    <option value="1">Like</option>
                    <option value="2">In</option>
                </select>
            </div>
            <div class="clearboth"></div>
            </div>
            <? 
		}
		break;
	case 3:
		?>
            <div class="clearboth" style="margin:2px 0;">&nbsp;</div>
            <div class="col-md-1">
                <label>&nbsp;</label>
            </div>
            <div class="col-md-5">
                <label style="border-bottom:1px solid #000;">Campo</label>
            </div>
            <div class="col-md-6">
                <label style="border-bottom:1px solid #000;">Tit Columna</label>
            </div>
            <div class="clearboth">&nbsp;</div>
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?><div class="form-group clearfix">
                <div class="col-md-1">
                    <input type="checkbox"  id="camposListadoAvanzada_<? echo $fila['Field']?>" name="camposListadoAvanzada[]" value="<? echo $fila['Field']?>" />
                </div>
                <div class="col-md-5">
                    <label for="camposListadoAvanzada_<? echo $fila['Field']?>"><? echo $fila['Field']?></label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="nombrecamposlistado_<? echo $fila['Field']?>" value="" /> 
                </div>
                <div class="clearboth"></div>
              </div>  
            <? 
		}
		break;
	case 4:
		?>
            <div class="clearboth" style="margin:2px 0;">&nbsp;</div>
            <div class="col-md-3">
                <label style="border-bottom:1px solid #000;">Campo</label>
            </div>
            <div class="col-md-9">
                <label style="border-bottom:1px solid #000;">Desc Error</label>
            </div>
            <div class="clearboth">&nbsp;</div>
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($fila['Field']!="ultmodfecha" && $fila['Field']!="ultmodusuario")
			{
			?><div class="form-group clearfix">
                <div class="col-md-3">
                    <label><? echo $fila['Field']?></label>
                </div>
                <div class="col-md-9">
                    <input type="text"  class="form-control form-control-lg" name="camposerrores_<? echo $fila['Field']?>" value="Debe ingresar un" /> 
                </div>
              </div>  
            <? 
			}
		}
		break;
		
		
	case 5:
		?>
            <div class="clearboth" style="margin:2px 0;">&nbsp;</div>
            <div class="col-md-1">
                <label>&nbsp;</label>
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label>
            </div>
            <div class="col-md-3">
                <label style="border-bottom:1px solid #000;">Campo</label>
            </div>
            <div class="col-md-3">
                <label style="border-bottom:1px solid #000;">Tipo</label>
            </div>
            <div class="col-md-4">
                <label style="border-bottom:1px solid #000;">Desc</label>
            </div>
            <div class="clearboth">&nbsp;</div>
            <div class="ordenCampos">
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		{
			?>
            	<div id="campoorden_<? echo $fila['Field']?>"  class="sortable_lst" style="background-color:#CCC; padding:5px; margin-bottom:5px;">
                    <div class="col-md-1">
                        <div class="anchoorden orden" style="cursor:move">
                            <div style="text-align:left">
                                <img src="images/up.png" alt="Ordenar" />
                            </div>
                            <div style="text-align:left">
                                <img src="images/down.png" alt="Ordenar" />
                            </div>
                        </div>     
                    </div>                   
                    <div class="col-md-1">
                        <input type="checkbox" name="campoalta[]" id="campoalta_<? echo $fila['Field']?>" value="<? echo $fila['Field']?>" />
                    </div>
                    <div class="col-md-3">
                        <label for="campoalta_<? echo $fila['Field']?>"><? echo $fila['Field']?></label>
                    </div>
                    <div class="col-md-3">
                        <select name="camposaltatipo_<? echo $fila['Field']?>" id="camposaltatipo_<? echo $fila['Field']?>" onchange="VerificarTipoCampo(this,'<? echo $fila['Field']?>')"> 
                            <option value="0">Texto (input)</option>
                            <option value="1">Texto (textarea)</option>
                            <option value="2">Texto TinyMce</option>
                            <option value="3">Texto TinyMce (Arroba)</option>
                            <option value="4">Fecha</option>
                            <option value="5">Radio Si/No</option>
                            <option value="6">Combo Si/No</option>
                            <option value="7">Oculto (hidden)</option>
                            <option value="8">Multimedia Foto</option>
                            <option value="9">Multimedia Video</option>
                            <option value="10">Multimedia Audio</option>
                            <option value="11">Multimedia Archivo</option>
                            <option value="12">Combo (Tabla Externa)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="full" name="camposaltadesc_<? echo $fila['Field']?>" value="" /> 
                    </div>
                    <div class="clearboth"></div>
                    <div id="tablaExterna_<? echo $fila['Field']?>" style="display:none">
                        <div class="col-md-3">
                        	<label for="tabla_<? echo $fila['Field']?>">Tabla</label>
                            <select name="tabla_<? echo $fila['Field']?>" class="form-control form-control-lg chzn-select" id="tabla_<? echo $fila['Field']?>"  onchange="BuscarCamposTabla('<? echo $fila['Field']?>')">
                                <option value="">Seleccione un tabla...</option>
                                <? foreach($tablas as $filaTabla){?>
                                    <option value="<? echo $filaTabla[$Tables]?>"><? echo $filaTabla[$Tables]?></option>
                                <? }?>
                            </select>
                        </div>
                        <div class="col-md-3">
                        	<label for="campofk_<? echo $fila['Field']?>">Campo FK</label>
                            <select name="campofk_<? echo $fila['Field']?>" id="campofk_<? echo $fila['Field']?>" class="form-control form-control-lg">
                                <option value="">Sin datos...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                        	<label for="campodesc_<? echo $fila['Field']?>">Campo Descripci&oacute;n</label>
                            <select name="campodesc_<? echo $fila['Field']?>" id="campodesc_<? echo $fila['Field']?>" class="form-control form-control-lg">
                                <option value="">Sin datos...</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                        	<label for="campoestado_<? echo $fila['Field']?>">Campo Estado</label>
                            <select name="campoestado_<? echo $fila['Field']?>" id="campoestado_<? echo $fila['Field']?>" class="form-control form-control-lg">
                                <option value="">Sin datos...</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearboth" style="height:50px;"></div>
                </div>
            <? 
		}
		?>
            </div>
        
        <? 
		break;
		
}
?>