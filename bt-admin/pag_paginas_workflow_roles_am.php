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

header('Content-Type: text/html; charset=iso-8859-1');


?>
<div class="form">
    <form action="pag_paginas_workflow_roles_am.php" method="post" name="formworkflow" id="formworkflow" >
        <div class="datosgenerales">
            <div>
                <label>Seleccione un rol:</label>
            </div>
            <div class="clearboth brisa_vertical">&nbsp;</div>
            <div>
                <?php 	
                    $roles=new cRoles($conexion);
                    $roles->RolesSP($spnombre,$spparam);
                    FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formworkflow","rolcod","rolcod","roltodo","","Seleccione un rol",$regnousar,$selecnousar,1,"CargarEstados()","","",false);
                ?>
            </div>
            <div class="aire clearboth">&nbsp;</div>
             <div>
                <label>Estado inicial:</label>
             </div>
             <div id="Estados">
                Seleccione un rol para cargar los estados
             </div>   
             <div class="aire clearboth">&nbsp;</div>
             <div>
                <label>Acciones:</label>
             </div>
             <div id="Acciones">
                Seleccione un rol y un estado para cargar las acciones
             </div>   
        </div>
    </form>
</div>
                      

<?php  ?>