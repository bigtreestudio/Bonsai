<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de grupos de modulos y modulos
abstract class cGruposmodModulosdb
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
		$sparam=array('pestadogrupocod' =>0);
		$sparam+=array('pestadomodcod' =>0);
	
		$sparam+=array('pgrupomodcod' =>"");
		$sparam+=array('pmodulocod' =>"");

		$sparam+=array('porderby' =>"grupomodcod");

		
		if (isset ($ArregloDatos['grupomodcod']))
		{
			if ($ArregloDatos['grupomodcod']!="")
			{	
				$sparam['pgrupomodcod']= $ArregloDatos['grupomodcod'];
				$sparam['pestadogrupocod']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['modulocod']))
		{
			if ($ArregloDatos['modulocod']!="")
			{	
				$sparam['pmodulocod']= $ArregloDatos['modulocod'];
				$sparam['pestadomodcod']= 1;
			}
		}
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		
		$spnombre="sel_grupomod_modulos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Grupo Modulo - Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar ($ArregloDatos)
	{
		
		
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$sparam+=array("pgrupomodcod"=>$ArregloDatos['grupomodcod']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		
		$spnombre="ins_gruposmod_modulos";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Grupomod-Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	protected function Eliminar ($ArregloDatos)
	{
		
		$sparam =array("pmodulocod"=>$ArregloDatos['modulocod']);
		$sparam +=array("pgrupomodcod"=>$ArregloDatos['grupomodcod']);

		$spnombre="del_gruposmod_modulos_xgrupocod_xmodulocod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Eliminar el Grupomod-Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}

?>