<?php 
//set_time_limit(3600);
require('./config/include.php');
require_once("./Logica/cExcel.php");
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


$oFormularios=new cFormularios($conexion);
//----------------------------------------------------------------------------------------- 	



//defino nombre dle archivo
$nombrearchivo="Formulario".date("Y")."_".date("d-m-Y").".xls";
$oExcel= new cEscribirExcel($nombrearchivo);



//$arregloTiposTratamientos= array();

$oExcel->AddHoja ("");
$oExcel->EsconderGrillas();
$oExcel->PaginaHorizontal();

//defino el ancho de cada columna 
$oExcel->FormatoFila (0,20);
$oExcel->FormatoFila (1,20);
//DATOS PERSONALES
$oExcel->FormatoColumna (0,15); 
$oExcel->FormatoColumna (1,20); 
$oExcel->FormatoColumna (2,50); 
$oExcel->FormatoColumna (3,70); 
$oExcel->FormatoColumna (4,20); 
$oExcel->FormatoColumna (5,30); 
$oExcel->FormatoColumna (6,120); 

$totcols=6;
$totcolscontacto=6;


//estilo de la fila
$oExcel->AddFormato ("Bold",1);
$oExcel->AddFormato ("VAlign","vCenter");
$oExcel->AddFormato ("Color","blanco");
$oExcel->AddFormato ("Align","center");
$oExcel->AddFormato ("FgColor","gris");
$oExcel->AddFormato ("Border","1");


//FILA 0
$nro_fila_inicial = 0;

//la fila 0 cubre todo el ancho
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,0,$nro_fila_inicial,$totcols,"Contactos ".date("Y")."- Fecha :".date("d/m/Y H:i:s"),true);

$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,$totcolscontacto+1,$nro_fila_inicial,$totcolscontacto,"Datos Contacto",true);

$oExcel->AddFormato ("VAlign","vCenter");
$oExcel->AddFormato ("Color","blanco");
$oExcel->AddFormato ("Align","center");
$oExcel->AddFormato ("FgColor","gris");

//FILA 1 
$nro_fila_inicial  = $nro_fila_inicial + 1;

//DATOS CONTACTO
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,0,$nro_fila_inicial+2,0,"codigo",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,1,$nro_fila_inicial+2,1,"Formulario",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,2,$nro_fila_inicial+2,2,"Nombre",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,3,$nro_fila_inicial+2,3,"Email",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,4,$nro_fila_inicial+2,4,"Provincia",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,5,$nro_fila_inicial+2,5,"Localidad",true);
$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,6,$nro_fila_inicial+2,6,"Mensaje",true);

		$oExcel->BorrarFormato ("FgColor","gris");
		
		$oExcel->AddFormato ("VAlign","vCenter");
		$oExcel->AddFormato ("Color","negro");
		$oExcel->AddFormato ("Align","center");
		$oExcel->AddFormato ("FgColor","blanco");


//cursor de columnas
//$cursor=6;

//tratamientos del paciente, N cols por tipo
//$oExcel->CargarDatosVariasCeldas ($nro_fila_inicial,$cursor,$nro_fila_inicial,($cursor+$cantColumnasTratamientos-1),"Tratamientos del Paciente",true);
/*$cursor=$cursor+$cantColumnasTratamientos; //27
//paso a la sgte fila y llamo a la funcion GenerarEcxel/**/
$nro_fila=$nro_fila_inicial+3;
GenerarExcel($conexion,$oExcel,$nro_fila);
/**/
//grabo XLS
$oExcel->Enviar();


//funcion generar excel, completa el contenido de la planilla
function GenerarExcel($conexion,$oExcel,$nro_fila)
{
$oFormularios=new cFormularios($conexion);
//-----------------------------------------------------------------------------------------
//Elegir el metodo en el que llega el medicocod $_GET , $_POST  
	$datos['orderby'] = "formulariodatoscod ASC";
	if (!$oFormularios->BusquedaAvanzada ($datos,$resultado,$numfilas))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
				
	while ($filaformulario = $conexion->ObtenerSiguienteRegistro($resultado))
	{ 
			if (!$oFormularios->BuscarDatosxCodigo($filaformulario,$resultadomensaje,$numfilas))
				echo "aaaaa";
		
			$datosmensaje = $conexion->ObtenerSiguienteRegistro($resultadomensaje);


		  // print_r($datosmensaje);
			//DATOS PERSONALES

			$oExcel->AddFormato ("Bold",1);
			$oExcel->AddFormato ("Align","left");
			$oExcel->CargarDatos ($nro_fila,0, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['formulariodatoscod'],ENT_QUOTES),true);
			$oExcel->CargarDatos ($nro_fila,1, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['formulariotipotitulo'],ENT_QUOTES),true);
			$oExcel->CargarDatos ($nro_fila,2, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['formularionombre'],ENT_QUOTES),true); 
			$oExcel->CargarDatos ($nro_fila,3, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['formulariomail'],ENT_QUOTES),true); 
			$oExcel->CargarDatos ($nro_fila,4, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['provinciadesc'],ENT_QUOTES),true); 
			$oExcel->CargarDatos ($nro_fila,5, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['departamentodesc'],ENT_QUOTES),true); 
			$oExcel->CargarDatos ($nro_fila,6, FuncionesPHPLocal::HtmlspecialcharsBigtree ($datosmensaje['formulariocomentario'],ENT_QUOTES),true); 
			
			
			//$oExcel->AddFormato ("Align","center");
			//tratamientos - marco con una X los seleccionados
			
					
			
			
			$oExcel->BorrarFormato ("Align","left");
		
			$nro_fila++;
	

	}

	
	
	return true; 
} 

?>