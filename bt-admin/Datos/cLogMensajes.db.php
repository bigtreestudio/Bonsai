<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a bd de log mensajes


abstract class cLogMensajesdb
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



	function Buscar ($ArregloDatos,&$numfilas,&$resultado)
	{
		$sparam=array('pestadocod' =>0);
		$sparam+=array('pestadodesc' =>0);
		$sparam+=array('pestadonivel' =>0);
		$sparam+=array('pcodigo_mensaje' =>"");
		$sparam+=array('pdescripcion' =>"");
		$sparam+=array('pnivel' =>"");

		$sparam+=array('porderby' =>"codigo_mensaje");

		if (isset ($ArregloDatos['codigo_mensaje']))
		{
			if ($ArregloDatos['codigo_mensaje']!="")
			{	
				$sparam['pcodigo_mensaje']= $ArregloDatos['codigo_mensaje'];
				$sparam['pestadocod']= 1;
			}
		}
		
		if (isset ($ArregloDatos['descripcion']))
		{
			if ($ArregloDatos['descripcion']!="")
			{	
				$sparam['pdescripcion']= $ArregloDatos['descripcion'];
				$sparam['pestadodesc']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['nivel']))
		{
			if ($ArregloDatos['nivel']!="")
			{	
				$sparam['pnivel']= $ArregloDatos['nivel'];
				$sparam['pestadonivel']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		
		$spnombre="sel_log_mensajes";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Log Mensaje.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	function Insertar ($ArregloDatos, &$codigoinsertado)
	{

		$spnombre="ins_log_mensajes";
		$sparam=array(
			'pcodigo_mensaje'=> $ArregloDatos['codigo_mensaje'],
			'pdescripcion'=> $ArregloDatos['descripcion'],
			'pnivel'=> $ArregloDatos['nivel'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Log Mensaje.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}


	function Modificar ($ArregloDatos)
	{
		$spnombre="upd_log_mensajes_xcodigo_mensaje";
		$sparam=array(
			'pdescripcion'=> $ArregloDatos['descripcion'],
			'pnivel'=> $ArregloDatos['nivel'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcodigo_mensaje'=> $ArregloDatos['codigo_mensaje']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el Log Mensaje.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	

	function Eliminar ($ArregloDatos)
	{
		$spnombre="del_log_mensajes_xcodigo_mensaje";
		$sparam=array(
			'pcodigo_mensaje'=>$ArregloDatos['codigo_mensaje'] 
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Eliminar el Log Mensaje.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}




		
		
}//FIN CLASE

?>