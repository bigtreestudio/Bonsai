<?php  
abstract class cLinksdb
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
protected function BuscarxLinkCod($sparam,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_lin_links_xlinkcod";
		$sparam=array(
			'plinkcod'=> $sparam['linkcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los links. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
	


//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------


protected function BuscarLinkxCategoria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_lin_links_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los links. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
//----------------------------------------------------------------------------------------- 


// Retorna:
//		los link que corresponden a esa categoria. 
	protected function BuscarAvanzadaxLink($datos,&$resultado,&$numfilas)
	{
			$spnombre="sel_lin_links_busqueda_xcatcod";
			$sparam=array(
				'pcatcod'=> $datos['catcod'],
				'porderby'=> $datos['orderby'],
				'plimit'=> $datos['limit']
				);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los links. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
	
	protected function BuscarLinkUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_lin_links_max_xorden";
		$sparam=array(
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_lin_links";
		$sparam=array(
			'plinkcod'=> $datos['linkcod'],
			'pcatcod'=> $datos['catcod'],
			'plinktitulo'=> $datos['linktitulo'],
			'plinkdesclarga'=> $datos['linkdesclarga'],
			'plinklink'=> $datos['linklink'],
			'plinktarget'=> $datos['linktarget'],
			'plinkarchubic'=> $datos['linkarchubic'],
			'plinkarchnombre'=> $datos['linkarchnombre'],
			'plinkarchsize'=> $datos['linkarchsize'],
			'plinkestado'=> $datos['linkestado'],
			'plinkorden'=> $datos['linkorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{
		$spnombre="upd_lin_links_xlinkcod";
		$sparam=array(
			'plinktitulo'=> $datos['linktitulo'],
			'plinkdesclarga'=> $datos['linkdesclarga'],
			'plinklink'=> $datos['linklink'],
			'plinktarget'=> $datos['linktarget'],
			'plinkarchubic'=> $datos['linkarchubic'],
			'plinkarchnombre'=> $datos['linkarchnombre'],
			'plinkarchsize'=> $datos['linkarchsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'plinkcod'=> $datos['linkcod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstadoLink($datos)
	{
		$spnombre="upd_lin_links_xlinkestado";
		$sparam=array(
			'plinkestado'=> $datos['linkestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'plinkcod'=> $datos['linkcod']
			);	

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado del link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	

	protected function EliminarLink($datos)
	{

		$spnombre="del_lin_links_xlinkcod";
		$sparam=array(
			'plinkcod'=> $datos['linkcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


//ordenar lin_links.php
	protected function ModificarOrden($datos)
	{
			$spnombre="upd_lin_links_orden_xlinkcod";
			$sparam=array(
				'plinkorden'=> $datos['linkorden'],
				'pultmodfecha'=> date("Y/m/d H:i:s"),
				'pultmodusuario'=> $_SESSION['usuariocod'],
				'plinkcod'=> $datos['linkcod']
				);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden del link. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}

//funciones de LIN_CATEGORIAS
//arma el listado de las categorias
	protected function BuscarListadoxCategoria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_lin_link_categorias_listado_xcatcod";
		$sparam=array(
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener las categorias. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
	
		protected function BuscarCategoriaxCatcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_lin_link_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
		);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la categorias. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}

//orden categorias lin_categorias.php

	protected function ModificarOrdenCategoria($datos)
	{
		$spnombre="upd_lin_link_categorias_orden_xcatcod";
		$sparam=array(
			'pcatorden'=> $datos['catorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
		
	protected function ModificarEstadoCategoria($datos)
	{
		$spnombre="upd_lin_link_categorias_estado_xcatcod";
		$sparam=array(
			'pcatestado'=> $datos['catestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	protected function EliminarCategoria($datos)
	{
		$spnombre="del_lin_link_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	protected function InsertarCategorias($datos,&$codigoinsertado)
	{

		$spnombre="ins_lin_link_categorias";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pcatnom'=> $datos['catnom'],
			'pcatdesc'=> $datos['catdesc'],
			'pcatsuperior'=> $datos['catsuperior'],
			'pcatorden'=> $datos['catorden'],
			'pcatestado'=> $datos['catestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
			
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	protected function ModificarCategoria($datos)
	{
		$spnombre="upd_lin_link_categorias_xcatcod";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pcatdesc'=> $datos['catdesc'],
			'pcatsuperior'=> $datos['catsuperior'],
			'pcatorden'=> $datos['catorden'],
			'pcatestado'=> $datos['catestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatcod'=> $datos['catcod']
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

}


?>