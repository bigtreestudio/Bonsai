<?php  

require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$fileTapasModulos = file_get_contents(PUBLICA."json/tapas_modulos_1.json");
$arrayTapasModulos = json_decode($fileTapasModulos,true);
$arrayTapasModulos = FuncionesPHPLocal::ConvertiraUtf8($arrayTapasModulos);
$arrayAccesoDirectos = array();
if(count($arrayTapasModulos)>0)
{
	foreach($arrayTapasModulos as $key=>$datos)
	{
		foreach($datos['modulos'] as $key=>$modulos)
		{
				if($modulos['moduloaccesodirecto']==1)
				{
					$arrayAccesoDirectos[$modulos['modulocod']]=array();
					$arrayAccesoDirectos[$modulos['modulocod']]['modulocod']= $modulos['modulocod'];
					$arrayAccesoDirectos[$modulos['modulocod']]['modulodesc']= $modulos['modulodesc'];
					$arrayAccesoDirectos[$modulos['modulocod']]['moduloicono']= $modulos['moduloicono'];
					
				}
		}
	}
}
$catcod='';	
$modulocod='';

?>

<script type="text/javascript" src="modulos/tap_tapas/js/modulos_categorias.js"></script>
<?php if(count($arrayAccesoDirectos)>0){ ?>
<ul style="list-style: none;color: #FFF; width: auto;">
	<?php foreach ($arrayAccesoDirectos as $key=>$datosiconos) {?>
    	<li style="float: left;width: 100px;margin-left: 20px !important;">
        	<a href="javascript:void(0);" title="Abrir <?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosiconos['modulodesc'],ENT_QUOTES)?>" class="modulo_icono" onclick="AbrirAgregarModulosAccesosDirectos(<?php echo $datosiconos['modulocod']?>)">
            	<i class="fa <? echo $datosiconos['moduloicono']?>" style='font-size:48px;color:#69F'></i>
            </a>
	<?php } ?>
</ul>
<div style="clear:both">&nbsp;</div>
<span id="nombre_modulo" style="font-size:12px;color:#69F;">&nbsp;</span>
<hr />
<div style="clear:both">&nbsp;</div>
<?php } ?>
<form action="javascript:void(0)" method="post" name="formulario_add_module" id="formulario_add_module">

        <div class="ancho_10">
            <div class="ancho_3">
                <label>Seleccione una categoria: </label>
            </div>
            <div class="ancho_7" >
              <select name="catcod" id="catcod"  onchange="CargarModulo($(this).val())" >
              	<option value="" <?php if($catcod=="")  echo 'selected="selected"';?>>Seleccione una Categoria</option>
               	<?php foreach($arrayTapasModulos as $keycatcod=>$datos){ ?>
                	<option value="<?php echo $keycatcod?>" <?php if($catcod==$keycatcod) echo 'selected="selected"';?>><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['catdesc'],ENT_QUOTES)?></option>
                <?php } ?>
               </select> 
            </div>
        </div>   
        <div style="clear:both">&nbsp;</div>
        <div id="Modulos">
        	<div class="ancho_10">
                <div class="ancho_3">
                    <label>Seleccione un modulo: </label>
                </div>
                <div class="ancho_7">
                    <select name="modulocod" id="modulocod" style="width:200px" >
                    <option value="" <?php if($modulocod=="")  echo 'selected="selected"';?>>Todos</option>
                    </select>
                </div>
            </div>        
        </div>           
</form>

