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

$oPagina = new cPaginas($conexion);
$oPaginasWorkflow = new cPaginasWorkflowRoles($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 


$pagestadocod = PAGEDICION;
$datos['rolcod'] = $_SESSION['rolcod'];
$datos['pagestadocod'] = $pagestadocod;
$editar = false;
if (isset($_POST['pagcod']) && $_POST['pagcod']!="")
{
	$pagcod = $datos['pagcod'] = $_POST['pagcod'];
	if(!$oPagina->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
		die();
	}
	$datospagina = $conexion->ObtenerSiguienteRegistro($resultado);
	$editar = true;
	$datos['pagestadocod'] = $datospagina['pagestadocod'];
}

if(!$oPaginasWorkflow->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
	return false;

$accioneliminar = false;
if($editar && $oPaginasWorkflow->TieneAccionEliminar($datos,$nombrebotoneliminar,$paginaworkflowcoddel))
{	
	FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_eliminar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcoddel,"accion"=>1),$geteliminar,$md5eliminar);
	$accioneliminar = true;
}

$accionpublicar = false;
if($editar && $oPaginasWorkflow->TieneAccionPublicar($datos,$nombrebotonpublicar,$paginaworkflowcodpub))
{	
	FuncionesPHPLocal::ArmarLinkMD5("pag_paginas_publicar.php",array("pagcod"=>$pagcod,"paginaworkflowcod"=>$paginaworkflowcodpub,"accion"=>1),$getpublicar,$md5publicar);
	$accionpublicar = true;
}


switch($_POST['accion'])
{
	case 1:
		$class = "left";			
		$i = 1;
		while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoacciones)){
			if ($i==$numfilasacciones && $i>1)
				$class="right";
				?>
                    <li class="states"><a class="boton base" id="<?php  echo $fila['paginaestadocodfinal']?>" rel="<?php  echo $fila['paginaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['paginaaccion'],ENT_QUOTES);?></a></li>
				<?php 
			$i++;
			$class = "middle";
		}
		?>
        <?php  if ($accionpublicar){?>
            <li class="states"><a class="left boton verde" href="pag_paginas_publicar.php?<?php  echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
        <?php  }?>
        <?php  if ($accioneliminar){?>
            <li class="states"><a class="left boton rojo" href="pag_paginas_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la pagina?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
        <?php  }?>
		<?php  if ($editar){?>
            <li class="states"><a class="left boton base" href="pag_paginas_salto.php?pagcod=<?php  echo $pagcod?>" target="_blank">Previsualizar</a></li>
        <?php  }?>
        <li class="states"><a class="left boton base" href="pag_paginas.php">Volver</a></li>
		<?php  
		break;
		
	case 2:
		break;
		
}
?>