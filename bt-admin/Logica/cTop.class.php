<?php  
include(DIR_CLASES_DB."cTop.db.php");

class cTop extends cTopdb	
{
	protected $conexion;
	protected $formato;
	private $carpeta = "top/";
	
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
//		datos: datos completos del registro top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarTop($datos)
	{
		$nombrearchivo = $datos['toparchubic'];
		$pathinfo = pathinfo($nombrearchivo);
		$extension = strtolower($pathinfo['extension']);
		$html = "";
		switch($extension)
		{
			case "jpg":
			case "png":
			case "gif":
				$html = '<img src="'.DOMINIO_SERVIDOR_MULTIMEDIA.$this->carpeta.$nombrearchivo.'?rand='.rand(0,50000).'" alt="Top">'; 
				break;
			case "swf":
				list($anchotop, $altotop, $tipo, $atr) = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$nombrearchivo);
				$ancho = 400;
				$alto = round(($ancho*$altotop)/$anchotop);
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
//			topcod = codigo del top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarTopxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTopxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Retorna una consulta con los datos del tipo de top

// Parámetros de Entrada:
//		datos: arreglo de datos
//			toptipocod = codigo del tipo de top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaTopTipos(&$spnombre,&$sparam)
	{
		
		$sparam=array(
				'orderby'=> "",
				);
	
		if (isset ($datos['toptipocod']) && $datos['toptipocod']!="")
		{
			$sparam['toptipocod']= $datos['toptipocod'];
			$sparam['xtoptipocod']= 1;
		}	

		if (!parent::BusquedaTopTipos ($spnombre,$sparam))
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

	public function BusquedaTopTipoSP(&$spnombre,&$sparam)
	{
		
		if (!parent::BusquedaTopTipoSP ($spnombre,$sparam))
			return false;
		
		return true;		
	}	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top
//			toptipocod = tipo de top
//			topdesc = descripcion del top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
					
		$sparam=array(
			'xtoptipocod'=> 0,
			'toptipocod'=> "",
			'xtopdesc'=> 0,
			'topdesc'=> "",
			'orderby'=> "topcod desc",
			'limit'=> ""
		);	
			
		if (isset ($datos['toptipocod']) && $datos['toptipocod']!="")
		{
			$sparam['toptipocod']= $datos['toptipocod'];
			$sparam['xtoptipocod']= 1;
		}	
		if (isset ($datos['topdesc']) && $datos['topdesc']!="")
		{
			$sparam['topdesc']= $datos['topdesc'];
			$sparam['xtopdesc']= 1;
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
// Genera un nuevo archivo y modifica el registro de tops

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarArchivo($datos)
	{
		
		$input = fopen($datos['ubicacionfisica'], "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		$datos['toparchsize'] = $realSize;
		fclose($input);		
		
		if (!$this->BuscarTopxCodigo($datos,$resultado,$numfilas))
			return false;
		
		$datostop = $this->conexion->ObtenerSiguienteRegistro($resultado);	


		$pathinfonuevo = pathinfo($datos['toparchubic']);
		$extensionnuevo = strtolower($pathinfonuevo['extension']);
		if($datostop['toparchnombre']!="")
		{			
			$pathinfoold = pathinfo($datostop['toparchubic']);
			$extensionold = strtolower($pathinfoold['extension']);
			
			//EN EL CASO DE QUE LA EXTENSION SEA DIFERENTE, ELIMINO EL ARCHIVO E INSERTO EL NUEVO.
			if ($extensionold!=$extensionnuevo)
			{
				unlink(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$datostop['toparchubic']);
			}else
				$datos['toparchubic'] = $datostop['toparchubic'];
		}


		if(!$this->ModificarDatosArchivo($datos))
			return false;

		$nombrearchivo=$datos['toparchubic'];
		$target = fopen(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$this->carpeta.$nombrearchivo, "w");        
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

			
		return true;	
	}

	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un nuevo top

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			topcod = codigo del top
//			toptipocod = tipo de top
//			toptarget = target del top
//			toporden = orden del top
//			topdesclarga = descripcion larga del top
//			topdesc = descripcion del top
//			topurl = descripcion de la url del top


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->ValidarInsertar($datos,$datosvalidados))
			return false;
		
		$datosvalidados["toporden"] = "NULL";
		if (!parent::Insertar ($datosvalidados,$codigoinsertado))
			return false;
			
		$datos['topcod'] = $codigoinsertado;	
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar un  top

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos,$datosvalidados))
			return false;

		$datosvalidados["toporden"] = "NULL";
		$datosvalidados['topcod']= $datos['topcod'];		
		if (!parent::Modificar ($datosvalidados))
			return false;
			
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar/Desactivar  de un top cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

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

// Desactivar un top cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topod = codigo del top

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Desactivar($datos)
	{	
		$datos['topestado'] = NOPUBLICADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar el estado del top cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{	
		$datos['topestado'] = PUBLICADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminarun top cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top
//			topestado = estado del top


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		$datos['topestado'] = ELIMINADO;
		if (!$this->ModificarEstado($datos))
			return false;
		
		return true;	
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar el estado del top

// Parámetros de Entrada:
//		datos: arreglo de datos
//			topcod = codigo del top

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
//			topchubic = Nombre del archivo (fisico)
//			toparchnombre = Nombre del archivo (nombre del archivo subido)
//			toparchsize = Tamaño del archivo.
//			topcod = codigo del top


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

// Function que valida los datos al momento de insertar un nuevo top

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function ValidarInsertar($datos,&$datosvalidados)
	{	
		
		if (!$this->_ValidarDatosVacios($datos,$datosvalidados))
			return false;
	
		if ($datos['toptipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar tipo de top ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));				
			return false;				
		}else
			$datosvalidados['toptipocod']=$datos['toptipocod'];	


		return true;
	}	
	 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de modificar un top

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

// Function que valida los datos al momento de insertar un nuevo top

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			topcod = codigo del top
//			toptipocod = tipo de top
//			toptarget = target del top
//			toporden = orden del top
//			topdesclarga = descripcion larga del top
//			topdesc = descripcion del top
//			topurl = descripcion de la url del top

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

private function _ValidarDatosVacios($datos,&$datosvalidados)
	{

		$datosvalidados=array();
		if ($datos['topdesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar una descripci&oacute;n. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['topdesc'] = $datos['topdesc'];
		
		if ($datos['topdesclarga']=="")
		{
			$datosvalidados['topdesclarga']="NULL";
		}else
			$datosvalidados['topdesclarga'] = $datos['topdesclarga'];

		if ($datos['topurl']=="")
		{
			$datosvalidados['topurl']="NULL";
		}else
			$datosvalidados['topurl'] = $datos['topurl'];
			
		if ($datos['toptarget']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un target. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['toptarget'] = $datos['toptarget'];
		
		if ($datos['topestado']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Estado incorrecto. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
			$datosvalidados['topestado'] = $datos['topestado'];

		return true;
	}


}
?>