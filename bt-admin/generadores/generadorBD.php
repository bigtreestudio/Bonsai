<?php 

$oStored=new cStored($conexion);

if (!$oStored->TraerCampos($tablaencontrada,$resultado))
	$result=false;

$arregloCamposTabla = array();
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	$arregloCamposTabla[$fila['Field']] = $fila['Field'];

foreach($arregloCampos as $campo=>$clave)
{
	if ($campo!="tabla")
	{
		if (!array_key_exists($clave,$arregloCamposTabla))
		{
			echo "El Campo <b>".$clave."</b> no existe en la tabla ".$tablaencontrada;
			die();
		}
	}
	
}

$ClassPHP = "<?php \n";	
$ClassPHP .= "abstract class ".$nombreClase."db\n";
$ClassPHP .= "{\n\r\n\r";

$ClassPHP .= "\tfunction __construct(){}\n\r";
$ClassPHP .= "\tfunction __destruct(){}\n\r";


GenerarSqlBuscarxCodigo($conexion,$oStored,$arregloCampos);
BuscarxCodigo($arregloCampos,$ClassPHP);

GenerarSqlBuscarxCodigoxMultimedia($conexion,$oStored,$arregloCampos);
BuscarxCodigoxMultimedia($arregloCampos,$ClassPHP);

GenerarSqlBuscarxCodigoxMultimediaConjunto($conexion,$oStored,$arregloCampos);
BuscarxCodigoxMultimediaConjunto($arregloCampos,$ClassPHP);

GenerarSqlBuscarUltimoOrdenxCodigoxConjunto($conexion,$oStored,$arregloCampos);
BuscarMultimediaUltimoOrdenxCodigoxConjunto($arregloCampos,$ClassPHP);

GenerarSqlEliminarxCodigo($conexion,$oStored,$arregloCampos);
EliminarxCodigo($arregloCampos,$ClassPHP);

GenerarSqlEliminar($conexion,$oStored,$arregloCampos);
Eliminar($arregloCampos,$ClassPHP);

GenerarSqlInsertar($conexion,$oStored,$arregloCampos);
Insertar($arregloCampos,$ClassPHP);

GenerarSqlModificarOrdenMultimedia($conexion,$oStored,$arregloCampos);
ModificarOrden($arregloCampos,$ClassPHP);

GenerarSqlModificarHomeMultimedia($conexion,$oStored,$arregloCampos);
ModificarHomeMultimedia($arregloCampos,$ClassPHP);

GenerarSqlModificarTituloMultimedia($conexion,$oStored,$arregloCampos);
ModificarTituloMultimedia($arregloCampos,$ClassPHP);

GenerarSqlModificarDescripcionMultimedia($conexion,$oStored,$arregloCampos);
ModificarDescripcionMultimedia($arregloCampos,$ClassPHP);

GenerarSqlModificarPreviewImagen($conexion,$oStored,$arregloCampos);
ModificarPreviewImagen($arregloCampos,$ClassPHP);

$ClassPHP .= "\n\r\n\r}\n?>";

if(FuncionesPHPLocal::GuardarArchivo(DIR_CLASES_DB,$ClassPHP,$nombreClase.".db.php"))
{
	$msgactualizacion = "Se ha publicado correctamente.";
	$ret['IsSuccess'] = true;
}




function GenerarSqlBuscarxCodigo($conexion,$oStored,$arregloCampos)
{
	$spnombre="sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	

	$sql = "SELECT * FROM ".$arregloCampos['tabla']." WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\"";
	$datos['spoperacion'] = "SEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}

function BuscarxCodigo($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function BuscarMultimediaxCodigo(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar el archivo multimedia por codigo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function GenerarSqlBuscarxCodigoxMultimedia($conexion,$oStored,$arregloCampos)
{
	$spnombre="sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	

	$sql = "SELECT * FROM ".$arregloCampos['tabla']." WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "SEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}


function BuscarxCodigoxMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function BuscarMultimediaxCodigoxMultimedia(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar el archivo multimedia por codigo y multimedia. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function GenerarSqlBuscarxCodigoxMultimediaConjunto($conexion,$oStored,$arregloCampos)
{
	$spnombre="sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimediaConjunto'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "SELECT a.*, b.multimedianombre, b.multimediaubic, b.multimediatipocod, b.multimediaidexterno, c.multimediacatcarpeta ,d.multimediatipoarchivo, d.multimediatipodesc, d.multimediatipoicono FROM ".$arregloCampos['tabla']." as a ";
	$sql .= "INNER JOIN mul_multimedia AS b ON a.multimediacod=b.multimediacod ";
	$sql .= "INNER JOIN mul_multimedia_categorias AS c ON b.multimediacatcod=c.multimediacatcod  ";
	$sql .= "INNER JOIN mul_multimedia_tipos AS d ON b.multimediatipocod=d.multimediatipocod  ";
	$sql .= "WHERE a.".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND a.".$arregloCampos['multimediaConjunto']."=\"#p".$arregloCampos['multimediaConjunto']."#\" ";
	$sql .= "ORDER BY ".$arregloCampos['orden'];
	$datos['spoperacion'] = "SEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}

function BuscarxCodigoxMultimediaConjunto($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function BuscarMultimediaxCodigoxMultimediaConjunto(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimediaConjunto']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimediaConjunto']."'=> \$datos['".$arregloCampos['multimediaConjunto']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar el archivo multimedia por codigo y conjunto de multimedia. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function GenerarSqlBuscarUltimoOrdenxCodigoxConjunto($conexion,$oStored,$arregloCampos)
{
	$spnombre="sel_".$arregloCampos['tabla']."_max_orden_x".$arregloCampos['codigo']."_x".$arregloCampos['multimediaConjunto'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "SELECT MAX(".$arregloCampos['orden'].") AS maximo FROM ".$arregloCampos['tabla']." WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimediaConjunto']."=\"#p".$arregloCampos['multimediaConjunto']."#\"";
	$datos['spoperacion'] = "SEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}

function BuscarMultimediaUltimoOrdenxCodigoxConjunto($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function BuscarMultimediaUltimoOrdenxCodigoxConjunto(\$datos,&\$resultado,&\$numfilas)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"sel_".$arregloCampos['tabla']."_max_orden_x".$arregloCampos['codigo']."_x".$arregloCampos['multimediaConjunto']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimediaConjunto']."'=> \$datos['".$arregloCampos['multimediaConjunto']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno) )\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al buscar el maximo orden del multimedia por tipo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
		
		
	return true;	
}




function GenerarSqlEliminarxCodigo($conexion,$oStored,$arregloCampos)
{
	$spnombre="del_".$arregloCampos['tabla']."_x".$arregloCampos['codigo'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "DELETE FROM ".$arregloCampos['tabla']." WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\"";
	$datos['spoperacion'] = "DEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}



function EliminarxCodigo($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function EliminarCompleto(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"del_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al eliminar los multimedias por codigo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}




function GenerarSqlEliminar($conexion,$oStored,$arregloCampos)
{
	$spnombre="del_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];
	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "DELETE FROM ".$arregloCampos['tabla']." WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "DEL";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}


function Eliminar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function Eliminar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"del_".$arregloCampos['tabla']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al eliminar el multimedia por codigos. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function GenerarSqlInsertar($conexion,$oStored,$arregloCampos)
{
	$spnombre="ins_".$arregloCampos['tabla'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "INSERT INTO ".$arregloCampos['tabla']." (".$arregloCampos['codigo'].", ".$arregloCampos['multimediaConjunto'].",".$arregloCampos['multimedia'].",";
	$sql .= $arregloCampos['titulo'].", ".$arregloCampos['descripcion'].",".$arregloCampos['orden'].", multimediacodpreview,usuariodioalta,".$arregloCampos['fAlta'].",";
	$sql .= "ultmodusuario, ultmodfecha)";
	$sql .= " VALUES (\"#p".$arregloCampos['codigo']."#\", \"#p".$arregloCampos['multimediaConjunto']."#\", \"#p".$arregloCampos['multimedia']."#\",";
	$sql .= "\"#p".$arregloCampos['titulo']."#\", \"#p".$arregloCampos['descripcion']."#\", \"#p".$arregloCampos['orden']."#\", \"#pmultimediacodpreview#\", \"#pusuariodioalta#\",\"#p".$arregloCampos['fAlta']."#\",";
	$sql .= "\"#pultmodusuario#\", \"#pultmodfecha#\")";

	$datos['spoperacion'] = "INS";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}


function Insertar($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function Insertar(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"ins_".$arregloCampos['tabla']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimediaConjunto']."'=> \$datos['".$arregloCampos['multimediaConjunto']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['titulo']."'=> \$datos['".$arregloCampos['titulo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['descripcion']."'=> \$datos['".$arregloCampos['descripcion']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['orden']."'=> \$datos['".$arregloCampos['orden']."'],\n";
	$ClassPHP.="\t\t\t'pmultimediacodpreview'=> \$datos['multimediacodpreview'],\n";
	$ClassPHP.="\t\t\t'pusuariodioalta'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['fAlta']."'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\")\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al insertar el multimedia. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function GenerarSqlModificarHomeMultimedia($conexion,$oStored,$arregloCampos)
{
	$spnombre="upd_".$arregloCampos['tabla']."_".$arregloCampos['home']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "UPDATE ".$arregloCampos['tabla']." SET ".$arregloCampos['home']."=\"#p".$arregloCampos['home']."#\", ";
	$sql .= "ultmodusuario=\"#pultmodusuario#\", ultmodfecha=\"#pultmodfecha#\"";
	$sql .= " WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "UPD";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}



function ModificarHomeMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function ModificarHomeMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"upd_".$arregloCampos['tabla']."_".$arregloCampos['home']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['home']."'=> \$datos['".$arregloCampos['home']."'],\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al modificar el campo home. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}


function GenerarSqlModificarTituloMultimedia($conexion,$oStored,$arregloCampos)
{
	$spnombre="upd_".$arregloCampos['tabla']."_".$arregloCampos['titulo']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "UPDATE ".$arregloCampos['tabla']." SET ".$arregloCampos['titulo']."=\"#p".$arregloCampos['titulo']."#\", ";
	$sql .= "ultmodusuario=\"#pultmodusuario#\", ultmodfecha=\"#pultmodfecha#\"";
	$sql .= " WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "UPD";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}



function ModificarTituloMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function ModificarTituloMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"upd_".$arregloCampos['tabla']."_".$arregloCampos['titulo']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['titulo']."'=> \$datos['".$arregloCampos['titulo']."'],\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al modificar el campo titulo. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function GenerarSqlModificarDescripcionMultimedia($conexion,$oStored,$arregloCampos)
{
	$spnombre="upd_".$arregloCampos['tabla']."_".$arregloCampos['descripcion']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "UPDATE ".$arregloCampos['tabla']." SET ".$arregloCampos['descripcion']."=\"#p".$arregloCampos['descripcion']."#\", ";
	$sql .= "ultmodusuario=\"#pultmodusuario#\", ultmodfecha=\"#pultmodfecha#\"";
	$sql .= " WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "UPD";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}


function ModificarDescripcionMultimedia($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function ModificarDescripcionMultimedia(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"upd_".$arregloCampos['tabla']."_".$arregloCampos['descripcion']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['descripcion']."'=> \$datos['".$arregloCampos['descripcion']."'],\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al modificar el campo descripcion. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function GenerarSqlModificarOrdenMultimedia($conexion,$oStored,$arregloCampos)
{
	$spnombre="upd_".$arregloCampos['tabla']."_".$arregloCampos['orden']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	


	$sql = "UPDATE ".$arregloCampos['tabla']." SET ".$arregloCampos['orden']."=\"#p".$arregloCampos['orden']."#\", ";
	$sql .= "ultmodusuario=\"#pultmodusuario#\", ultmodfecha=\"#pultmodfecha#\"";
	$sql .= " WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "UPD";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}

function ModificarOrden($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function ModificarOrden(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"upd_".$arregloCampos['tabla']."_".$arregloCampos['orden']."_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['orden']."'=> \$datos['".$arregloCampos['orden']."'],\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al modificar el orden. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}





function ModificarPreviewImagen($arregloCampos,&$ClassPHP)
{
	$ClassPHP.="\tprotected function ModificarPreview(\$datos)\n";
	$ClassPHP.="\t{\n";
	$ClassPHP.="\t\t\$spnombre=\"upd_".$arregloCampos['tabla']."_preview_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia']."\";\n";
	$ClassPHP.="\t\t\$sparam=array(\n";
	$ClassPHP.="\t\t\t'pmultimediacodpreview'=> \$datos['multimediacodpreview'],\n";
	$ClassPHP.="\t\t\t'pultmodusuario'=> \$_SESSION['usuariocod'],\n";
	$ClassPHP.="\t\t\t'pultmodfecha'=> date(\"Y/m/d H:i:s\"),\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['codigo']."'=> \$datos['".$arregloCampos['codigo']."'],\n";
	$ClassPHP.="\t\t\t'p".$arregloCampos['multimedia']."'=> \$datos['".$arregloCampos['multimedia']."']\n";
	$ClassPHP.="\t\t);\n";			
	$ClassPHP.="\t\tif(!\$this->conexion->ejecutarStoredProcedure(\$spnombre,\$sparam,\$resultado,\$numfilas,\$errno))\n";
	$ClassPHP.="\t\t{\n";
	$ClassPHP.="\t\t\tFuncionesPHPLocal::MostrarMensaje(\$this->conexion,MSG_ERRGRAVE,\"Error al modificar el preview de un multimedia. \",array(\"archivo\" => __FILE__,\"funcion\" => __FUNCTION__, \"linea\" => __LINE__),array(\"formato\"=>\$this->formato));\n";
	$ClassPHP.="\t\t\treturn false;\n";
	$ClassPHP.="\t\t}\n\r";
	$ClassPHP.="\t\treturn true;\n";
	$ClassPHP.="\t}\n\r\n\r\n\r";
		
	return true;	
}



function GenerarSqlModificarPreviewImagen($conexion,$oStored,$arregloCampos)
{
	$spnombre="upd_".$arregloCampos['tabla']."_preview_x".$arregloCampos['codigo']."_x".$arregloCampos['multimedia'];

	$datos['spnombre'] = $spnombre;
	if(!$oStored->Buscar ($datos,$numfilas,$resultado))
		return false;
	if ($numfilas==1)
	{
		$datosStored = $conexion->ObtenerSiguienteRegistro($resultado);
		if(!$oStored->Eliminar ($datosStored))
			return false;
	}	

	$sql = "UPDATE ".$arregloCampos['tabla']." SET multimediacodpreview=\"#pmultimediacodpreview#\", ";
	$sql .= "ultmodusuario=\"#pultmodusuario#\", ultmodfecha=\"#pultmodfecha#\"";
	$sql .= " WHERE ".$arregloCampos['codigo']."=\"#p".$arregloCampos['codigo']."#\" AND ".$arregloCampos['multimedia']."=\"#p".$arregloCampos['multimedia']."#\"";
	$datos['spoperacion'] = "UPD";
	$datos['sptabla'] = strtoupper($arregloCampos['tabla']);
	$datos['spsqlstring'] = $sql;
	$datos['spobserv'] = "";
	if(!$oStored->Insertar ($datos,$codigoinsertado))
		return false;
		
}




?>