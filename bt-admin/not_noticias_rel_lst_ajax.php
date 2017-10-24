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

$oNoticias = new cNoticiasNoticias($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 



$puedeeditar = false;
if (isset($_GET['noticiacod']) && $_GET['noticiacod']!="")
{
	$datosbuscar = $_GET;
	$datosbuscar['rolcod'] = $_SESSION['rolcod'];
	if($oNoticias->PuedeEditarNoticiasRelacionadas($datosbuscar))
		$puedeeditar = true;
}



if(!$oNoticias->BuscarNoticiasRelacionadasxNoticia($_GET,$resultado,$numfilas)) {
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
	
		$fila['noticiatitulo'] = utf8_encode($fila['noticiatitulo']);
		$fila['noticiavolanta'] = utf8_encode($fila['noticiavolanta']);
		$fila['catnom'] = utf8_encode($fila['catnom']);
		$fecha = FuncionesPHPLocal::ConvertirFecha($fila['noticiafecha'],"aaaa-mm-dd","dd/mm/aaaa");

		FuncionesPHPLocal::ArmarLinkMD5("not_noticias_rel_upd.php",array("noticiacod"=>$fila['noticiacodrel']),$get,$md5);

		if($puedeeditar)
			$link = '<div><a class="eliminar" href="javascript:void(0)" style="font-size:10px; display:block;" title="Eliminar Noticia" onclick="EliminarNoticiaRelacionada('.$fila['noticiacodrel'].')">Eliminar</a></div>';
		else
			$link = '';

		
		if($puedeeditar){
			if ($fila['noticiaimportante']==1)
			{
				$titulo = '<div style="font-size:10px; font-weight:bold;">'.$fila['noticiavolanta'].'</div>';
				$titulo .= '<div style="font-size:10px; font-weight:bold; white-space: normal !important;">'.$fila['noticiatitulo']."</div>";
				$linkdestacar = '<div><a class="destacar" style="font-size:10px; display:block;" href="javascript:void(0)" title="Destacar Noticia" onclick="DestacarNoticiaRelacionada(0,'.$fila['noticiacodrel'].')">No Destacar</a></div>';
			}else	
			{
				$titulo = '<div style="font-size:10px;">'.$fila['noticiavolanta'].'</div>';
				$titulo .= '<div style="font-size:10px; white-space: normal !important;">'.$fila['noticiatitulo']."</div>";
				$linkdestacar = '<div><a class="destacar" style="font-size:10px; display:block;" href="javascript:void(0)" title="Destacar Noticia" onclick="DestacarNoticiaRelacionada(1,'.$fila['noticiacodrel'].')">Destacar</a></div>';
			}
		}else
		{
			if ($fila['noticiaimportante']==1){
				$titulo = '<div style="font-size:10px; font-weight:bold;">'.$fila['noticiavolanta'].'</div>';
				$titulo .= '<div style="font-size:10px; font-weight:bold; white-space: normal !important;">'.$fila['noticiatitulo']."</div>";
				$linkdestacar = '<div style="font-size:10px; font-weight:bold" >Destacada</div>';
   			}else{
				$titulo = '<div style="font-size:10px;">'.$fila['noticiavolanta'].'</div>';
				$titulo .= '<div style="font-size:10px; white-space: normal !important;">'.$fila['noticiatitulo']."</div>";
				$linkdestacar = '<div style="font-size:10px; font-weight:bold" >No Destacada</div>';
			}
		}

		$fecha = '<div style="font-size:10px;">'.$fecha."</div>";
		$datosmostrar = array(
		$fila['noticiacodrel'],
		$titulo,$fila['catnom'],$fecha,$linkdestacar,$link);
		$responce->rows[$i]['noticiacod'] = $fila['noticiacodrel'];
		$responce->rows[$i]['id'] = $fila['noticiacodrel'];
		$responce->rows[$i]['cell'] = $datosmostrar;
		$i++;
	}
}


echo json_encode($responce);

?>