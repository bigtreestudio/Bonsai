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

$oContactos= new cContactos($conexion);

define("PAIS",1);



$oPais = new cPaises($conexion);
$datos['paiscod'] = PAIS;
if (!$oPais->BuscarxCodigo($datos,$resultadopais,$numfilaspais))
	die();
$datospais = $conexion->ObtenerSiguienteRegistro($resultadopais);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$act_des = "";	
$formulariocod = "";
$formularioestado = "";	
$formulariotipocod ="";
$formulariotipotitulo = "";
$formulariodireccion = "";
$formulariotelefono1 = "";
$formulariotelefono2 = "";
$formulariocelular= "";
$formulariomail = "";
$formularioweb = "";
$formulariotwitter = "";
$formulariofacebook= "";
$formulariolatitud = "";
$formulariolongitud= "";
$formulariociudad = "";
$provinciacod= "";
$paiscod = 1;
$departamentocod = "";
$formulariomaildesde = ""; 
$formulariojson = ""; 
$formulariotexto= "";
$formulariomaildesde= "";
$formularioestado= "";
$formulariocp="";
$formulariopiso="";
$formulariodisclaimer="";
$volver = "";
$accion = 1;
$edit = false;
$funcionJs="return InsertarFormContacto()";
$boton = "botonalta";
$botontexto = "Alta de Formulario";
$esbaja  = false;

$mapa_tipo="google.maps.MapTypeId.ROADMAP";
$formulariolatitud="-34.651285198954135";//lat
$formulariolongitud="-58.77685546875";//long
$formulariomapazoom=10;
$dominioform="";


if (isset($_GET['formulariocod']) && $_GET['formulariocod']!="")
{
	
	$formulariocod = $_GET['formulariocod'];
	if (!$oContactos->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el formulario por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosformulario = $conexion->ObtenerSiguienteRegistro($resultado);	
	
	if (!$oContactos->BuscarxCodigo($datosformulario,$resultadotipo,$numfilastipo))
		return false;
	$datosformulariotipo = $conexion->ObtenerSiguienteRegistro($resultadotipo);		
	
	$funcionJs="return ModificarFormContacto()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar Formulario";

	$formulariotipodesc = $datosformulariotipo['formulariotipodesc'];
	$formulariotipocod = $datosformulario['formulariotipocod']; 
	$formularioestado = $datosformulario['formularioestado'];
	$formulariotipotitulo = $datosformulario['formulariotipotitulo'];
	$formulariodireccion = $datosformulario['formulariodireccion'];
	$formulariotelefono1 = $datosformulario['formulariotelefono1'];
	$formulariotelefono2 = $datosformulario['formulariotelefono2'];	
	$formulariocelular = $datosformulario['formulariocelular'];	
	$formulariomail = $datosformulario['formulariomail'];	
	$formularioweb = $datosformulario['formularioweb'];	
	$formulariotwitter = $datosformulario['formulariotwitter'];	
	$formulariofacebook = $datosformulario['formulariofacebook'];	
	$formulariociudad = $datosformulario['formulariociudad'];
	$formulariocp = $datosformulario['formulariocp'];		
	$formulariopiso = $datosformulario['formulariopiso'];	
	$provinciacod = $datosformulario['provinciacod'];	
	$formulariotexto = $datosformulario['formulariotexto'];	
	$formulariomaildesde = $datosformulario['formulariomaildesde'];		
	$formularioestado = $datosformulario['formularioestado'];	
	$formulariodisclaimer = $datosformulario['formulariodisclaimer'];	
	
	$dominio="";
	$dominioform = FuncionesPHPLocal::EscapearCaracteres($formulariotipotitulo);
	$dominioform=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioform));
	$dominioform=str_replace(' ', '-', trim($dominioform))."_c".$formulariocod;


	if (trim($datosformulario['formulariolatitud'])!="")
		$formulariolatitud=$datosformulario['formulariolatitud'];
	
	if (trim($datosformulario['formulariolongitud'])!="")
		$formulariolongitud=$datosformulario['formulariolongitud'];
		
	if ($datosformulario['formulariomapazoom']!="")
		$formulariomapazoom=$datosformulario['formulariomapazoom'];
	
	if ($datosformulario['formulariomapatipo']!="")
		$mapa_tipo=$datosformulario['formulariomapatipo'];
				
	$mostrarformulario = false;

}

?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/maps/googlemaps.js"></script>
<script type="text/javascript" src="modulos/con_contactos/js/con_contactos_am.js"></script>
<link rel="stylesheet" type="text/css" href="js/maps/estilos.css" />

<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">Formulario</h2>
</div>  
<div class="form">
<form action="ban_banners_upd.php" method="post" name="formulario" id="formulario">
    <input type="hidden" name="formulariocod" id="formulariocod" value="<?php  echo $formulariocod;?>" />
    <input type="hidden" name="accion" id="accion" value="<?php  echo $accion?>">
	<div class="ancho_10">
         <div class="ancho_4">
            <div class="datosgenerales">
                <div>
                    <label>Tipo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <?php  
						$oContactos= new cContactos($conexion);
						$oContactos->BusquedaFormularioTipoSP($spnombre,$sparam);
						FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","formulariotipocod","formulariotipocod","formulariotipodesc",$formulariotipocod,"Todos",$regactual,$seleccionado,1,"","width: 200px",false,false);
                    ?>          
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
				
                <div>
                    <label>Dominio:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div style="font-size:14px; margin-left:10px;">
                    <?php  if ($dominioform!=""){?>
						<b><?php  echo DOMINIOWEB.$dominioform;?></b>
                    <?php  }?>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>                
                <div>
                    <label>T&iacute;tulo:</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <input type="text" name="formulariotipotitulo" id="formulariotipotitulo" class="full" maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariotipotitulo,ENT_QUOTES);?>" />
                    <div id="bannerdescCharCount" class="charCount">
                        Cantidad de caracteres:
                        <span class="counter">0</span>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>                
                <div>
                    <label>Provincia:</label>
                </div>

                 <div class="ancho_3">
                    <?php  
                        $oProvincias=new cProvincias($conexion);
                        $oProvincias->ProvinciasSP($spnombre,$sparam);						
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","provinciacod","provinciacod","provinciadesc",$provinciacod,"Provincia...",$regnousar,$selecnousar,1,"return CargarComboCiudad(\"#ComboCiudad\")","width: 200px",false,false,"",false,"",16); 
                    ?>
                 </div>
                <div class="clearboth aire_menor">&nbsp;</div>
 				<div>
                    <label>Ciudad / Localidad:</label>
                </div>   
                 <div class="ancho_3" id="ComboCiudad">
                    <?php 
                        $oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");
                        $oDepartamentos->DepartamentoSP($spnombre,$sparam);
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","departamentocod","departamentocod","departamentodesc",$formulariociudad,"Localidad...",$regnousar,$selecnousar,1,"","width: 200px",false,false,"",false,"",17);
                    ?>
                 </div>
                       
				</div>

         	       <div class="clearboth aire_menor">&nbsp;</div>
 				<div>
                    <label>Direcci&oacute;n (Calle + Nro)</label>
                </div>					
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="left brisa_derecha">
                    <input class="inputtext"  type="text" id="formulariodireccion" name="formulariodireccion" value="<?php  echo $formulariodireccion?>" size="38" maxlength="150" />
                </div>
                
                <div class="clearboth aire_menor">&nbsp;</div>                
                <div id="suggest_list"></div>
	
                <div class="clearboth" style="height:5px;"></div>

                <div>
                    <label>Piso:</label>
                </div>

                 <div class="ancho_3">
                    <input class="inputtext"  type="text" id="formulariopiso" name="formulariopiso" value="<?php  echo $formulariopiso?>" size="38" maxlength="150" />
                 </div>
         	  
                 <div class="clearboth aire_menor">&nbsp;</div>                
                <div>
                    <label>C&oacute;digo Postal</label>
                </div>

                 <div class="ancho_3">
                    <input class="inputtext"  type="text" id="formulariocp" name="formulariocp" value="<?php  echo $formulariocp?>" size="38" maxlength="150" />
                 </div>
				<div class="clearboth">&nbsp;</div>
               

                 <div class="ancho_4">
                    <div>          
                        <label>Latitud:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariolatitud" id="formulariolatitud" class="full" maxlength="255" size="40" value="<?php  echo $formulariolatitud;?>" />
                    </div>                        
          		</div>  

				<div class="ancho_2">&nbsp;</div>
               <div class="ancho_4">
                    <div>          
                        <label>Longitud:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div style="padding-right:10px">
                        <input type="text" name="formulariolongitud" id="formulariolongitud" class="full" maxlength="255" size="40" value="<?php  echo $formulariolongitud;?>" />
                    </div>                        
          		</div>   
               
				<div class="clearboth">&nbsp;</div>
					
 
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Tel&eacute;fono:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariotelefono1"id="formulariotelefono1" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariotelefono1,ENT_QUOTES);?>" />
                    </div>                        
          		</div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Tel&eacute;fono adicional:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariotelefono2" id="formulariotelefono2" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariotelefono2,ENT_QUOTES);?>" />
                    </div>                        
          		</div>  
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Celular:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariocelular" id="formulariocelular" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariocelular,ENT_QUOTES);?>" />
                    </div>                        
          		</div> 
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Email:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariomail" id="formulariomail" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariomail,ENT_QUOTES);?>" />
                    </div>                        
          		</div> 

                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>WEB:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formularioweb" id="formularioweb" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formularioweb,ENT_QUOTES);?>" />
                    </div>                        
          		</div>                                                 
                <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Twitter:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariotwitter" id="formulariotwitter" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariotwitter,ENT_QUOTES);?>" />
                    </div>                        
          		</div>                                                 
                 <div class="clearboth aire_menor">&nbsp;</div>
                <div class="ancho_5">
                    <div>          
                        <label>Facebook:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="formulariofacebook" id="formulariofacebook" class="full" maxlength="255" size="40" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($formulariofacebook,ENT_QUOTES);?>" />
                    </div>                        
          		</div>    
                 <div class="clearboth aire_menor">&nbsp;</div>
				<div class="ancho_5">	
                    <div>          
                        <label>Estado:</label>
                    </div>
                    
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="formularioestado" id="formularioestado" style="width:200px;">
                            <option <?php  if ($formularioestado==ACTIVO || $formularioestado==""  ) echo 'selected="selected"'?> value="<?php  echo ACTIVO?>">Activo</option>
                            <option <?php  if ($formularioestado==NOACTIVO) echo 'selected="selected"'?> value="<?php  echo NOACTIVO?>">No activo</option>
                        </select>
                    </div>
                </div>         
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Descripci&oacute;n Larga</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="formulariotexto" id="formulariotexto" class="textarea full rich-text" rows="15" cols="40" wrap="hard"><?php  echo $formulariotexto;?></textarea>
                    <div class="wordCountclass">
                        <div id="bannerdesclargaWordCount" class="wordCount"></div>
                    </div>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
                <div>
                    <label>Disclaimer en mail</label>
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
                <div>
                    <textarea name="formulariodisclaimer" id="formulariodisclaimer" class="textarea full" rows="15" cols="40" wrap="hard"><?php  echo $formulariodisclaimer;?></textarea>
                </div>
                <div class="clearboth aire_menor">&nbsp;</div>
           </div>
            <div class="ancho_1">&nbsp;</div>
            <div class="ancho_4">
                <div id="divGoogleMaps"  style="display:block;position:relative;height:400px !important; margin:auto;"></div>
				<script language="javascript">
				var ObjMapa;
                $(document).ready(function() {
                    ObjMapa = $("#divGoogleMaps").mapaBigTree({
                            'IdBuscador': {'idBuscador':'formulariodireccion', 'idBuscadorListado':'suggest_list'},
                            'beforeSelected':  CargarIds,	
                            'onChangeZoom':  ModificarZoom,	
                            'onChangeMapType':  CargarTipoMapa,	
                            'zoom':	<?php  echo $formulariomapazoom?>,
                            'lat':	<?php  echo $formulariolatitud?>,
                            'long':	<?php  echo $formulariolongitud?>,
							'tipo': <?php  echo $mapa_tipo?>,
                            'MultipleMarkers':  false					
                        }
                    );
                    ObjMapa.Inicializate();
                    ObjMapa.AcceptAddMarkerButton("rightclick");
                    <?php  
                    if ($edit)
                    {?>ObjMapa.AddMarker(<?php  echo $formulariolatitud?>,<?php  echo $formulariolongitud?>);<?php  }
                    ?>
                });

                function CargarIds(e,location)
                {
                    $("#formulariolatitud").val(location.lat());
                    $("#formulariolongitud").val(location.lng());
                }
				
                function ModificarZoom(zoom)
                {
					$("#formulariomapazoom").val(zoom);
                }
                function CargarTipoMapa(type)
                {
					$("#formulariomapatipo").val(type);
                }
                </script>
            </div>
    </div>
    <input type="hidden" name="formulariojson" id="formulariojson" value="<?php  echo $formulariojson ;?>" />
    <input type="hidden" name="formulariomaildesde" id="formulariomaildesde" value="<?php  echo $formulariomaildesde ;?>" />
    <input type="hidden" name="paiscod" id="paiscod" value="<?php  echo $paiscod ;?>" />
    <input type="hidden" name="formulariociudad" id="formulariociudad" value="<?php  echo $departamentocod ;?>" />  
    <input type="hidden" name="formulariomapatipo" id="formulariomapatipo" value="<?php  echo $mapa_tipo ;?>" />  
    <input type="hidden" name="formulariomapazoom" id="formulariomapazoom" value="<?php  echo $formulariomapazoom ;?>" />  
      
    <div style="clear:both">&nbsp;</div>
    <div class="clear aire_vertical">&nbsp;</div>
    <div class="msgaccioncontacto">&nbsp;</div> 
    <div class="ancho_10">
     <div class="menubarra">
         <ul>
                <li><a class="boton verde" href="javascript:void(0)" onclick="<?php  echo $funcionJs?>"><?php  echo $botontexto?></a></li>
                <li><a class="boton base" href="con_contactos.php">Volver sin guardar</a></li>
                <?php  if ($edit) {?>
                	<li><a class="boton rojo" href="javascript:void(0)" onclick="EliminarFormContacto(<?php  echo $formulariocod;?>)">Eliminar</a></li>
                <?php  }?>
        </ul>
     </div>
  </div>
</form>
</div> 
<div class="clear aire_vertical">&nbsp;</div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>