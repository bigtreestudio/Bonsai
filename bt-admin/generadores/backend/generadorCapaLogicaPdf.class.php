<?php 


class cGeneradorABMClasesPdf 
{
	protected $conexion;
	protected $carpetaPaquete;
	protected $generarSitio;
	protected $generarPaquete;

	public function __construct($conexion,$carpetaPaquete,$generarSitio=true,$generarPaquete=true){
		$this->conexion = &$conexion;
		$this->carpetaPaquete = $carpetaPaquete;
		$this->generarSitio = $generarSitio;
		$this->generarPaquete = $generarPaquete;
		
		if($this->generarPaquete)
		{
			if (!is_dir($this->carpetaPaquete))
				mkdir($this->carpetaPaquete);
		}
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function GenerarArchivo($arregloCamposTabla,$arregloCampos,$arregloSp,$nombreClase)
	{

		$ClassPHP = "<?php \n";	
		
		$ClassPHP .= "class ".$nombreClase."PDF\n";
		$ClassPHP .= "{\n\r";
		
		$ClassPHP .= "\tprotected \$conexion;\n";
		
		$ClassPHP .= "\tfunction __construct(\$conexion,\$formato=FMT_TEXTO){\n";
		$ClassPHP .= "\t\t\$this->conexion = &\$conexion;\n";
		$ClassPHP .= "\t}\n\r";
		
		$ClassPHP .= "\tfunction __destruct(){}\n\r";
		
		$this->GenerarPdf($arregloCamposTabla,$arregloCampos,$ClassPHP);
		$this->GenerarHeader($arregloCamposTabla,$arregloCampos,$ClassPHP); 
		$this->GenerarFooter($arregloCamposTabla,$arregloCampos,$ClassPHP); 
		$this->GenerarHtml($arregloCamposTabla,$arregloCampos,$ClassPHP);
		
		$ClassPHP .= "\n\r\n\r}\n?>";
		
		
		if($this->generarSitio && FuncionesPHPLocal::GuardarArchivo(DIR_CLASES_LOGICA,$ClassPHP,$nombreClase."PDF.class.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}
		
		
		if($this->generarPaquete && FuncionesPHPLocal::GuardarArchivo($this->carpetaPaquete,$ClassPHP,$nombreClase."PDF.class.php"))
		{
			$msgactualizacion = "Se ha publicado correctamente.";
			$ret['IsSuccess'] = true;
		}

	}


	public function GenerarPdf($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function BuscarxCodigo(\$datos,&\$resultado,&\$numfilas)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif (!parent::BuscarxCodigo(\$datos,\$resultado,\$numfilas))\n";
		$ClassPHP.="\t\t\treturn false;\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
		
		
		
		$ClassPHP.="\tpublic function GenerarPdf(\$datos,\$formatosalida=\"F\")\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$mpdf=new mPDF('utf-8','A4','','','15','15','20','20');\n";
		$ClassPHP.="\t\t\$stylesheet = file_get_contents('modulos/".$arregloCampos['archivo']."/css/".$arregloCampos['archivo']."_pdf.css');\n";
		$ClassPHP.="\t\tif(\$formatosalida==\"F\")\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\tif(file_exists(PUBLICA.\$datos[\"".$arregloCampos['codigo']."\"].\".pdf\"))\n";
		$ClassPHP.="\t\t\tunlink(PUBLICA.\$datos[\"".$arregloCampos['codigo']."\"].\".pdf\");\n\r";			
		$ClassPHP.="\t}\n";
		$ClassPHP.="\t\$mpdf->SetTitle(\"".$arregloCampos['archivo']."_\".\$datos[\"".$arregloCampos['codigo']."\"].\".pdf\");\n";
		$ClassPHP.="\t\$mpdf->WriteHTML(\$stylesheet,1);\n";
		$ClassPHP.="\t\$this->GenerarHeader(\$datos,\$header);\n"; 
		$ClassPHP.="\t\$this->GenerarFooter(\$datos,\$footer,\$mpdf);\n"; 
		$ClassPHP.="\t\$mpdf->SetHTMLHeader(\$header);\n";
		$ClassPHP.="\t\$mpdf->SetHTMLFooter(\$footer);\n";

		$ClassPHP.="\t\$this->GenerarHtml(\$datos,\$html,\$mpdf);\n";
		$ClassPHP.="\t\$mpdf->WriteHTML(\$html);\n";
		$ClassPHP.="\t\$mpdf->Output(PUBLICA.\$datos[\"".$arregloCampos['codigo']."\"].\".pdf\", \$formatosalida);\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
		
			
			
		return true;	
	}
	
	public function GenerarHeader($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GenerarHeader(\$datos,&\$html)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$html = '<div></div>';\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
		
		return true;	
	}
	
	public function GenerarFooter($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GenerarFooter(\$datos,&\$html,\$mpdf)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP.="\t\t\$html = '<div class=\"footer\">{PAGENO} / {nb}</div>';\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
		
		return true;	
	}
	
	public function GenerarHtml($arregloCamposTabla,$arregloCampos,&$ClassPHP)
	{
		$ClassPHP.="\tpublic function GenerarHtml(\$datos,&\$html,\$mpdf)\n";
		$ClassPHP.="\t{\n";
		$ClassPHP .= "\t\t\$html = '".FuncionesPHPLocal::RenderFile("generadores/templates/template_pdf.php",$arregloCampos)."';\n";
		$ClassPHP.="\t\treturn true;\n";
		$ClassPHP.="\t}\n\r\n\r\n\r";	
		
		return true;	
	}
	
	

}
?>