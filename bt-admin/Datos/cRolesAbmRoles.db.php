<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con el acceso a base de datos para el manejo de los abm de roles
abstract class cRolesAbmRolesdb
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
		$sparam=array('pestadoactualizado' =>0);
		$sparam+=array('pestadoactualiza' =>0);
	
		$sparam+=array('prolcodactualizado' =>"");
		$sparam+=array('prolcodactualiza' =>"");

		$sparam+=array('porderby' =>"rolcodactualizado");

		if (isset ($ArregloDatos['rolcodactualizado']))
		{
			if ($ArregloDatos['rolcodactualizado']!="")
			{	
				$sparam['prolcodactualizado']= $ArregloDatos['rolcodactualizado'];
				$sparam['pestadoactualizado']= 1;
			}
		}
		
		if (isset ($ArregloDatos['rolcodactualiza']))
		{
			if ($ArregloDatos['rolcodactualiza']!="")
			{	
				$sparam['prolcodactualiza']= $ArregloDatos['rolcodactualiza'];
				$sparam['pestadoactualiza']= 1;
			}
		}	
		
		if (isset ($ArregloDatos['orderby']))
		{
			if ($ArregloDatos['orderby']!="")
				$sparam['porderby']= $ArregloDatos['orderby'];
		}	
		
		$spnombre="sel_roles_abm_roles";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el Roles-ABM-Roles.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		

		return true;
	}



	protected function Insertar ($ArregloDatos)
	{
		
		$sparam =array("prolcodactualiza"=>$ArregloDatos['rolcodactualiza']);
		$sparam+=array("prolcodactualizado"=>$ArregloDatos['rolcodactualizado']);
		$sparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$sparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));
		$spnombre="ins_roles_abm_roles";
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Roles-ABM-Roles.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function Eliminar ($ArregloDatos)
	{
		$sparam =array("prolcodactualiza"=>$ArregloDatos['rolcodactualiza']);
		$sparam +=array("prolcodactualizado"=>$ArregloDatos['rolcodactualizado']);
		$spnombre="del_roles_abm_roles_xrolcodactualiza_rolcodactualizado";	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el Roles-ABM-Roles.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}

?>