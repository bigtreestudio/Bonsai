<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de modulos
abstract class cModulosdb
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
		$sparam=array('pestadocod' =>0);
		$sparam+=array('pestadodesc' =>0);
		$sparam+=array('pestadotext' =>0);
		$sparam+=array('pestadoarchcod' =>0);
		$sparam+=array('pestadosec' =>0);
		$sparam+=array('pestadomostrar' =>0);
		
	
		$sparam+=array('pmodulocod' =>"");
		$sparam+=array('pmodulodesc' =>"");
		$sparam+=array('pmodulotextomenu' =>"");
		$sparam+=array('parchivocod' =>"");
		$sparam+=array('pmodulosec' =>"");
		$sparam+=array('pmodulomostrar' =>"");

		$sparam+=array('porderby' =>"modulocod");

		if (isset ($ArregloDatos['modulocod']))
		{
			if ($ArregloDatos['modulocod']!="")
			{	
				$sparam['pmodulocod']= $ArregloDatos['modulocod'];
				$sparam['pestadocod']= 1;
			}
		}
		
		if (isset ($ArregloDatos['modulodesc']))
		{
			if ($ArregloDatos['modulodesc']!="")
			{	
				$sparam['pmodulodesc']= $ArregloDatos['modulodesc'];
				$sparam['pestadodesc']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['modulotextomenu']))
		{
			if ($ArregloDatos['modulotextomenu']!="")
			{	
				$sparam['pmodulotextomenu']= $ArregloDatos['modulotextomenu'];
				$sparam['pestadotext']= 1;
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
		
		if (isset ($ArregloDatos['modulosec']))
		{
			if ($ArregloDatos['modulosec']!="")
			{	
				$sparam['pmodulosec']= $ArregloDatos['modulosec'];
				$sparam['pestadosec']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['modulomostrar']))
		{
			if ($ArregloDatos['modulomostrar']!="")
			{	
				$sparam['pmodulomostrar']= $ArregloDatos['modulomostrar'];
				$sparam['pestadomostrar']= 1;
			}
		}	
		
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		
		$spnombre="sel_modulos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar ($ArregloDatos, &$codigoinsertado)
	{
		
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$sparam+=array("pmodulodesc"=>$ArregloDatos['modulodesc']);
		$sparam+=array("pmodulotextomenu"=>$ArregloDatos['modulotextomenu']);
		$sparam+=array("parchivocod"=>$ArregloDatos['archivocod']);
		$sparam+=array("pmodulosec"=>$ArregloDatos['modulosec']);
		$sparam+=array("pmodulomostrar"=>$ArregloDatos['modulomostrar']);
		$sparam+=array("pmoduloimg"=>$ArregloDatos['moduloimg']);
		$sparam+=array("pmodulodash"=>$ArregloDatos['modulodash']);
		$sparam+=array("pmoduloacciones"=>$ArregloDatos['moduloacciones']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		

		
		$spnombre="ins_modulos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}



	protected function Modificar ($ArregloDatos)
	{
		
		$sparam =array("pmodulocodnuevo"=>$ArregloDatos['modulocodnuevo']);
		$sparam+=array("pmodulocodviejo"=>$ArregloDatos['modulocod']);
		$sparam+=array("pmodulodesc"=>$ArregloDatos['modulodesc']);
		$sparam+=array("pmodulotextomenu"=>$ArregloDatos['modulotextomenu']);
		$sparam+=array("parchivocod"=>$ArregloDatos['archivocod']);
		$sparam+=array("pmodulosec"=>$ArregloDatos['modulosec']);
		$sparam+=array("pmodulomostrar"=>$ArregloDatos['modulomostrar']);
		$sparam+=array("pmoduloimg"=>$ArregloDatos['moduloimg']);
		$sparam+=array("pmodulodash"=>$ArregloDatos['modulodash']);		
		$sparam+=array("pmoduloacciones"=>$ArregloDatos['moduloacciones']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));

		$spnombre="upd_modulos_xmodulocod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	protected function Eliminar ($ArregloDatos)
	{
		
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$spnombre="del_modulos_xmodulocod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Eliminar el Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}

?>