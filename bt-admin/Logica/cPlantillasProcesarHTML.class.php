<?php 
class cPlantillasProcesarHTML
{
	
	protected $esadmin;
	protected $conexion;
	protected $formato;
	
	// Constructor de la clase
	function __construct($conexion,$esadmin = false,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		$this->esadmin = $esadmin;
    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 



	function ProcesarHTML($datostapa, $html,&$html_generado)
	{

		cSepararHTML::ProcesarHTML($html,$partes);

		foreach($partes as $partehtml)
		{
			if(is_array($partehtml))
			{
				switch($partehtml["Tipo"])
				{
					case "Analytics":
						$html_generado .= "";
					break;
					case "Plantilla":
						switch($partehtml["SubTipo"])
						{
							case "Campo":
								$html_generado .= PUBLICADEADMIN;
								$html_generado .= $datostapa[$partehtml["Nombre"]];
								
							break;
						}
						break;

					case "Tapas":
						switch($partehtml["SubTipo"])
						{
							case "CampoJson":
								$html_generado .= "";
								
							break;
						}
						
						break;
					
					case "Menu":
					
						switch($partehtml["SubTipo"])
						{
							case "Menu":
								$oTapasMenuTipos = new cTapasMenuTipos($this->conexion,$this->formato);
								$datos['menutipocte'] = $partehtml['Nombre'];
								if(!$oTapasMenuTipos->BuscarxCte($datos,$resultado,$numfilas))
									return false;
								$datosarchivo = $this->conexion->ObtenerSiguienteRegistro($resultado);	
								$html_generado .= FuncionesPHPLocal::RenderFile(PUBLICA.$datosarchivo['menutipoarchivo'],array());
								
							break;
						}

						break;
				}
			}else
				$html_generado .= $partehtml;
		}
		
		return true;
	}




	function Procesar($datosplantilla,&$html_generado,&$arreglozonas)
	{
		$arreglozonas = array();
		$oZonasMacros = new cPlantillasMacrosZonas($this->conexion,$this->formato);
		$oMacros = new cPlantillasMacros($this->conexion,$this->formato);
		$oPlantillasAreas = new cPlantillasAreas($this->conexion,$this->formato);

		$this->ProcesarHTML($datosplantilla,$datosplantilla['planthtmlheader'],$html_generado);

		if(!$oPlantillasAreas->TraerxPlantilla($datosplantilla,$resultadoAreas,$numfilasAreas))
			return false;
		
		$html_generado .= '<div id="msgGuardando"></div>';
		$html_generado .= '<div id="PopupCargaZona"></div>';
		$html_generado .= '<link href="css/jquery-ui-11/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />';
		$html_generado .= '<link href="modulos/tap_tapas/css/estilos.css" rel="stylesheet" type="text/css" />';
		$html_generado .= '<script type="text/javascript" src="js/jquery-ui.min.js"></script>';
		$html_generado .= '<script type="text/javascript" src="js/jquery.blockUI.js"></script>';
		$html_generado .= '<script type="text/javascript" src="modulos/tap_tapas/js/confeccionar_plantilla.js"></script>';

		
		if ($numfilasAreas>0)
		{
		$html_generado .= '<div class="macrosMenu">';
			$html_generado .= '<div class="menucarga">';
				$html_generado .= '<ul>';
					$html_generado .= '<li>';
						$html_generado .= '<a class="boton verde" onclick="CargarNuevoMacro('.$datosplantilla['plantcod'].')" href="javascript:void(0)">';
							$html_generado .= 'Cargar Nuevo Macro';
						$html_generado .= '</a>';
					$html_generado .= '</li>';
				$html_generado .= '</ul>';
			$html_generado .= '</div>';
		$html_generado .= '</div>';
		}else
		{
			
			$html_generado .= '<div style="font-size:18px; text-align:center; padding:50px 0;">Debe agregar al menos un &aacute;rea</div>';
		}
		while($filaAreas = $this->conexion->ObtenerSiguienteRegistro($resultadoAreas))
		{
			$html_generado .= '<div class="areas" >';
			$this->ProcesarHTML($datosplantilla,$filaAreas['areahtmlinicio'],$html_generado);
			$oMacros->TraerxArea($filaAreas,$resultadomacro,$numfilasmacro);
			$html_generado .= '<div class="macros generar_html" id="area_'.$filaAreas['areacod'].'">';
			if ($numfilasmacro>0)
			{
				while ($datosmacro = $this->conexion->ObtenerSiguienteRegistro($resultadomacro))
				{
					$oZonasMacros->BuscarZonasxPlantMacrocod($datosmacro,$resultadozonas,$numfilaszonas);
					if ($numfilaszonas>0)
					{
						$this->MostrarMacro($datosmacro,$resultadozonas,$htmlmacro);
						$html_generado .= $htmlmacro;
					}
				}
			}
			$html_generado .= '</div>';
			$html_generado .= '</div>';
			$this->ProcesarHTML($datosplantilla,$filaAreas['areahtmlfin'],$html_generado);
		}
		$this->ProcesarHTML($datosplantilla,$datosplantilla['planthtmlfooter'],$html_generado);
		return true;	
	}
	
	
	
	public function RecargarMacro($datos,&$html_generado)
	{
		$html_generado="";
		
		$oPlantillasMacros = new cPlantillasMacros($this->conexion,$this->formato);
		if(!$oPlantillasMacros->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosmacro = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		$oZonasMacros = new cPlantillasMacrosZonas($this->conexion,$this->formato);
		$oZonasMacros->BuscarZonasxPlantMacrocod($datosmacro,$resultadozonas,$numfilaszonas);
		$this->MostrarMacro($datosmacro,$resultadozonas,$html_generado);


		unset($oZonasMacros);
		unset($oPlantillasMacros);
		return true;
	}
	
	private function MostrarMacro($datosmacro,$resultadozonas,&$html_generado)
	{
		$html_generado="";
		$class = "";
		if ($datosmacro['plantmacrodatos']!="")
		{
			$datosextra = json_decode($datosmacro['plantmacrodatos']);
			if (isset($datosextra->Class))
				$class = $datosextra->Class;
		}
		$html_generado .= '<div class="zonascargadas clearfix '.$class.'" id="plantmacrocod_'.$datosmacro['plantmacrocod'].'" style="position:relative">';
		$html_generado .= '<div class="clearfix titulomacro">Macro: '. FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmacro['macrodesc'],ENT_QUOTES).' - C&oacute;digo:'.$datosmacro['plantmacrocod'].'</div>';
		while ($datoszona = $this->conexion->ObtenerSiguienteRegistro($resultadozonas))
		{
			$html_generado .= '<div class="zona '.$datoszona['estructuraclass'].' clearfix">';
				$html_generado .= '<div class="add_column">';
				$html_generado .= '	<div><a href="javascript:void(0)" onclick="ModalAgregarColumna('.$datoszona['macrozonacod'].','.$datosmacro['plantmacrocod'].')" title="AgregarColumna">agregar columnas</a></div>';
				$html_generado .= '</div><div style="clear:both; height:1px;">&nbsp;</div>';
				$html_generado .= '<div id="macrozonacod_'.$datoszona['macrozonacod'].'">';
					$this->CargarColumnas($datoszona,$html);
					$html_generado .=$html;
				$html_generado .= '</div>';
			$html_generado .= '</div>';
			$html_generado .= '<script type="text/javascript">jQuery(document).ready(function(){CargarMovimientoMacro('.$datoszona['macrozonacod'].')});;</script>';
		}
		$html_generado .= '<div class="modules_header_zonas" id="tools_'.$datosmacro['plantmacrocod'].'">';
		$html_generado .= '	<div class="modules_edit"><a href="javascript:void(0)" onclick="EditarMacro('.$datosmacro['plantmacrocod'].')" title="Editar">&nbsp;</a></div>';
		$html_generado .= '	<div class="modules_move"><a href="javascript:void(0)" title="Mover">&nbsp;</a></div>';
		$html_generado .= '	<div class="modules_delete"><a href="javascript:void(0)" onclick="EliminarMacro('.$datosmacro['plantmacrocod'].')" title="Eliminar">&nbsp;</a></div>';
		$html_generado .= '</div>';
		$html_generado .= '</div>';
		
		return true;
	}
	

	public function CargarColumnas($datos,&$html_generado)
	{
		$oPlantillasMacrosZonasColumnas = new cPlantillasMacrosZonasColumnas($this->conexion,$this->formato);
		$oPlantillasZonas = new cPlantillasZonas($this->conexion,$this->formato);
		if(!$oPlantillasMacrosZonasColumnas->BuscarZonasColumnasxMacrozonacod($datos,$resultado,$numfilas))
			return false;
		
		$html_generado="";
		while ($datoscol = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if(!$oPlantillasZonas->BuscarZonasxPlantMacroColumnacod($datoscol,$resultadoestruc,$numfilasestruc))
				return false;
			$html_generado .= '<div class="columnascargadas clearfix" id="plantmacrocolumnacod_'.$datoscol['plantmacrocolumnacod'].'" style="position:relative">';
				$html_generado .= '<div class="clearfix titulocolumna">Columna: '. FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscol['columnadesc'],ENT_QUOTES).' - C&oacute;digo:'.$datoscol['plantmacrocolumnacod'].'</div>';
			while ($datoscolestruc = $this->conexion->ObtenerSiguienteRegistro($resultadoestruc))
			{

				$class = "";
				if ($datoscolestruc['zonadatos']!="")
				{
					$datosextra = json_decode($datoscolestruc['zonadatos']);
					if (isset($datosextra->Class))
						$class = $datosextra->Class;
				}
	
				$html_generado .= '<div class="columna '.$datoscolestruc['colestructuraclass'].' '.$class.'">&nbsp;';
					$html_generado .= '<div>'.$datoscolestruc['colestructuradesc']."</div>";
					$html_generado .= '<div class="modules_header_columnas" id="toolsplant_'.$datoscol['plantmacrocolumnacod'].'">';
					$html_generado .= '	<div class="modules_edit"><a href="javascript:void(0)" onclick="EditarColumna('.$datoscolestruc['zonacod'].')" title="Editar">&nbsp;</a></div>';
					$html_generado .= '</div>';
				$html_generado .= '</div>';
			}
				$html_generado .= '<div class="modules_header_columnas" id="toolsplant_'.$datoscol['plantmacrocolumnacod'].'">';
				$html_generado .= '	<div class="modules_move_column"><a href="javascript:void(0)" title="Mover">&nbsp;</a></div>';
				$html_generado .= '	<div class="modules_delete"><a href="javascript:void(0)" onclick="EliminarColumna('.$datoscol['plantmacrocolumnacod'].')" title="Eliminar">&nbsp;</a></div>';
				$html_generado .= '</div>';
			$html_generado .= '</div>';
		}
		
		
		return true;
	}



	
}//FIN CLASE

?>