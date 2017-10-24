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
$modulocod='';

$fileTapasModulos = file_get_contents(PUBLICA."json/tapas_modulos_1.json");
$arrayTapasModulos = json_decode($fileTapasModulos,true);
$arrayTapasModulos = FuncionesPHPLocal::ConvertiraUtf8($arrayTapasModulos);

?>

<form action="javascript:void(0)" method="post" name="formulario_add_module" id="formulario_add_module">

        <div class="ancho_10">
            <div class="ancho_3">
                <label>Seleccione un modulo: </label>
            </div>
            <div class="ancho_7">
                <select name="modulocod" id="modulocod" style="width:200px" >
              	<option value="" <?php if($modulocod=="")  echo 'selected="selected"';?>>Todos</option>
               	<?php foreach($arrayTapasModulos[$_POST['catcod']]['modulos'] as $keymodulocod=>$datos){ ?>
                    <option value="<?php echo $keymodulocod?>" <?php if($modulocod==$keymodulocod) echo 'selected="selected"';?>><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['modulodesc'],ENT_QUOTES)?></option>
				<?php } ?>
               </select> 
            </div>

        </div>     
        <div style="clear:both">&nbsp;</div>
        <div class="menucarga" style="text-align:center">
                <ul>
                    <li style="width:100% !important">
                        <a class="boton verde centro" href="javascript:void(0)" onclick="AbrirAgregarModulos()">Agregar</a>
                    </li>
                </ul>
        </div>            
        <div style="clear:both">&nbsp;</div>
</form>