<?php  
abstract class cTapasTiposMetadataCamposdb
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

	protected function BuscarCamposxEstado($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_tipos_metadata_campos_xtapatipometadataestado";
		$sparam=array(
			'ptapatipometadataestado'=> $datos['tapatipometadataestado']
			);	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menu por estado.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un formato multimedia

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menutipocod: Codigo del Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_tipos_metadata_campos_xtapatipometadatacod";
		$sparam=array(
			'ptapatipometadatacod'=> $datos['tapatipometadatacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscarel tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un formato multimedia

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_tipos_metadata_campos_busqueda";
		$sparam=array(
			'pxtapatipometadatacampo'=> $datos['xtapatipometadatacampo'],
			'ptapatipometadatacampo'=> $datos['tapatipometadatacampo'],
			'pxtapatipometadatacte'=> $datos['xtapatipometadatacte'],
			'ptapatipometadatacte'=> $datos['tapatipometadatacte'],
			'pxtapatipometadataestado'=> $datos['xtapatipometadataestado'],
			'ptapatipometadataestado'=> $datos['tapatipometadataestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			fomrnatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_tap_tapas_tipos_metadata_campos";
		$sparam=array(
			'ptapatipometadatacampo'=> $datos['tapatipometadatacampo'],
			'ptapatipometadataestado'=> $datos['tapatipometadataestado'],
			'ptapatipometadatacte'=> strtoupper($datos['tapatipometadatacte']),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			fomrnatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_tap_tapas_tipos_metadata_campos_xtapatipometadatacod";
		$sparam=array(
			'ptapatipometadatacampo'=> $datos['tapatipometadatacampo'],
			'ptapatipometadatacte'=> strtoupper($datos['tapatipometadatacte']),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptapatipometadatacod'=> $datos['tapatipometadatacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_tap_tapas_tipos_metadata_campos_xtapatipometadatacod";
		$sparam=array(
			'ptapatipometadatacod'=> $datos['tapatipometadatacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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
	protected function ActivarDesactivar ($datos)
	{
		$spnombre="upd_tap_tapas_tipos_metadata_campos_estado_xtapatipometadatacod";
		$sparam=array(
			'ptapatipometadataestado'=> $datos['tapatipometadataestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptapatipometadatacod'=> $datos['tapatipometadatacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar/desactivar el tipo de metadata campo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}

}
?>