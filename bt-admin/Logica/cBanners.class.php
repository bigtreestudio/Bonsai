<?php  
include(DIR_CLASES_DB."cBanners.db.php");

class cBanners extends cBannersdb	
{
	protected $conexion;
	protected $formato;
	private $carpeta = "banners/";
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	



//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: datos completos del registro banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarBanner($datos)
	{
		$nombrearchivo = $datos['bannerarchubic'];
		$pathinfo = pathinfo($nombrearchivo);
		$extension = strtolower($pathinfo['extension']);
		$html = "";
		switch($extension)
		{
			case "jpg":
			case "png":
			case "gif":
				$html = '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$this->carpeta.$nombrearchivo.'?rand='.rand(0,50000).'" alt="Banner">'; 
				break;
			case "swf":
				list($anchobanner, $altobanner, $tipo, $atr) = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$nombrearchivo);
				$ancho = 400;
				$alto = round(($ancho*$altobanner)/$anchobanner);
				$html .= '<object type="application/x-shockwave-flash" data="'.DOMINIO_SERVIDOR_MULTIMEDIA.$this->carpeta.$nombrearchivo.'" width="'.$ancho.'" height="'.$alto.'">';
				$html .= '	<param name="movie" value="'.DOMINIO_SERVIDOR_MULTIMEDIA.$this->carpeta.$nombrearchivo.'" />';
				$html .= '	<param name="quality" value="high" />';
				$html .= '	<param name="wmode" value="transparent" />';
				$html .= '	<embed src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$this->carpeta.$nombrearchivo.'" width="'.$ancho.'" height="'.$alto.'" quality="high" type="application/x-shockwave-flash"  pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
				$html .= '</object> ';
				break;
		}
		return $html;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarBannerxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarBannerxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Retorna una consulta con los datos del tipo de banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannertipocod = codigo del tipo de banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaBannerTipos(&$spnombre,&$sparam)
	{
		
		$sparam=array(
				'orderby'=> "",
				);
	
		if (isset ($datos['bannertipocod']) && $datos['bannertipocod']!="")
		{
			$sparam['bannertipocod']= $datos['bannertipocod'];
			$sparam['xbannertipocod']= 1;
		}	

		if (!parent::BusquedaBannerTipos ($spnombre,$sparam))
			return false;
		return true;	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaBannerTipoSP(&$spnombre,&$sparam)
	{
		
		if (!parent::BusquedaBannerTipoSP ($spnombre,$sparam))
			return false;
		
		return true;		
	}	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner
//			bannertipocod = tipo de banner
//			bannerdesc = descripcion del banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
					
		$sparam=array(
			'xbannertipocod'=> 0,
			'bannertipocod'=> "",
			'xbannerdesc'=> 0,
			'bannerdesc'=> "",
			'orderby'=> "bannercod desc",
			'limit'=> ""
		);	
			
		if (isset ($datos['bannertipocod']) && $datos['bannertipocod']!="")
		{
			$sparam['bannertipocod']= $datos['bannertipocod'];
			$sparam['xbannertipocod']= 1;
		}	
		if (isset ($datos['bannerdesc']) && $datos['bannerdesc']!="")
		{
			$sparam['bannerdesc']= $datos['bannerdesc'];
			$sparam['xbannerdesc']= 1;
		}
				
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}




//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo y modifica el registro de banners

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarArchivo($datos)
	{
		
		$input = fopen($datos['ubicacionfisica'], "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		$datos['bannerarchsize'] = $realSize;
		fclose($input);		
		
		if (!$this->BuscarBannerxCodigo($datos,$resultado,$numfilas))
			return false;
		
		$datosbanner = $this->conexion->ObtenerSiguienteRegistro($resultado);	


		$pathinfonuevo = pathinfo($datos['bannerarchubic']);
		$extensionnuevo = strtolower($pathinfonuevo['extension']);
		if($datosbanner['bannerarchnombre']!="")
		{			
			$pathinfoold = pathinfo($datosbanner['bannerarchubic']);
			$extensionold = strtolower($pathinfoold['extension']);
			
			//EN EL CASO DE QUE LA EXTENSION SEA DIFERENTE, ELIMINO EL ARCHIVO E INSERTO EL NUEVO.
			if ($extensionold!=$extensionnuevo)
			{
				unlink(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$datosbanner['bannerarchubic']);
			}else
				$datos['bannerarchubic'] = $datosbanner['bannerarchubic'];
		}


		if(!$this->ModificarDatosArchivo($datos))
			return false;

		$nombrearchivo=$datos['bannerarchubic'];
		$target = fopen(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$nombrearchivo, "w");        
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

			
		return true;	
	}

	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un nuevo banner

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			bannercod = codigo del banner
//			bannertipocod = tipo de banner
//			bannertarget = target del banner
//			bannerorden = orden del banner
//			bannerdesclarga = descripcion larga del banner
//			bannerdesc = descripcion del banner
//			bannerurl = descripcion de la url del banner


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->ValidarInsertar($datos,$datosvalidados))
			return false;
		
		$datosvalidados["bannerorden"] = "NULL";
		if (!parent::Insertar ($datosvalidados,$codigoinsertado))
			return false;
			
		$datos['bannercod'] = $codigoinsertado;	
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar un  banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos,$datosvalidados))
			return false;

		$datosvalidados["bannerorden"] = "NULL";
		$datosvalidados['bannercod']= $datos['bannercod'];		
		if (!parent::Modificar ($datosvalidados))
			return false;
			
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar/Desactivar  de un banner cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActivarDesactivar($datos)
	{	
		if($_POST['accion']=4){
		if (!$this->Activar($datos))
			return false;
		}else{
			if($_POST['accion']=5){
		if (!$this->Desactivar($datos))
		return false;
			}else{
				//msj de error
			}
		}
			
		return true;	
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar un banner cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Desactivar($datos)
	{	
		$datos['bannerestado'] = NOPUBLICADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar el estado del banner cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{	
		$datos['bannerestado'] = PUBLICADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminarun banner cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner
//			bannerestado = estado del banner


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		$datos['bannerestado'] = ELIMINADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar el estado del banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function ModificarEstado($datos)
	{	
		
		if (!parent::ModificarEstado ($datos))
			return false;
		
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar los datos del archivo en la bd

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannerarchubic = Nombre del archivo (fisico)
//			bannerarchnombre = Nombre del archivo (nombre del archivo subido)
//			bannerarchsize = Tamaño del archivo.
//			bannercod = codigo del banner


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function ModificarDatosArchivo($datos)
	{	
		
		if (!parent::ModificarDatosArchivo ($datos))
			return false;
		
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de insertar un nuevo banner

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function ValidarInsertar($datos,&$datosvalidados)
	{	
		
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;
	
		if ($datos['bannertipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar tipo de banner ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));				
			return false;				
		}else
			$datosvalidados['bannertipocod']=$datos['bannertipocod'];	


		return true;
	}	
	 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de modificar un banner

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarModificar($datos,&$datosvalidados)
	{
		
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;

	
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de insertar una nueva noticia

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			bannercod = codigo del banner
//			bannertipocod = tipo de banner
//			bannertarget = target del banner
//			bannerorden = orden del banner
//			bannerdesclarga = descripcion larga del banner
//			bannerdesc = descripcion del banner
//			bannerurl = descripcion de la url del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

private function _ValidarDatosVacios($datos,&$datosvalidados)
	{

		$datosvalidados=array();
		if ($datos['bannerdesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar una descripci&oacute;n. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['bannerdesc'] = $datos['bannerdesc'];
		
		if ($datos['bannerdesclarga']=="")
		{
			$datosvalidados['bannerdesclarga']="NULL";
		}else
			$datosvalidados['bannerdesclarga'] = $datos['bannerdesclarga'];

		if ($datos['bannerurl']=="")
		{
			$datosvalidados['bannerurl']="NULL";
		}else
			$datosvalidados['bannerurl'] = $datos['bannerurl'];
			
		if ($datos['bannertarget']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un target. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['bannertarget'] = $datos['bannertarget'];
		
		if ($datos['bannerestado']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Estado incorrecto. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['bannerestado'] = $datos['bannerestado'];

		return true;
	}


}
?>