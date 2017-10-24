<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$oNoticiasMultimedia = new cNoticiasMultimedia($vars['conexion'],"");

$oPaises = new cPaises($vars['conexion']);
$oMultimedia = new cMultimedia($vars['conexion'],"");

$oPaises->BuscarpaisesActivos ($resultadoPaises,$numfilasPaises);

$oCategorias = new cCategorias($vars['conexion']);
$datos["catestado"]=ACTIVO;
if (!$oCategorias->BuscaCategoriasxEstado($datos,$resultadoCat,$numfilasCat))
	return false;


?>
        
<div class="cajamultimediahome tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
    <div class="caja clearfix">
    	<div class="clearboth aire">&nbsp;</div>
        <div class="menu">
        	<div class="menuinterno">
                <h2><span class="iconomultimedia video">&nbsp;</span> VIDEOS</h2>
                <hr />
                <div class="combo">
                    <div class="encabezadocombo">
                        <a href="javascript:void(0)" onclick="$('#PaisesVideos').toggle();">
                            Eleg&iacute;r pa&iacute;s
                            <span class="icon iconFlechaCombo icon-opendiv" id="flechacomboimg" style="display: block;">&nbsp;</span>
                        </a>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div id="PaisesVideos" class="lst_paises_multimedia" style="display: none;">
                        <ul>
                            <?php  while($filaPais = $vars['conexion']->ObtenerSiguienteRegistro($resultadoPaises)){?>
                                <li>
                                    <a href="/multimedia/<?php  echo $filaPais['paisdominio']?>/videos" title="Multimedia de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaPais['paisdesc'],ENT_QUOTES)?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaPais['paisdesc'],ENT_QUOTES)?></a>
                                </li>
                            <?php  }?>    
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                </div>
                <div class="clearboth">&nbsp;</div>
                <hr />
                <div class="combo">
                    <div class="encabezadocombo">
                        <a href="javascript:void(0)" onclick="$('#CategoriaVideos').toggle();">
                            Eleg&iacute;r categor&iacute;a
                            <span class="icon iconFlechaCombo icon-opendiv" style="display: block;">&nbsp;</span>
                        </a>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div id="CategoriaVideos" class="lst_categorias_multimedia" style="display: none;">
                        <ul>
                            <?php  while($filaCategoria = $vars['conexion']->ObtenerSiguienteRegistro($resultadoCat)){?>
                                <li>
                                    <a href="/multimedia/<?php  echo $filaCategoria['catdominio']?>/videos" title="Multimedia de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCategoria['catnom'],ENT_QUOTES)?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCategoria['catnom'],ENT_QUOTES)?></a>
                                </li>
                            <?php  }?>    
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                </div>
                <div class="clearboth">&nbsp;</div>
                <hr />
            </div>    
        </div>
        <div class="listado">
                <ul>
                    <?php 
                    foreach ($objDataModel->video_noticiacod as $noticiacod=>$multimediacod)
                    {
                            $datosbusqueda['noticiacod'] = $noticiacod;
                            if(!$oNoticias->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
                                return false;
                            
                            $datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                        
                            $titulo = $datosnoticia['noticiatitulo'];
                            $catdominio = $datosnoticia["catdominio"];
                            $dominio = $datosnoticia["noticiadominio"];
                            $catnom = $datosnoticia["catnom"];
                            $catcod = $datosnoticia["catcod"];
                            $catcolor = $datosnoticia["catcolor"];
                            $paisprefijo = $datosnoticia["paisprefijo"];
                            $paiscolor = $datosnoticia["paiscolor"];
                            $paisdesc=$datosnoticia["paisdesc"];	
                            if ($paiscolor!="")
                                $paiscolor="style='color:".$paiscolor."'";					
                            if ($catcolor!="")
                                $catcolor="style='color:".$catcolor."'";					
                            $url = DOMINIORAIZSITE.$catdominio."/".$dominio;
							$conFoto = false;
							
                            $datosnoticia['multimediacod'] = $multimediacod;
							if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticiaxCodigoMultimedia($datosnoticia,$resultado,$numfilas))
								die();
								
							if ($numfilas!=0)
							{
								$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
								$conFoto = true;
							}
                            
                            ?>
                            <?php  if ($conFoto){?>
                            <li>
                               	<a href="<?php  echo $url?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES)?>">
                                    <img src="<?php  echo $oMultimedia->ArmarImagenVideo($datosFoto["multimediatipocod"],$datosFoto["multimediaidexterno"])?>" alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES)?>" />
                             	</a>  
                            </li>
                            <?php  }?>
                       <?php  }?>
                       
                </ul>
        </div>
    </div>
    <div class="clearboth aire">&nbsp;</div>
    <hr />
    <div class="clearboth aire">&nbsp;</div>
    <div class="caja clearfix">
        <div class="menu">
        	<div class="menuinterno">
                <h2><span class="iconomultimedia foto">&nbsp;</span> FOTOS</h2>
                <hr />
                
                <div class="combo">
                    <div class="encabezadocombo">
                        <a href="javascript:void(0)" onclick="$('#PaisesFotos').toggle();">
                            Eleg&iacute;r pa&iacute;s
                            <span class="icon iconFlechaCombo icon-opendiv" style="display: block;">&nbsp;</span>
                        </a>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div id="PaisesFotos" class="lst_paises_multimedia" style="display: none;">
                        <ul>
                            <?php  
                                $vars['conexion']->MoverPunteroaPosicion($resultadoPaises,0);
                                while($filaPais = $vars['conexion']->ObtenerSiguienteRegistro($resultadoPaises)){?>
                                <li>
                                    <a href="/multimedia/<?php  echo $filaPais['paisdominio']?>" title="Multimedia de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaPais['paisdesc'],ENT_QUOTES)?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaPais['paisdesc'],ENT_QUOTES)?></a>
                                </li>
                            <?php  }?>    
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                </div>
                 <div class="clearboth">&nbsp;</div>
                <hr />
                <div class="combo">
                    <div class="encabezadocombo">
                        <a href="javascript:void(0)" onclick="$('#CategoriaFotos').toggle();">
                           Eleg&iacute;r categor&iacute;a
                            <span class="icon iconFlechaCombo icon-opendiv" style="display: block;">&nbsp;</span>
                        </a>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div id="CategoriaFotos" class="lst_categorias_multimedia" style="display: none;">
                        <ul>
                            <?php  
                            $vars['conexion']->MoverPunteroaPosicion($resultadoCat,0);
                            while($filaCategoria = $vars['conexion']->ObtenerSiguienteRegistro($resultadoCat)){?>
                                <li>
                                    <a href="/multimedia/<?php  echo $filaCategoria['catdominio']?>/videos" title="Multimedia de <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCategoria['catnom'],ENT_QUOTES)?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCategoria['catnom'],ENT_QUOTES)?></a>
                                </li>
                            <?php  }?>    
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                </div>
                <div class="clearboth">&nbsp;</div>
                <hr />
            </div>
        </div>
      <div class="listado">
       	<ul>
            	<?php 
                    foreach ($objDataModel->foto_noticiacod as $noticiacod=>$multimediacod)
                    {
                            $datosbusqueda['noticiacod'] = $noticiacod;
                            if(!$oNoticias->BuscarDatosCompletosNoticiasPublicadasxCodigo($datosbusqueda,$resultado,$numfilas))
                                return false;
                            
                            $datosnoticia = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                        
                            $titulo = $datosnoticia['noticiatitulo'];
                            $catdominio = $datosnoticia["catdominio"];
                            $dominio = $datosnoticia["noticiadominio"];
                            $catnom = $datosnoticia["catnom"];
                            $catcod = $datosnoticia["catcod"];
                            $catcolor = $datosnoticia["catcolor"];
                            $paisprefijo = $datosnoticia["paisprefijo"];
                            $paiscolor = $datosnoticia["paiscolor"];
                            $paisdesc=$datosnoticia["paisdesc"];	
                            if ($paiscolor!="")
                                $paiscolor="style='color:".$paiscolor."'";					
                            if ($catcolor!="")
                                $catcolor="style='color:".$catcolor."'";					
                            $url = DOMINIORAIZSITE.$catdominio."/".$dominio;
							$conFoto = false;
							
                            $datosnoticia['multimediacod'] = $multimediacod;
							if(!$oNoticiasMultimedia->BuscarMultimediaxCodigoNoticiaxCodigoMultimedia($datosnoticia,$resultado,$numfilas))
								die();
								
							if ($numfilas!=0)
							{
								$datosFoto = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
								$conFoto = true;
							}
                            
                            ?>
                            <li>
                               <?php  if ($conFoto){
								   $tamanio = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosFoto["multimediacatcarpeta"]."S/".$datosFoto["multimediaubic"]);
								   
								   //achico proporcionalmente
								   $alto=round((236*$tamanio[1])/$tamanio[0]);
								   $margintop=0;
								   if ($alto<177)
								   {
								   		$margintop=round((177-$alto)/2);
										?> <div class="clearboth" style="height:<?php  echo $margintop?>px;">&nbsp;</div><?php 
								   }
								   ?>
                                    <a href="<?php  echo $url?>" title="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES)?>">
                                        <img src="<?php  echo $oMultimedia->DevolverDireccionImgTamanio($datosFoto["multimediacatcarpeta"],"S/",$datosFoto["multimediaubic"])?>"  alt="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES)?>" />
                                    </a>  
                               <?php  }?>
                               
                            </li>
                       <?php  }?>
          </ul>
        </div>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth aire">&nbsp;</div>
</div>
