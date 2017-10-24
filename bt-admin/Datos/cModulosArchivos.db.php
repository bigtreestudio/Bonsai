<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de modulos archivos
abstract class cModulosArchivosdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	
	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con todos los usuarios que cumplan con las condiciones

// Parámetros de Entrada:
//		ArregloDatos: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Buscar ($ArregloDatos,&$numfilas,&$resultado)
	{
		$sparam=array('pestadomodcod' =>0);
		$sparam+=array('pestadoarchcod' =>0);
	
		$sparam+=array('pmodulocod' =>"");
		$sparam+=array('parchivocod' =>"");

		$sparam+=array('porderby' =>"modulocod");

		if (isset ($ArregloDatos['modulocod']))
		{
			if ($ArregloDatos['modulocod']!="")
			{	
				$sparam['pmodulocod']= $ArregloDatos['modulocod'];
				$sparam['pestadomodcod']= 1;
			}
		}
		
		if (isset ($ArregloDatos['archivocod']))
		{
			if ($ArregloDatos['archivocod']!="")
			{	
				$sparam['parchivocod']= $ArregloDatos['archivocod'];
				$sparam['pestadoarchcod']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		
		$spnombre="sel_modulos_archivos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Archivo-Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar ($ArregloDatos)
	{
		
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$sparam+=array("parchivocod"=>$ArregloDatos['archivocod']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		
		$spnombre="ins_modulos_archivos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Archivo-Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function Eliminar ($ArregloDatos)
	{
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$sparam +=array("parchivocod"=>$ArregloDatos['archivocod']);
		$spnombre="del_modulos_archivos_xgrupocod_xarchivocod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el Archivo-Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}

?>