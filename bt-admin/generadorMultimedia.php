<? 
$btadmin ="bt-admin/"; // "" SI NO TIENE CARPETA bt-admin
$classpanel = "0"; // 1 <div class="panel-style space">

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

$oStored=new cStored($conexion);

$_SESSION['usuariocod']=1;


if (isset($_POST['botonGenerarMultimedia']) && $_POST['botonGenerarMultimedia']=="GenerarMultimedia")
{
	$tablaencontrada = $_POST['nombreTablaMultimedia'];
	$tablaRelacion = $_POST['tablaRelacion'];
	$borrarCrearTabla = $_POST['borrarCrearTabla'];
	$arregloCampos['tabla'] =  $_POST['nombreTablaMultimedia'];
	$arregloCampos['codigo'] =  $_POST['codigo'];
	$arregloCampos['multimedia'] = "multimediacod";
	$arregloCampos['multimediaConjunto'] = "multimediaconjuntocod";
	$arregloCampos['orden'] =  $_POST['preconfigmultimedia']."multimediaorden";
	$arregloCampos['descripcion'] = $_POST['preconfigmultimedia']."bamultimediadesc";
	$arregloCampos['titulo'] = $_POST['preconfigmultimedia']."multimediatitulo";
	$arregloCampos['home'] = $_POST['preconfigmultimedia']."multimediamuestrahome";
	$arregloCampos['fAlta'] = $_POST['preconfigmultimedia']."multimediafalta";
	$nombreClase = $_POST['nombreClaseMultimedia'];
	
	
	$tieneimg =0;
	if(isset($_POST['tieneimg']))
		$tieneimg =1;
  	$tienevideo =0;
	if(isset($_POST['tienevideo']))
		$tienevideo =1;
  	$tieneaudio =0;
	if(isset($_POST['tieneaudio']))
  		$tieneaudio =1;
	$tienearchivos =0;
	if(isset($_POST['tienearchivos']))
  		$tienearchivos =1;
	$tienetitulo =0;
	if(isset($_POST['tienetitulo']))
		$tienetitulo =1;
  	$tienedesc =0;
	if(isset($_POST['tienedesc']))
		$tienedesc =1;
  	$tienehome =0;
	if(isset($_POST['tienehome']))
		$tienehome =1;
	$tieneorden =0;	
	if(isset($_POST['tieneorden']))	
  		$tieneorden =1;
	
	$configtipo = strtoupper($_POST['preconfigmultimedia']);
	
	$sql = "SELECT * FROM mul_multimedia_configuracion WHERE LCASE(configtipo) = LCASE('".$configtipo."');";
	$erroren="";
	$conexion->_EjecutarQuery($sql,$erroren,$resultadoArchivo,$errno);
	$numfilas = $conexion->ObtenerCantidadDeRegistros($resultadoArchivo);
	if($numfilas=="0")
	{
		$sql = "INSERT INTO mul_multimedia_configuracion (configtipo, tieneimg, tienevideo, tieneaudio, tienearchivos, tienetitulo, tienedesc, tienehome, tieneorden, ultmodusuario, ultmodfecha) VALUES ('".$configtipo."', '".$tieneimg."', '".$tienevideo."', '".$tieneaudio."', '".$tienearchivos."', '".$tienetitulo."', '".$tienedesc."', '".$tienehome."', '".$tieneorden."', '1','2016-08-08 00:00:00');";
		$erroren="";
		$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	}
	else
	{
		$sql = "UPDATE mul_multimedia_configuracion SET tieneimg='".$tieneimg."', tienevideo='".$tienevideo."', tieneaudio='".$tieneaudio."', tienearchivos='".$tienearchivos."', tienetitulo='".$tienetitulo."', tienedesc='".$tienedesc."', tienehome='".$tienehome."', tieneorden='".$tieneorden."' WHERE configtipo='".$configtipo."';";
		$erroren="";
		$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	}
	
	
	
	include("generadores/generadorBaseDatos.php");
	include("generadores/generadorBD.php");
	include("generadores/generadorClases.php");
	?>
    <div class="inner-page-title" style="padding-top:25px; padding-bottom:15px;">
        <h2>Se ha generado la clase <? echo $nombreClase?> - Recuerde agregar:</h2>
        <div style="margin-top:10px; margin-left:10px;">
            <h3>Logica/cMultimediaGeneral.class.php la siguiente linea:</h3>
            <div style="font-size:14px;">
            	Ir a la funcion getTipo(); y agregar la linea para poder utilizar el multimedia
            	Recuerde insertar en la tabla mul_multimedia_configuracion el nuevo TIPO generado.
                Ej: case "<?php echo strtoupper($_POST['preconfigmultimedia']) ?>":
                    return new <? echo $nombreClase?>($this->conexion,$this->formato);	
            </div>
            <div style="height:15px;">&nbsp;</div>
            <h3>Archivo que utiliza el multimedia agregar las siguientes lineas:</h3>
            <div style="font-size:14px;">
				 
                 <? echo htmlspecialchars("<?",ENT_QUOTES);?><br />
                 	<div style="margin-left:10px;">
                        $oMultimediaFormulario = new cMultimediaFormulario($conexion,"<?php echo strtoupper($_POST['preconfigmultimedia']) ?>",$<? echo $arregloCampos['codigo']?>);<br />
                        echo $oMultimediaFormulario->CargarBotonera();<br />
                        echo $oMultimediaFormulario->CargarListado();<br />
                        <? echo htmlspecialchars("?>",ENT_QUOTES);?><br />
                    </div>
            </div>

        </div>
        <div class="clear brisa_vertical">&nbsp;</div>
        <div class="menubarra">
            <ul>
                <li><div class="ancho_boton aire"><a class="boton verde" href="generadorMultimedia.php">Volver</a></div></li>
            </ul>
        </div>
       

    </div>
    <? 
	die();
}


if (!$oStored->TraerTablas(BASEDATOS,$query))
	$result=false;

$Tables = "Tables_in_".BASEDATOS;





?>
     <?php if($classpanel=="1"){?>
    <div class="panel-style space">
    <?php }?>
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2>Generador de Clase de Multimedia</h2>
    </div>
    <div style="font-size:12px; margin-bottom:15px;">
        Recuerde que al generar se borrar&aacute; la tabla con los datos y se crear&aacute; nuevamente.
    </div>
    <link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
	<script type="text/javascript" src="js/generadorABM.js"></script>
    <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
     <div class="form">
        <form action="generadorMultimedia.php" method="post" name="formgenerador">
            <div class="form-group row clearfix">
                        <div class="col-md-6">
                            <label>Nombre de la tabla Multimedia</label>
                            <input type="text" value="" maxlength="255" class="form-control form-control-lg"  name="nombreTablaMultimedia" id="nombreTablaMultimedia" />
                            <span style="font-size:10px;">Ej. not_noticias_mul_multimedia</span>
                         </div>
             </div>  
             <div class="clearboth">&nbsp;</div>
             <div class="form-group row clearfix">
                        <div class="col-md-6">
                            <label>Nombre de la tabla a la que se relaciona</label>
                            <select name="tablaRelacion" class="form-control form-control-lg chzn-select" id="tablaRelacion" >
                                <option value="">Seleccione un tabla...</option>
                                <? while($fila = $conexion->ObtenerSiguienteRegistro($query)){?>
                                    <option value="<? echo $fila[$Tables]?>"><? echo $fila[$Tables]?></option>
                                <? }?>
                            </select>
                            <span style="font-size:10px;">Ej. not_noticias</span>
                         </div>
             </div>   
             <div class="clearboth brisa_vertical">&nbsp;</div>
             <div class="form-group row clearfix">
                        <div class="col-md-6">
                            <label>Se Borra la tabla y se regenera?</label>
                        </div>
                        <div class="clearboth brisa_vertical">&nbsp;</div>
                        <div class="col-md-2">
                            <select name="borrarCrearTabla" class="form-control form-control-lg" id="borrarCrearTabla" >
                                <option value="">Selecione...</option>
                                <option value="1">SI</option>
                                <option value="1">NO</option>
                            </select>
                         </div>
             </div>
             <div class="clearboth brisa_vertical">&nbsp;</div>
             <div class="form-group row clearfix">
                        <div class="col-md-6">
                             <label>C&oacute;digo Foreign key</label>
                            <input type="text" value="" maxlength="255" class="form-control form-control-lg"  name="codigo" id="codigo" />
                            <span style="font-size:10px;">Ej. noticiacod</span>
                         </div>
             </div>  
             <div class="clearboth brisa_vertical">&nbsp;</div>
             <div class="form-group row clearfix">
                        <div class="col-md-6">
                            <label for="prefijomultimedia">Prefijo Tabla</label>
                            <input type="text" value=""  class="form-control form-control-lg"  maxlength="255" name="prefijomultimedia" id="prefijomultimedia" />
                            <span style="font-size:10px;">Ej. not (<strong>not</strong>multimediatitulo)</span>
                         </div>
             </div>  
             <div class="clearboth brisa_vertical">&nbsp;</div>  
             <div class="form-group row clearfix">
                        <div class="col-md-6">
                             <label for="preconfigmultimedia">Nombre Clase Multimedia</label>
                            <input type="text" value=""  class="form-control form-control-lg" maxlength="255" name="nombreClaseMultimedia" id="nombreClaseMultimedia" />
                            <span style="font-size:10px;">Ej. cNoticiasMultimedia</span>
                         </div>
             </div> 
             <div class="clearboth aire_vertical">&nbsp;</div>  
             <h2>Configuraci&oacute;n Multimedia</h2>
             <div class="form-group row clearfix">
                        <div class="col-md-3">
                             <label for="preconfigmultimedia">Prefijo Config</label>
                            <input type="text" value=""  class="form-control form-control-lg" style="text-transform: uppercase"   maxlength="255" name="preconfigmultimedia" id="preconfigmultimedia" />
                            <span style="font-size:10px;">Ej. NOT (tabla <strong>mul_multimedia_configuracion</strong>)</span>
                         </div>
             </div>
             <div class="clearboth brisa_vertical">&nbsp;</div>
             <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Tiene Imagen</th>
                    <th>Tiene Video</th>
                    <th>Tiene Audio</th>
                    <th>Tiene Archivos</th>
                    <th>Tiene T&iacute;tulo</th>
                    <th>Tiene Descripci&oacute;n</th>
                    <th>Tiene Home</th>
                    <th>Tiene Orden</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="checkbox" value="1" name="tieneimg" id="tieneimg"></td>
                    <td><input type="checkbox" value="1" name="tienevideo" id="tienevideo"></td>
                    <td><input type="checkbox" value="1" name="tieneaudio" id="tieneaudio"></td>
                    <td><input type="checkbox" value="1" name="tienearchivos" id="tienearchivos"></td>
                    <td><input type="checkbox" value="1" name="tienetitulo" id="tienetitulo"></td>
                    <td><input type="checkbox" value="1" name="tienedesc" id="tienedesc"></td>
                    <td><input type="checkbox" value="1" name="tienehome" id="tienehome"></td>
                    <td><input type="checkbox" value="1" name="tieneorden" id="tieneorden"></td>
                  </tr>
                </tbody>
              </table>
             <div class="clearboth brisa_vertical">&nbsp;</div> 
                <div class="clearboth">&nbsp;</div>
                <div class="col-md-6">
                    <div style="text-align:right">
                        <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonGenerarMultimedia" onclick="return ValidarMultimedia()" value="GenerarMultimedia" /></div>
                    </div>
                </div>
            <div class="clearboth"></div>
        </form> 
    </div>
     <?php if($classpanel=="1"){?>
    </div>
    <?php }?>   
<? 



?>