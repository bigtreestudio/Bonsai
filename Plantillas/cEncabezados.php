<?php  

class cEncabezados
{
	var $conexionencab;
	var $metatags;
	var $plantillacarga;
	var $planthtmlcod=NULL;
	var $menucod;
	var $fondocod;

	function cEncabezados($conexion)
	{
		$this->conexionencab = &$conexion;
		$this->Init();
	}

//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// 

	

	private function Init()
	{

		$this->metatags['TITULO'] = "";
		$this->metatags['PALABRASCLAVE'] = "";
		$this->metatags['DESCRIPCION'] = "";
		$this->metatags['AUTOR'] = "";
		$this->menucod = array();
        $this->metatags['OGTITLE'] = "";
		$this->metatags['OGIMAGE'] = DOMINIOGENERAL."/public/cms/imagenes/logo.png";
		$this->metatags['OGURL'] =  "";
		$this->metatags['OGDESCRIPTION'] = "";

	}



	public function setTitle($title){$this->metatags['TITULO'] = $title;}
	public function setKeywords($keywords){$this->metatags['PALABRASCLAVE'] = $keywords;}
	public function setDescription($description){$this->metatags['DESCRIPCION'] = $description;}
	public function setOgTitle($og_title){$this->metatags['OGTITLE'] = $og_title;}
	public function setOgImage($og_image){$this->metatags['OGIMAGE'] = $og_image;}
	public function setOgUrl($og_url){$this->metatags['OGURL'] = $og_url;}
	public function setOgDescription($og_description){$this->metatags['OGDESCRIPTION'] = $og_description;}  
	public function setPlantilla($planthtmlcod){$this->planthtmlcod = $planthtmlcod;}
	public function setMenu($menucod){$this->menucod[]=$menucod;}
	public function setFondo($fondocod){$this->fondocod=$fondocod;}
	public function vaciarMenu(){$this->menucod = array();}

	


	public function EncabezadoMenuEmergente()
	{
		$this->CargarPlantilla($this->planthtmlcod);
		$html=file_get_contents(PUBLICA."plantillaHeader_".$this->plantillacarga['planthtmlcod'].".html");
		$this->Procesar($html,$htmlprocesado);
		echo $htmlprocesado;
	}


	public function PieMenuEmergente()
	{
		$html=file_get_contents(PUBLICA."plantillaFooter_".$this->plantillacarga['planthtmlcod'].".html");
		$this->Procesar($html,$htmlprocesado);
		echo $htmlprocesado;
	}
	
	
	
	
	public function Procesar($html,&$html_generado)
	{
		
		cSepararHTML::ProcesarHTML($html,$partes);
		

		$html_generado = "";
		
		include(PUBLICA."fondos.php");
		foreach($partes as $partehtml)
		{
			$html_generado="";
			if(is_array($partehtml))
			{
				switch($partehtml["Tipo"])
				{

					case "Hora":
						
						switch($partehtml["SubTipo"])
						{
							case "Actual":
								$html_generado .= FuncionesPHPLocal::ReemplazarTextoFechas(date($partehtml["Formato"]));
								echo $html_generado;
							break;
						}
						break;
						
					case "Plantilla":
						
						switch($partehtml["SubTipo"])
						{
							case "Campo":
								$html_generado .= $this->plantillacarga['planthtmldisco'];
								echo $html_generado;
							break;
						}
						break;
						
					case "Fondo":
							if (isset($this->fondocod) && $this->fondocod!="" && array_key_exists($this->fondocod,$arregloFondos))
								$html_generado .= ' style="background-image:url(\''.$arregloFondos[$this->fondocod]."".'\')"';
							elseif (isset($partehtml["Valor"]) && array_key_exists($partehtml["Valor"],$arregloFondos))
								$html_generado .= ' style="background-image:url(\''.$arregloFondos[$partehtml["Valor"]]."".'\')"';
							echo $html_generado;
						break;

					case "Tapas":

						switch($partehtml["SubTipo"])
						{
							case "CampoJson":
								$html_generado .= $this->metatags[$partehtml['Nombre']];
								echo $html_generado;								
							break;
						}
						
						break;
					
					case "Menu":
						
						switch($partehtml["SubTipo"])
						{
							case "Menu":
								$oTapasMenuTipos = new cMenu($this->conexionencab);
								$datos['menutipocte'] = $partehtml['Nombre'];
								if(!$oTapasMenuTipos->BuscarTipoxCte($datos,$resultado,$numfilas))
									return false;
								$datosarchivo = $this->conexionencab->ObtenerSiguienteRegistro($resultado);	
								$menu = file_get_contents(PUBLICA.$datosarchivo['menutipoarchivo']);
								if (isset($partehtml['Seleccionado']) && $partehtml['Seleccionado']!="")
								{
									$arregloMenu = explode("|",$partehtml['Seleccionado']);
									foreach($arregloMenu as $menucod)
										$this->setMenu($menucod);
										
								}
								$this->Procesar($menu,$html);
								echo $html;
								unset($oTapasMenuTipos);
							break;
							
							case "Class":
								if (in_array($partehtml['Codigo'],$this->menucod))
								{
									$html = " class='seleccionado' ";
									echo $html;
								}
							break;
								
						}
						break;

					case "Include":
						$conexion = &$this->conexionencab;
						$datosEnvio = explode('||',$partehtml['Parametros']);
						foreach ($datosEnvio as $index => $avPair)
						{
							  list($idValue, $value) = explode("=", $avPair);
							  $dataPostSend[$idValue] = $value;
						}
						include("htmldinamico/".$partehtml['Archivo']);
						
						break;
				
				}
			}else
				echo $partehtml;
		}
		
		return true;
	}
	



	private function CargarPlantilla($planthtmlcod=NULL)
	{
		
		$spnombre="sel_tap_plantillas_html";
		$sparam=array(
			"pxplanthtmlcod"=>($planthtmlcod==NULL)?0:1,
			"pplanthtmlcod"=>($planthtmlcod==NULL)?"":$planthtmlcod
			);
			
		if(!$this->conexionencab->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexionencab,"Error al buscar la plantilla default.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		if ($numfilas!=1)
			return false;
			
		$this->plantillacarga = $this->conexionencab->ObtenerSiguienteRegistro($resultado);
		return true;	
	}
	

	
}//fin clase
?>