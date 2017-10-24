<? 
$version = "4.1"; // Numero de version del generador
$btadmin ="bt-admin/"; // "" SI NO TIENE CARPETA bt-admin
$classpanel = "0"; // 1 <div class="panel-style space">

require('./config/include.php');
include("generadores/backend/generadorAltaCsvInicioModulo.class.php");
include("generadores/backend/generadorAltaPdfInicioModulo.class.php");
include("generadores/backend/generadorCapaLogica.class.php");
include("generadores/backend/generadorCapaLogicaPdf.class.php");
include("generadores/backend/generadorAltaPdfCssInicioModulo.class.php");
include("generadores/backend/generadorCapaDatos.class.php");
include("generadores/backend/generadorAltaModificacion.class.php");
include("generadores/backend/generadorAltaModificacionJS.class.php");
include("generadores/backend/generadorAltaInicioModulo.class.php");
include("generadores/backend/generadorAltaLstInicioModulo.class.php");
include("generadores/backend/generadorAltaLstJSInicioModulo.class.php");
include("generadores/backend/generadorAltaUpd.class.php");
include("generadores/backend/generarModuloBackend.class.php");

include("generadores/front/generadorLogica.class.php");



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

$_SESSION['usuariocod']=1;

$oStored=new cStored($conexion);


$carpetaPaquete = "paquetes/";

if(isset($_POST['tabla']))
	$carpetaPaquete = "paquetes/".$_POST['tabla']."/";
	
if (isset($_POST['eliminarmodulocompleto']))
{
	if ($_POST['codigomodulo']!="")
	{
		$oArchivos= new cArchivos($conexion);
		$oModulos=new cModulos($conexion); 
		$oModulosArchivos=new cModulosArchivos($conexion); 
		$oRoles_Modulos=new cRolesModulos ($conexion);
		$oGrupomod_Modulos= new cGruposmodModulos($conexion);
		
		
	}
}



if (isset($_POST['botonGenerar']) && $_POST['botonGenerar']=="Generar")
{
	
	
	$generarPaquete=false;
	if (isset($_POST['Paquete']) && $_POST['Paquete']==1)
		$generarPaquete=true;
	
	
	$generarEnSitio=false;
	if (isset($_POST['GenerarEnSitio']) && $_POST['GenerarEnSitio']==1)
		$generarEnSitio=true;
		
	if ($generarPaquete)
	{
		if (!is_dir($carpetaPaquete))
			mkdir($carpetaPaquete);
		
		
		if (!is_dir($carpetaPaquete.$btadmin))
			mkdir($carpetaPaquete.$btadmin);
	
	}	
	
	
	$TieneCsv=0;
	if (isset($_POST['Csv']) && $_POST['Csv']==1)
		$TieneCsv=1;

	$TienePdf=0;
	if (isset($_POST['Pdf']) && $_POST['Pdf']==1)
		$TienePdf=1;


	$arregloCampos['tieneCsv'] = $TieneCsv;
	$arregloCampos['tienePdf'] = $TienePdf;
	$tieneFoto = $arregloCampos['tieneFoto'] = 0;
	$tieneAudio = $arregloCampos['tieneAudio'] =0;
	$tieneVideo = $arregloCampos['tieneVideo'] =0;
	$tieneArchivo = $arregloCampos['tieneArchivo'] =0;
	$TieneMultimedia = $arregloCampos['TieneMultimedia'] =0;
	
	foreach ($_POST['campoalta'] as $camposAlta)
	{
		if(isset($_POST['camposaltatipo_'.$camposAlta]))
		{
			$camposMult = $_POST['camposaltatipo_'.$camposAlta]; 
			if($camposMult==8 || $camposMult==9 || $camposMult==10 || $camposMult==11)
			{
				$TieneMultimedia = $arregloCampos['TieneMultimedia']  = 1;
				switch($camposMult)
				{
					case "8":
						$tieneFoto = $arregloCampos['tieneFoto'] = 1;
					break;
					case "9":
						$tieneVideo = $arregloCampos['tieneVideo']= 1;
					break;
					case "10":
						$tieneAudio = $arregloCampos['tieneAudio'] =1;
					break;
					case "11":
						$tieneArchivo = $arregloCampos['tieneArchivo'] = 1;
					break;
				}//fin  swich	
			}
		
		}
	}

	$tablaencontrada = $_POST['tabla'];
	
	$tablaencontrada.
		
	$nombreClase = $_POST['nombreClase'];
	$arregloCampos['tabla'] = $tablaencontrada;
	$arregloCampos['codigo'] = $_POST['campocodigo'];
	
	$arregloCampos['tieneOrden'] = $_POST['tieneorden'];
	$arregloCampos['campoOrden'] = $_POST['campoorden'];
	
	if(isset($_POST['jsonlistado']))
		$arregloCampos['JsonListado'] = $_POST['jsonlistado'];
	if(isset($_POST['jsoncodigo']))
	$arregloCampos['JsonCodigo'] = $_POST['jsoncodigo'];
	if(isset($_POST['ClaseFront']))
	$arregloCampos['ClaseFront'] = $_POST['ClaseFront'];
	
	$arregloCampos['tieneEstado'] = $_POST['tienemodificarestado'];
	$arregloCampos['campoEstado'] = $_POST['campoestado'];
	$arregloCampos['TieneMultimedia'] = $TieneMultimedia;
	$arregloCampos['TipoEliminacion'] = $_POST['tipoeliminacion'];
	$arregloCampos['CarpetaJson'] = $_POST['carpetajson'];
	
	
	$arregloCampos['tieneActivarDesactivar'] = $_POST['tieneaprobardesaprobar'];

	$arregloCampos['tieneEliminarLst'] = true;
	
	$arregloCampos['TieneClaseMultimedia'] = false;
	if($_POST['clasemultimedia']!="")
	{
		$arregloCampos['TieneClaseMultimedia'] = true;
		$arregloCampos['ClaseMultimedia'] = $_POST['clasemultimedia'];	
		$arregloCampos['PrefijoMultimedia'] = $_POST['prefijomultimedia'];
		$arregloCampos['PrefijoConfigMultimedia'] = $_POST['preconfigmultimedia'];
	}


	$arregloCampos['otroscampos'] = $_POST;
	$arregloCampos['otroscampos']['TieneMultimedia'] = $TieneMultimedia;
	$arregloCampos['tieneBusquedaAvanzada'] = $_POST['tienebusquedaavanzada'];
	if (isset($_POST['camposbusquedaavanzada']))
	{	
		$arregloCampos['camposBusquedaAvanzada'] = $_POST['camposbusquedaavanzada'];
	}else
	{
		$arregloCampos['camposBusquedaAvanzada'] = array();
	}
	if (isset($_POST['camposListadoAvanzada']))
		$arregloCampos['camposListadoAvanzada'] = $_POST['camposListadoAvanzada'];
	else
		$arregloCampos['camposListadoAvanzada'] = array();
		
	$tinymceAvanzado=false;
	$arregloCampoTiny = array();
	foreach($arregloCampos['otroscampos']['campoalta'] as $Campos)
	{
		if ($arregloCampos['otroscampos']['camposaltatipo_'.$Campos]==3)
		{
			$tinymceAvanzado=true;
			$arregloCampoTiny[$Campos] = $Campos;
		}
	}
	
	if($tinymceAvanzado)
	{
		foreach($arregloCampoTiny as $CampoTiny)
		{
			$Sqlcampo ="SHOW COLUMNS FROM ".$tablaencontrada." LIKE '".$CampoTiny."procesado';";
			$erroren="";
			$conexion->_EjecutarQuery($Sqlcampo,$erroren,$resultadoCampoProsesado,$errno);	
			$numfilas = $conexion->ObtenerCantidadDeRegistros($resultadoCampoProsesado);
			if($numfilas=="0")
			{
				$SqlUPDcampo ="ALTER TABLE ".$tablaencontrada." ADD COLUMN ".$CampoTiny."procesado TEXT NULL AFTER ".$CampoTiny.";";
				$erroren="";
				$conexion->_EjecutarQuery($SqlUPDcampo,$erroren,$resultadoCampoUPD,$errno);	
			}
		}
	}	
		
	if (!$oStored->TraerCampos($tablaencontrada,$resultado))
		$result=false;
	
	$arregloCamposTabla = array();
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
		$arregloCamposTabla[$fila['Field']] = $fila;



	$archivo = $_POST['archivonombre'];
	$arregloCampos['titulo'] = $_POST['titulopantalla'];
	$arregloCampos['archivo'] = $archivo;
	$arregloCampos['classpanel'] = $classpanel;
	$arregloCampos['camposTabla'] = $arregloCamposTabla;
	

	
	if (!is_dir("modulos/".$archivo))
	{
		mkdir("modulos/".$archivo);
		mkdir("modulos/".$archivo."/js");
		mkdir("modulos/".$archivo."/css");
	}


	$arregloSp = array();
	$arregloCampos['camposconCombo'] = array();
	foreach ($arregloCampos['otroscampos']['campoalta'] as $camposAlta){
		$campotipo = $arregloCampos['otroscampos']['camposaltatipo_'.$camposAlta];
		if ($campotipo==12)
		{
			$tabla = $arregloCampos['otroscampos']['tabla_'.$camposAlta];
			$fk = $arregloCampos['otroscampos']['campofk_'.$camposAlta];
			$desc = $arregloCampos['otroscampos']['campodesc_'.$camposAlta];
			$estado = $arregloCampos['otroscampos']['campoestado_'.$camposAlta];
			$arregloCampos['camposconCombo'][$camposAlta]['tabla']= $arregloSp[$tabla]['tabla'] = $tabla;
			$arregloCampos['camposconCombo'][$camposAlta]['fk']= $arregloSp[$tabla]['fk'] = $fk;
			$arregloCampos['camposconCombo'][$camposAlta]['desc']= $arregloSp[$tabla]['desc'] = $desc;
			$arregloCampos['camposconCombo'][$camposAlta]['estado']= $arregloSp[$tabla]['estado'] = $estado;
		}
	}
	
	if (isset($_POST['Alta']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin;
		$oABM = new cGeneradorAltaModificacion($conexion,$folderClass,$generarEnSitio,$generarPaquete);
		$oABM->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase,$archivo);

		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."modulos/";
		$oJsAbm = new cGeneradorJSAltaModificacion($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oJsAbm->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

	}
	
	
	if (isset($_POST['Listado']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin;
		$oPantallaInicio = new GenerarAltaInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oPantallaInicio->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

		$oPantallaInicio = new GenerarAltaLstInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oPantallaInicio->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

		$oPantallaInicio = new GenerarAltaUpd($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oPantallaInicio->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."modulos/";
		$oPantallaInicio = new GenerarAltaLstJSInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oPantallaInicio->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);
	}
	
	if (isset($_POST['Csv']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin;
		$oABM = new GenerarAltaCsvInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oABM->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase,$archivo);

	}
	
	if (isset($_POST['Clases']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."Logica/";
		$oClases = new cGeneradorABMClases($conexion,$folderClass,$generarEnSitio,$generarPaquete);
		$oClases->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."Datos/";
		$folderSql= DIR_ROOT.$carpetaPaquete."sql/";
		$oDbClases = new cGeneradorABMBD($conexion,$folderClass,$folderSql,$generarEnSitio,$generarPaquete);
		$oDbClases->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);

	}
	if (isset($_POST['Pdf']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."Logica/";
		$oClases = new cGeneradorABMClasesPdf($conexion,$folderClass,$generarEnSitio,$generarPaquete);
		$oClases->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);
		
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin;
		$oABM = new GenerarAltaPdfInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oABM->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase,$archivo);
		
		$folderClass= DIR_ROOT.$carpetaPaquete.$btadmin."modulos/";
		$oPantallaInicio = new GenerarAltaPdfCssInicioModulo($conexion,$folderClass,$archivo,$generarEnSitio,$generarPaquete);
		$oPantallaInicio->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);
					
	}
	if (isset($_POST['ClaseFront']))
	{
		$folderClass= DIR_ROOT.$carpetaPaquete."Clases/";
		$oClases = new generadorLogicaFront($conexion,$folderClass,$generarEnSitio,$generarPaquete);
		$oClases->GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase);
	}
	
	if (isset($_POST['generarmodulo']) && $_POST['generarmodulo']=="1")
	{
		
		if ($_POST['nombremodulo']!="")
		{
			$folderClass= DIR_ROOT.$carpetaPaquete;
			$folderSql= DIR_ROOT.$carpetaPaquete."sql/";
			
			$oABM = new cGenerarModuloBackend($conexion,$folderClass,$folderSql,$archivo,$generarEnSitio,$generarPaquete);
			if ($generarEnSitio)
				$oABM->GenerarModulo($arregloCamposTabla,$arregloCampos,$nombreClase,$archivo);
			if ($generarPaquete)
				$oABM->GenerarModuloSQL($arregloCamposTabla,$arregloCampos,$nombreClase,$archivo);
			
		}		
	}
	?>
    <?php if($classpanel=="1"){?>
    <div class="panel-style space">
    <?php }?>
    <div class="inner-page-title" style="padding-top:25px; padding-bottom:15px;">
        <h2>Se ha generado correctamente - <? echo $nombreClase?></h2>
        <div class="clear brisa_vertical">&nbsp;</div>
        <div class="menubarra">
            <ul>
                <li><div class="ancho_boton aire"><a class="boton verde" href="generadorABM.php">Volver</a></div></li>
            </ul>
        </div>
    </div>
     <?php if($classpanel=="1"){?>
    </div>
    <?php }?>
    <? 
	die();
}



if (!$oStored->TraerTablas(BASEDATOS,$query))
	$result=false;

$Tables="Tables_in_".BASEDATOS;

?>
    <?php if($classpanel=="1"){?>
    	<div class="panel-style space">
    <?php }?>
    <div class="inner-page-title" style="padding-bottom:2px;">
        <h2>Generador de Clase. <b>Versi&oacute;n: <?php echo $version;?></b></h2>
    </div>
    <div style="font-size:12px; margin-bottom:15px;">
        Recuerde que al generar se borrar&aacute; la tabla con los datos y se crear&aacute; nuevamente.
    </div>
    <link href="css/chosen.css" rel="stylesheet" title="style" media="all" />
	<script type="text/javascript" src="js/generadorABM.js"></script>
    <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
    <div class="form">
    	<form  class="general_form" action="generadorABM.php" method="post" name="formgenerador" >
            
           
           <table style="width:70%">
           		<tr>
                	<td>
                    	<input style="width:auto"  type="checkbox" name="Paquete" id="Paquete" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Paquete">Generar Paquete</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="GenerarEnSitio" id="GenerarEnSitio" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="GenerarEnSitio">Generar Archivos en el sitio</label>
                    </td>
              </tr> 
          </table> 
          <div class="clearboth" style="height:10px"></div>
          <table style="width:50%">
           		<tr>
                	<td>
                    	<input style="width:auto"  type="checkbox" name="Clases" id="Clases" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Clases">Generar Clases</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="Listado" id="Listado" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Listado">Listado</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="Alta" id="Alta" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Alta">Alta</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="Csv" id="Csv" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Csv">Csv</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="Pdf" id="Pdf" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="Pdf">Pdf</label>
                    </td>
              </tr> 
          </table> 
          <div class="clearboth" style="height:10px"></div>
          
          
          
          
          
            <div style="font-size:16px;">
                
                
           
            <div class="clearboth" style="height:10px"></div>
            <b>Generar archivos Json</b>
            <div class="clearboth" style="height:15px;"></div>
            <table style="width:50%">
           		<tr>
                	<td>
                    	<input style="width:auto"  type="checkbox" name="jsonlistado" id="jsonlistado" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="jsonlistado">Generar Json Listado</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="jsoncodigo" id="jsoncodigo" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="jsoncodigo">Generar Json por c&oacute;digo</label>
                    </td>
                    <td>
                    	<input style="width:auto" type="checkbox" name="ClaseFront" id="ClaseFront" value="1" />
                    </td>
                    <td style="font-size:16px;">
                    	 <label for="ClaseFront">Generar Clase de Front</label>
                    </td>
              </tr> 
          </table> 
            <div class="clearboth">&nbsp;</div>
            <div class="col-md-6">
               <label for="carpetajson">Carpeta</label>
               <input type="text" value=""  class="medium"  maxlength="255" name="carpetajson" id="carpetajson" />
               <span style="font-size:10px;"><strong>Se ubicara en /public/json/</strong>nombre carpeta</span>
            </div>
 
            <div class="clearboth" style="height:10px"></div>
            <div class="clearboth">&nbsp;</div>
            <hr />
            <h2>Generalidades</h2>
            <div style="font-size:16px" >
                <div class="form-group row clearfix">
                    <div class="col-md-6">
                        <label>Seleccione la tabla</label>
                        <select name="tabla" class="form-control form-control-lg chzn-select" id="tabla"  onchange="BuscarCampos()">
                            <option value="">Seleccione un tabla...</option>
                            <? while($fila = $conexion->ObtenerSiguienteRegistro($query)){?>
                                <option value="<? echo $fila[$Tables]?>"><? echo $fila[$Tables]?></option>
                            <? }?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Nombre de la clase a generar</label>
                        <input type="text" value="" size="50" maxlength="255" class="form-control form-control-lg"  name="nombreClase" id="nombreClase" />
                        <div style="font-size:10px;">Ej. cNoticias</div>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                </div>    
                <div  class="form-group row clearfix">
                    <div class="col-md-6">
                        <label>Campo C&oacute;digo</label>
                        <select name="campocodigo" id="campocodigo"  class="form-control form-control-lg">
                            <option value="">Sin datos...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Prefijo de los nombre de los archivos</label>
                        <input type="text" value="" size="50" maxlength="255"  class="form-control form-control-lg"  name="archivonombre" id="archivonombre" />
                        <div style="font-size:10px;">Ej. not_noticias <strong>(agregar sin el .php)</strong></div>
                    </div>
 				</div>
            	<h2>En caso de Generar archivos Json y el mismo contiene multimedia</h2>
                <div class="form-group row clearfix">
                    <div class="col-md-6">
                        <label for="clasemultimedia">Clase Multimedia</label>
                        <select name="clasemultimedia" id="clasemultimedia" class="chzn-select">
                            <option value="">Seleccione...</option>
                           <? if ($dh = opendir(DIR_CLASES_LOGICA)) 
                            {
                                while (($file = readdir($dh)) !== false)
                                {
                                    if($file!='.' && $file!='..' && $file!='cExcel.php' && array_pop(explode('.', $file))=="php")
                                    {
                                        $arr = explode(".", $file, 2);
                                        $class = $arr[0];
                                    ?>
                                        <option value="<? echo substr($class, 1);?>"><? echo $file; ?></option>
                                    <? }
                                } // fin while
                                closedir($dh);
                            }?>
                        </select>  
                    </div>  
                    
                    <div class="col-md-3">
                        <label for="prefijomultimedia">Prefijo Tabla</label>
                        <input type="text" value=""  class="form-control form-control-lg"  maxlength="255" name="prefijomultimedia" id="prefijomultimedia" />
                        <span style="font-size:10px;">Ej. not (<strong>not</strong>multimediatitulo)</span>
                    </div>
                    <div class="col-md-3">
                        <label for="preconfigmultimedia">Prefijo Config</label>
                        <input type="text" value=""  class="form-control form-control-lg" style="text-transform: uppercase"   maxlength="255" name="preconfigmultimedia" id="preconfigmultimedia" />
                        <span style="font-size:10px;">Ej. NOT (tabla <strong>mul_multimedia_configuracion</strong>)</span>
                    </div>
                    <div class="clearboth">&nbsp;</div>    
                    <div style="font-size:12px; margin-bottom:15px;">
                        Recuerde que las Clases de Front se generar&aacute; con los json seleccionados .
                    </div>
       			</div>
                <div class="clearboth">&nbsp;</div>
                
                <div class="col-md-12">
                    <label>Titulo Pantalla</label>
                    <input type="text" name="titulopantalla" class="form-control form-control-lg"  value="" id="titulopantalla" />
                </div>
               <div class="clearboth"></div>
                
                <hr />
                <h2>Tipo Eliminaci&oacute;n</h2>
                <div class="col-md-6">
                    <label>Tipo Eliminaci&oacute;n</label>
                    <select name="tipoeliminacion" id="tipoeliminacion" style="width:200px;">
                        <option value="0" selected="selected">F&iacute;sica</option>
                        <option value="1">L&oacute;gica</option>
                    </select>
                </div>
                <div class="clearboth"></div>
                
                <hr />
                <h2>Orden</h2>
                <div class="col-md-6">
                    <label>Tiene Orden</label>
                    <select name="tieneorden" id="tieneorden"  class="form-control form-control-lg">
                        <option value="1">Si</option>
                        <option value="0" selected="selected">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Campo Orden</label>
                    <select name="campoorden" id="campoorden" class="form-control form-control-lg" onchange="VerificarOrden()">
                        <option value="">Sin datos...</option>
                    </select>
                </div>
                <div class="clearboth">&nbsp;</div>
                <hr />
                <h2>Estado</h2>
                <div class="col-md-6">
                    <label>Modifica Estado</label>
                    <select name="tienemodificarestado" id="tienemodificarestado" class="form-control form-control-lg">
                        <option value="1">Si</option>
                        <option value="0" selected="selected">No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Campo Estado</label>
                    <select name="campoestado" id="campoestado" class="form-control form-control-lg" onchange="VerificarEstado()">
                        <option value="">Sin datos...</option>
                    </select>
                </div>
                <div class="clearboth">&nbsp;</div>
                <div class="col-md-6">
                    <label>Tiene Aprobar / Desaprobar</label>
                    <select name="tieneaprobardesaprobar" id="tieneaprobardesaprobar" class="form-control form-control-lg">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                    <div style="font-size:11px; margin-top:5px;">Utiliza las constantes <strong>ACTIVO, NOACTIVO</strong></div>
                </div>
                <div class="clearboth">&nbsp;</div>
                
                <hr />
                <h2>B&uacute;squeda avanzada</h2>
                <div class="clearboth">&nbsp;</div>
                <div class="col-md-3">
                    <label>Tiene B&uacute;squeda Avanzada</label>
                </div>
                <div class="col-md-2">
                    <select name="tienebusquedaavanzada" id="tienebusquedaavanzada" class="form-control form-control-lg">
                        <option value="1">Si</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="clearboth">&nbsp;</div>
                
                <div style="font-size:11px; margin-top:5px;">Seleccione los campos los cuales desea que se realice una b&uacute;squeda avanzada</div>
                <div class="col-md-6">
                    <label>Campos Filtros</label><br />
                    <div id="camposbusquedaavanzadalst">
                    
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Campos Listado</label><br />
                    <div id="camposListadoAvanzada">
                    
                    </div>
                </div>
                <div class="clearboth"></div>
                <hr />
                <h2>Errores (Capa L&oacute;gica)</h2>
                <div class="col-md-12">
                    <label>Descripci&oacute;n de errores</label>
                </div>
                <div class="col-md-12">
                    <div id="camposErrores">
                    
                    </div>
                </div>
                <div class="clearboth"></div>
                <hr />
                <h2>ABM (Alta / Modificaci&oacute;n)</h2>
                <div class="col-md-6">
                    <label>Generar el Alta</label>
                    <select name="altatipo" class="full" id="altatipo" >
                        <option value="1">Popup</option>
                        <option value="0" selected="selected">Otra pantalla</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Template</label>
                    <select name="templatealta" class="full" id="templatealta" >
                        <option value="0">1 Columna</option>
                        <option value="1" selected="selected">2 columnas</option>
                    </select>
                </div>
                <div class="clearboth"></div>
                <div class="col-md-12">
                    <label>Campos del alta</label>
                </div>
        
                <div class="col-md-12">
                    <div id="camposAlta">
                    
                    </div>
                </div>
                <div class="clearboth"></div>
                <hr />
                <h2>M&oacute;dulo / Grupo M&oacute;dulo</h2>
                <div class="col-md-3">
                    <label>Generar Nuevo M&oacute;dulo</label>
                    <select name="generarmodulo" id="generarmodulo" class="full">
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>C&oacute;digo del M&oacute;dulo</label>
                    <input type="text" name="codigomodulo" class="full" value="" id="codigomodulo">
                </div>
                <div class="col-md-3">
                    <label>M&oacute;dulo</label>
                    <input type="text" name="nombremodulo" class="full" value="" id="nombremodulo">
                </div>
                <div class="col-md-3">
                 	<label>&nbsp;</label>
                   <? FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_modulos_orden",array('porderby'=>"modulocod"),"formulario","modulocodver","modulocodver","modulotodo","","Ver Codigos",$regnousar,$selecnousar,1,"","","",false);?>
                </div>
                
                <div class="clearboth"></div>
               
                <div class="col-md-6">
                    <label>Grupo M&oacute;dulo</label>
                    <?	FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_gruposmod_orden",array('porderby'=>"grupomodcod"),"formulario","grupomodcod","grupomodcod","grupomodtodo","","Nuevo grupo",$regnousar,$selecnousar,1,"","","",false); ?>
                </div>
                <div class="col-md-6">
                	<label>&nbsp;</label>
                    <input type="text" name="grupomodulo" id="grupomodulo" value="" class="full" />
                </div>
                
                <div class="clearboth"></div>
                
                  
                <div class="col-md-3">
                    <label>Rol</label>
                    <?	
                        $roles=new cRoles($conexion);
                        $roles->RolesSP($spnombre,$spparam);
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formulario","rolcod","rolcod","roltodo","","Seleccione Rol",$regnousar,$selecnousar,1,"","","",false);
                    ?>
                </div>
               
                <div class="clearboth"></div>
                <hr />
                <div class="ancho_9">
                    <div style="text-align:right">
                        <div class="ancho_boton aire"><input class="boton verde" type="submit" name="botonGenerar" onclick="return Validar()" value="Generar" /></div>
                    </div>
                </div>
            </div>
    	</form>
    </div>    
    <div class="clearboth"></div>
</div>
<div class="clearboth"></div>
<?php if($classpanel=="1"){?>
    	</div>
    <?php }?>
<? 
?>