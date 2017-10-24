<?php  

class GeneradorLstAjaxInicioModuloFront
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
		
	} 

	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{

		
		
		$ClassPHP = "<?\n";
		$ClassPHP .= "\$cantidadpaginacion = 10;\n\r";
		$ClassPHP .= "if (!isset(\$_GET['pagina']))\n";
	    $ClassPHP .= "\$page = 1;\n";
		$ClassPHP .= "else\n";
		$ClassPHP .= "\$page = \$_GET['pagina'];\n";
		
		$ClassPHP .= "\$oObjeto = new ".$nombreClase."(\$conexion,\"\");\n";
		$ClassPHP .= "\$datos = \$_POST;\n\r";
		
		$ClassPHP .= "if(!\$oObjeto->BusquedaAvanzada (\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		
		$ClassPHP .= "\$total = \$numfilas;\n";
		$ClassPHP .= "FuncionesPHPLocal::ArmarPaginadoFront(\$cantidadpaginacion,\$total,\$page,\$primera,\$ultima,\$numpages,\$current,\$TotalSiguiente,\$TotalVer);\n";

		$ClassPHP .="\$datos['limit']= \"LIMIT \".\$current.\",\".\$cantidadpaginacion;\n";
		$ClassPHP .= "if(!\$oObjeto->BusquedaAvanzada (\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP .= "\tdie();\n\r";
		$ClassPHP .= "if (\$numfilas>0)\n";
		$ClassPHP .= "{\n";
		$ClassPHP .= "\twhile(\$fila = \$conexion->ObtenerSiguienteRegistro(\$resultado))\n";
	    $ClassPHP .= "\t{\n";
		$ClassPHP .= "\t?>\n";
        $ClassPHP .= "\t\t<tr>\n";
		
		$i=0;
		foreach ($arregloCampos['camposListadoAvanzada'] as $Campos)
		{
			$muestraCampoestado = true;
			if ($arregloCampos['tieneActivarDesactivar'])
				$muestraCampoestado = false;
			
			if ($Campos!=$arregloCampos['campoEstado'])
			{
				if (array_key_exists($Campos,$arregloCampos['camposconCombo']))
				{
						$ClassPHP .= "\t\t\t<td><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."desc'],ENT_QUOTES);?></td>\n";
						$i++;
				}else{
					if ($Campos==$arregloCampos['codigo'])
					{
						$ClassPHP .= "\t\t\t<td><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."'],ENT_QUOTES);?></td>\n";
						$i++;
					}else{	
						if (substr($arregloCampos['camposTabla'][$Campos]['Type'],0,4)=="date" || substr($arregloCampos['camposTabla'][$Campos]['Type'],0,8)=="datetime")
						{	
							$ClassPHP .= "\t\t\t<td><? echo FuncionesPHPLocal::ConvertirFecha( \$fila['".$Campos."'],'aaaa-mm-dd','dd/mm/aaaa');?></td>\n";
							$i++;
						}else{
							$ClassPHP .= "\t\t\t<td><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(\$fila['".$Campos."'],ENT_QUOTES);?></td>\n";
							$i++;
						}
					}
				}
			}
		
		}
		  		
		  $ClassPHP .= "\t\t</tr>\n";
		  $ClassPHP .= "<?  }\n"; 
		  $ClassPHP .= "?>\n";
		  $ClassPHP .= "<tr class=\"trpaginado\">\n";
		  $ClassPHP .= "\t<td colspan=\"".$i."\">\n";
		  $ClassPHP .= "\t\t<div class=\"paginator clearfix\">\n";
		  $ClassPHP .= "\t\t\t<div class=\"clearfix\">\n";
		  $ClassPHP .= "\t\t\t<?\n";	
		  $ClassPHP .= "\t\t\tif(\$page>1) {?>\n"; 
		  $ClassPHP .= "\t\t\t\t<a href=\"javascript:void(0)\" onclick=\"return Buscar(<? echo \$page-1?>)\" class=\"prev\">Anterior</a>\n";
		  $ClassPHP .= "\t\t\t<?php } ?>\n";
		  $ClassPHP .= "\t\t\t<div class=\"pages\">\n";
		  $ClassPHP .= "\t\t\t\t<?php  for (\$i = \$primera; \$i <= \$ultima; \$i++) { \n";
		  $ClassPHP .= "\t\t\t\t\t\$class = \"\";\n";
		  $ClassPHP .= "\t\t\t\t\tif(\$i == \$page)\n";
		  $ClassPHP .= "\t\t\t\t\t\t\$class = 'class=\"active\"';\n";
		  $ClassPHP .= "\t\t\t\t\t?>\n";
		  $ClassPHP .= "\t\t\t\t\t\t<a <?php echo \$class; ?> href=\"javascript:void(0)\" onclick=\"return Buscar(<?php echo \$i?>)\"><?php echo \$i; ?></a>\n";
		  $ClassPHP .= "\t\t\t\t\t<?php	} ?>\n";
		  $ClassPHP .= "\t\t\t\t</div>\n";
		  $ClassPHP .= "\t\t\t\t<?php	if(\$page<\$numpages) {?>\n"; 
		  $ClassPHP .= "\t\t\t\t\t<a href=\"javascript:void(0)\" onclick=\"return Buscar(<?php echo \$page+1?>)\"  class=\"next\">Siguiente</a>\n";
		  $ClassPHP .= "\t\t\t\t<?php } ?>\n";
		  $ClassPHP .= "\t\t\t</div>\n";
		  $ClassPHP .= "\t\t</div>\n";
		  $ClassPHP .= "\t</td>\n";
		  $ClassPHP .= "</tr>\n";
		  $ClassPHP .= "<?php \n"; 
		  $ClassPHP .= "}else{ ?>\n";
		  $ClassPHP .= "\t<tr>\n";
		  $ClassPHP .= "\t\t<td colspan=\"".$i."\">\n";
		  $ClassPHP .= "\t\t\t<div class=\"sinresultados\">Sin resultados encontrados</div>\n";
		  $ClassPHP .= "\t\t</td>\n";
		  $ClassPHP .= "\t</tr>\n";
		  $ClassPHP .= "<?php \n";
		  $ClassPHP .= "}\n";

		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DOCUMENT_ROOT,$ClassPHP,$this->archivoModulo."_lst_ajax.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$this->archivoModulo."_lst_ajax.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
	
	
	}
	
}
?>