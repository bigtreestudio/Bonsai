<?php  
class GenerarAltaLstJSInicioModulo 
{

	protected $conexion;
	protected $carpetaPaquete;
	protected $archivoModulo;
	protected $generarSitio;
	protected $generarPaquete;

	public function __construct($conexion,$carpetaPaquete,$archivoModulo,$generarSitio=true,$generarPaquete=true){
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->archivoModulo = $archivoModulo;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;
		
		if($this->generarPaquete)
		{
			if (!is_dir($this->carpetaPaquete))
				mkdir($this->carpetaPaquete);
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo))
				mkdir($this->carpetaPaquete.$this->archivoModulo);
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo."/js"))
				mkdir($this->carpetaPaquete.$this->archivoModulo."/js");
			if (!is_dir($this->carpetaPaquete.$this->archivoModulo."/css"))
				mkdir($this->carpetaPaquete.$this->archivoModulo."/css");
		}
	} 

	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{
		$esFecha=false;
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			if (substr($arregloCampos['camposTabla'][$Campos]['Type'],0,4)=="date" || substr($arregloCampos['camposTabla'][$Campos]['Type'],0,8)=="datetime")
			{
				$esFecha=true;
				break;
			}
		}
		$JsBusqueda = "jQuery(document).ready(function(){\n";
		$JsBusqueda .= "\tlistar();\n";
		if ($esFecha)
			$JsBusqueda .= "\t\$(\".fechacampo\").datepicker( {dateFormat:\"dd/mm/yy\"});\n";
		
		$JsBusqueda .= "});\n\r";
		
		$JsBusqueda .= "var timeoutHnd;\n";
		$JsBusqueda .= "function doSearch(ev){ \n";
		$JsBusqueda .= "\tif(timeoutHnd) \n\t\tclearTimeout(timeoutHnd) \n";
		$JsBusqueda .= "\ttimeoutHnd = setTimeout(gridReload,500)\n";
		$JsBusqueda .= "}\n\r";
		
		$JsBusqueda .= "function gridReload(){\n";
		$JsBusqueda .= "\tvar datos = $(\"#formbusqueda\").serializeObject();\n";
		$JsBusqueda .= "\tjQuery(\"#listarDatos\").jqGrid('setGridParam', {url:\"".$this->archivoModulo."_lst_ajax.php?rand=\"+Math.random(), postData: datos,page:1}).trigger(\"reloadGrid\");\n";
		
		if($arregloCampos['tieneOrden']==1)
		{
			$JsBusqueda .= "\tif(Filtrado())\n";
			$JsBusqueda .= "\t{\n";
			$JsBusqueda .= "\t\t\$(\"#listarDatos\").find(\">tbody\").sortable(\"disable\");\n";
			$JsBusqueda .= "\t}\n";
			$JsBusqueda .= "\telse\n";
			$JsBusqueda .= "\t{\n";
			$JsBusqueda .= "\t\t\$(\"#listarDatos\").find(\">tbody\").sortable(\"enable\");\n";
			$JsBusqueda .= "\t}\n";
		}
		
		
		$JsBusqueda .= "}\n";
		
		
		$campoestado = "";
		if($arregloCampos['campoEstado']!="" && $arregloCampos['TipoEliminacion']==1)
			$campoestado = $arregloCampos['campoEstado'];
		
		$JsBusqueda .= "function Resetear(){\n";
		foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
		{
			
			if($Campos!=$campoestado)
				$JsBusqueda .= "\t\$(\"#".$Campos."\").val(\"\");\n";
		}
		
		$JsBusqueda .= "\ttimeoutHnd = setTimeout(gridReload,500);\n";
		$JsBusqueda .= "}\n\r";
		
		if($arregloCampos['tieneOrden']==1)
		{
			$JsBusqueda .= "function Filtrado(){\n";
			foreach($arregloCampos['camposBusquedaAvanzada'] as $Campos)
			{
				if($Campos!=$campoestado)
				{
					$JsBusqueda .= "\tif(\$(\"#".$Campos."\").val()!=\"\")\n";
					$JsBusqueda .= "\t\t return true;\n";
				}
			}
			$JsBusqueda .= "\t return false;\n";
			$JsBusqueda .= "}\n\r";
		}
		
		if($arregloCampos['tieneOrden']==1)
		{
			$JsBusqueda .= "function Reordenar(orden){\n";
			$JsBusqueda .= "\t\$(\"#MsgGuardando\").show();\n";
			$JsBusqueda .= "\tparam  = \"orden=\"+orden\n"; 
			$JsBusqueda .= "\tparam += \"&accion=6\";\n";
			$JsBusqueda .= "\tvar param, url;\n";
			$JsBusqueda .= "\t\$.ajax({\n";
			$JsBusqueda .= "\t\ttype: \"POST\",\n";
			$JsBusqueda .= "\t\turl: \"".$this->archivoModulo."_upd.php\",\n";
			$JsBusqueda .= "\t\tdata: param,\n";
			$JsBusqueda .= "\t\tdataType:\"json\",\n";
			$JsBusqueda .= "\t\tsuccess: function(msg){\n";
			$JsBusqueda .= "\t\t\tif (msg.IsSucceed==true)\n";
			$JsBusqueda .= "\t\t\t{\n";
			$JsBusqueda .= "\t\t\t\n";
			$JsBusqueda .= "\t\t\t}\n";
			$JsBusqueda .= "\t\t\telse\n";
			$JsBusqueda .= "\t\t\t{\n";
			$JsBusqueda .= "\t\t\t\talert(msg.Msg);\n";
			$JsBusqueda .= "\t\t\t}\n";
			$JsBusqueda .= "\t\t\t\$(\"#MsgGuardando\").hide();\n";
			$JsBusqueda .= "\t\t}\n";
			$JsBusqueda .= "\t});\n";
			$JsBusqueda .= "}\n";
		}
		
		
		$JsBusqueda .= "function listar(){\n";
		$JsBusqueda .= "\tvar datos = $(\"#formbusqueda\").serializeObject();\n";
		$JsBusqueda .= "\tjQuery(\"#listarDatos\").jqGrid(\n";
		$JsBusqueda .= "\t{\n";
		//MEDIO
		$JsBusqueda .= "\t\turl:'".$this->archivoModulo."_lst_ajax.php?rand='+Math.random(),\n";
		$JsBusqueda .= "\t\tpostData: datos,\n";
		$JsBusqueda .= "\t\tdatatype: \"json\", \n";
		
		$JsBusqueda .= "\t\tcolNames:[";
		foreach ($arregloCampos['camposListadoAvanzada'] as $Campos)
		{
			$muestraCampoestado = true;
			if ($arregloCampos['tieneActivarDesactivar'])
				$muestraCampoestado = false;
		
			if ($Campos!=$arregloCampos['campoEstado'])
			{
				$string = json_encode(utf8_encode($arregloCampos['otroscampos']['nombrecamposlistado_'.$Campos]));
				$string = "'".substr($string,1,-1)."'";
				
				$JsBusqueda .= $string.',';
			}
			elseif ($muestraCampoestado)
			{
				$string = json_encode(utf8_encode($arregloCampos['otroscampos']['nombrecamposlistado_'.$Campos]));
				$string = "'".substr($string,1,-1)."'";
				
				$JsBusqueda .= $string.',';
			}
		}
		if ($arregloCampos['tienePdf'])
			$JsBusqueda .= "'PDF',";
		if ($arregloCampos['tieneActivarDesactivar'])
			$JsBusqueda .= "'Estado',";
		$JsBusqueda .= "'Editar'";
		if ($arregloCampos['tieneEliminarLst'])
			$JsBusqueda .= ",'Eliminar'";
		$JsBusqueda .= "],\n";
		
		
		$JsBusqueda .= "\t\tcolModel:[\n";
		foreach ($arregloCampos['camposListadoAvanzada'] as $Campos)
		{
			$muestraCampoestado = true;
			if ($arregloCampos['tieneActivarDesactivar'])
				$muestraCampoestado = false;
			
			if ($Campos==$arregloCampos['codigo'])
				$JsBusqueda .= "\t\t\t{name:'".$Campos."',index:'".$Campos."', width:25, align:'center'},\n";
			elseif ($Campos!=$arregloCampos['campoEstado'])
				$JsBusqueda .= "\t\t\t{name:'".$Campos."',index:'".$Campos."', align:'left'},\n";
			elseif ($muestraCampoestado)
				$JsBusqueda .= "\t\t\t{name:'".$Campos."',index:'".$Campos."', align:'center'},\n";
		
		}
		if ($arregloCampos['tienePdf'])
			$JsBusqueda .= "\t\t\t{name:'pdf',index:'pdf', width:30,  align:'center', sortable:false},\n";
		if ($arregloCampos['tieneActivarDesactivar'])
			$JsBusqueda .= "\t\t\t{name:'act',index:'act', width:30,  align:'center', sortable:false},\n";
		$JsBusqueda .= "\t\t\t{name:'edit',index:'edit', width:30,  align:'center', sortable:false}";
		if ($arregloCampos['tieneEliminarLst'])
			$JsBusqueda .= ",\n\t\t\t{name:'del',index:'del', width:30,  align:'center', sortable:false}\n";
		$JsBusqueda .= "\t\t], \n";
		
						
		if($arregloCampos['tieneOrden']==1)
		{				
			$JsBusqueda .= "\t\trowNum:1000,\n";
		}
		else
		{
			$JsBusqueda .= "\t\trowNum:20,\n";
		}
		$JsBusqueda .= "\t\tajaxGridOptions: {cache: false},\n";
		if($arregloCampos['tieneOrden']==0)
		{				
			$JsBusqueda .= "\t\trowList:[20,40,60],\n";
		}
		$JsBusqueda .= "\t\tmtype: \"POST\",\n";
		if($arregloCampos['tieneOrden']==0)
		{
			$JsBusqueda .= "\t\tpager: '#pager2',\n";
		}
		if($arregloCampos['tieneOrden']==1)
		{
			
			$JsBusqueda .= "\t\tsortname: '".$arregloCampos['campoOrden']."',\n";
			$JsBusqueda .= "\t\tviewrecords: true,\n";
			$JsBusqueda .= "\t\tsortorder: \"ASC\", \n";
			
		}
		else
		{
			$JsBusqueda .= "\t\tsortname: '".$arregloCampos['codigo']."',\n";
			$JsBusqueda .= "\t\tviewrecords: true,\n";
			$JsBusqueda .= "\t\tsortorder: \"DESC\", \n";
		}
		$JsBusqueda .= "\t\theight:290, \n";
		$JsBusqueda .= "\t\tcaption:\"\", \n";
		$JsBusqueda .= "\t\temptyrecords: \"Sin datos para mostrar.\", \n";
		$JsBusqueda .= "\t\tloadError : function(xhr,st,err) { \n";
		$JsBusqueda .= "\t\t} \n";
		
		
		//----
		$JsBusqueda .= "\t});\n\r";
		
		$JsBusqueda .= "\t\$(window).bind('resize', function() {\n";
		$JsBusqueda .= "\t\t$(\"#listarDatos\").setGridWidth(\$(\"#LstDatos\").width());\n";
		$JsBusqueda .= "\t}).trigger('resize');\n\r";
		if($arregloCampos['tieneOrden']==1)
		{
			$JsBusqueda .="\tjQuery(\"#listarDatos\").jqGrid('sortableRows',\n"; 
			$JsBusqueda .="\t\t{ cursor: 'move',items: '.jqgrow:not(.unsortable)',\n";
			$JsBusqueda .="\t\t\tupdate : function(e,ui) {\n";
			$JsBusqueda .="\t\t\tvar order = $(\"#listarDatos\").jqGrid(\"getDataIDs\");\n";
			$JsBusqueda .="\t\t\tReordenar(order);\n";
			$JsBusqueda .="\t\t}}\n";
			$JsBusqueda .="\t);\n";
		}else{
			$JsBusqueda .= "\tjQuery(\"#listarDatos\").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});\n\r";
		}
		$JsBusqueda .= "}\n\r";
		
		if ($arregloCampos['tieneActivarDesactivar'])
		{
			$JsBusqueda .= "function ActivarDesactivar(codigo,tipo){\n";
			$JsBusqueda .= "\tvar param;\n";
			$JsBusqueda .= "\t\$.blockUI({ message: '<div style=\"font-size:20px; font-weight:bold\"><img src=\"./images/cargando.gif\" />Actualizando...</h1>',baseZ: 9999999999 })	\n";
			$JsBusqueda .= "\tparam = \"".$arregloCampos['codigo']."=\"+codigo;\n";
			$JsBusqueda .= "\tparam += \"&accion=\"+tipo;\n";
			$JsBusqueda .= "\tEnviarDatos(param);\n\r";
			$JsBusqueda .= "}\n\r";
		
		}
		
		if ($arregloCampos['tieneEliminarLst'])
		{
			$JsBusqueda .= "function Eliminar(codigo){\n";
			$JsBusqueda .= "\tif (!confirm(\"Esta seguro que desea eliminar?\"))\n";
			$JsBusqueda .= "\t\treturn false;\n";
			$JsBusqueda .= "\tvar param;\n";
			$JsBusqueda .= "\t\$.blockUI({ message: '<div style=\"font-size:20px; font-weight:bold\"><img src=\"./images/cargando.gif\" />Eliminando...</h1>',baseZ: 9999999999 })	\n";
			$JsBusqueda .= "\tparam = \"".$arregloCampos['codigo']."=\"+codigo;\n";
			$JsBusqueda .= "\tparam += \"&accion=3\";\n";
			$JsBusqueda .= "\tEnviarDatos(param);\n\r";
			$JsBusqueda .= "}\n\r";
		
		}
		
		
		$JsBusqueda .= "function EnviarDatos(param){\n";
		$JsBusqueda .= "\t\$.ajax({\n";
		$JsBusqueda .= "\t\ttype: \"POST\",\n";
		$JsBusqueda .= "\t\turl: \"".$this->archivoModulo."_upd.php\",\n";
		$JsBusqueda .= "\t\tdata: param,\n";
		$JsBusqueda .= "\t\tdataType:\"json\",\n";
		$JsBusqueda .= "\t\tsuccess: function(msg){\n";
		$JsBusqueda .= "\t\t\tif (msg.IsSucceed==true)\n";
		$JsBusqueda .= "\t\t\t{\n";
		$JsBusqueda .= "\t\t\t\tgridReload();\n";
		$JsBusqueda .= "\t\t\t\talert(msg.Msg);\n";
		$JsBusqueda .= "\t\t\t\t\$.unblockUI();\n";
		$JsBusqueda .= "\t\t\t}\n";
		$JsBusqueda .= "\t\t\telse\n";
		$JsBusqueda .= "\t\t\t{\n";
		$JsBusqueda .= "\t\t\t\talert(msg.Msg);\n";
		$JsBusqueda .= "\t\t\t\t\$.unblockUI();\n";
		$JsBusqueda .= "\t\t\t}\n";
		$JsBusqueda .= "\t\t}\n";
		$JsBusqueda .= "\t});\n";
		$JsBusqueda .= "}\n";
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_ROOT."modulos/".$this->archivoModulo."/js/",$JsBusqueda,$this->archivoModulo.".js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete.$this->archivoModulo."/js/",$JsBusqueda,$this->archivoModulo.".js"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
	}

}

?>