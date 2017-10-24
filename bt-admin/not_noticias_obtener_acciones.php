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

$oNoticias = new cNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$oNoticiasAcciones = new cNoticiasWorkflowRoles($conexion);

$datos['noticiaestadocod'] = $noticiaestadocod = NOTBORRADOR;
$datos['rolcod'] = $_SESSION['rolcod'];
$editar = false;
if (isset($_POST['noticiacod']) && $_POST['noticiacod']!="")
{
	$noticiacod = $datos['noticiacod'] = $_POST['noticiacod'];
	if(!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
		die();
	}
	$datosnoticia = $conexion->ObtenerSiguienteRegistro($resultado);
	$datos['noticiaestadocod'] = $datosnoticia['noticiaestadocod'];
	$editar = true;
}

if(!$oNoticiasAcciones->ObtenerAccionesRol($datos,$resultadoacciones,$numfilasacciones))
	return false;

$accioneliminar = false;
if($editar && $oNoticiasAcciones->TieneAccionEliminar($datos,$nombrebotoneliminar,$noticiaworkflowcoddel))
{	
	FuncionesPHPLocal::ArmarLinkMD5("not_noticias_eliminar.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$noticiaworkflowcoddel,"accion"=>1),$geteliminar,$md5eliminar);
	$accioneliminar = true;
}

$accionpublicar = false;
if($editar && $oNoticiasAcciones->TieneAccionPublicar($datos,$nombrebotonpublicar,$noticiaworkflowcodpub))
{	
	FuncionesPHPLocal::ArmarLinkMD5("not_noticias_publicar.php",array("noticiacod"=>$noticiacod,"noticiaworkflowcod"=>$noticiaworkflowcodpub,"accion"=>1),$getpublicar,$md5publicar);
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
                <li class="states "><a class="boton base" id="<?php  echo $fila['noticiaestadocodfinal']?>" rel="<?php  echo $fila['noticiaworkflowcod']?>" href="javascript:void(0)" onclick="Guardar(this)"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiaaccion'],ENT_QUOTES);?></a></li>
				<?php 
			$i++;
			$class = "middle";
		}
		?>
		<?php  if ($accionpublicar){?>
            <li class="states"><a class="left boton verde" href="not_noticias_publicar.php?<?php  echo $getpublicar?>" onclick="if (!confirm('Esta seguro que desea publicar la noticia?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotonpublicar,ENT_QUOTES);?></a></li>
        <?php  }?>
		<?php  if ($accioneliminar){?>
            <li class="states"><a class="left boton rojo " href="not_noticias_eliminar.php?<?php  echo $geteliminar?>" onclick="if (!confirm('Esta seguro que desea eliminar la publicacion?')) return false;"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($nombrebotoneliminar,ENT_QUOTES);?></a></li>
        <?php  }?>
		<?php  if ($editar){?>
            <li class="states"><a class="left boton base" href="not_noticias_salto.php?noticiacod=<?php  echo $noticiacod?>" target="_blank">Previsualizar</a></li>
        <?php  }?>
		<li class="states"><a class="left boton base" href="not_noticias.php">Volver</a></li>
		<?php  
		break;
		
	case 2:
		break;
		
}
?>