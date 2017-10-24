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

$oArchivos=new cArchivos($conexion);

	
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla


$ArregloDatos=array();
if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$resultado))
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$result=false;
}


?>
</script>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	$(".chzn-select-archivos").chosen();

});
</script>

<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|archivonomnuevo");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
<?php 
	
$i = 1;
while($filaarchivo=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filaarchivo['archivonom'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function Validarjs(formulario) 
{
	if (formulario.archivonomnuevo.value=="")
	{
		alert("Debe ingresar el nombre del archivo");
		formulario.archivonomnuevo.focus();
		return false;
	}
	else
		return true;
}

function CheckearTodosIns(id)
{
	if($("#"+id).is(':checked'))
	{
		$('.chkIns').prop('checked', true);
		$('.chktodosIns').prop('checked', true);
	}
	else
	{
		
			$('.chkIns').prop('checked', false);
			$('.chktodosIns').prop('checked', false);
	}
	
	return true;	
}

function CheckearTodosDel(id)
{
	if($("#"+id).is(':checked'))
	{
		$('.chkDel').prop('checked', true);
		$('.chktodosDel').prop('checked', true);
	}
	else
	{
		
			$('.chkDel').prop('checked', false);
			$('.chktodosDel').prop('checked', false);
	}
	
	return true;	
}

function LimpiarChk()
{
	$('.chkIns').prop('checked', false);
	$('.chktodosIns').prop('checked', false);
	$('.chkDel').prop('checked', false);
	$('.chktodosDel').prop('checked', false);
	
}

function checkvaluetodosIns()
{
	var todos = true;
	$(".chkIns").each(function(){
		if ($(this).prop("checked")==false)
		{
			todos = false;
		}
	})	
	
	if(todos==false)
		$("#chktodosIns").prop("checked",false);
	else
		$("#chktodosIns").prop("checked",true);	
	
}

function checkvaluetodosDel()
{
	var todos = true;
	$(".chkDel").each(function(){
		if ($(this).prop("checked")==false)
		{
			todos = false;
		}
	})	
	
	if(todos==false)
		$("#chktodosDel").prop("checked",false);
	else
		$("#chktodosDel").prop("checked",true);	
	
}



function ValidarInsSeleccionados()
{
	var chk = false;
	$(".chkIns").each(function(){
		if (this.checked==true)
			chk=true;
	})
	
	if (!chk)
	{
		alert("Debe seleccionar al menos un archivo a insertar.");
		return false;
	}
	return true;
}

function ValidarDelSeleccionados()
{
	var chk = false;
	$(".chkDel").each(function(){
		if (this.checked==true)
			chk=true;
	})
	
	if (!chk)
	{
		alert("Debe seleccionar al menos un archivo a eliminar.");
		return false;
	}
	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Archivos</h2>
</div>
<div class="form">
    
        <form action="archivos_upd.php" method="post" name="formulario" >
                    
                    <div class="ancho_10 aire">
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_9">
                            <div class="ancho_2">
                                <label>Archivo:</label>
                            </div>        
                            <div class="ancho_4">
                                <?php 	
                                    FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_archivos_orden",array('porderby'=>"archivonom"),"formulario","archivocod","archivocod","archivotodo","","Nuevo Archivo",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex);LimpiarChk()","",false,"","","","chzn-select-archivos","");
                                ?>
                            </div>
                        </div>
                        <div class="clearboth brisa_vertical"></div>
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_9">
                            <div class="ancho_2">
                                <label>Nombre Archivo:</label>
                            </div>        
                            <div class="ancho_3">
                                <input name="archivonomnuevo" id="archivonomnuevo" class="full" type="text"  size="50" maxlength="100" value="" onKeyDown="LimpiarChk()" />
                            </div>
                        </div>
                        <div class="clearboth brisa_vertical"></div>
                    </div>                    
                
                    <div class="clearboth aire">&nbsp;</div>
                             <div class="ancho_1">&nbsp;</div>
                            <div class="ancho_9">
                                <div class="ancho_boton aire"><input type="submit" name="botonalta" class="boton verde" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                                <div class="ancho_boton aire"><input name="Cancelar" type="button" id="Cancelar" class="boton base" value="Limpiar" onclick="formulario.archivocod.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                                <div class="ancho_boton aire"><input type="submit" name="botonbaja" class="boton rojo" value="Borrar" onclick="return confirm('¿Está seguro de que desea borrar el archivo?');" /></div>
                            </div>
                   <div class="clearboth brisa_vertical"></div>
        <br />
        
          	<div>
                <div class="tabla_titulo bold" style="float:left;padding-top:12px;">Archivos en Directorio faltantes en tabla</div> <div style="float:right"><input title="Insertar Todos los Selecionados" name="BotonAltaSelecionados" type="submit" value="Insertar Todos los Selecionados" class="boton verde"  onClick="return ValidarInsSeleccionados()"/></div>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <table class="data" summary="Archivos en Directorio faltantes en tabla">
                <tr>
                    <th><input type="checkbox" onclick="CheckearTodosIns('chktodosIns')" value="todos" id="chktodosIns" class="chktodosIns"></th>
                    <th width="80%">Archivo</th>
                    <th>Seleccionar</th>
                 </tr>   
                
        <?php 	
            $archivosdir=array();
            if ($dh = opendir(DIR_ROOT)) 
            {
                while (($file = readdir($dh)) !== false) 
                {
                    $value = explode(".", $file);
   					$extension = strtolower(array_pop($value));
					if($file!='.' && $file!='..' && $extension=="php")
                        $archivosdir[]=$file;
                } // fin while
                closedir($dh);
            }
            natcasesort($archivosdir);
            $result=true;
            
            foreach($archivosdir as $file)
            {
                $ArregloDatos['archivonom']=$file;
                if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$query))
                {	
                    FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
                    $result=false;
                }
                
                if(($numfilas==0) && ($result))
                {
        ?>				<tr>
                            <td><input type="checkbox" name="checkins[]" id="checkins[]" value="<?php  echo $file?>" class="chkIns" onClick="checkvaluetodosIns()"></td> 
                            <td><?php  echo htmlspecialchars($file,ENT_QUOTES); ?></td>
                            <td style="text-align:center">
                                <input title="Insertar" name="Alta_<?php  echo $file?>" type="submit" value="Insertar" class="boton" onClick="LimpiarChk()" />
                            </td>
                         </tr>   
                            
        <?php 
                } // if busqueda del archivo.
            }
        ?>	</table>
        
        <?php  if ($result){ ?>
        	<div class="clearboth aire_vertical">&nbsp;</div>
            <div class="tabla_titulo bold">
                <div class="tabla_titulo bold" style="float:left;padding-top:12px;">Archivos en Tabla faltantes en Directorio</div> <div style="float:right"><input title="Eliminar Todos los Selecionados" name="BotonBorrarSelecionados" type="submit" value="Eliminar Todos los Selecionados" class="boton rojo"  onClick="return ValidarDelSeleccionados()"/></div>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <table class="data" summary=" Archivos en Tabla faltantes en Directorio">
                <tr>
                   <th><input type="checkbox" onclick="CheckearTodosDel('chktodosDel')" value="todos" id="chktodosDel" class="chktodosDel"></th>
                    <th width="80%">Archivo</th>
                    <th>Seleccionar</th>
                 </tr>   
        
        <?php 
            $ArregloDatos=array();
            if (!$oArchivos->Buscar ($ArregloDatos,$numfilas,$queryarch))
            {	
                FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
                $result=false;
            }
            while($filaarchivo=$conexion->ObtenerSiguienteRegistro($queryarch))
            {	
                    $value = explode(".", $filaarchivo['archivonom']);
   					$extension = strtolower(array_pop($value));
			    if($extension!="php" || !file_exists(DIR_ROOT.$filaarchivo['archivonom']))
                {
        ?>
        
                            <tr>
                            <td><input type="checkbox" name="checkdel[]" id="checkdel[]" value="<?php  echo $filaarchivo['archivocod']?>" class="chkDel" onClick="checkvaluetodosDel()"></td> 
                            <td><?php  echo htmlspecialchars($filaarchivo['archivonom'],ENT_QUOTES); ?></td>
                            <td style="text-align:center">
                            <input title="Eliminar" name="Baja_<?php  echo $filaarchivo['archivocod']?>" type="submit" value="Eliminar" class="boton" onClick="LimpiarChk()"/></td>
                         </tr>   
        
        <?php 	
                } // no file_exists			
            } // while del recorrido de los archivos
        ?>	
            </table>
        <?php  
        }
        ?></div>
            </form>

</div>        
<div style="height:50px; clear:both">&nbsp;</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
