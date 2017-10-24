<?php 
class cProcesarElementosDinamicosHTML
{

	protected $conexion;

	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 


	function Procesar($html,&$html_generado)
	{
		
		cSepararHTML::ProcesarHTML($html,$partes);
		$html_generado = "";
		foreach($partes as $partehtml)
		{
			if(is_array($partehtml))
			{
				$Class = new $partehtml["Tipo"]($this->conexion);
				$Class->Procesar($partehtml,$html);
				$html_generado .= $html;
			}else
				$html_generado .= $partehtml;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

	
}//FIN CLASE


class cNoticiasDinamicas
{

	protected $conexion;

	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	
	
	
	public function Procesar($partehtml,&$html)
	{
		$html = "";
		$parametros = "";
		if (isset($partehtml['Parametros']))
			$parametros = explode("||",$partehtml['Parametros']);
		$oNoticiaService = new cNoticias($this->conexion);
		
		switch($partehtml['SubTipo'])
		{
			case "UltimasNoticias":
				foreach ($parametros as $parametro)
				{
					$partes = explode("=",$parametro);
					if ($partes[1]!="")
						$datosfiltro[$partes[0]] = $partes[1];
				}

				$oListUltNoticias = $oNoticiaService->getUltimasNoticias($datosfiltro);
				
				$html = '<ul>';
				foreach($oListUltNoticias as $oUltimaNoticia){
					$html .= '<li class="clearfix">';
					$html .= '		<div class="fechaNovedad"><div class="iconocalendario fondoiconos">&nbsp;</div>'.$oUltimaNoticia->getFecha("d F, Y").'</div>';
					$html .= '		<h4>';
					$html .= '			<a href="'.DOMINIORAIZSITE.$oUltimaNoticia->getDominioCategoria().'/'.$oUltimaNoticia->getDominio().'" title="'.$oUltimaNoticia->getTitulo().'">';
					$html .= '				'.$oUltimaNoticia->getTitulo();
					$html .= '			</a>';
					$html .= '		</h4>';
					$html .= '	</li>';
				}
				$html .= '</ul>';
			break;
		}
		
	}
}
?>