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

$oPaginas = new cPaginas($conexion);

$mensajeaccion = "";
$pagcod = "";
$muestramenu=1;
$pagestadocod = PAGEDICION;	
$estado = "Nueva";
$pagtitulo = "";
$pagsubtitulo = "";
$pagtitulocorto="";
$pagcodsuperior="";
$md5_upd = "";
$catcod = "";
$pagcopete = "";
$pagcuerpo = "";
$edit = false;
$funcionJs="return AgregarPagina()";
$boton = "botonalta";
$botontexto = "Alta de P&aacute;gina";

if (isset($_GET['pagcod']) && $_GET['pagcod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("pagcod"=>$_GET['pagcod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	$pagcod = $_GET['pagcod'];
	if (!$oPaginas->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la pagina por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datospagina = $conexion->ObtenerSiguienteRegistro($resultado);	
	
	$pagcod = $datospagina['pagcod'];
	$catcod = $datospagina['catcod'];
	$pagestadocod = $datospagina['pagestadocod'];	
	$pagtitulo = $datospagina['pagtitulo'];
	$pagsubtitulo = $datospagina['pagsubtitulo'];
	$pagtitulocorto=$datospagina['pagtitulocorto'];
	$pagcopete = str_replace('<div class="space">&nbsp;</div>',"<p></p>",$datospagina['pagcopete']);
	$pagcuerpo = str_replace('<div class="space">&nbsp;</div>',"<p></p>",$datospagina['pagcuerpo']);
	$pagcodsuperior = $datospagina['pagcodsuperior'];
	$muestramenu=$datospagina['muestramenu'];
	$estado = $datospagina['pagestadodesc'];
	$funcionJs="return ModificarPagina()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar P&aacute;gina";

}


function CargarCategorias($catnom,$arreglocategorias,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		 $catnom2 = $fila['catnom'];
		 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
			$catnom2 .="  (".$fila['estadonombre'].")";
		
		?>

        <option <? if (array_key_exists($fila['catcod'],$arreglocategorias)) echo 'selected="selected"'?>  value="<? echo $fila['catcod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom),ENT_QUOTES).$nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>
		<? 
		if (isset($fila['subarbol']) && count ($fila['subarbol'])>0)
		{
			$catnom = $catnom.html_entity_decode(" &raquo;&raquo; ").$catnom;
			CargarCategorias($catnom,$arreglocategorias,$fila['subarbol'],$nivel);
			//$nivel = substr($nivel,0,strlen($nivel)-strlen("&raquo;&raquo;"));
		}
	}
}

function CargarPaginas($pagcod,$pagcodsuperior,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		if ($fila['pagcod']!=$pagcod)
		{
			?>
			<option value="<?php  echo $fila['pagcod']?>" <?php  if ($fila['pagcod']==$pagcodsuperior) echo 'selected="selected"'?>><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['pagtitulo'],ENT_QUOTES)?></option>
			<?php  
			if (isset($fila['subarbol']))
			{
				$nivel .= "---";
				CargarPaginas($pagcod,$pagcodsuperior,$fila['subarbol'],$nivel);
				$nivel = substr($nivel,0,strlen($nivel)-3);
			}
		}
	}
}



if(!$oPaginas->ArmarArbolPaginas("",$arbolpaginas))
	die();


$oPaginasWorkflow = new cPaginasWorkflowRoles($conexion);
$datos['rolcod'] = $_SESSION['rolcod'];
$datos['pagestadocod'] = $pagestadocod;
if(!$oPaginasWorkflow->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
	return false;

$puedeeditar = false;		
if ($numfilasacciones>0)
{
	$puedeeditar = true;
}

$accioneliminar = false;
if($oPaginasWorkflow->TieneAccionEliminar($datos,$nombrebotoneliminar,$paginaworkflowcoddel))
{	
	FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_eliminar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcoddel,"accion"=>1),$geteliminar,$md5eliminar);
	$accioneliminar = true;
}

$accionpublicar = false;
if($oPaginasWorkflow->TieneAccionPublicar($datos,$nombrebotonpublicar,$paginaworkflowcodpub))
{	
	FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_publicar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcodpub,"accion"=>1),$getpublicar,$md5publicar);
	$accionpublicar = true;
}


?>
<link href="modulos/pag_paginas/css/paginas.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/pag_paginas/js/paginas_am.js"></script>
<script type="text/javascript" src="modulos/pag_paginas/js/paginas_modulos.js"></script>

<div id="contentedor_modulo">
	<div id="contenedor_interno">
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2 id="titulo">P&aacute;gina</h2>
</div>  
<div class="row">
    <span class="obligatorio">(*)</span> Recuerde <span style="font-weight:bold;">guardar</span>  antes de <span style="font-weight:bold;">publicar</span> una página para salvar los cambios realizados.
</div>
<div id="DetallePaginaAm">
    <div class="msgaccionpagina">&nbsp;</div>
    <div class="menubarra">
        <ul class="accionespagina">
            <?php  
                $class = "left";			
                $i = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                    if ($i==$numfilasacciones && $i>1)
                        $class="right";
                    
                        ?>
                            <li class="states "><a class="boton base " id="<?php  echo $fila['paginaestadocodfinal']?>" rel="<?php  echo $fila['paginaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['paginaaccion'],ENT_QUOTES);?></a></li>
                        <?php 
                    $i++;
                    $class = "middle";
                }
            ?>
            <?php  if ($accionpublicar && $edit){?>
                <li class="states"><a class="boton verde" href="pag_paginas_publicar.php?<?php  echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
            <?php  }?>
            <?php  if ($accioneliminar && $edit){?>
                <li class="states"><a class="left boton rojo" href="pag_paginas_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
            <?php  }?>
			<?php  if ($edit){?>
                <li class="states "><a class="boton base" href="pag_paginas_salto.php?pagcod=<?php  echo $pagcod?>" target="_blank">Previsualizar</a></li>
            <?php  }?>
            <li class="statese"><a class=" boton base" href="pag_paginas.php">Volver</a></li>
        </ul>
    </div>
    <div class="clear fixalto">&nbsp;</div>
    
   <div class="form">
    <form action="pag_paginas_upd.php" method="post" name="formulario" id="formulario">
        <div class="ancho_10">
             <div class="ancho_5">
                <div class="datosgenerales">
                    <div style="font-size:14px; text-align:right">
                        <label>Estado de la p&aacute;gina: <span id="estadonombre" style="font-weight:normal"><?php  echo $estado?></span></label>
                    </div>

                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>T&iacute;tulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="pagtitulo" id="pagtitulo" class="full" <?php  if (!$puedeeditar) echo 'disabled="disabled"';?> maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagtitulo,ENT_QUOTES);?>" />
                        <div id="pagtituloCharCount" class="charCount">
                            Cantidad de caracteres:
                            <span class="counter">0</span>
                        </div>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Subt&iacute;tulo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="pagsubtitulo" id="pagsubtitulo" class="full" <?php  if (!$puedeeditar) echo 'disabled="disabled"';?> maxlength="145" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagsubtitulo,ENT_QUOTES);?>" />
                        <div id="pagsubtituloCharCount" class="charCount">
                            Cantidad de caracteres:
                            <span class="counter">0</span>
                        </div>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>T&iacute;tulo corto:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <input type="text" name="pagtitulocorto" id="pagtitulocorto" class="full" <?php  if (!$puedeeditar) echo 'disabled="disabled"';?> maxlength="80" size="60" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($pagtitulocorto,ENT_QUOTES);?>" />
                        <div id="pagtitulocortoCharCount" class="charCount">
                            Cantidad de caracteres:
                            <span class="counter">0</span>
                        </div>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Muestra menu lateral:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <select name="muestramenu" id="muestramenu" <?php  if (!$puedeeditar) echo 'disabled="disabled"';?>  class="full" style="width:90px; text-align:left;">
                        <option value="0" <?php  if ($muestramenu=="0") echo 'selected="selected"'?>>No</option>
                        <option value="1" <?php  if ($muestramenu=="1") echo 'selected="selected"'?>>Si</option>
                    </select>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Categor&iacute;a:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <?php
                        $oCategorias=new cPaginasCategorias($conexion);
                        $catsuperior="";
						$estadocombocat = "";
						if($edit == false)
							$estadocombocat = ACTIVO;
                        if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol,$estadocombocat))
                            $mostrar=false;
						$arreglocategoriasSeleccionado = array();
						if ($catcod!="")
							$arreglocategoriasSeleccionado[$catcod] = $catcod;
					?>
                    
                    <select name="catcod" id="catcod" style=" width:100%;" class="chzn-select-categorias">
                        <option value="">Seleccione una categoria...</option>
                    <?php 
                        foreach($arbol as $fila)
                        {

							 $catnom2 =  $catnom =$fila['catnom'];
							 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
								$catnom2 .="  (".$fila['estadonombre'].")";	
							?>

                            <option <?php if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php echo $fila['catcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>

                            <?php 
                            if (isset($fila['subarbol']))
                            {
                                $nivel = " &raquo;&raquo; ";
                                CargarCategorias($catnom,$arreglocategoriasSeleccionado,$fila['subarbol'],$nivel);
                            }
                        }
                        ?>
                     </select>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>P&aacute;gina Superior:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <select name="pagcodsuperior" id="pagcodsuperior" class="full" <?php  if (!$puedeeditar) echo 'disabled="disabled"';?> >
                            <option value="">Raiz...</option>
                        <?php 
                            foreach($arbolpaginas as $fila)
                            {
                                if ($fila['pagcod']!=$pagcod)
								{
									?>
									<option value="<?php  echo $fila['pagcod']?>" <?php  if ($fila['pagcod']==$pagcodsuperior) echo 'selected="selected"'?> ><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['pagtitulo']),ENT_QUOTES)?></option>
									<?php  
									if (isset($fila['subarbol']))
									{
										$nivel = "---";
										CargarPaginas($pagcod,$pagcodsuperior,$fila['subarbol'],$nivel);
									}
								}
                            }
                            ?>
                         </select>
                    </div>
    
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Copete:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                       <?php  if (!$puedeeditar){?>
                            <div style="max-height:120px; overflow-y:auto; background-color:#EBEBE4; border:1px solid #CCC">
                               <?php  echo $pagcopete?>
                            </div>
                       <?php  }else{?>
                        <textarea name="pagcopete" id="pagcopete" class="textarea full rich-text" rows="15" cols="40" wrap="hard"><?php  echo $pagcopete?></textarea>
                        <div class="wordCountclass">
                            <div id="pagcopeteWordCount" class="wordCount"></div>
                        </div>
                        <?php  }?>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                        <label>Cuerpo:</label>
                    </div>
                    <div class="clearboth brisa_vertical">&nbsp;</div>
                    <div>
                        <? if (!$puedeeditar){?>
                            <div style="max-height:120px; overflow-y:auto; background-color:#EBEBE4; border:1px solid #CCC">
                               <? echo $pagcuerpo?>
                            </div>
                       <? }else{?>
                            <div class="alineacion_arroba">
                                <div style="padding:5px">
                                    <? /*<strong>Frase Wide:</strong> (FW)texto (A)autor(A)(FW) <br /><br />*/?>
                                    <strong>Foto Wide:</strong> @fotoC@ <br /><br />
                                    <strong>Foto Izq:</strong> @fotoI@ <br /><br />
                                    <strong>Foto Der:</strong> @fotoD@ <br /><br />
                                    <strong>Video Wide:</strong> @videoC@ <br /><br />
                                    <strong>Video Izq:</strong> @videoI@ <br /><br />
                                    <strong>Video Der:</strong> @videoD@ <br /><br />
                                    <strong>Video Der:</strong> @videoD@ <br /><br />
                                    <strong>Bot&oacute;n Descarga:</strong> (DWBUT)Texto(DWBUT)<br /><br />
                                    <strong>Bot&oacute;n Link:</strong> (BUT)(Link)Link(Link)(Tex)Texto(Tex)(BUT)<br /><br />
                                     <strong>Galer&iacute;a de fotos:</strong> @galleriaFM@<br /><br />
                                     <strong>Atajo BA:</strong> (atajo)<br />
                                     &nbsp;&nbsp;(Col)Columnas(Col)<br />
                                     &nbsp;&nbsp;(Cl)Class(Cl)<br />
                                     &nbsp;&nbsp;(Tex)Texto(Tex)<br />
                                     &nbsp;&nbsp;(Link)Link(Link)<br />
                                     (atajo)
                                </div> 	
                            </div>
                            <div  class="alineacion_cuerpo">
                               <textarea name="pagcuerpo" id="pagcuerpo" class="textarea full rich-text-avanzado" rows="15" cols="40" wrap="hard"><? echo $pagcuerpo?></textarea>
                                 <div class="wordCountclass">
                                    <div id="pagcuerpoWordCount" class="wordCount"></div>
                                </div>
                            </div>
                            <div style="clear:both;"></div>    
					  <? }?>
                 </div>
               </div>


               <div class="ancho_05">&nbsp;</div>
            	<div class="ancho_4">
   				 <div class="datosextrapagina">
                    <?php  if ($puedeeditar){ 
                        $oMultimediaFormulario = new cMultimediaFormulario($conexion,"PAG",$pagcod);
                        echo $oMultimediaFormulario->CargarBotonera();
                      } ?> 
                      <div class="clearboth">&nbsp;</div>
                    <div class="multimedia">
                        <?php  echo $oMultimediaFormulario->CargarListado();?>
                    </div>
                  </div>  
                    <hr style="margin:30px 0" />
                    
                	<div>
                    	<h2 style="font-size:14px; font-weight:bold;">M&oacute;dulos</h2>
                    	<?php  
							if ($puedeeditar)
							{
								$datos['modulotipocod'] = 2;
								$oTapasModulosCategorias= new cTapasModulosCategorias($conexion);
								$oTapasModulosCategorias->BuscarSPxTipo($datos, $spnombre,$sparam);
								FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","catcodModulo","catcod","catdesc","","Seleccione una categoria...",$regactual,$seleccionado,1,"CargarModulo(this.selectedIndex)",false,false);
							}
						?>
                        <div class="clear aire_vertical">&nbsp;</div>
                    	<?php  
							if ($puedeeditar)
							{?>
                                <div class="ancho_4">
                                    <h2 style="font-size:14px; font-weight:bold;">M&oacute;dulos a asignar</h2>
                                    <div id="Modulos" style="background:#FFC; border:2px #000 dashed; min-height:50px;">
                                        &nbsp;
                                    </div>
                                </div>   
                                <div class="ancho_05">&nbsp;</div>
                                <div class="ancho_4">
                                    <h2 style="font-size:14px; font-weight:bold;">M&oacute;dulos Asignados</h2>
                                    <div>
                                        <div id="sortable" style="list-style:none; border:1px solid #CCC; min-height:20px; background:#E6F7FF; border:2px #000 dashed; min-height:50px;">
            
                                        </div>
                                    </div>
                                </div>
                             <?php  }else{?>   
                                <div class="ancho_4">
                                    <h2 style="font-size:14px; font-weight:bold;">M&oacute;dulos Asignados</h2>
                                    <ul>
                                        <?php 
                                        $oPaginasModulos = new cPaginasModulos($conexion);
                                        if(!$oPaginasModulos->BuscarxPagina($datospagina,$resultado,$numfilas))
                                            return false;
                                        
                                        
                                        while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
                                        {
                                            ?>
                                                <li style="border-bottom:1px dashed #CCC; padding:10px 0;">
                                                    <div style=" text-align:center; font-weight:bold; font-size:14px">
                                                        <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['modulodesc'],ENT_QUOTES);?>
                                                    </div>
                                                </li>
                                            <?php  
                                        }     
                                        ?>   
                                    </ul>   
                                </div>
                             <?php  }?>
                        <div cla}ss="clear aire_vertical">&nbsp;</div>
                    </div>
                </div>
        </div>
        <div class="clear aire_vertical">&nbsp;</div>
        <input type="hidden" name="pagestadocod" id="pagestadocod" value="<?php  echo $pagestadocod;?>" />
        <input type="hidden" name="pagcod" id="pagcod" value="<?php  echo $pagcod;?>" />
        <input type="hidden" name="md5" id="md5" value="<?php  echo $md5_upd;?>" />
        <input type="hidden" name="paginaedit" id="paginaedit" value="<?php  echo $edit?>" />
    </form>
    </div>
    <div class="msgaccionpagina">&nbsp;</div>
    <div class="menubarra">
        <ul class="accionespagina">
            <?php  
                if ($puedeeditar)
                    $conexion->MoverPunteroaPosicion($resultadoacciones,0);
                $class = "left";			
                $i = 1;
                while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
                    if ($i==$numfilasacciones && $i>1)
                        $class="right";
                    
                        ?>
                            <li class="states botonesaccion"><a class=" boton base" id="<?php  echo $fila['paginaestadocodfinal']?>" rel="<?php  echo $fila['paginaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['paginaaccion'],ENT_QUOTES);?></a></li>
                        <?php 
                    $i++;
                    $class = "middle";
                }
            ?>
            <?php  if ($accionpublicar && $edit){?>
                <li class="states botonesaccion"><a class="left boton verde" href="pag_paginas_publicar.php?<?php  echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
            <?php  }?>
            <?php  if ($accioneliminar && $edit){?>
                <li class="states botonesaccion"><a class="left boton rojo" href="pag_paginas_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
            <?php  }?>
			<?php  if ($edit){?>
                <li class="states botonesaccion"><a class="left boton base" href="pag_paginas_salto.php?pagcod=<?php  echo $pagcod?>" target="_blank">Previsualizar</a></li>
            <?php  }?>
            <li class="states botonesaccion"><a class="left boton base" href="pag_paginas.php">Volver</a></li>
        </ul>
    </div>
    <div class="clearfix aire">&nbsp;</div>
<div class="row">
    <span class="obligatorio">(*)</span> Recuerde <span style="font-weight:bold;">guardar</span>  antes de <span style="font-weight:bold;">publicar</span> una página para salvar los cambios realizados.
</div>
<div class="clearfix aire">&nbsp;</div>
    <div class="clear fixalto">&nbsp;</div>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="PopupModulo"></div>
<?php 
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>