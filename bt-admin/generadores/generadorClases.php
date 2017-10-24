<?php  

$ClassPHP = "<?php \n";	

$ClassPHP .= "include(DIR_CLASES_DB.\"".$nombreClase.".db.php\");\n\r";
$ClassPHP .= "class ".$nombreClase." extends ".$nombreClase."db\n";
$ClassPHP .= "{\n\r";

$ClassPHP .= "\tprotected \$conexion;\n";
$ClassPHP .= "\tprotected \$formato;\n\r";

$ClassPHP .= "\tfunction __construct(\$conexion,\$formato=FMT_TEXTO){\n";
$ClassPHP .= "\t\t\$this->conexion = &\$conexion;\n";
$ClassPHP .= "\t\t\$this->formato = &\$formato;\n";
$ClassPHP .= "\t\tparent::__construct();\n";
$ClassPHP .= "\t}\n\r";

$ClassPHP .= "\tfunction __destruct(){parent::__destruct();}\n\r";

ClassBuscarxCodigo($arregloCampos,$ClassPHP);
ClassBuscarxCodigoxMultimedia($arregloCampos,$ClassPHP);
ClassBuscarxCodigoxMultimediaConjunto($arregloCampos,$ClassPHP);
ClassInsertar($arregloCampos,$ClassPHP);
ClassEliminarxCodigo($arregloCampos,$ClassPHP);
ClassEliminar($arregloCampos,$ClassPHP);
ClassModificarHomeMultimedia($arregloCampos,$ClassPHP);
ClassModificarTituloMultimedia($arregloCampos,$ClassPHP);
ClassModificarDescripcionMultimedia($arregloCampos,$ClassPHP);
ClassModificarOrden($arregloCampos,$ClassPHP);
ClassBuscarMultimedias($arregloCampos,$ClassPHP);
ClassAsociar($arregloCampos,$ClassPHP);
ClassDesAsociar($arregloCampos,$ClassPHP);
ClassModificarPreview($arregloCampos,$ClassPHP);

$ClassPHP .="\n\n//-----------------------------------------------------------------------------------------\n";
$ClassPHP .="//FUNCIONES PRIVADAS\n";
$ClassPHP .="//-----------------------------------------------------------------------------------------\n\n";

ClassValidarInsertar($arregloCampos,$ClassPHP);
ClassValidarEliminarCompleto($arregloCampos,$ClassPHP);
ClassValidarEliminar($arregloCampos,$ClassPHP);
ClassObtenerProximoOrden($arregloCampos,$ClassPHP);

$ClassPHP .= "\n\r\n\r}\n?>";

if(FuncionesPHPLocal::GuardarArchivo(DIR_CLASES_LOGICA,$ClassPHP,$nombreClase.".class.php"))
{
	$msgactualizacion = "Se ha publicado correctamente.";
	$ret['IsSuccess'] = true;
}




function ClassBuscarxCodigo($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function BuscarMultimediaxCodigo(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!parent::BuscarMultimediaxCodigoEvento(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";		
		
	return true;	
}


function ClassBuscarxCodigoxMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function BuscarMultimediaxCodigoxMultimedia(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!parent::BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function ClassBuscarxCodigoxMultimediaConjunto($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function BuscarMultimediaxCodigoxMultimediaConjunto(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!parent::BuscarMultimediaxCodigoxMultimediaConjunto(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}





function ClassInsertar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function Insertar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!\$this->_ValidarInsertar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t\$this->ObtenerProximoOrden(\$datos,\$proxorden);\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['orden']."'] = \$proxorden;\n";
	$ClassPHP.="\t\t\$datos['multimediacodpreview']=\"NULL\";\n";
	$ClassPHP.="\t\tif (!parent::Insertar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function ClassValidarInsertar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprivate function _ValidarInsertar(\$datos)\n";
	$ClassPHP.="\t{\n\n";
	$ClassPHP.="\t\t //VALIDAR EN EL CLASS QUE CORRESPONDA SI EXISTE EL CODIGO (".$arregloCampos['codigo'].") \n\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas>0){\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"El archivo multimedia ya se encuentra relacionado. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}

function ClassObtenerProximoOrden($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprivate function ObtenerProximoOrden(\$datos,&\$proxorden)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$proxorden = 0;\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaUltimoOrdenxCodigoxConjunto(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas!=0){\n";
	$ClassPHP.="\t\t\t\$datos = \$this->conexion->ObtenerSiguienteRegistro(\$resultado);\n";
	$ClassPHP.="\t\t\t\$proxorden = \$datos['maximo'] + 1;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}






function ClassEliminarxCodigo($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function Eliminar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!\$this->_ValidarEliminar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (!parent::Eliminar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
	return true;	
}


function ClassValidarEliminar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function _ValidarEliminar(\$datos)\n";
	$ClassPHP.="\t{\n\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas!=1){\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"El archivo multimedia no se encuentra relacionado. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function ClassEliminar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function EliminarCompleto(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\tif (!\$this->_ValidarEliminarCompleto(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (!parent::EliminarCompleto(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}

function ClassValidarEliminarCompleto($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprivate function _ValidarEliminarCompleto(\$datos)\n";
	$ClassPHP.="\t{\n\n";
	$ClassPHP.="\t\t //VALIDAR EN EL CLASS QUE CORRESPONDA SI EXISTE EL CODIGO (".$arregloCampos['codigo'].") \n\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function ClassModificarHomeMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function ModificarHomeMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas!=1){\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Multimedia inexistente. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['home']."'] = \$datos['multimediahome'];	\n";
	$ClassPHP.="\t\tif (!parent::ModificarHomeMultimedia(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function ClassModificarTituloMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function ModificarTituloMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas!=1){\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Multimedia inexistente. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['titulo']."'] = \$datos['multimediatitulo'];	\n";
	$ClassPHP.="\t\tif (!parent::ModificarTituloMultimedia(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function ClassModificarDescripcionMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function ModificarDescripcionMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\tif (!\$this->BuscarMultimediaxCodigoxMultimedia(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (\$numfilas!=1){\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Multimedia inexistente. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['descripcion']."'] = \$datos['multimediadesc'];	\n";	
	$ClassPHP.="\t\tif (!parent::ModificarDescripcionMultimedia(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function ClassModificarOrden($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function ModificarOrden(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['orden']."'] = 1;\n";
	$ClassPHP.="\t\t\$datosmodif['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\t\$arreglomultimedia = \$datos['multimedia'];\n";
	$ClassPHP.="\t\tforeach (\$arreglomultimedia as \$multimediacod){\n";
	$ClassPHP.="\t\t\t\$datosmodif['multimediacod'] = \$multimediacod;\n";
	$ClassPHP.="\t\t\tif (!parent::ModificarOrden(\$datosmodif))\n";
	$ClassPHP.="\t\t\t\treturn false;\n";
	$ClassPHP.="\t\t\t\$datosmodif['".$arregloCampos['orden']."']++;\n";
	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function ClassAsociar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function Asociar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['home']."'] = 0;\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['titulo']."'] = \$datos['multimediatitulo'];\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['descripcion']."'] = \$datos['multimediadesc'];\n";
	$ClassPHP.="\t\tif (!\$this->Insertar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";

	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}

function ClassDesAsociar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function DesAsociar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\tif (!\$this->_ValidarEliminar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\tif (!parent::Eliminar(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}

function ClassModificarPreview($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function ModificarPreview(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$datos['multimediacodpreview'] = \$datos['multimediacodRelacion'];\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['multimedia']."'] = \$datos['".$arregloCampos['multimedia']."'];\n";
	$ClassPHP.="\t\tif (!parent::ModificarPreview(\$datos))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function ClassBuscarMultimedias($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tpublic function BuscarMultimedias(\$datos,&\$arreglo)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$oMultimedia = new cMultimedia(\$this->conexion,\$this->formato);\n";
	$ClassPHP.="\t\t\$arreglo = array();\n";
	$ClassPHP.="\t\tif (\$datos['codigo']==\"\")\n";
	$ClassPHP.="\t\t\treturn false;\n\r";
	$ClassPHP.="\t\t\$datos['multimediaconjuntocod'] = \$datos['tipo'];\n";
	$ClassPHP.="\t\t\$datos['".$arregloCampos['codigo']."'] = \$datos['codigo'];\n";
	$ClassPHP.="\t\tif (!parent::BuscarMultimediaxCodigoxMultimediaConjunto(\$datos,\$resultado,\$numfilas))\n";
	$ClassPHP.="\t\t\treturn false;\n\r";

	$ClassPHP.="\t\t\$puedeeditar = true;\n";
	$ClassPHP.="\t\t\$i = 0;\n";

	$ClassPHP.="\t\twhile (\$fila = \$this->conexion->ObtenerSiguienteRegistro(\$resultado))\n";
	$ClassPHP.="\t\t{\n";

	$ClassPHP.="\t\t\t\$arreglo[\$i]['codigo'] = \$fila['".$arregloCampos['codigo']."'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimediacod'] = \$fila['multimediacod'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimediaconjuntocod'] = \$fila['multimediaconjuntocod'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimedianombre'] = \$fila['multimedianombre'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimediatitulo'] = \$fila['".$arregloCampos['titulo']."'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimediadesc'] = \$fila['".$arregloCampos['descripcion']."'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['home'] = \$fila['".$arregloCampos['home']."'];\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['puedeeditar'] = \$puedeeditar;\n";
	$ClassPHP.="\t\t\t\$img = \$oMultimedia->DevolverDireccionImg(\$fila);\n";
	$ClassPHP.="\t\t\t\$arreglo[\$i]['multimediaimg'] = \$img;\n";
	$ClassPHP.="\t\t\t\$i++;\n";

	$ClassPHP.="\t\t}\n";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



?>