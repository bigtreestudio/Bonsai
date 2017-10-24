<?php 
include(DIR_DATA."multimediaData.php");

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias 

class cMultimedia
{
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function SetData(&$oData,$datos)
	{
		if (isset($datos['multimediacod']))
			$oData->setCodigo($datos['multimediacod']);
		if (isset($datos['multimediacatcod']))
			$oData->setCodigoCategoria($datos['multimediacatcod']);
		if (isset($datos['multimediatitulo']))
			$oData->setTitulo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediatitulo'],ENT_QUOTES));
		if (isset($datos['multimediadesc']))
			$oData->setDescripcion( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimediadesc'],ENT_QUOTES));
		if (isset($datos['multimedianombre']))
			$oData->setNombre( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['multimedianombre'],ENT_QUOTES));
		if (isset($datos['multimediaubic']))
			$oData->setUbicacion($datos['multimediaubic']);
		if (isset($datos['multimediaidexterno']))
			$oData->setIdExterno($datos['multimediaidexterno']);
		if (isset($datos['multimediatipocod']))
			$oData->setTipoCodigo($datos['multimediatipocod']);
		if (isset($datos['multimediaestadocod']))
			$oData->setEstado($datos['multimediaestadocod']);
		if (isset($datos['multimediacatcarpeta']))
			$oData->setCarpetaCategoria($datos['multimediacatcarpeta']);
		
		return true;
	}

	public function BuscarxCodigo($datos)
	{
		$spnombre="sel_mul_multimedia_xmultimediacod";
		$sparam=array(
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar elmento multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oData = new MultimediaData();
		$this->SetData($oData,$datos);
		
		return $oData;	
	}
	
	function esAudioExterno($tipo)
	{
		switch($tipo)
		{
			case GOEA:
				return true;
		}
		return false;
	}
	

	function ArmarUrlVideo($tipo,$idExterno)
	{
		$url = "";
		switch($tipo)
		{
			case 5:
				$url = "http://www.youtube.com/embed/".$idExterno;
				break;
			case 7:
				$url = "http://player.vimeo.com/video/".$idExterno;
				break;
		}	
		return $url;
	}


	function ArmarImagenVideo($tipo,$idExterno)
	{
		$url = "";
		switch($tipo)
		{
			case 5:
				if (file_exists(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."externalimg/you/".$idExterno.".jpg"))
					$url = DOMINIO_SERVIDOR_MULTIMEDIA."externalimg/you/".$idExterno.".jpg";
				else
				{	
					$url = "http://img.youtube.com/vi/".$idExterno."/0.jpg";
					cMultimedia::grab_image($url,CARPETA_SERVIDOR_MULTIMEDIA_FISICA."externalimg/you/".$idExterno.".jpg");
				}
				break;
			case 7:
				if (file_exists(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."externalimg/vim/".$idExterno.".jpg"))
					$url = DOMINIO_SERVIDOR_MULTIMEDIA."externalimg/vim/".$idExterno.".jpg";
				else
				{	
					$ch = curl_init();
					$timeout = 0;
					curl_setopt ($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/".$idExterno.".json");
					curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
					
					// Getting binary data
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
					$image = json_decode(curl_exec($ch));
					curl_close($ch);
					$url = $image[0]->thumbnail_medium;
					cMultimedia::grab_image($url,CARPETA_SERVIDOR_MULTIMEDIA_FISICA."externalimg/vim/".$idExterno.".jpg");
				}
				break;
		}	
		return $url;
	}
	
	
	function grab_image($url,$saveto){
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$raw=curl_exec($ch);
		curl_close ($ch);
		if(file_exists($saveto)){
			unlink($saveto);
		}
		$fp = fopen($saveto,'x');
		fwrite($fp, $raw);
		fclose($fp);
	}	



}//FIN CLASE

?>