<?php 
class cTapasProcesarHTML
{
	
	protected $esadmin;
	protected $conexion;
	protected $formato;
	protected $tapacod;
	protected $publicar;
	protected $puedebloqueardesbloquear=false;
	protected $previsualizar;
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		$this->publicar = false;
    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

	public function SetearPublicar(){$this->publicar = true;}
	public function SetearPrevisualizar(){$this->previsualizar = true;}
	public function SetearPuedeBloquear(){$this->puedebloqueardesbloquear = true;}

	public function RecargarModulo($datos,&$html_generado)
	{
		$html_generado='';
		$oModulosTapa= new cTapasZonasModulos($this->conexion);
		$datosbusqueda['zonamodulocod'] = $datos['zonamodulocod'];
		if(!$oModulosTapa->BuscarModuloxCodigo($datosbusqueda,$resultado,$numfilas))
			return false;
	
		if($numfilas>0){
			$datosmodulo = $this->conexion->ObtenerSiguienteRegistro($resultado);
			if(!$this->CargarModulo($datosmodulo,$html_generado))
				return false;		
		}
		
		return true;
	}
	
	
	private function CargarModulo($datosModulo,&$html_generado)
	{
		$datosModulo['conexion'] = $this->conexion;
		if (!$this->publicar && !$this->previsualizar)
		{
			$datosModulo['htmledit'] = '<div class="modules_header" id="tools_'.$datosModulo['zonamodulocod'].'">';
			if ($this->puedebloqueardesbloquear && $datosModulo['modulobloqueado']==0)
				$datosModulo['htmledit'] .= '	<div class="modules_unblock"><a href="javascript:void(0)" onclick="BloquearDebloquearModulo('.$datosModulo['zonamodulocod'].',1)" title="Bloquear">&nbsp;</a></div>';
			elseif ($this->puedebloqueardesbloquear && $datosModulo['modulobloqueado']==1)
				$datosModulo['htmledit'] .= '	<div class="modules_block"><a href="javascript:void(0)" onclick="BloquearDebloquearModulo('.$datosModulo['zonamodulocod'].',0)" title="DesBloquear">&nbsp;</a></div>';
			
			if ($datosModulo['modulobloqueado']==0 || $this->puedebloqueardesbloquear)
				$datosModulo['htmledit'] .= '	<div class="modules_delete"><a href="javascript:void(0)" onclick="EliminarModulos('.$datosModulo['zonamodulocod'].')" title="Eliminar">&nbsp;</a></div>';		
			if ($datosModulo['modulobloqueado']==0 || $this->puedebloqueardesbloquear)
				$datosModulo['htmledit'] .= '	<div class="modules_edit"><a href="javascript:void(0)" onclick="AbrirEditarModulos('.$datosModulo['zonamodulocod'].')" title="Editar">&nbsp;</a></div>';
			if ($datosModulo['modulobloqueado']==0 || $this->puedebloqueardesbloquear)
				$datosModulo['htmledit'] .= '	<div class="modules_move"><a href="javascript:void(0)" title="Mover">&nbsp;</a></div>';
			$datosModulo['htmledit'] .= '</div>';
			$datosModulo['mouseaction'] = 'onmouseout="hideTools(\'tools_'.$datosModulo['zonamodulocod'].'\');" onmouseover="viewTools(\'tools_'.$datosModulo['zonamodulocod'].'\');"';
		}else
		{
			$datosModulo['mouseaction'] = "";
			$datosModulo['htmledit'] = "";
		}
		$htmlModuleRender = FuncionesPHPLocal::RenderFile("tapas_modulos/html/".$datosModulo['moduloarchivo'],$datosModulo);
		$this->ProcesarHTML($datosModulo, $htmlModuleRender,$html);
		$html_generado .= $html;
		
		return true;
	}




	function ProcesarHTML($datostapa, $html,&$html_generado)
	{

		cSepararHTML::ProcesarHTML($html,$partes);
		
		foreach($partes as $partehtml)
		{
			if(is_array($partehtml))
			{
				switch($partehtml["Tipo"])
				{

                    case "Hora":
						
						switch($partehtml["SubTipo"])
						{
							case "Actual":
								if (!$this->publicar)
									$html_generado .= FuncionesPHPLocal::ReemplazarTextoFechas(date($partehtml["Formato"]));
								else
									$html_generado .= htmlentities("$\$Tipo='Hora' SubTipo='Actual' Formato='l d \d\e M Y H:i'$$");
							break;
						}
						break;
						
                    case "Include":
					
						$conexion = &$this->conexion;
						$datosEnvio = explode('||',$partehtml['Parametros']);
						foreach ($datosEnvio as $index => $avPair)
						{
							  list($idValue, $value) = explode("=", $avPair);
							  $dataPostSend[$idValue] = $value;
						}
						$dataPostSend["conexion"] = $conexion;
						if (!$this->publicar)
							$html_generado .= FuncionesPHPLocal::RenderFile("tapas_modulos/htmldinamico/".$partehtml['Archivo'],$dataPostSend);
						else
							$html_generado .= htmlentities("$\$Tipo='Include' Archivo='".$partehtml['Archivo']."' Parametros='".$partehtml['Parametros']."'$$");

						break;

					case "Analytics":
						switch($partehtml["SubTipo"])
						{
							case "CodigoAnalytics":
								//codigo de analytics
								$oGoogle = new cGoogle($this->conexion);
								$datos['googlecod'] = $googlecod =1;
								if (!$oGoogle ->Buscar($datos,$resultado,$numfilas))
									return false;
								if ($numfilas==1)
								{
									$datosgoogle = $this->conexion->ObtenerSiguienteRegistro($resultado);
									$html_generado .= $datosgoogle['googlecodanalytics'];
								}
							break;
						}
						break;
						
					case "Plantilla":
						
						switch($partehtml["SubTipo"])
						{
							case "Campo":
								if (!$this->publicar)
									$html_generado .= PUBLICADEADMIN;
								$html_generado .= $datostapa[$partehtml["Nombre"]];
								
							break;
						}
						
						break;

					case "Fondo":

						if (isset($datostapa['fondocod']) && $datostapa['fondocod']!="")
						{
							$oFondos = new cFondos($this->conexion);
							$oFondos->BuscarxCodigo($datostapa,$resultadoFondo,$numfilasFondo);
							if ($numfilasFondo>0)
							{
								$datosFondo = $this->conexion->ObtenerSiguienteRegistro($resultadoFondo);
								if ($datosFondo['fondoimgubic'])
								{
									if (!$this->publicar || $this->previsualizar)
										$html_generado .= ' style="background-image:url(\''.DOMINIO_SERVIDOR_MULTIMEDIA."fondos/N".$datosFondo['fondoimgubic']."".'\')"';
									else
										$html_generado .= htmlentities("$\$Tipo='Fondo' Valor='".$datosFondo['fondocod']."'$$");
								}
							}
						}
						break;
					

					case "Tapas":

						$camposjson = "";
						if ($datostapa['tapametadata']!="")
							$camposjson = json_decode($datostapa['tapametadata'],1);
						switch($partehtml["SubTipo"])
						{
							case "CampoJson":
								if (isset($camposjson[$partehtml['Nombre']]))
									$html_generado .= utf8_decode($camposjson[$partehtml['Nombre']]);
								
							break;
						}
						
						break;
					
					case "Menu":
						
						switch($partehtml["SubTipo"])
						{
							case "Menu":
								if (!$this->publicar || $this->previsualizar)
								{
									$oTapasMenuTipos = new cTapasMenuTipos($this->conexion,$this->formato);
									$datos['menutipocte'] = $partehtml['Nombre'];
									if(!$oTapasMenuTipos->BuscarxCte($datos,$resultado,$numfilas))
										return false;
									$datosarchivo = $this->conexion->ObtenerSiguienteRegistro($resultado);	
									$html_generado .= FuncionesPHPLocal::RenderFile(PUBLICA.$datosarchivo['menutipoarchivo'],array());
									unset($oTapasMenuTipos);
								}else
								{
									$seleccionado="";
									if ($datostapa['menucod']!="")
									{
										$oTapasMenu = new cTapasMenu($this->conexion,$this->formato);
										if (!$oTapasMenu->BuscarxCodigo($datostapa,$resultadoTapas,$numfilas))
											return false;
										$tapaseleccionada=$datostapa['menucod'];
										if ($numfilas>0)
										{
											$datosmenusup = $this->conexion->ObtenerSiguienteRegistro($resultadoTapas);
											if ($datosmenusup['menucodsup']!="")	
												$tapaseleccionada .= "|".$datosmenusup['menucodsup'];
										}
										$seleccionado = " Seleccionado='".$tapaseleccionada."'";
									}
									$html_generado .= htmlentities("$\$Tipo='Menu'".$seleccionado." SubTipo='Menu' Nombre='".$partehtml['Nombre']."'$$");
								}
								break;
						}
						break;
				}
			}else
				$html_generado .= $partehtml;
		}
		
		return true;
	}




function Procesar($datostapa,&$html_generado,&$arreglozonas)
	{
		$arreglozonas = array();
		$oTapas = new cTapas($this->conexion,$this->formato);
		$oZonasMacros = new cPlantillasMacrosZonas($this->conexion,$this->formato);
		$oMacros = new cPlantillasMacros($this->conexion,$this->formato);
		$oAccesosDirectos = new cAccesosDirectos($this->conexion,$this->formato);
		$oPlantillasAreas = new cPlantillasAreas($this->conexion,$this->formato);
		
		$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($this->conexion,$this->formato);
		$puedePublicar = $oUsuariosModulosAcciones->TienePermisosAccion("000611");
		
		$this->tapacod = $datostapa['tapacod'];
		
		
		$this->ProcesarHTML($datostapa,$datostapa['planthtmlheader'],$html_generado);
		//$datosacceso['tipoacceso']=1;
		//$oAccesosDirectos->BuscarAccesosDirectosxTipoacceso($datosacceso,$resultadoacceso,$numfilasacceso);
		if(!$oPlantillasAreas->TraerxPlantilla($datostapa,$resultadoAreas,$numfilasAreas))
			return false;
		
		if (!$this->publicar && !$this->previsualizar)
		{
			$fileTapasModulos = file_get_contents(PUBLICA."json/tapas_modulos_1.json");
			$arrayTapasModulos = json_decode($fileTapasModulos,true);
			$arrayTapasModulos = FuncionesPHPLocal::ConvertiraUtf8($arrayTapasModulos);
			$arrayAccesoDirectos = array();
				
			$html_generado .= '<div class="macrosMenu">';
				$html_generado .= '<link href="css/jquery-ui-11/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />';
				$html_generado .= '<link href="modulos/tap_tapas/css/estilos.css" rel="stylesheet" type="text/css" />';
				$html_generado .= '<link href="modulos/tap_tapas/css/menu.css" rel="stylesheet" type="text/css" />';
				
				$html_generado .= '<script type="text/javascript" src="js/jquery-ui.min.js"></script>';
				$html_generado .= '<script type="text/javascript" src="js/jquery.blockUI.js"></script>';
				$html_generado .= '<script type="text/javascript" src="modulos/tap_tapas/js/confeccionar.js"></script>';
				$html_generado .= '<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js?v=1.2"></script>';
				$html_generado .= '<script type="text/javascript" src="js/touch-punch.min.js"></script>';

				

				$html_generado .= '<div id="PopupModulo"></div><div id="PopupEdit"></div>';
				$html_generado .= '<div class="menucargader">';
					$html_generado .= '<ul >';
						$html_generado .= '<li>';
							$html_generado .= '<a class="boton azul"  target="_blank" href="tap_tapas_previsualizar.php?tapacod='.$datostapa['tapacod'].'" title="Previsualizar">';
								$html_generado .= 'Previsualizar';
							$html_generado .= '</a>';
						$html_generado .= '</li>';
						if ($datostapa['tapaestado']==ACTIVO && $puedePublicar)
						{
							$html_generado .= '<li>';
								$html_generado .= '<a class="boton verde" onclick="Publicar('.$datostapa['tapacod'].')" href="javascript:void(0)">';
									$html_generado .= 'Publicar';
								$html_generado .= '</a>';
							$html_generado .= '</li>';
						}
					$html_generado .= '</ul>';
					$html_generado .= '<div style="clear:both;"></div>';
				$html_generado .= '</div>';



				$html_generado .= '<div class="menuizq" >';
				$html_generado .= '<div id="menu-wrap" >';
					$html_generado .= '<ul id="menu">';
						$html_generado .= '<li>';
							$html_generado .= '<a href="#">';
								$html_generado .= 'Cargar Nuevo Modulo';
							$html_generado .= '</a>';
							$html_generado .= '<ul>';
    						foreach($arrayTapasModulos as $keycatcod=>$datos){ 
								$html_generado .='<li><a href="#">'.FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['catdesc'],ENT_QUOTES).'</a>';
							    $html_generado .= '<ul>';
								foreach($datos['modulos'] as $keymodulocod=>$datosmodule){ 
				                    $html_generado .='<li><a href="javascript:void(0)" onclick="AbrirAgregarModulos('.$keymodulocod.')">'.FuncionesPHPLocal::HtmlspecialcharsBigtree($datosmodule['modulodesc'],ENT_QUOTES).'</a></li>';}   							
								$html_generado .= '</ul></li>';
								}
							$html_generado .= '</ul>';
						$html_generado .= '</li>';
					$html_generado .= '</ul>';
				$html_generado .= '</div>';	
					$html_generado .= '<div style="clear:both; height:1px;"></div>';
					/*$html_generado .= '<ul id="accesosdirectos">';
					while ($accesosdirectos = $this->conexion->ObtenerSiguienteRegistro($resultadoacceso))
					{
						$html_generado .= '<li><img src="'.$accesosdirectos["accesoimg"].'" alt="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($accesosdirectos["accesotextomenu"],ENT_QUOTES).'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($accesosdirectos["accesotextomenu"],ENT_QUOTES).'" onclick="AbrirAgregarModulosAccesosDirectos('.$accesosdirectos['accmodulocod'].')" /></li>';
					}
					$html_generado .= '</ul>';
					$html_generado .= '<div style="clear:both; height:1px;"></div>';*/
					$html_generado .= '<div id="modulostmp"></div>';
				
			$html_generado .= '</div>';

		}
		
		while($filaAreas = $this->conexion->ObtenerSiguienteRegistro($resultadoAreas))
		{
		
			$this->ProcesarHTML($datostapa,$filaAreas['areahtmlinicio'],$html_generado);
			$oMacros->TraerxArea($filaAreas,$resultadomacro,$numfilasmacro);
			
			if ($numfilasmacro>0)
			{
				$html_generado .= '<div class="macros plantillagral">';
	
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
	
	
			}
			$this->ProcesarHTML($datostapa,$filaAreas['areahtmlfin'],$html_generado);

		}
		
		$this->ProcesarHTML($datostapa,$datostapa['planthtmlfooter'],$html_generado);
		return true;
		
	}	
	
	private function MostrarMacro($datosmacro,$resultadozonas,&$html_generado)
	{
		$oMacros = new cMacros($this->conexion,$this->formato);
		$html_generado="";
		$class = "";
		if ($datosmacro['plantmacrodatos']!="")
		{
			$datosextra = json_decode($datosmacro['plantmacrodatos']);
			$class = $datosextra->Class;
		}
		
		$classzona = "";
		if (!$this->publicar)
			$classzona = "zonascargadas";
		
		$muestramacro = true;
	
		if ($this->publicar)
		{
			if (!$oMacros->BuscarNoticiasMacroxplantmacrocod($datosmacro,$resultadomacrosnot,$numfilasmacrosnot))
				return false;
				
			if($numfilasmacrosnot==0)
				$muestramacro = false;
		}
		
		if ($muestramacro)
		{
			$html_generado .= '<div class="'.$classzona.' clearfix '.$class.'" id="plantmacrocod_'.$datosmacro['plantmacrocod'].'" style="position:relative">';
			while ($datoszona = $this->conexion->ObtenerSiguienteRegistro($resultadozonas))
			{
				$html_generado .= '<div class="'.$datoszona['estructuraclass'].' clearfix">';
					$this->CargarColumnas($datoszona,$html);
					$html_generado .=$html;
				$html_generado .= '</div>';
			}
			$html_generado .= '</div>';
		}
		return true;
	}
	

	public function CargarColumnas($datos,&$html_generado)
	{
		$oPlantillasMacrosZonasColumnas = new cPlantillasMacrosZonasColumnas($this->conexion,$this->formato);
		$oPlantillasZonas = new cPlantillasZonas($this->conexion,$this->formato);
		$oModulosTapa= new cTapasZonasModulos($this->conexion);
		if(!$oPlantillasMacrosZonasColumnas->BuscarZonasColumnasxMacrozonacod($datos,$resultadocol,$numfilas))
			return false;
		
		$html_generado="";
		while ($datoscol = $this->conexion->ObtenerSiguienteRegistro($resultadocol))
		{
			if(!$oPlantillasZonas->BuscarZonasxPlantMacroColumnacod($datoscol,$resultadoestruc,$numfilasestruc))
				return false;

			$html_generado .= '<div class="'.$datoscol['columnaclass'].' clearfix">';
			while ($datoscolestruc = $this->conexion->ObtenerSiguienteRegistro($resultadoestruc))
			{
				$class = "";
				if ($datoscolestruc['zonadatos']!="")
				{
					$datosextra = json_decode($datoscolestruc['zonadatos']);
					$class = $datosextra->Class;
				}
				$classzona = "";
				if (!$this->publicar)
					$classzona = "zona";
				$html_generado .= '<div class="'.$classzona.' '.$datoscolestruc['colestructuraclass'].' '.$class.'" id="zonacod_'.$datoscolestruc['zonacod'].'">';
				$datosbusqueda['tapacod'] = $this->tapacod;
				$datosbusqueda['zonacod'] = $datoscolestruc['zonacod'];
				if(!$oModulosTapa->BuscarModulosxZonaxTapa($datosbusqueda,$resultado,$numfilas))
					return false;
				while ($datosModulo = $this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					if(!$this->CargarModulo($datosModulo,$html_generado))
						return false;										
				}
				$html_generado .= '</div>';
			}
			$html_generado .= '</div>';
			$html_generado .= '<div class="clearboth">&nbsp;</div>';
		}
		
		
		return true;
	}
	
	
}//FIN CLASE

?>