<?php 

class FuncionesPHPLocal
{

//----------------------------------------------------------------------------------------- 
// Genera todas las constantes necesarias para el sistema

	static function removeMagicQuotes ($postArray, $trim = false)
	{
	   if (get_magic_quotes_gpc() == 1)
	   {
		   $newArray = array();   
		  
		   foreach ($postArray as $key => $val)
		   {
			   if (is_array($val))
			   {
				   $newArray[$key] = FuncionesPHPLocal::removeMagicQuotes ($val, $trim);
			   }
			   else
			   {
				   if ($trim == true)
				   {
					   $val = trim($val);
				   }
				   $newArray[$key] = stripslashes($val);
			   }
		   }   
		  
		   return $newArray;   
	   }
	   else
	   {
		   return $postArray;   
	   }       
	}


	static function CargarConstantes($conexion,$tipocarga)
	{


		if(isset($tipocarga['roles']))
		{
			// constantes ROL de USUARIOS
			$spparam=array('porderby'=>"rolcod" );
			if(!$conexion->ejecutarStoredProcedure("sel_roles_orden",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar los roles");
			
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
				if($fila['rolcod']!=0)
					if(!defined($fila['rolnom']))
						define ($fila['rolnom'],$fila['rolcod']);
		}

		
		if(isset($tipocarga['sistema']))
		{

			// Constantes Generales 
			$spparam=array('psistemanom'=>$tipocarga['sistema']);

			if(!$conexion->ejecutarStoredProcedure("sel_constantes_grales_xsistemanom",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar las constantes");

			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
				if(!defined($fila['constantenom'])) 
					define ($fila['constantenom'],$fila['constantecod']);
		}

		if(isset($tipocarga['multimedia']))
		{

			// Constantes Generales 
			$spparam=array();

			if(!$conexion->ejecutarStoredProcedure("sel_mul_multimedia_constantes",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar las constantes de multimedia");

			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
				if(!defined($fila['cte'])) 
					define ($fila['cte'],$fila['valor']);
		}

			// Constantes Generales 
		$spparam=array();
		if(!$conexion->ejecutarStoredProcedure("sel_not_noticias_estados_constantes",$spparam,$resultado,$numfilas,$errno))
			die("Error al cargar las constantes de los estados de la noticia");

		while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
			if(!defined($fila['noticiaestadocte'])) 
				define ($fila['noticiaestadocte'],$fila['noticiaestadocod']);

			// Constantes Generales 
		$spparam=array();
		if(!$conexion->ejecutarStoredProcedure("sel_gra_graficos_conjuntos",$spparam,$resultado,$numfilas,$errno))
			die("Error al cargar los conjuntos de los graficos");

		while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
			if(!defined($fila['conjuntocte'])) 
				define ($fila['conjuntocte'],$fila['conjuntocod']);
			
		
		$spparam=array();
			if(!$conexion->ejecutarStoredProcedure("sel_paginas_constantes",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar las constantes de los estados de la pagina");

			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
				if(!defined($fila['pagestadocte'])) 
					define ($fila['pagestadocte'],$fila['pagestadocod']);	
			

		$spparam=array("pagendaagendaestado"=>ACTIVO);
			if(!$conexion->ejecutarStoredProcedure("sel_age_agenda_estados_activos",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar las constantes de los estados de la agenda");


			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
				if(!defined($fila['agendaestadocte'])) 
					define ($fila['agendaestadocte'],$fila['agendaestadocod']);	
			
			
	}
	
//----------------------------------------------------------------------------------------- 
// Usada para el envio automatico de mails, según rol/jurisdiccion

// Retorna true si no hubo problema, 
//		sino retorna false en caso de error al ejecutar el SP para seleccionar los usuarios,
//		por rol inválido o por error al enviar el mail.

	static function MandarMail($conexion,$rolcod,$jurisdcod,$subject,$texto)
	{
		switch($rolcod)
		{
			case ADMISITE:
				$spparam=array('prolcod'=>$rolcod,'pusuarioestado'=>USUARIOACT);		
				$spnombre="sel_mail_usuarios_con_mail_xrol_xmenorestado";
				break;
	
			default:
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Intento de enviar mail a rol inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
		}
		

		if(!isset($spnombre))
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al generar el mail.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
			return false;
	
		while($fila=$conexion->ObtenerSiguienteRegistro($resultado))
			if(!mail($fila['usuarioemail'],$subject,$texto))
			{
				echo "Imposible enviar mail.";
				return false;
			}
		
		return true;	
	}

//----------------------------------------------------------------------------------------- 
// Muestra un mensaje en pantalla y, segun el nivel del mensaje, envía tambien mail a ADMISITE

// Parametros: $formatomensaje es un array asociativo
//					$formatomensaje['formato']=FMT_TEXTO|FMT_TABLA
//					$formatomensaje['cantcols'] usado para colspan cuando es FMT_TABLA
//				$enviarmail: en caso que sea necesario enviar mail y esta variable esta seteada en true, envia mail al administrador
//						definida en la conexion

	static function MostrarMensaje($conexion,$nivelmensaje,$texto,$ubicerror,$formatomensaje,$enviarmail=true)
	{
		//if ($nivelmensaje==MSG_ERRGRAVE)
			//$texto.=" Avise a su Administrador.";
		
		switch($nivelmensaje)
		{
			case MSG_OK:
				if($formatomensaje['formato']==FMT_TEXTO)
					echo '<div class="alert_success"><p><img src="images/icon_accept.png" alt="Ok" />'.$texto.'</p></div>';	
				else
					echo $texto;	
				break;
			case MSG_INF:
			case MSG_ERRGRAVE:
			case MSG_ERRSOSP:
				if($formatomensaje['formato']==FMT_TEXTO)
					echo '<div class="alert_error"><p><img src="images/icon_error.png" alt="Error"/>'.$texto.'</p></div>';	
				else
					echo $texto;	
				break;
		}

		if($enviarmail)
		{
			// envio de mail
			/*
			switch($nivelmensaje)
			{
				case MSG_ERRGRAVE:
					$subject=SISTEMA." - Error de Inconsistencia de Datos ";
					$textomail="Se produjo un error:\n";
					$textomail.="  Ejecutando Archivo: ".$_SERVER['PHP_SELF']."\n";
					$textomail.="  Ubic Archivo: ".$ubicerror['archivo']."\n";
					$textomail.="  Ubic Función: ".($ubicerror['funcion']==""?"-":$ubicerror['funcion'])."\n";
					$textomail.="  Ubic Línea: ".$ubicerror['linea']."\n";
					$textomail.="Texto error:".$texto."\n";
					if(isset($_SESSION['usuariocod']))
						$textomail.="Usuario sesion: ".$_SESSION['usuariocod']."\n";
					echo $textomail;
					break;
				case MSG_ERRSOSP:
					$subject=SISTEMA." - Error Datos Sospechosos ";
					$textomail="Se produjo un error:\n";
					$textomail.="  Ejecutando Archivo: ".$_SERVER['PHP_SELF']."\n";
					$textomail.="  Ubic Archivo: ".$ubicerror['archivo']."\n";
					$textomail.="  Ubic Función: ".($ubicerror['funcion']==""?"-":$ubicerror['funcion'])."\n";
					$textomail.="  Ubic Línea: ".$ubicerror['linea']."\n";
					$textomail.="Texto error:".$texto."\n";
					if(isset($_SESSION['usuariocod']))
						$textomail.="Usuario sesion: ".$_SESSION['usuariocod']."\n";
					echo $textomail;
					break;
			}*/
		}
	}
//----------------------------------------------------------------------------------------- 
// Registra el acceso de un usuario en el sistema

	static function RegistrarAcceso($conexion,$codigo_mensaje,$acciondesc,$usuariocod) 
	{
		/*
		$archivonom=substr(strrchr($_SERVER['PHP_SELF'],'/'),1);
	
		if($usuariocod=='')
			$usuariocod=0;
		
		$spnombrelog='ins_logusuarios';
		$spparamlog=array('pcodigo_mensaje'=>$codigo_mensaje,'pacciondesc'=>$acciondesc,'pnumeroip'=>$_SERVER['REMOTE_ADDR'],'parchivonom'=>$archivonom,'pfecha'=>date('Y/m/d H:i:s'),'pusuariocod'=>$usuariocod);
	
		if(!$conexion->ejecutarStoredProcedure($spnombrelog,$spparamlog,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			$textoerror="Error registrar_acceso ".$errno." - ".$conexion->TextoError();
			// MANDAR MAIL con los parámetros de la llamada errónea
				
			$subject=SISTEMA." - Error en registrar acceso";
			$texto="Se ha producido un error en ".$_SERVER['PHP_SELF']." \n\n";
			if($usuariocod!=0)
			{
				if(!$conexion->TraerCampo("usuarios","concat(usuarionombre,' ',usuarioapellido)",array("usuariocod='",$usuariocod,"'" ),$nombreusuario,$numfilas,$errno))
					$texto.="Usuario ".$nombreusuario."\n\n";
			}
			$texto.="parámetros de acceso \n";
			$texto.="pcodigo_mensaje ".$codigo_mensaje."\n";
			$texto.="pacciondesc ".$acciondesc."\n";
			$texto.="pnumeroip ".$_SERVER['REMOTE_ADDR']."\n";
			$texto.="parchivonom ".$archivonom."\n";
			$texto.="pfecha ".date('Y/m/d H:i:s')."\n";
			$texto.="pusuariocod ".$usuariocod;
			FuncionesPHPLocal::MandarMail($conexion,$conexion->VerAdmiGeneral(),"",$subject,$texto);
			return false;
		}*/
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Arma un combo que recarga la página según la opción seleccionada

// Parametros:
// 		->spnombre y spparam se usan para armar el query
// 		->nomformulario es el nombre del formulario sobre el que está el combo
// 		->nomcombo es el nombre del combo
// 		->clavecombo y desccombo son campos del query con los que arma el combo
// 		->querystring lo coloca despues del nombre del php
// 		->textolineauno es el texto del primer option del combo, cuando no hay nada seleccionado
// 		->regactual, en caso que exista una variables GET con el nombre del combo y coincide con algun registro del query, retorna la fila
// 		->seleccionado, en caso que se haya seleccionado un option del combo

	static function ArmarCombo_BD($conexion,$spnombre,$spparam,$nomformulario,$nomcombo,$clavecombo,$desccombo,$querystring,$textolineauno,&$regactual,&$seleccionado)
	{
		// Cargo los registros del query seleccionado
		if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$num_filas,$errno))
			return false;

		// Genera codigo JavaScript para autollamarse cuando selecciona un elemento del combo
		echo "<script type='text/javascript'>\n";
		echo "<!--\n";
		
		echo "function ".$nomcombo."_newpage() {\n";
	
		$temp="";
		if($querystring!='')
			$temp="?";
	
		echo "if(document.".$nomformulario.".".$nomcombo.".selectedIndex==0)\n";
		echo "    location='".$_SERVER['PHP_SELF'].$temp.$querystring."';\n";
		echo "else\n";
	
		if($querystring!='')
			$querystring.="&";
	
		echo "    location='".$_SERVER['PHP_SELF']."?".$querystring.$nomcombo."='+document.".$nomformulario.".".$nomcombo."[document.".$nomformulario.".".$nomcombo.".selectedIndex].value;\n";
		echo "}\n";
		
		echo "//-->\n";
		echo "</script>\n";
	
		// Generación del combo, poniendo como primera linea $textolineauno
		echo "<select name='".$nomcombo."' id='".$nomcombo."' size='1' onchange='".$nomcombo."_newpage();' class='textoinput' style='width: 370px'>\n";
		echo "<option value=''>". FuncionesPHPLocal::HtmlspecialcharsBigtree($textolineauno,ENT_QUOTES)."</option>\n";
		$seleccionado=false;
		if($num_filas>0) 
		{
			// Recorre el cursor desde el principio, poniendo cada fila en una linea del combo
			$textopopup='';
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
			{ 
				if(isset($_GET[$nomcombo]) && $fila[$clavecombo]==$_GET[$nomcombo]) 
				{
					echo "<option selected value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
					$seleccionado=true;
					$regactual=$fila;
					$textopopup=$fila[$desccombo];
				}
				else
					echo "<option value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
			}
		}
	
		echo("</select>\n");
		if(isset ($textopopup))
			echo "<br /><a href='javascript:void(0)' onclick=\"javascript:popup('ventanatexto.php?textopopup='+".$nomformulario.".".$clavecombo."[".$nomformulario.".".$clavecombo.".selectedIndex].text,'ventanatexto',330,80,screen.availWidth-350,screen.availHeight-140,'yes')\" class='linkfondoblanco'><span class='textoaclaraciones'>Ver texto completo</span></a>\n";
		else
			echo "<div style='font-size: 7px;'>&nbsp;</div>\n";
	}

//----------------------------------------------------------------------------------------- 
// Arma un combo con la informacion del SP enviado

// Parametros:
// 		->spnombre y spparam se usan para armar el query
// 		->nomformulario es el nombre del formulario sobre el que está el combo
// 		->nomcombo es el nombre del combo
// 		->clavecombo y desccombo son campos del query con los que arma el combo
// 		->claveselec es el registro que hay que seleccionar
// 		->textolineauno es el texto del primer option del combo, cuando no hay nada seleccionado
// 		->regactual, en caso que se haya seleccionado un option se retorna la fila completa
// 		->seleccionado, en caso que se haya seleccionado un option del combo
//		->$filas, si es 1, es un combo, sino es una lista
//		->$onchange: la accion javascript al momento de seleccionar un elemento de la lista
//		->$style: estilo a aplicar sobre el combo
//		->$multiple: si se permite que en una lista se puedan elegir mas de un item
//		->$vertextocompleto: indica si se muestra el link abajo para ver texto completo

	static function ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,$nomformulario,$nombrecombo,$clavecombo,$desccombo,$claveselec,$textolineauno,&$regactual,&$seleccionado,$filas=1,$onchange="",$style="width: 370px",$multiple=false,$vertextocompleto=true,$onDbClick="",$disabled=false,$class="",$tabindex="")
	{
		// Cargo los registros del query seleccionado
		if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$num_filas,$errno))
			return false;
		$txtdisabled = "";
		if ($disabled)
			$txtdisabled = "disabled='disabled'";

		$tabindextxt = "";
		if ($tabindex!="")
			$tabindextxt = " tabindex='".$tabindex."'";

		// Generación del combo, poniendo como primera linea $textolineauno
		echo "<select name='".$nombrecombo."' id='".$nombrecombo."' size='".$filas."' class='textoinput ".$class."' style='".$style."' onchange='".$onchange."'  ondblclick='".$onDbClick."' ".($multiple?"multiple ='multiple'":"")." ".$txtdisabled.$tabindextxt."  >\n";
		if($filas==1)
			echo "<option value=''>". FuncionesPHPLocal::HtmlspecialcharsBigtree($textolineauno,ENT_QUOTES)."</option>\n";

		$seleccionado=false;
		if($num_filas>0) 
		{
			// Recorre el cursor desde el principio, poniendo cada fila en una linea del combo
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
			{ 
				if($fila[$clavecombo]==$claveselec) 
				{
					echo "<option selected=\"selected\" value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
					$seleccionado=true;
					$regactual=$fila;
				}
				else
					echo "<option value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
			}
		}
	
		echo("</select>\n");
		/*
		if($filas==1 && $vertextocompleto)
			echo "<br /><a href='javascript:void(0)' onclick=\"javascript:popup('ventanatexto.php?textopopup='+".$nomformulario.".".$nombrecombo."[".$nomformulario.".".$nombrecombo.".selectedIndex].text,'ventanatexto',330,80,screen.availWidth-350,screen.availHeight-140,'yes')\" class='linkfondoblanco'><span class='textoaclaraciones'>Ver texto completo</span></a>\n";
		*/	
	}
	
	
	
	
//----------------------------------------------------------------------------------------- 
// Arma un combo en base a la informacion proveniente en un array. Con salto.

// Parametros:
// 		->valores_combo: array asociativo, del que salen los valores para el combo
//			valores_combo=array(1=>array($clavecombo=>"valor",$desccombo=>"valor"),2=>array($clavecombo=>"valor",$desccombo=>"valor"))
// 		->nomformulario es el nombre del formulario sobre el que está el combo
// 		->nomcombo es el nombre del combo
// 		->clavecombo y desccombo son campos del array con los que arma el combo
//		->querystring es el string que se agrega a la linea
// 		->textolineauno es el texto del primer option del combo, cuando no hay nada seleccionado
// 		->regactual, en caso que se haya seleccionado un option se retorna la fila completa
// 		->seleccionado, en caso que se haya seleccionado un option del combo

	static function ArmarCombo_deArray($valores_combo,$nomformulario,$nomcombo,$clavecombo,$desccombo,$querystring,$textolineauno,&$regactual,&$seleccionado,$filas=1,$style="width: 370px",$multiple=false,$vertextocompleto=true) 
	{
		$seleccionado=false;
		$regactual=array();
		
		// Genera codigo JavaScript para autollamarse cuando selecciona un elemento del combo
		echo "<script  type='text/javascript'>\n";
		echo "<!--\n";
		
		echo "static function ".$nomcombo."_newpage() {\n";
	
		$temp="";
		if($querystring!='')
			$temp="?";
	
		echo "if(document.".$nomformulario.".".$nomcombo.".selectedIndex==0)\n";
		echo "    location='".$_SERVER['PHP_SELF'].$temp.$querystring."';\n";
		echo "else\n";
	
		if($querystring!='')
			$querystring.="&";
	
		echo "    location='".$_SERVER['PHP_SELF']."?".$querystring.$nomcombo."='+document.".$nomformulario.".".$nomcombo."[document.".$nomformulario.".".$nomcombo.".selectedIndex].value;\n";
		echo "}\n";
		
		echo "//-->\n";
		echo "</script>\n";
	
		// Generación del combo, poniendo como primera linea $textolineauno
		echo "<select name='".$nomcombo."' id='".$nomcombo."' size='1' style='".$style."' ".($multiple?"multiple ='multiple'":"")."  onchange='".$nomcombo."_newpage();' class='textotabla' >";
		echo "<option value=''>". FuncionesPHPLocal::HtmlspecialcharsBigtree($textolineauno,ENT_QUOTES)."</option>";
	
		foreach($valores_combo as $optioncombo)
		{
			// Recorre el array, poniendo cada fila en una linea del combo
			echo "<option ";
	
			if(isset($_GET[$nomcombo]) && $optioncombo[$clavecombo]==$_GET[$nomcombo]) 
			{
				echo " selected ";
				$seleccionado=true;
				$regactual=$optioncombo;
			}
			
			echo " value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($optioncombo[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($optioncombo[$desccombo],ENT_QUOTES);
			echo "</option>";
		}
	
		echo("</select>");
		if ($vertextocompleto)
			echo "<br /><a href='javascript:void(0)' onclick=\"javascript:popup('ventanatexto.php?textopopup='+formulario.".$nomcombo."[formulario.".$nomcombo.".selectedIndex].text,'ventanatexto',330,80,screen.availWidth-350,screen.availHeight-140,'yes')\" class='linkfondoblanco'><span class='textoaclaraciones'>Ver texto completo</span></a>";
	}
	
//----------------------------------------------------------------------------------------- 
// Arma un combo en base a la informacion proveniente en un array. Con salto.

// Parametros:
// 		->valores_combo: array asociativo, del que salen los valores para el combo
//			valores_combo=array(1=>array($clavecombo=>"valor",$desccombo=>"valor"),2=>array($clavecombo=>"valor",$desccombo=>"valor"))
// 		->nomformulario es el nombre del formulario sobre el que está el combo
// 		->nomcombo es el nombre del combo
// 		->clavecombo y desccombo son campos del array con los que arma el combo
//		->querystring es el string que se agrega a la linea
// 		->textolineauno es el texto del primer option del combo, cuando no hay nada seleccionado
// 		->regactual, en caso que se haya seleccionado un option se retorna la fila completa
// 		->seleccionado, en caso que se haya seleccionado un option del combo

	static function ArmarCombo_SinSalto_deArray($valores_combo,$nomformulario,$nomcombo,$clavecombo,$desccombo,$querystring,$textolineauno,&$regactual,&$seleccionado,$mostrar_txt_completo=true,$claveselec="",$style="width: 370px") 
	{
		$seleccionado=false;
		$regactual=array();
			
		// Generación del combo, poniendo como primera linea $textolineauno
		echo "<select name='".$nomcombo."' id='".$nomcombo."' size=1 class='textotabla' style='".$style."'>";
		echo "<option value=''>". FuncionesPHPLocal::HtmlspecialcharsBigtree($textolineauno,ENT_QUOTES)."</option>";
	
		foreach($valores_combo as $optioncombo)
		{
			// Recorre el array, poniendo cada fila en una linea del combo
			echo "<option ";
	
	
			if($optioncombo[$clavecombo]==$claveselec) 
			{	
				echo " selected ";
				$seleccionado=true;
				$regactual=$optioncombo;
			}
			/*
			if(isset($_GET[$nomcombo]) && $optioncombo[$clavecombo]==$_GET[$nomcombo]) 
			{
				echo " selected ";
				$seleccionado=true;
				$regactual=$optioncombo;
			}*/
			
			echo " value='". FuncionesPHPLocal::HtmlspecialcharsBigtree($optioncombo[$clavecombo],ENT_QUOTES)."'> ". FuncionesPHPLocal::HtmlspecialcharsBigtree($optioncombo[$desccombo],ENT_QUOTES);
			echo "</option>";
		}
	
		echo("</select>");
		if ($mostrar_txt_completo)
			echo "<br /><a href='javascript:void(0)' onclick=\"javascript:popup('ventanatexto.php?textopopup='+formulario.".$nomcombo."[formulario.".$nomcombo.".selectedIndex].text,'ventanatexto',330,80,screen.availWidth-350,screen.availHeight-140,'yes')\" class='linkfondoblanco'><span class='textoaclaraciones'>Ver texto completo</span></a>";
	}	
		
	
//----------------------------------------------------------------------------------------- 
// Si existe la variable en el QUERY_STRING, elimina todo desde ahi en adelante

	static function ArmarQueryString($variable)
	{
		// si existe en el get la variable $variable, la elimino
		if(isset($_GET[$variable]))
		{
			$posicion=strpos($_SERVER['QUERY_STRING'],$variable);
			if($posicion===false)
				return $_SERVER['QUERY_STRING'];
			elseif($posicion==0)
				return "";
			else	
				return substr($_SERVER['QUERY_STRING'],0,$posicion-1);
		}
		else
			return $_SERVER['QUERY_STRING'];
	}

//----------------------------------------------------------------------------------------- 
// Valida el contenido del campo según tipovalidacion

	static function ValidarContenido($conexion,$campo,$tipovalidacion)
	{
		switch($tipovalidacion)
		{
			case "AlfanumericoPuro": // campo alfanumerico sin caracteres especiales
				if ( strspn(strtoupper($campo), '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ) != strlen($campo) ) 
					return false;
				break;
			case "Email": // campo alfanumerico con algunos caracteres especiales (emails)
				if(!preg_match("/^[-\._a-z0-9]+@([a-z0-9]+[-\.]{1}){1,2}[a-z]{2,4}([\.]{1}[a-z]{2}){0,1}$/",$campo))
					return false;
				break;
			case "FechaDDMMAAAA": // valida fecha en formato DD/MM/AAAA
				if(!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",$campo))
					return false;	
				list($dia, $mes, $anio) = explode ('/', $campo); 
				if(!checkdate($mes, $dia, $anio))
					return false;
				break;
			case "Periodo": // campo MM/AAAA
				if(!preg_match("/^[0-9]{2}\/[0-9]{4}$/",$campo))
					return false;
			
				$anio = substr($campo,3,4);
				$mes = substr($campo,0,2);
				if(($anio < 2000) || ($anio > 2050) || ($mes < 1) || ($mes > 12))
				   return false;
					
				break;	
			case "Hexa6Digitos": // color semáforo
				if(!preg_match("/^[0-9A-F]{6}$/",$campo))
					return false;
				break;
			case "Jurisdiccion": // campo numerico con puntos (sólo para Jurisdicciones)
				if(!preg_match("/^[0-9]{2}\.([0-9]{3}\.([0-9a-zA-Z ]{2}\.){0,7}){0,1}$/",$campo))
					return false;
				break;
			case "NumericoEntero": // campo numerico entero
				if ( strspn($campo, '0123456789' ) != strlen($campo) ) 
					return false;
				break;
			case "Numerico2Decimales": // campo numerico con 2 decimales maximo
				if(!preg_match("/^[0-9]+([\.][0-9]{1,2}){0,1}$/",$campo))
					return false;
				break;
			case "URL": // campo numerico con 2 decimales maximo
				if (!preg_match('/^[a-z\d_-]{1,200}$/i', $campo)) 
					return false;
				break;
			case "CUIT": // 11 posiciones numericas con digito mod.11
				if ( strspn($campo, '0' ) == strlen($campo) ) 
					return false;

				if(!preg_match("/^[0-9]{11}$/",$campo))
					return false;
					
				$array_factor_peso = array(5,4,3,2,7,6,5,4,3,2);
				$suma=0;
				$digito_verificador=0;
 
 				for ($i=0; $i<=9; $i++)  
					$suma=$suma+(substr($campo,$i,1)*$array_factor_peso[$i]); 	
			
				$digito_verificador=11-($suma%11);
				
				if ($digito_verificador == 11)
					$digito_verificador = 0;
			
				if ($digito_verificador!=substr($campo,10,1)) 
					return false; 
				break;
			default:
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Validación no definida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
				break;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Valida la clave

	static function ValidarPassword($clave,$claveactual,$identificacion,$longmin)
	{
		$i = 0;
		$p = 0; // codigo de error que se produjo
		$c3 = 1; // cantidad de letras iguales secuenciales
		$a = 0; // mantiene el codigo ascii al recorrer la clave para las validaciones
		$sa = 1; // cantidad de letras consecutivas segun abecedario
		$sd = 1; // cantidad de letras consecutivas segun abecedario inverso
		$pa = 0; // mantiene la cantidad de caracteres iguales entre la nueva clave y la clave actual
		$ca = 0; // cuenta la cantidad de letras
		$cn = 0; // cuenta la cantidad de numeros
		$ant = 0; // mantiene un registro de la letra anterior
		$layout = array(); // array que contiene secuencia de caracteres comunes
	
		$layout[] = "QWERTY";
		$layout[] = "YTREWQ";
		$layout[] = "ASDFG";
		$layout[] = "GFDSA";
		$layout[] = "ZXCVB";
		$layout[] = "BVCXZ";
	
		if (strlen($clave)<$longmin)
			$p=12;
	
		$UsrId = strrev($identificacion); // contiene la identificacion al reves
	
		// Verifica si la clave contiene a la identificacion (al derecho y al reves)
		if(strpos($identificacion,$clave)!==false || strpos($clave,$identificacion)!==false)
			$p = 9;
		else
			if(strpos($UsrId,$clave)!==false || strpos($clave,$UsrId)!==false)
				$p = 10;
	
		// Verifica si la clave contiene una secuencia de caracteres comunes
		if ($p == 0)
		{
			for ($i = 0; $i < count($layout); $i++)
			{
				if (strpos(strtoupper($clave),$layout[$i])!== false)
				{
					$p = 6;
					break;
				}
			}
		}
	
		if ($p == 0)
		{
			for ($i = 0; $i < strlen($clave); $i++)
			{
				$a = ord(substr($clave,$i,1));

				if ($a >= 48 && $a <= 57)
					$cn++;
				else
					if (($a >= 65 && $a <= 90) || ($a >= 97 && $a <= 122))
						$ca++;
	
				if ($a == $ant)
				{
					if (++$c3 > 3) // si tengo mas de 3 letras iguales consecutivas
					{
						$p = 2;
						break;
					}
				}
				else
				{
					$c3 = 1;
					if ($ant == $a - 1)
					{
						if (++$sa > 3) // mas de 3 letras consecutivas segun abecedario
						{
							$p = 4;
							break;
						}
						$sd = 1;
					}
					else
					{
						if ($ant == $a + 1)
						{
							if (++$sd > 3) // mas de 3 letras consecutivas segun abecedario inverso
							{
								$p = 5;
								break;
							}
							$sa = 1;
						}
						else
						{
							$sa = 1;
							$sd = 1;
						}
					}
				}
	
				if ($a == ord(substr($claveactual,$i,1)))
				{
					if (++$pa > 5) // si tiene mas de 5 caracteres iguales que la clave actual
					{
						$p = 14;
						break;
					}
				}
	
				$ant = $a;
	
			}
		}
	
		if ($p == 0 && (($ca + $cn) != strlen($clave))) // si la cantidad de letras y numeros es distinta al largo total, entonces hay caracteres especiales
			$p = 11;
		if ($ca == 0 || $cn==0)
			$p=11;
			
		if ($p != 0)
			return false;
		else
			return true;
	}
	
/*
//----------------------------------------------------------------------------------------- 
// Arma los 2 listbox para pasar de uno al otro (de izquierda a derecha)

// Parametros:
// 		->sp_nombre y sp_param es el SP con el que se genera la lista de la izquierda
// 		->clavecombo y desccombo son campos del array con los que arma el combo
// 		->nomformulario es el nombre del formulario sobre el que está el combo
//		->nombrelista es el nombre que van a recibir las listas, botones, etc
//		->textoizq y textoder es el texto que aparecerá arriba de cada lista
//		->style es el estilo que se aplicará a las listas
//		->altura1/altura2: son las alturas en px de cada lista
//		->subebaja si se quiere que en la lista de la derecha aparezcan los botones para subir y bajar elementos

	function ArmarListasConCajaTexto($conexion,$sp_nombre,$sp_param,$clavecombo,$desccombo,$nomformulario,$nombrelista,$textoizq,$textoder,$style,$altura1,$altura2,$subebaja)
	{
?>
<table width="100%" class="textotabla">
	<tr>
		<td width="50%" valign="bottom">
			<label>
				<b><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoizq,ENT_QUOTES) ?></b><br />
				<input type="text" name="model_<?php  echo $nombrelista ?>" id="intext_<?php  echo $nombrelista ?>" size="25" onkeyup="ListaBuscarTexto('<?php  echo $nombrelista ?>',this.value,cod_todos_<?php  echo $nombrelista ?>,txt_todos_<?php  echo $nombrelista ?>,cod_activos_<?php  echo $nombrelista ?>,txt_activos_<?php  echo $nombrelista ?>);" style="width:188px;">
			</label>
<?php 
	if(!$conexion->ejecutarStoredProcedure($sp_nombre,$sp_param,$result,$numfilas,$errno)) die();

	$codigos=array();
	$textos=array();
	while($fila=mysql_fetch_assoc($result))
	{
		$codigos[]=FuncionesPHPLocal::ReemplazarComillas($fila[$clavecombo]);
		$textos[]=FuncionesPHPLocal::ReemplazarComillas($fila[$desccombo]);
	}
	
	if(count($codigos)>0)
	{
		$codigos="'".implode("','",$codigos)."'";
		$textos="'".implode("','",$textos)."'";
	}
	else
	{
		$codigos="";
		$textos="";
	}
?>
<script language="javascript" type="text/javascript">

var cod_todos_<?php  echo $nombrelista ?>=new Array(<?php  echo $codigos ?>);
var txt_todos_<?php  echo $nombrelista ?>=new Array(<?php  echo $textos ?>);
var cod_activos_<?php  echo $nombrelista ?>=new Array();
var txt_activos_<?php  echo $nombrelista ?>=new Array();

var selec_cod_<?php  echo $nombrelista ?> = new Array();
var selec_txt_<?php  echo $nombrelista ?> = new Array();

</script>

		</td>
		<td width="50%" valign="bottom">
			<b><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoder,ENT_QUOTES) ?>:</b>
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<i>click para agregar:</i>
			<div id="<?php  echo $nombrelista ?>" style="height:<?php  echo $altura1 ?>px;border:1px solid #c0c0c0;padding:5px;overflow:auto;<?php  echo $style ?>"></div>
		</td>
		<td width="50%" valign="top">
			<i>click para eliminar:</i>
			<div id="<?php  echo $nombrelista ?>_selec" style="height:<?php  echo $altura2 ?>px;border:1px solid #c0c0c0;padding:5px;overflow:auto;<?php  echo $style ?>"></div>
			<p>		
			<input type="hidden" name="<?php  echo $nombrelista ?>_cod_selec" id="<?php  echo $nombrelista ?>_cod_selec"> 
			</p>
		</td>
	</tr>
</table>
<?php 	
	}
*/
//----------------------------------------------------------------------------------------- 
// Arma los 2 listbox para pasar de uno al otro (de izquierda a derecha)

// Parametros:
// 		->sp_nombre y sp_param es el SP con el que se genera la lista de la izquierda
// 		->clavecombo y desccombo son campos del array con los que arma el combo
// 		->nomformulario es el nombre del formulario sobre el que está el combo
//		->nombrelista es el nombre que van a recibir las listas, botones, etc
//		->textoizq y textoder es el texto que aparecerá arriba de cada lista
//		->style es el estilo que se aplicará a las listas
//		->filas es la cantidad de filas en la lista
//		->subebaja si se quiere que en la lista de la derecha aparezcan los botones para subir y bajar elementos


	static function ArmarListas($conexion,$sp_nombre,$sp_param,$clavecombo,$desccombo,$nomformulario,$nombrelista,$textoizq,$textoder,$style,$filas,$subebaja)
	{
?>


<table class="nostyle">
    <tr>
        <td  style="width:300px;">
           <label><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoizq,ENT_QUOTES) ?></label>
            <?php  
                    $texto="PasarItemListaMultiple(document.".$nomformulario."[\"".$nombrelista."izq[]\"],document.".$nomformulario."[\"".$nombrelista."der[]\"],true)";
                    FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$sp_nombre,$sp_param,$nomformulario,$nombrelista."izq[]",$clavecombo,$desccombo,"","",$regnousar,$validonousar,$filas,"",$style,true,true,$texto)
            ?>		
    	</td>
        <td style="width:50px; vertical-align:middle; text-align:center">
        	<div style="margin-top:0px;">
                <a href="JavaScript:PasarItemListaMultiple(<?php  echo "document.".$nomformulario."['".$nombrelista."izq" ?>[]'],<?php  echo "document.".$nomformulario."['".$nombrelista."der" ?>[]'],true)"><img src="images/ver.png" alt="der" border="0" /></a>
                <br />
                <a href="JavaScript:PasarItemListaMultiple(<?php  echo "document.".$nomformulario."['".$nombrelista."der" ?>[]'],<?php  echo "document.".$nomformulario."['".$nombrelista."izq" ?>[]'],true)"><img src="images/izq.png" alt="izq" border="0" align="middle" /></a>   
            </div>
    	</td>
        <td style="width:300px;">
           <label><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoder,ENT_QUOTES) ?></label>
			<?php  
            $texto="PasarItemListaMultiple(document.".$nomformulario."[\"".$nombrelista."der[]\"],document.".$nomformulario."[\"".$nombrelista."izq[]\"],true)";
			?>
                <select name="<?php  echo $nombrelista.'der' ?>[]" id="<?php  echo $nombrelista.'der' ?>[]" size="<?php  echo $filas ?>" style="<?php  echo $style ?>" ondblclick='<?php  echo $texto?>' multiple="multiple"></select>
			<?php 
            if($subebaja)
            {
			?>			
                <input type="button" name="<?php  echo $nombrelista."botonsubir" ?>" value="Subir" class="botones" onClick="MoverItemLista(formulario['<?php  echo $nombrelista."der[]" ?>'],'subir')" />&nbsp;&nbsp;
                <input type="button" name="<?php  echo $nombrelista."botonbajar" ?>" value="Bajar" class="botones" onClick="MoverItemLista(formulario['<?php  echo $nombrelista."der[]" ?>'],'bajar')" />
			<?php 
            }
			?>			
        </td>
	</tr>
</table>

<?php 
		return true;
	}
/*
//----------------------------------------------------------------------------------------- 
// Arma los 2 listbox para pasar de uno al otro (de arriba a abajo )

// Parametros:
// 		->sp_nombre y sp_param es el SP con el que se genera la lista de la izquierda
// 		->clavecombo y desccombo son campos del array con los que arma el combo
// 		->nomformulario es el nombre del formulario sobre el que está el combo
//		->nombrelista es el nombre que van a recibir las listas, botones, etc
//		->textoarriba y textoabajo es el texto que aparecerá arriba de cada lista
//		->style es el estilo que se aplicará a las listas
//		->filas es la cantidad de filas en la lista
//		->subebaja si se quiere que en la lista de la derecha aparezcan los botones para subir y bajar elementos
	
	function ArmarListasAB($conexion,$sp_nombre,$sp_param,$clavecombo,$desccombo,$nomformulario,$nombrelista,$textoarriba,$textoabajo,$style,$filas,$subebaja)
	{
?>

<table width="100%" class="textotabla">
	<tr align="center">
		<td><strong><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoarriba,ENT_QUOTES) ?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center">
<?php  
		FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$sp_nombre,$sp_param,$nomformulario,$nombrelista."arriba",$clavecombo,$desccombo,"","",$regnousar,$validonousar,$filas,"",$style)
?>		
		</td>
	</tr>
	<tr>
		<td align="center">
			<a href="JavaScript:PasarItemLista(<?php  echo "document.".$nomformulario.".".$nombrelista."arriba" ?>,<?php  echo "document.".$nomformulario."['".$nombrelista."abajo" ?>[]'],true)"><img src="images/bajar.jpg" border="0" alt="bajar"></a>
			<a href="JavaScript:PasarItemLista(<?php  echo "document.".$nomformulario."['".$nombrelista."abajo" ?>[]'],<?php  echo "document.".$nomformulario.".".$nombrelista."arriba" ?>,true)"><img src="images/subir.jpg" border="0" alt="subir"></a>
		</td>
	
	</tr>
	<tr align="center">
		<td><strong><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($textoabajo,ENT_QUOTES) ?></strong></td>
	</tr>
	<tr>
		<td align="center">
			<select name="<?php  echo $nombrelista.'abajo' ?>[]" size="<?php  echo $filas ?>" style="<?php  echo $style ?>" multiple></select>
<?php 
		if($subebaja)
		{
?>			
			<input type="button" name="<?php  echo $nombrelista."botonsubir" ?>" value="Subir" onclick="MoverItemLista(formulario['<?php  echo $nombrelista."abajo[]" ?>'],'subir')">&nbsp;&nbsp;
			<input type="button" name="<?php  echo $nombrelista."botonbajar" ?>" value="Bajar" onclick="MoverItemLista(formulario['<?php  echo $nombrelista."abajo[]" ?>'],'bajar')">
<?php 
		}
?>			

		</td>
	</tr>
</table>

<?php 
		return true;
	}

*/
//----------------------------------------------------------------------------------------- 
// cambia algunos caracteres para que no de error el JS

	static function ReemplazarComillas($texto)
	{
		if($texto!="")
			return str_replace(array("'","\r","\n","</"),array("\'","\\r","\\n","<\/"),$texto);
		else
			return $texto;
	}

//----------------------------------------------------------------------------------------- 	
// Convierte la fecha de un formato a otro

	static function ConvertirFecha($fecha,$formatoinput,$formatooutput)
	{
		if($fecha=='') return "";
		
		if($formatoinput=='dd/mm/aaaa' && $formatooutput=='aaaa/mm/dd')
			return substr($fecha,6,4)."/".substr($fecha,3,2)."/".substr($fecha,0,2);
		elseif($formatoinput=='dd/mm/aaaa' && $formatooutput=='aaaa-mm-dd')
			return substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
		elseif($formatoinput=='aaaa-mm-dd' && $formatooutput=='dd/mm/aaaa')
			return substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
		elseif($formatoinput=='aaaa-mm-dd' && $formatooutput=='dd-mm-aaaa')
			return substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
		elseif($formatoinput=='timestamp' && $formatooutput=='dd/mm/aaaa')
			return substr($fecha,6,2)."/".substr($fecha,4,2)."/".substr($fecha,0,4);
	}
/*
//----------------------------------------------------------------------------------------- 
// Genera una tabla, cuya celda inferior se puede cerrar y abrir

	function GenerarTablaDesplegable($nombrecelda,$textotitulo,$textoinferior)
	{
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #A0C6E5" class="textotabla">
		<tr onclick="EC('<?php  echo $nombrecelda ?>');" style="cursor:pointer" class='colorfondoseleccion'> 
			<td style="padding-left:10px"><?php  echo $textotitulo ?></td>
			<td align="right" style="padding-right:7px"><img id="<?php  echo $nombrecelda ?>img" src="images/i.p.arr.down.jpg" border=0 align="middle" hspace="1" alt="flecha"></td>
		</tr>
		<tr> 
			<td colspan="2" style="padding:0 0 0 0">
				<div id="<?php  echo $nombrecelda ?>" style="display:block; visibility:hidden; position:absolute"><?php  echo $textoinferior ?></div>
			</td>
		</tr>
	</table>
<?php 	
	}

//----------------------------------------------------------------------------------------- 
// Arma el link para enlances abajo de los combos

// Parametros:
// 		->archivonom es a donde va a apuntar el link
//		->querystring es el texto despues del archivonom
//		->nomnivelactualiz y codnivelactualiz es el nuevo codigo que se agrega al get
//		->separador es el texto antes del link
//		->texto que va en el link

	function ArmarLinkEstrucVertical($conexion,$archivonom,$querystring,$nomnivelactualiz,$codnivelactualiz,$separador,$texto)
	{
		echo "<span class='textoaclaraciones'>". FuncionesPHPLocal::HtmlspecialcharsBigtree($separador,ENT_QUOTES);
		if($nomnivelactualiz!="")
			$querystring.="&".$nomnivelactualiz."=".$codnivelactualiz;
		if($querystring!="")
			$querystring="?".$querystring;
			
		echo "<a href='". FuncionesPHPLocal::HtmlspecialcharsBigtree($archivonom,ENT_QUOTES). FuncionesPHPLocal::HtmlspecialcharsBigtree($querystring,ENT_QUOTES)."' class='linkfondoblanco'>";
		echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($texto,ENT_QUOTES);
		echo "</a></span>";
	} // fin ArmarLinkEstrucVertical

//----------------------------------------------------------------------------------------- 
// Retorna un nombre de archivo que no se repita con uno existente

// Parametros:
// 		->spnombre y spparam para la tabla donde estan los nombres de archivos existentes
//		->nombrecampo es el campo donde buscar el nombre de archivo
//		->archivo es el nombre original de archivo
//		->nuevonombre es el nombre que no se repite

	function NombreArchivoValido($conexion,$spnombre,$spparam,$nombrecampo,$archivo,&$nuevonombre) 
	{
		$encontrado=false;
		$nuevonombre=$archivo;
		$n=1;
		while(!$encontrado)
		{
			$arraybusq=array($nombrecampo=>$nuevonombre);
			if(!$conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filaret,$numfilasmatcheo,$errno))
			{
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Se produjo un error al obtener la imagen. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}
	
			if($numfilasmatcheo==0)
				$encontrado=true;
			else
			{
				$nuevonombre=substr($nuevonombre,0,strrpos($nuevonombre,"."))."_".$n.".".substr(strrchr($nuevonombre,"."),1);
				$n++;
			}
		}
		
		return true;
	}
*/
//----------------------------------------------------------------------------------------- 
// Genera una password aleatoria del largo ingresado

	static function GenerarPassword($largo)
	{
		$nuevapwd="";
		
		for($i=1;$i<=$largo;$i++)
			$nuevapwd.=chr(rand(65,90));
			
		return $nuevapwd;
		
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna el querystring y el md5 correspondiente

	static function ArmarLinkMD5($pagina,$parametros,&$get,&$md5)
	{
		$codigospagina["usuarios_modificar_datos.php"]="COD1000";
		$codigospagina["usuarios_modificar_datos_upd.php"]="COD1002";
		$codigospagina["usuario_eliminar_rol_upd.php"]="COD1004";
		$codigospagina["usuarios_act_dct.php"]="COD1005";
		$codigospagina["usuarios_cargar_acciones.php"]="COD1006";
		
		$codigospagina["ciudades.php"]="COD1050";
		
		$codigospagina["not_noticias_am.php"]="COD2000";
		$codigospagina["not_noticias_publicacion.php"]="COD2001";
		$codigospagina["not_noticias_eliminar.php"]="COD2002";
		$codigospagina["not_noticias_bajar_publicacion.php"]="COD2003";
		$codigospagina["not_noticias_publicar.php"]="COD2004";
		$codigospagina["not_noticias_despublicar.php"]="COD2005";		
	
		$codigospagina["not_categorias.php"]="COD2100";		


		$codigospagina['ban_banners_am.php'] = "COD3001";
		$codigospagina['ban_banners_upd.php'] = "COD3002";
		
		$codigospagina["gal_albums.php"]="COD4100";	
		$codigospagina["gal_albums_gal_galerias.php"]="COD4101";	
		
		$codigospagina['top_top_am.php'] = "COD3003";
		$codigospagina['top_top_upd.php'] = "COD3004";
		

		//CONFECCIONAR TAPAS
		$codigospagina['tap_tapas_confeccionar.php'] = "COD4001";
		$codigospagina['tap_plantillas_confeccionar.php'] = "COD4002";
		$codigospagina['tap_plantillas_confeccionar_areas.php'] = "COD4003";
		$codigospagina['tap_modulos_editar.php'] = "COD4003";
		
		//GRAFICOS
		$codigospagina['gra_graficos_am.php'] = "COD5001";
		$codigospagina['gra_graficos_am_porcentajes.php'] = "COD5001";
		
		//PAGINAS
		$codigospagina["pag_categorias.php"]="COD6001";
		$codigospagina["pag_paginas.php"]="COD6002";
		$codigospagina["pag_paginas_am.php"]="COD6003";
		$codigospagina["pag_paginas_publicacion.php"]="COD6004";
		$codigospagina["pag_paginas_eliminar.php"]="COD6005";
		$codigospagina["pag_paginas_publicar.php"]="COD6006";
		$codigospagina["pag_paginas_despublicar.php"]="COD6007";
		$codigospagina["pag_paginas_bajar_publicacion.php"]="COD6008";


		$codigospagina["tap_macros_estructuras.php"]="COD7001";
		$codigospagina["tap_macros_columnas.php"]="COD7002";
		$codigospagina["tap_macros_columnas_estructuras.php"]="COD7003";
		
		//MENU
		$codigospagina["tap_menu_detalle.php"]="COD8001";
		
		//AGENDA
		$codigospagina["age_agenda_categorias.php"]="COD9000";		
		$codigospagina["age_agenda_categorias_lst.php"]="COD9001";		
		$codigospagina["age_agenda_categorias_am.php"]="COD9002";		
		$codigospagina["age_agenda_categorias_upd.php"]="COD9002";		
		$codigospagina["age_agenda_alta.php"]="COD9003";	

		//ENCUESTAS
		$codigospagina["enc_encuestas_respuestas.php"]="COD10001";


		//PLANTILLAS HTML
		$codigospagina["tap_plantillas_html_am.php"]="COD11001";
		$codigospagina["tap_plantillas_html_upd.php"]="COD11002";
		$codigospagina["tap_plantillas_html_editar.php"]="COD11003";
		$codigospagina["tap_plantillas_html_editar_connect.php"]="COD11004";
		
		//CONFIGURACION DE ARCHIVOS FILE
		$codigospagina["fil_config_am.php"]="COD12001";
		$codigospagina["fil_config_upd.php"]="COD12002";


		// Temas
		$codigospagina["tem_temas.php"]="COD14001";	
		
		// MAPAS
		$codigospagina["map_mapas_am.php"]="COD15001";	
		
		// CONTACTOS
		$codigospagina["con_contactos_xls.php"]="COD16001";
		
		// TAPAS
		$codigospagina["rev_tapas_am.php"]="COD17001";
		
		// TAPAS
		$codigospagina["not_comentarios.php"]="COD18001";
		
		
		
		
	

		
		$get_array=array();
		$get="";
		$codigo="";
		$md5="";
		
		if(!isset($codigospagina[$pagina]) || count($parametros)<1)
			return false;
		
		foreach($parametros as $nombparam => $valparam)
		{
			$get_array[]=$nombparam."=".$valparam;
			$codigo.=$valparam;
		}
		
		$codigo.=$codigospagina[$pagina];
		$codigo.=$_SESSION["usuariocod"];
		
		$md5=md5($codigo);
	
		$get=implode("&amp;",$get_array);
		$get.="&amp;md5=".$md5;
		
		return true;
	}	
	
	
	
	static function ArmarLinkMD5Front($pagina,$parametros,&$get,&$md5)
	{
		$codigospagina["noticia.php"]="NOT04234dFE$";
		$codigospagina["pagina.php"]="PAG09634dF%$";
		$codigospagina["galeria.php"]="GAL8342bY&$";
		$codigospagina["album.php"]="ALB3512bY?$";
		$codigospagina["banner_click.php"]="BAN4242bZ?$";	
		$codigospagina["usuario_valida_cambia_pass.php"]="USER04234dFE$";

		$get_array=array();
		$get="";
		$codigo="";
		$md5="";
		
		if(!isset($codigospagina[$pagina]) || count($parametros)<1)
			return false;
		
		foreach($parametros as $nombparam => $valparam)
		{
			$get_array[]=$nombparam."=".$valparam;
			$codigo.=$valparam;
		}
		
		$codigo.=$codigospagina[$pagina];
		
		$md5=md5($codigo);
	
		$get=implode("&amp;",$get_array);
		$get.="&amp;md5=".$md5;
		
		return true;
	}
	
	
	
	static function ConvertiraUtf8($arreglo)
	{
		$arregloenviar=array();
		foreach($arreglo as $clave=>$valor)
		{	
			if (is_array($valor))
				$arregloenviar[$clave] = FuncionesPHPLocal::ConvertiraUtf8($valor);
			else	
				$arregloenviar[$clave]=utf8_decode($valor);
		}
		

		return $arregloenviar;
	}

	static function DecodificarUtf8($arreglo)
	{
		$arregloenviar=array();

		foreach($arreglo as $clave=>$valor)
		{	
			if (is_array($valor))
				$arregloenviar[$clave] = FuncionesPHPLocal::DecodificarUtf8($valor);
			else	
				$arregloenviar[$clave]=utf8_encode($valor);
		}
		
		return $arregloenviar;
	}





	static function array_column_sort() 
	{ 
		$args = func_get_args();
		$array = array_shift($args);
		$sort_array=array();
		
		if(count($array)==0)
			return $array;
		
		// make a temporary copy of array for which will fix the 
		// keys to be strings, so that array_multisort() doesn't
		// destroy them
		$array_mod = array();
		foreach ($array as $key => $value)
			$array_mod['_' . $key] = $value;
		
		$i = 0;
		$multi_sort_line = "return array_multisort( ";
		foreach ($args as $arg) 
		{
			$i++;
			if ( is_string($arg) ) 
			{
				foreach ($array_mod as $row_key => $row) 
				{
					$sort_array[$i][$row_key] = $row[$arg];
				}
			} 
			else 
			{
				$sort_array[$i] = $arg;
			}
			$multi_sort_line .= "\$sort_array[" . $i . "], ";
		}
		$multi_sort_line .= "SORT_ASC,\$array_mod );";
	
		eval($multi_sort_line);
	
		// now copy $array_mod back into $array, stripping off the "_"
		// that we added earlier.
		$array = array();
		foreach ($array_mod as $key => $value)
			$array[ substr($key, 1) ] = $value;
	
		return $array;
	}



/*	
	function EstilosTextAreaHTML($textarea)
	{
?>
	<div style='font-size: 3px;'>&nbsp;</div>
	<input type="button" name="URL" value="URL" class="botones" onclick="AgregarTagLink(<?php  echo $textarea ?>)">
	<input type="button" name="Bold" value="B" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'B',true)">
	<input type="button" name="BoldFin" value="/B" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'B',false)">
	<input type="button" name="Italic" value="I" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'I',true)">
	<input type="button" name="ItalicFin" value="/I" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'I',false)">
	<input type="button" name="Underline" value="U" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'U',true)">
	<input type="button" name="UnderlineFin" value="/U" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'U',false)">
	<input type="button" name="Bullet" value="&#8226;" class="botones" onclick="AgregarEstilo(<?php  echo $textarea ?>,'V',true)">
	<div style='font-size: 3px;'>&nbsp;</div>
<?php 	
	
	}
*/
//----------------------------------------------------------------------------------------- 
/**
* array_column_sort 
* 
* function to sort an "arrow of rows" by its columns
* exracts the columns to be sorted and then
* uses eval to flexibly apply the standard
* array_multisort function
*
* uses a temporary copy of the array whith "_" prefixed to  the keys
* this makes sure that array_multisort is working with an associative
* array with string type keys, which in turn ensures that the keys 
* will be preserved.
*
* TODO: find a way of modifying the keys of $array directly, without using
* a copy of the array. 
* 
* flexible syntax:
* $new_array = array_column_sort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
* 
* original code credited to Ichier (www.ichier.de) here:
* http://uk.php.net/manual/en/function.array-multisort.php
*
* prefixing array indeces with "_" idea credit to steve at mg-rover dot org, also here:
* http://uk.php.net/manual/en/function.array-multisort.php
* 
*/
/*
	function array_column_sort() 
	{ 
		$args = func_get_args();
		$array = array_shift($args);
		$sort_array=array();
		
		if(count($array)==0)
			return $array;
		
		// make a temporary copy of array for which will fix the 
		// keys to be strings, so that array_multisort() doesn't
		// destroy them
		$array_mod = array();
		foreach ($array as $key => $value)
			$array_mod['_' . $key] = $value;
		
		$i = 0;
		$multi_sort_line = "return array_multisort( ";
		foreach ($args as $arg) 
		{
			$i++;
			if ( is_string($arg) ) 
			{
				foreach ($array_mod as $row_key => $row) 
				{
					$sort_array[$i][$row_key] = $row[$arg];
				}
			} 
			else 
			{
				$sort_array[$i] = $arg;
			}
			$multi_sort_line .= "\$sort_array[" . $i . "], ";
		}
		$multi_sort_line .= "SORT_ASC,\$array_mod );";
	
		eval($multi_sort_line);
	
		// now copy $array_mod back into $array, stripping off the "_"
		// that we added earlier.
		$array = array();
		foreach ($array_mod as $key => $value)
			$array[ substr($key, 1) ] = $value;
	
		return $array;
	}

//----------------------------------------------------------------------------------------- 
// Separa el codigo post de una imagen para obtener el codigo
	
	function DesglosarBt ($cadenapost,$cadenainicio,&$codigo)
	{
		$codigo=strstr ($cadenapost, $cadenainicio );  // string empezando por Borrar
 		$pos1=0;
		$pos2=0;
		$pos3=0;		
		$pos1 = strpos($codigo, '_'); 
		if (!$pos1===false)
			$possig1 = strpos(substr($codigo,$pos1+1), '_');
		if (!$possig1===false) 
		{
			$pos2= $pos1+$possig1+1;
		}
		
		if ($pos1>0 && $pos2>0) 
		{
			$codigolength=$pos2-$pos1-1;
			$codigo=substr($codigo,$pos1+1,$codigolength);
		}
	}

//----------------------------------------------------------------------------------------- 
// Hace la inversa a  FuncionesPHPLocal::HtmlspecialcharsBigtree

	function htmldecode($encoded) 
	{
		return strtr($encoded,array_flip(get_html_translation_table(HTML_ENTITIES)));
	}
*/



static function Guardafoto($file, $thumbD, $porcentajeCalidad, $formatoSalida){
	//Obtenemos la informacion de la imagen, el array info tendra los siguientes indices:
	//0: ancho de la imagen
	//1: alto de la imagen
	//mime: el mime_type de la imagen
	$info = getimagesize($file);
	//Dependiendo del mime type, creamos una imagen a partir del archivo original:
	switch($info['mime']){
		case 'image/jpeg':
		$image = imagecreatefromjpeg($file);
		break;
		case 'image/gif';
		$image = imagecreatefromgif($file);
		break;
		case 'image/png':
		$image = imagecreatefrompng($file);
		break;
	}
	if($formatoSalida == "T"){
			//Si el ancho es igual al alto, la imagen ya es cuadrada, por lo que podemos ahorrarnos unos pasos:		
			if($info[0] == $info[1]){
				$xpos = 0;
				$ypos = 0;
				$width = $info[1];
				$height = $info[1];
			//Si la imagen no es cuadrada, hay que hacer un par de averiguaciones:
			}else{
				if($info[0] > $info[1]){ 
					//imagen horizontal
					$xpos = ceil(($info[0] - $info[1]) /2);
					$ypos = 0;
					$width  = $info[1];
					$height = $info[1];
				}else{ 
					//imagen vertical
					$ypos = ceil(($info[1] - $info[0]) /2);
					$xpos = 0;
					$width  = $info[0];
					$height = $info[0];
				}
			}
			//Creamos una nueva imagen cuadrada con las dimensiones que queremos:
			$image_new = imagecreatetruecolor($thumbD, $thumbD);
			$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
			imagefilledrectangle($image_new, 0, 0, $thumbD, $thumbD, $bgcolor);
			imagealphablending($image_new, true);
			//Copiamos la imagen original con las nuevas dimensiones
			imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $thumbD, $thumbD, $width, $height);
		}else{ //if($formatoSalida == "T"){
			$xpos = 0;
			$ypos = 0;
			$width  = $info[0];
			$height = $info[1];
			if($info[0] > $info[1]){ 
				//imagen horizontal
				//preguntamos si el ancho es mayor que el parametro de tamaño, para no agrandar una foto pequeña y pixelarla
				if($info[0] > $thumbD){
					$nueva_altura = ceil($thumbD*($info[1]/$info[0]));
					$image_new = imagecreatetruecolor($thumbD, $nueva_altura);
					$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
					imagefilledrectangle($image_new, 0, 0, $thumbD, $nueva_altura, $bgcolor);
					imagealphablending($image_new, true);
					imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $thumbD, $nueva_altura, $width, $height);
				}else{ //if($info[0] > $ancho){
					$image_new = imagecreatetruecolor($info[0], $info[1]);
					$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
					imagefilledrectangle($image_new, 0, 0, $info[0], $info[1], $bgcolor);
					imagealphablending($image_new, true);
					imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $info[0], $info[1], $width, $height);
				} //if($info[0] > $ancho){
			}else{ //if($info[0] > $info[1]){ 
				//imagen vertical
				//preguntamos si el alto es mayor que el parametro de tamaño, para no agrandar una foto pequeña y pixelarla
				if($info[1] > $thumbD){
					$nueva_altura = ceil($thumbD*($info[0]/$info[1]));
					$image_new = imagecreatetruecolor($nueva_altura, $thumbD);
					$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
					imagefilledrectangle($image_new, 0, 0, $nueva_altura, $thumbD, $bgcolor);
					imagealphablending($image_new, true);
					imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $nueva_altura, $thumbD, $width, $height);
				}else{ //if($info[1] > $ancho){
					$image_new = imagecreatetruecolor($info[0], $info[1]);
					$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
					imagefilledrectangle($image_new, 0, 0, $info[0], $info[1], $bgcolor);
					imagealphablending($image_new, true);
					imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $info[0], $info[1], $width, $height);
				} //if($info[1] > $ancho){
			} //if($info[0] > $info[1]){ 
	} //if($formatoSalida == "T"){
	//Guardamos la nueva imagen como jpg
	return $image_new;
} //function Guardafoto($file, $savePath, $thumbD, $porcentajeCalidad, $formatoSalida){
	

/*
function Guardafoto($file, $thumbD, $porcentajeCalidad, $formatoSalida){
	//Obtenemos la informacion de la imagen, el array info tendra los siguientes indices:
	// 0: ancho de la imagen
	// 1: alto de la imagen
	// mime: el mime_type de la imagen
	$info = getimagesize($file);
	//Dependiendo del mime type, creamos una imagen a partir del archivo original:
	switch($info['mime']){
		case 'image/jpeg':
		$image = imagecreatefromjpeg($file);
		break;
		case 'image/gif';
		$image = imagecreatefromgif($file);
		break;
		case 'image/png':
		$image = imagecreatefrompng($file);
		break;
	}
	if($formatoSalida == "T"){
			//Si el ancho es igual al alto, la imagen ya es cuadrada, por lo que podemos ahorrarnos unos pasos:		
			if($info[0] == $info[1]){
				$xpos = 0;
				$ypos = 0;
				$width = $info[1];
				$height = $info[1];
			//Si la imagen no es cuadrada, hay que hacer un par de averiguaciones:
			}else{
				if($info[0] > $info[1]){ 
					//imagen horizontal
					$xpos = ceil(($info[0] - $info[1]) /2);
					$ypos = 0;
					$width  = $info[1];
					$height = $info[1];
				}else{ 
					//imagen vertical
					$ypos = ceil(($info[1] - $info[0]) /2);
					$xpos = 0;
					$width  = $info[0];
					$height = $info[0];
				}
			}
			//Creamos una nueva imagen cuadrada con las dimensiones que queremos:
			$image_new = imagecreatetruecolor($thumbD, $thumbD);
			$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
			imagefilledrectangle($image_new, 0, 0, $thumbD, $thumbD, $bgcolor);
			imagealphablending($image_new, true);
			//Copiamos la imagen original con las nuevas dimensiones
			imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $thumbD, $thumbD, $width, $height);
		}else{ //if($formatoSalida == "T"){
			$xpos = 0;
			$ypos = 0;
			$width  = $info[0];
			$height = $info[1];
			//Creamos una nueva imagen cuadrada con las dimensiones que queremos:
			$nueva_altura = ceil($thumbD*($info[1]/$info[0]));
			$image_new = imagecreatetruecolor($thumbD, $nueva_altura);
			$bgcolor = imagecolorallocate($image_new, 255, 255, 255);  
			imagefilledrectangle($image_new, 0, 0, $thumbD, $nueva_altura, $bgcolor);
			imagealphablending($image_new, true);
			//Copiamos la imagen original con las nuevas dimensiones
			imagecopyresampled($image_new, $image, 0, 0, $xpos, $ypos, $thumbD, $nueva_altura, $width, $height);
	} //if($formatoSalida == "T"){
	//Guardamos la nueva imagen como jpg
	return $image_new;
} //function Guardafoto($file, $savePath, $thumbD, $porcentajeCalidad, $formatoSalida){
*/


	static function ArmarLinkMD5PaginasComunes($parametros,&$get,&$md5)
	{
		$codigospagina="COD1000";
		$get_array=array();
		$get="";
		$codigo="";
		$md5="";
		if(count($parametros)<1)
			return false;
		foreach($parametros as $nombparam => $valparam)
		{
			$get_array[]=$nombparam."=".$valparam;
			$codigo.=$valparam;
		}
		$codigo.=$codigospagina;
		$md5=md5($codigo);
		$get=implode("&amp;",$get_array);
		$get.="&amp;md5=".$md5;
		
		return true;
	}
	
	static function crear_semilla()
	{
	   list($usec, $sec) = explode(' ', microtime());
	   return (float) $sec + ((float) $usec * 100000);
	}

	static function aleatorio($cantidad)
	{
		srand(FuncionesPHPLocal::crear_semilla());

		// Generamos la clave
		$clave="";
		$max_chars = round(rand($cantidad,$cantidad));  // tendrá entre 8 y 8 caracteres
		$chars = array();
		for ($i="a"; $i<"z"; $i++) $chars[] = $i;  // creamos vector de letras
		$chars[] = "z";
		for ($i=0; $i<$max_chars; $i++) {
		  $letra = round(rand(0, 1));  // primero escogemos entre letra y número
		  if ($letra) // es letra
			$clave .= $chars[round(rand(0, count($chars)-1))];
		  else // es numero
			$clave .= round(rand(0, 9));
		}

		return $clave;
	}
	static function eliminarImagen($archivo)
	{
		//el archivo viene con path
		if (unlink($archivo))
			return true;
		else 
			return false;
	}


	static function get_file_extension($filename) {
		preg_match("/(.*)\.([a-zA-Z0-9]{0,5})$/", $filename, $regs);
		return($regs[2]);
	}


    static function EscapearCaracteres($oracion)
    {
		$oracion = strtolower($oracion);
		$caracteresescapeados = trim(str_replace(array('/'),'', $oracion));
		$caracteresescapeados = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), $caracteresescapeados));
		$caracteresescapeados = trim(str_replace(array("Á","É","Í","Ó","Ú","Ñ"),array("a","e","i","o","u","n"), $caracteresescapeados));
		return $caracteresescapeados;	
    }


	static function MostrarJerarquia($oCategorias, $catcod,&$jerarquia,&$nivel)
	{
		$i=1;
		$jerarquia="";
		$nivel=0;
		$arrjerarquia = array();
		if(!$oCategorias->ArregloPadres($catcod,$arrjerarquia,$nivel))
			return false;

		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			$categoriadominio=FuncionesPHPLocal::EscapearCaracteres($valor['catnom']);
			$categoriadominio = preg_replace('/[^a-zA-Z0-9-_+ ]/', '-', $categoriadominio);
			$categoriadominio = str_replace(' ', '-', $categoriadominio);
			
			if ($i!=$nivel)
			{ 
				$jerarquia.="<a href='".$categoriadominio."_";
				$jerarquia.=$valor['catcod'];
				$jerarquia.="/' class='bold'>";
				$jerarquia.=$valor['catnom']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['catnom']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 




static function ReemplazarTextoFechas($texto)
{
	// reemplazo meses  
	$texto = str_replace("January","Enero",$texto); 
	$texto = str_replace("February","Febrero",$texto); 
	$texto = str_replace("March","Marzo",$texto); 
	$texto = str_replace("April","Abril",$texto); 
	$texto = str_replace("May","Mayo",$texto); 
	$texto = str_replace("June","Junio",$texto); 
	$texto = str_replace("July","Julio",$texto); 
	$texto = str_replace("August","Agosto",$texto); 
	$texto = str_replace("September","Septiembre",$texto); 
	$texto = str_replace("October","Octubre",$texto); 
	$texto = str_replace("November","Noviembre",$texto); 
	$texto = str_replace("December","Diciembre",$texto); 

	// reemplazo meses cortos 
	$texto = str_replace("Jan","Ene",$texto); 
	$texto = str_replace("Apr","Abr",$texto); 
	$texto = str_replace("Aug","Ago",$texto); 
	$texto = str_replace("Sep","Sep",$texto); 
	$texto = str_replace("Oct","Oct",$texto); 
	$texto = str_replace("Nov","Nov",$texto); 
	$texto = str_replace("Dec","Dic",$texto); 

	// reemplazo días 
	$texto = str_replace("Monday","Lunes",$texto); 
	$texto = str_replace("Tuesday","Martes",$texto); 
	$texto = str_replace("Wednesday","Miércoles",$texto); 
	$texto = str_replace("Thursday","Jueves",$texto); 
	$texto = str_replace("Friday","Viernes",$texto); 
	$texto = str_replace("Saturday","Sábado",$texto); 
	$texto = str_replace("Sunday","Domingo",$texto); 
	
	// reemplazo dias cortos 
	$texto = str_replace("Mon","Lun",$texto); 
	$texto = str_replace("Tue","Mar",$texto); 
	$texto = str_replace("Wed","Mié",$texto); 
	$texto = str_replace("Thu","Jue",$texto); 
	$texto = str_replace("Fri","Vie",$texto); 
	$texto = str_replace("Sat","Sáb",$texto); 
	$texto = str_replace("Sun","Dom",$texto); 

	return $texto;
}




/*FUNCTIONES DEL CALENDAR*/

static function js2PhpTime($jsdate){
  if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches)==1){
    $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
    //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
    $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
    //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }
  return $ret;
}

static function php2JsTime($phpDate){
    //echo $phpDate;
    //return "/Date(" . $phpDate*1000 . ")/";
    return date("m/d/Y H:i", $phpDate);
}

static function php2MySqlTime($phpDate){
    return date("Y-m-d H:i:s", $phpDate);
}

static function mySql2PhpTime($sqlDate){
    $arr = date_parse($sqlDate);
    return mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);

}


	static function ObtenerDiasSemana()
	{
		$arreglodias = 	array();
		$arreglodias['Lu'] = 'Lunes';
		$arreglodias['Ma'] = 'Martes';
		$arreglodias['Mi'] = 'Miercoles';
		$arreglodias['Ju'] = 'Jueves';
		$arreglodias['Vi'] = 'Viernes';
		$arreglodias['Sa'] = 'Sabado';
		$arreglodias['Do'] = 'Domingo';
		
		return $arreglodias;
	}


static function ReemplazarDiasSemanaBase($texto)
{
       // reemplazo dias cortos
       $texto = str_replace("Monday","Lu",$texto);
       $texto = str_replace("Tuesday","Ma",$texto);
       $texto = str_replace("Wednesday","Mi",$texto);
       $texto = str_replace("Thursday","Ju",$texto);
       $texto = str_replace("Friday","Vi",$texto);
       $texto = str_replace("Saturday","Sa",$texto);
       $texto = str_replace("Sunday","Do",$texto);
       
       // reemplazo dias cortos
       $texto = str_replace("Mon","Lu",$texto);
       $texto = str_replace("Tue","Ma",$texto);
       $texto = str_replace("Wed","Mi",$texto);
       $texto = str_replace("Thu","Ju",$texto);
       $texto = str_replace("Fri","Vi",$texto);
       $texto = str_replace("Sat","Sa",$texto);
       $texto = str_replace("Sun","Do",$texto);
       
       return $texto;
}

static function ObtenerImgYoutube ($url,$size) 
{
	if($url!='') {
		$temp = explode('=', $url);
		$videoid = $temp[1];
	}
	if ($size != "" ) {
		$size = $size;
	} else {
		$size = 0;
	}
	$videoth = 'http://img.youtube.com/vi/'.$videoid.'/'.$size.'.jpg';
	return $videoth;
}

static function ObtenerEdad($fecha_nacimiento){
   list($y, $m, $d) = explode("-", $fecha_nacimiento);
    $y_dif = date("Y") - $y;
    $m_dif = date("m") - $m;
    $d_dif = date("d") - $d;
    if ((($d_dif < 0) && ($m_dif == 0)) || ($m_dif < 0))
        $y_dif--;
    return $y_dif;
}


	static function RenderFile($template_file, $vars = array())
	{
		if(file_exists($template_file))
		{
			ob_start();
			extract($vars);
			include($template_file);
			return ob_get_clean();
		}
	}


	static function EncriptarFrase($string, $key) {
	   $result = '';
	   for($i=0; $i<strlen($string); $i++) {
		  $char = substr($string, $i, 1);
		  $keychar = substr($key, ($i % strlen($key))-1, 1);
		  $char = chr(ord($char)+ord($keychar));
		  $result.=$char;
	   }
	   return base64_encode($result);
	}

	static function DesencriptarFrase($string, $key) {
	   $result = '';
	   $string = base64_decode($string);
	   for($i=0; $i<strlen($string); $i++) {
		  $char = substr($string, $i, 1);
		  $keychar = substr($key, ($i % strlen($key))-1, 1);
		  $char = chr(ord($char)-ord($keychar));
		  $result.=$char;
	   }
	   return $result;
	}
	


	static function js_array($arreglo) {
	  $temp=array();
	  foreach ($arreglo as $fila)
	  { 
		$temp[] = FuncionesPHPLocal::js_str($fila);
	  }
	  return '['.implode(',', $temp).']';
	}
	
	static function js_str($s) {
	  return '"'.addcslashes($s, "\0..\37\"\\").'"';
	}
	
	static function js_query($conexion,$resultado,$clave) {
	  $temp=array();
	  while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	  { 
		$temp[] = FuncionesPHPLocal::js_str($fila[$clave]);
	  }
	  return '['.implode(',', $temp).']';
	}	
	
	
	
	static function GuardarArchivo($path,$data,$archivo)
	{		
		if (file_exists($path.$archivo))
		{
			unlink($path.$archivo);
		}

		if (!$file = fopen($path.$archivo, "w"))
			return false;
			
		$data = trim($data);	
		$fwrite = 0;
		for ($written = 0; $written < strlen($data); $written += $fwrite) {
			$fwrite = fwrite($file, substr($data, $written));
			if ($fwrite===FALSE)
				echo "error";
			if ($fwrite == 0 || $fwrite===false) {
				break;
			}
		}	
		
		fclose($file);
		
		return true;
	}
	
	
	
	
	
	
	static function BuscarDominioDuplicado($conexion,$url,&$resultado,&$numfilas)
	{
		return true;
		
		$spnombre="sel_dominios_xurl";
		$sparam=array(
			'purl'=> $url
			);

		if(!$conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar el dominio. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
	
	
	static function cortar_string ($string, $largo) { 
	   $marca = "<!--corte-->"; 
	
	   if (strlen($string) > $largo) { 
			
		   $string = wordwrap($string, $largo, $marca); 
		   $string = explode($marca, $string); 
		   $string = $string[0]." [...]"; 
	   } 
	   return $string; 
	
	}
	
static function ArmarPaginado($cantidadpaginacion,$element_count,$page,&$primera,&$ultima,&$numpages,&$current,&$TotalSiguiente,&$TotalVer)
	{
			
			$cant = $cantidadpaginacion;
			if ($element_count>$cantidadpaginacion)
				$TotalSiguiente = ($page*$cantidadpaginacion);
			else
				$TotalSiguiente = $element_count;
				
			if ($TotalSiguiente>$cantidadpaginacion)	
				$TotalVer = $TotalSiguiente - $cantidadpaginacion;
			elseif($element_count==0)
				$TotalVer = 0;
			else
				$TotalVer = 1;

			$current = ( $page - 1 ) * $cant;
						
			// Paginacion
			if ($current + $cant <= $element_count)
				$next_page = $page + 1;
			else
				$next_page = $page;
			
			if ($current - $cant >= 0)
				$prev_page = $page - 1;
			else
				$prev_page = $page;
			
			$mostrar = 8;
			$primera = $page - abs($mostrar / 2);
			$ultima = $page + abs($mostrar / 2);
			$numpages = ceil($element_count / $cant);
			
			
			if ($numpages < 1)
				$numpages = 1;
			
			if ($primera < 1) {
				$primera = 1;
				if ($numpages > $mostrar)
					$ultima = $mostrar;
				else
					$ultima = $numpages;
			}
			
			if ($ultima > $numpages) {
				$ultima = $numpages;
				$primera = $ultima - $mostrar;
				if ($primera < 1)
					$primera = 1;
			}
		
			return true;
	}	
	
	static function HtmlspecialcharsBigtree($string,$flags="ENT_COMPAT | ENT_HTML401",$encoding="ISO8859-1",$double_encode=true)
	{
		return  htmlspecialchars($string,$flags,$encoding,$double_encode);
		
	}
		
	static function ArmarArregloCodigos($arbol,&$arreglo)
	{
		
		foreach($arbol as $datoscategoria)
		{
			$arreglo[$datoscategoria['catcod']]=$datoscategoria['catcod'];
			if (count($datoscategoria['subarbol'])>0)
				ArmarArregloCodigos($datoscategoria['subarbol'],$arreglo);
		}
		return true;
	}
} // Fin clase FuncionesPHPLocal

?>