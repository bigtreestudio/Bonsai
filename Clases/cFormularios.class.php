<?php 
include(DIR_DATA."formularioData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cFormularios
{
	protected $conexion;
	protected $mails_destino;
	protected $datosformulario;
	protected $string_delimiter_mail = "; ";
	
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function BuscarFormulario($datos)
	{
		$spnombre="sel_con_formularios_xformulariocod";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el formulario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datosformulario = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$oFormularioData = new FormularioData();
		$this->SetData($oFormularioData,$datosformulario);
		
		return $oFormularioData;
			
	}	

	public function SetData(&$oFormularioData,$datos)
	{
		if (isset($datos['formulariocod']))
			$oFormularioData->setCodigo($datos['formulariocod']);
	
		if (isset($datos['formulariotipocod']))
			$oFormularioData->setTipoCodigo($datos['formulariotipocod']);
	
		if (isset($datos['formulariotipotitulo']))
			$oFormularioData->setTitulo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formulariotipotitulo'],ENT_QUOTES));
		
		if (isset($datos['formulariodireccion']))
			$oFormularioData->setDireccion( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formulariodireccion'],ENT_QUOTES));
		
		if (isset($datos['formulariotelefono1']))
			$oFormularioData->setTelefono1($datos['formulariotelefono1']);

		if (isset($datos['formulariotelefono2']))
			$oFormularioData->setTelefono2($datos['formulariotelefono2']);
		
		if (isset($datos['formulariocelular']))
			$oFormularioData->setCelular($datos['formulariocelular']);
		
		if (isset($datos['formulariocp']))
			$oFormularioData->setCodigoPostal($datos['formulariocp']);

		if (isset($datos['formulariopiso']))
			$oFormularioData->setPiso($datos['formulariopiso']);
		
		if (isset($datos['formulariomail']))
			$oFormularioData->setMail($datos['formulariomail']);

		if (isset($datos['formularioweb']))
			$oFormularioData->setWeb($datos['formularioweb']);

		if (isset($datos['formulariotwitter']))
			$oFormularioData->setTwitter($datos['formulariotwitter']);

		if (isset($datos['formulariofacebook']))
			$oFormularioData->setFacebook($datos['formulariofacebook']);

		if (isset($datos['formulariolatitud']))
			$oFormularioData->setLatitud($datos['formulariolatitud']);

		if (isset($datos['formulariolongitud']))
			$oFormularioData->setLongitud($datos['formulariolongitud']);

		if (isset($datos['formulariociudad']))
			$oFormularioData->setCiudad($datos['formulariociudad']);

		if (isset($datos['provinciacod']))
			$oFormularioData->setProvinciaCodigo($datos['provinciacod']);

		if (isset($datos['paiscod']))
			$oFormularioData->setPaisCodigo($datos['paiscod']);

		if (isset($datos['formulariojson']))
			$oFormularioData->setDatosExtra($datos['formulariojson']);

		if (isset($datos['formulariotexto']))
			$oFormularioData->setTexto($datos['formulariotexto']);

		if (isset($datos['formulariodisclaimer']))
			$oFormularioData->setDisclaimer($datos['formulariodisclaimer']);

		if (isset($datos['formulariomaildesde']))
			$oFormularioData->setMailDesde($datos['formulariomaildesde']);

		if (isset($datos['formularioestado']))
			$oFormularioData->setEstado($datos['formularioestado']);

		if (isset($datos['formulariomapazoom']))
			$oFormularioData->setMapaZoom($datos['formulariomapazoom']);

		if (isset($datos['formulariomapatipo']))
			$oFormularioData->setMapaTipo($datos['formulariomapatipo']);

		if (isset($datos['menucod']))
			$oFormularioData->setMenuCodigo($datos['menucod']);
			
		if (isset($datos['menutipocod']))
			$oFormularioData->setMenuTipoCodigo($datos['menutipocod']);

		if (isset($datos['formulariotipotitulo']))
		{
			$dominio="";
			$dominioform = FuncionesPHPLocal::EscapearCaracteres($datos['formulariotipotitulo']);
			$dominioform=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioform));
			$dominioform=str_replace(' ', '-', trim($dominioform))."_c".$datos['formulariocod'];
			$oFormularioData->setDominio($dominioform);
		}
		return true;
	}

	
	public function CargarMailsDetinos($oFormularios,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_envios";
		$sparam=array(
			'pformulariocod'=> $oFormularios->getCodigo()
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los destinos de mail.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		return true;
			
	}
	
	public function InsertarFormulario($datos,&$codigoinsertado)
	{
		$spnombre="ins_con_formulario_datos";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod'],
			'pformularionombre'=> $datos['formularionombre'],
			'pformularioapellido'=> $datos['formularioapellido'],
			'pformulariomail'=> $datos['formulariomail'],
			'pformulariotelefono'=> $datos['formulariotelefono'],
			'pformularioempresa'=> $datos['formularioempresa'],
			'pformularioubic'=> $datos['formularioubic'],
			'pprovinciacod'=> $datos['provinciacod'],
			'pdepartamentocod'=> $datos['departamentocod'],
			'pformulariocomentario'=> $datos['formulariocomentario'],
			'pformulariodatosjson'=> $datos['formulariodatosjson'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al insertar el formulario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		return true;
			
	}




	public function EnviarMailFormulario($datos,$oFormularios)
	{

		$mail = new PHPMailer ();
		
		$mail -> FromName = PROJECTNAME;
		$mail -> Subject = $oFormularios->getTitulo()." - ".PROJECTNAME;
		
		$htmlmail = '
		<!DOCTYPE html>
			<html lang="es">
			  <head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Formulario de contacto</title>
			  </head>
			  <body style="margin: 0;">
			   <!-- Contenido del email -->
					<div style="padding: 10px; text-align: center; background-color: #fcda59; border:0: color:#000;">
					  <img src="'.DOMINIOPORTAL.'/public/gcba/bastrap3/bac-header-2.png" alt="Vamos Buenos Aires">
					</div>
				
					<div style="font-family:Helvetica, Arial, sans-serif;">
					  <div style="max-width: 630px; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;">
						<h3 style="margin: 40px 0 30px; color: #333; font-size: 20px; line-height: 1.1; font-family:  Helvetica, Arial, sans-serif;">Formulario de contacto</h3>
						<p>Este es un mensaje recibido mediante el formulario de contacto del portal de BA en OGP.</p>
						<br>
						<b>Mensaje de '.FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formularionombre']." ".$datos['formularioapellido'],ENT_QUOTES).'.</b>
						<br/><br/><br/>
						E-Mail: <b>'. FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formulariomail'],ENT_QUOTES).'</b>
						<br/><br/>
						';
						if(isset($datos["formulariotelefono"]) && $datos["formulariotelefono"]!="")
						{
							$htmlmail .= "		<br/>
								Telefono: ". $datos['formulariotelefono']."
								<br/>
								";
						}
						if(isset($datos["formularioempresa"]) && $datos["formularioempresa"]!="" && $datos["formularioempresa"]!="NULL")
						{
							$htmlmail .= "		<br/>
								Empresa: ". $datos['formularioempresa']."
								<br/>
								";
						}
						
						if (trim($datos['formulariodatosjson'])!="")	
						{	
							$dataJson = json_decode($datos['formulariodatosjson']);
							
							if (is_array($dataJson) && count($dataJson)>0){
								foreach($dataJson as $clave=>$dato)
								{
									$htmlmail .= "<br/><br/>
										".$clave.": <b>". FuncionesPHPLocal::HtmlspecialcharsBigtree($dato,ENT_QUOTES)."</b>";
									
								}
							}
						}
						
						if ($oFormularios->getTipoCodigo()==2)
						{
							$htmlmail .= "<br/><br/>
								Ubicaci&oacute;n: <b>". FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formularioubic'],ENT_QUOTES)."</b>";
						}
		
						$htmlmail .= "		<br/><br/>
						Mensaje: ". FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formulariocomentario'],ENT_QUOTES)."
						<br/>
					  </div>
					</div>
					<!-- Fin Contenido del email -->
				
				
				  </body>
				</html>";

		
		$mail -> Body = $htmlmail;
		$mail -> IsHTML (true);
		
		$this->CargarMailsDetinos($oFormularios,$resultado,$numfilas);

		if ($numfilas>0)
		{
			while ($fila = $this->conexion->obtenerSiguienteRegistro($resultado))
			{
				switch($fila['enviotipo'])
				{
					case 1:
						$mail -> AddAddress($fila['enviomail']);
						break;
					case 2:
						$mail -> AddCC($fila['enviomail']);
						break;
					case 3:
						$mail -> AddBCC($fila['enviomail']);
						break;
					
				}
			}
			
			$mail -> AddReplyTo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formulariomail'],ENT_QUOTES), FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['formularionombre'],ENT_QUOTES));
			
			
			if (ENVIAREMAIL)
			{
				if (SMTP==1)
				{ 
					$mail->IsSMTP(); // establecemos que utilizaremos SMTP
					$mail->SMTPAuth   = true; // habilitamos la autenticación SMTP
					if (SMTP_SSL==1)
						$mail->SMTPSecure = "ssl";  // establecemos el prefijo del protocolo seguro de comunicación con el servidor
					elseif(SMTP_TLS==1)
						$mail->SMTPSecure = "tls";
					$mail->Host       = SMTP_HOST;      // establecemos GMail como nuestro servidor SMTP
					$mail->Port       = SMTP_PORT;                   // establecemos el puerto SMTP en el servidor de GMail
					$mail->Username   = SMTP_USER;  // la cuenta de correo GMail
					$mail->Password   = SMTP_PASSW; // password de la cuenta GMail		
				}
				if(!$mail->Send()) 
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al enviar el mail.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
					return false;
				}				
			}
		}
		
		return true;
		
			
	}

	
			
}//FIN CLASE

?>