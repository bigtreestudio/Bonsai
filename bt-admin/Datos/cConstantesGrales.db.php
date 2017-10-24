<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de las constantes generales
abstract class cConstantesGralesdb
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
		
		$spnombre="sel_constantes_grales";
		$sparam=array(
			'pestadocod'=> 0,
			'pconstantecod'=> "",
			'pestadotipo'=> 0,
			'pconstantetipo'=> "",
			'pestadonom'=> 0,
			'pconstantenom'=> "",
			'pestadosis'=> 0,
			'psistemanom'=> "",
			'pestadodesc'=> 0,
			'pconstantedesc'=> "",
			'porderby'=> "constantecod"
			);

		if (isset ($ArregloDatos['constantecod']))
		{
			if ($ArregloDatos['constantecod']!="")
			{	
				$sparam['pconstantecod']= $ArregloDatos['constantecod'];
				$sparam['pestadocod']= 1;
			}
		}
		
		if (isset ($ArregloDatos['constantetipo']))
		{
			if ($ArregloDatos['constantetipo']!="")
			{	
				$sparam['pconstantetipo']= $ArregloDatos['constantetipo'];
				$sparam['pestadotipo']= 1;
			}
		}	
		if (isset ($ArregloDatos['constantenom']))
		{
			if ($ArregloDatos['constantenom']!="")
			{	
				$sparam['pconstantenom']= $ArregloDatos['constantenom'];
				$sparam['pestadonom']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['sistemanom']))
		{
			if ($ArregloDatos['sistemanom']!="")
			{	
				$sparam['psistemanom']= $ArregloDatos['sistemanom'];
				$sparam['pestadosis']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['constantedesc']))
		{
			if ($ArregloDatos['constantedesc']!="")
			{	
				$sparam['pconstantedesc']= $ArregloDatos['constantedesc'];
				$sparam['pestadodesc']= 1;
			}
		}	

		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar Constante Generales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	function Insertar ($ArregloDatos, &$codigoinsertado)
	{
		
		$spnombre="ins_constantes_grales";
		$sparam=array(
			'psistemanom'=> $ArregloDatos['sistemanom'],
			'pconstantetipo'=> $ArregloDatos['constantetipo'],
			'pconstantecod'=> $ArregloDatos['constantecod'],
			'pconstantenom'=> $ArregloDatos['constantenom'],
			'pconstantedesc'=> $ArregloDatos['constantedesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar Constante Generales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}


	function Modificar ($ArregloDatos)
	{
		$spnombre="upd_constantes_grales_xconstantetipo_constantecod";
		$sparam=array(
			'psistemanom'=> $ArregloDatos['sistemanom'],
			'pconstantenom'=> $ArregloDatos['constantenom'],
			'pconstantedesc'=> $ArregloDatos['constantedesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pconstantetipomod'=> $ArregloDatos['constantetipomod'],
			'pconstantecodmod'=> $ArregloDatos['constantecodmod'],
			'pconstantetipo'=> $ArregloDatos['constantetipo'],
			'pconstantecod'=> $ArregloDatos['constantecod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar Constante Generales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	function Eliminar ($ArregloDatos)
	{
		$spnombre="del_constantes_grales_xconstantetipo_constantecod";
		$sparam=array(
			'pconstantetipo'=> $ArregloDatos['constantetipo'] ,
			'pconstantecod'=> $ArregloDatos['constantecod'] 
			);
		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Eliminar Constante Generales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

		
		
}//FIN CLASE

?>