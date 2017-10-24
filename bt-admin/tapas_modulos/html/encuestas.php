<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$encuestacod = "";
$existeencuesta = false;
$oEncuestas = new cEncuestas($vars['conexion']);
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	if (isset($objDataModel->encuestacod))
	{
		$encuestacod = $objDataModel->encuestacod;
		$datosbusqueda['encuestacod'] = $encuestacod;

		if (!$oEncuestas->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
		$datosencuesta = $vars['conexion']->ObtenerSiguienteRegistro($resultado);	
		$dominioencuesta = FuncionesPHPLocal::EscapearCaracteres($datosencuesta['encuestapregunta']);
		$dominioencuesta=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioencuesta));
		$dominioencuesta=str_replace(' ', '-', trim($dominioencuesta));
		$dominioencuesta = "encuesta/".$datosencuesta['encuestacod']."-".$dominioencuesta;
		
		if (!$oEncuestas->BuscarEncuestasOpciones($datosencuesta,$resultadoopciones,$numfilasopciones))
			return false;

		$existeencuesta = true;
	}
}



?>
<script type="text/javascript" src="<?php  echo DOMINIORAIZSITE?>js/encuestas.js"></script>
<div class="caja_encuesta tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
<?php  echo $vars['htmledit'];?>
<?php  if ($existeencuesta){?>     
	<div class="titencuesta">Encuesta</div>
	<h2 class="pregunta"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree( $datosencuesta['encuestapregunta'],ENT_QUOTES);?></h2>
    <div class="clearboth">&nbsp;</div>
    <div id="ResultadosEncuesta"></div>
    <div class="divVotando"><div class="loadingVotando">Votando encuesta...</div></div>
	<div id="FormEncuesta">
        <div class="opcionespreguntas">
            <form action="<?php  echo DOMINIORAIZSITE?><?php  echo $dominioencuesta?>/responder" method="post" name="formulario_encuesta" onsubmit="return false;">
            <?php 
                while ($filaopciones = $conexion->ObtenerSiguienteRegistro($resultadoopciones)){?>
                    <div class="opciones">
                        <div class="opcion">
                            <input type="radio" tabindex="6" name="opcioncod"  id="opcion_<?php  echo $filaopciones['opcioncod']?>_<?php  echo $vars['zonamodulocod']?>" value="<?php  echo $filaopciones['opcioncod'] ?>" />
                        </div>
                        <div class="txtopcion">
                           <label for="opcion_<?php  echo $filaopciones['opcioncod']?>_<?php  echo $vars['zonamodulocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaopciones['opcionnombre'],ENT_QUOTES) ?></label>
                        </div>
                        <div class="clearboth">&nbsp;</div>
                    </div>
                <?php  }?>
                <div class="divbotonvotar">
                    <input type="hidden" value="<?php  echo $encuestacod?>" name="encuestacod" id="encuestacod" />
                    <input type="submit" name="Votar" class="botonvotar" id="BtVotar"  value="Votar" onclick="VotarEncuesta('.divVotando','#FormEncuesta','#ResultadosEncuesta','#BtVotar',<?php  echo $encuestacod?>); return false;" />
                    <div class="botonverresultados">
                        <a href="<?php  echo DOMINIORAIZSITE?><?php  echo $dominioencuesta?>" title="Ver resultados de la encuesta <?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree( $datosencuesta['encuestapregunta'],ENT_QUOTES);?>">Ver Resultados</a>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                </div>    
            </form>
        </div>
    </div>
	<?php  
}?>    
</div>
<?php  