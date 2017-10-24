<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de grupos de modulos
abstract class cGruposModulosdb
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
		$sparam+=array('pestadotext' =>0);
		
		$sparam+=array('pgrupomodcod' =>"");
		$sparam+=array('pgrupomodtextomenu' =>"");
		
		$sparam+=array('porderby' =>"grupomodtextomenu");

		if (isset ($ArregloDatos['grupomodtextomenu']))
		{
			if ($ArregloDatos['grupomodtextomenu']!="")
			{	
				$sparam['pgrupomodtextomenu']= $ArregloDatos['grupomodtextomenu'];
				$sparam['pestadotext']= 1;
			}
		}
		if (isset ($ArregloDatos['grupomodcod']))
		{
			if ($ArregloDatos['grupomodcod']!="")
			{	
				$sparam['pgrupomodcod']= $ArregloDatos['grupomodcod'];
				$sparam['pestadocod']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		$spnombre="sel_gruposmod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Grupo Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar ($ArregloDatos,&$codigoinsertado)
	{
		$sparam =array("pgrupomodtextomenu"=>$ArregloDatos['grupomodtextomenu']);
		$sparam+=array("pgrupomodsec"=>$ArregloDatos['grupomodsec']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		$spnombre="ins_gruposmod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Grupos Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}

	protected function Modificar ($ArregloDatos)
	{
		$sparam =array("pgrupomodcod"=>$ArregloDatos['grupomodcod']);
		$sparam+=array("pgrupomodtextomenu"=>$ArregloDatos['grupomodtextomenu']);
		$sparam+=array("pgrupomodsec"=>$ArregloDatos['grupomodsec']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		$spnombre="upd_gruposmod_xgrupomodcod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el Grupos Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function Eliminar ($ArregloDatos)
	{
		$sparam =array("pgrupomodcod"=>$ArregloDatos['grupomodcod']);
		$spnombre="del_gruposmod_xgrupomodcod";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Eliminar el Grupo Módulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}

?>