<?php 
include(DIR_DATA."noticiascomentariosData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias 

class cNoticiasComentarios 
{
	protected $conexion;

	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	
	public function SetData(&$oNoticiasComentariosData,$datoscomentarios)
	{
		if (isset($datoscomentarios['comentariocod']))
			$oNoticiasComentariosData->setCodigo($datoscomentarios['comentariocod']);
		
		if (isset($datoscomentarios['noticiacod']))
			$oNoticiasComentariosData->setCodigoNoticia($datoscomentarios['noticiacod']);
		
		if (isset($datoscomentarios['comentarionombre']))
			$oNoticiasComentariosData->setNombre( FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscomentarios['comentarionombre'],ENT_QUOTES));;
		
		if (isset($datoscomentarios['comentarioemail']))
			$oNoticiasComentariosData->setEmail( FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscomentarios['comentarioemail'],ENT_QUOTES));
		
		if (isset($datoscomentarios['comentariodesc']))
			$oNoticiasComentariosData->setComentarioDescripcion( FuncionesPHPLocal::HtmlspecialcharsBigtree($datoscomentarios['comentariodesc'],ENT_QUOTES));
		
		if (isset($datoscomentarios['comentarioestado']))
			$oNoticiasComentariosData->setEstado($datoscomentarios['comentarioestado']);
		
		if (isset($datoscomentarios['comentariofalta']))
			$oNoticiasComentariosData->setFechaAlta($datoscomentarios['comentariofalta']);
		
		return true;
	}




	public function BusquedaAvanzada($datos,&$resultado,&$numfilas,&$CantidadTotal)
	{
			
		$spnombre="sel_not_comentarios_busqueda_avanzada";
		$sparam=array(
			'pfields'=> "COUNT(*) as total",
			'pnoticiacod'=> $datos['noticiacod'],
			'plimit'=> "",
			'porderby'=> "comentariofalta desc"
		);	
		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['porderby']= $datos['orderby'];


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los comentarios.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		$sparam['pfields'] = " * ";
		if ($datos['limit']!="")
			$sparam['plimit'] = $datos['limit'];

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los comentarios.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
		
	}
	
	
	public function BuscarxCodigo($datos)
	{
		$spnombre="sel_not_comentarios_xcomentariocod";
		$sparam=array(
			'pcomentariocod'=> $datos['comentariocod']
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar un comentario",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$fila = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$oNoticiasComentarios = new cNoticiasComentarios();
		$this->SetData($oNoticiasComentarios,$fila);
		
		return $oData;	
	}
	
	
	public function Insertar($datos,&$codigoinsertado)
	{
		
		if(!$this->_ValidarDatosVacios($datos))
			return false;
		
		
		$spnombre="ins_not_comentarios";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcomentarionombre'=> $datos['comentarionombre'],
			'pcomentarioemail'=> $datos['comentarioemail'],
			'pcomentariodesc'=> $datos['comentariodesc'],
			'pcomentarioestado'=> 90,
			'pcomentariofalta'=> date("Y-m-d H:i:s"),
			'pultmodfecha'=> date("Y-m-d H:i:s")
		);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al enviar un comentario",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	public function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['noticiacod']) || $datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Debe ingresar una noticia",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['noticiacod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error debe ingresar un campo numérico",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$oNoticias = new cNoticias($this->conexion);
		if(!$oNoticias->BuscarNoticia($datos))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error no existe la noticia",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		if (!isset($datos['comentarionombre']) || $datos['comentarionombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		if (!isset($datos['comentarioemail']) || $datos['comentarioemail']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Debe ingresar un email",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['comentarioemail'],"Email"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error debe ingresar un email valido",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		if (!isset($datos['comentariodesc']) || $datos['comentariodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Debe ingresar una descripcion",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}


		return true;
	}

			
}//FIN CLASE





?>