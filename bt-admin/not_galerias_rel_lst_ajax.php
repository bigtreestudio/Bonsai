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

$oNoticiasGalerias = new cNoticiasGalerias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 

$puedeeditar = false;
if (isset($_GET['noticiacod']) && $_GET['noticiacod']!="")
{
	$datosbuscar = $_GET;
	$datosbuscar['rolcod'] = $_SESSION['rolcod'];
	if($oNoticiasGalerias->PuedeEditarGaleriasRelacionadas($datosbuscar))
		$puedeeditar = true;
}


if(!$oNoticiasGalerias->BuscarGaleriasRelacionadasxNoticia($_GET,$resultado,$numfilas)) {
	$error = true;
}


$i = 0;

$responce =new StdClass; 
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();




if ($numfilas>0)
{
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		$fila['galeriatitulo'] = utf8_encode($fila['galeriatitulo']);
		$fecha = FuncionesPHPLocal::ConvertirFecha($fila['galeriafalta'],"aaaa-mm-dd","dd/mm/aaaa");

		FuncionesPHPLocal::ArmarLinkMD5("not_galerias_rel_upd.php",array("galeriacod"=>$fila['galeriacod']),$get,$md5);
		
		if ($puedeeditar)
			$link = '<div><a class="eliminar" href="javascript:void(0)" style="font-size:10px; display:block;" title="Eliminar Galeria" onclick="EliminarGaleriaRelacionada('.$fila['galeriacod'].')">Eliminar</a></div>';
		else
			$link = '';

		if ($puedeeditar)
		{
			if ($fila['galeriaimportante']==1)
			{
				$titulo = '<div style="font-size:10px; font-weight:bold">'.$fila['galeriatitulo']."</div>";
				$linkdestacar = '<div><a class="destacar" style="font-size:10px; display:block;" href="javascript:void(0)" title="Destacar Galeria" onclick="DestacarGaleriaRelacionada(0,'.$fila['galeriacod'].')">No Destacar</a></div>';
			}else	
			{
				$titulo = '<div style="font-size:10px;">'.$fila['galeriatitulo']."</div>";
				$linkdestacar = '<div><a class="destacar" style="font-size:10px; display:block;" href="javascript:void(0)" title="Destacar Galeria" onclick="DestacarGaleriaRelacionada(1,'.$fila['galeriacod'].')">Destacar</a></div>';
			}
		}else
		{
			if ($fila['galeriaimportante']==1){
				$titulo = '<div style="font-size:10px; font-weight:bold">'.$fila['galeriatitulo']."</div>";
				$linkdestacar = '<div style="font-size:10px; font-weight:bold" >Destacada</div>';
			}else{
				$titulo = '<div style="font-size:10px;">'.$fila['galeriatitulo']."</div>";
				$linkdestacar = '<div style="font-size:10px; font-weight:bold" >No Destacada</div>';
			}				
		}
		$fecha = '<div style="font-size:10px;">'.$fecha."</div>";
		$datosmostrar = array($fila['galeriacod'],$titulo,$fecha,$linkdestacar,$link);
		$responce->rows[$i]['galeriacod'] = $fila['galeriacod'];
		$responce->rows[$i]['id'] = $fila['galeriacod'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}

echo json_encode($responce);


?>
