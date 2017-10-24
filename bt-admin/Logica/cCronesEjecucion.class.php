<?php 
include(DIR_CLASES_DB."cCronesEjecucion.db.php");

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de usuarios

class cCronesEjecucion extends cCronesEjecuciondb
{
	function cCronesEjecucion($conexion)
	{
		$this->conexion = &$conexion;
	}

//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

	public function BuscarErroresxEjecutobien($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarErroresxEjecutobien ($datos,$resultado,$numfilas))
			return false;
		return true;
	}
	
	
	
	public function Insertar($datos,&$codigoinsertado)
	{		
		if (!parent::Insertar ($datos,$codigoinsertado))
			return false;

		return true;		
	}
	
	
	
	public function ActualizarFechaFinxCroncod($datos)
	{
		if (!parent::ActualizarFechaFinxCroncod ($datos))
			return false;

		return true;		
	}


	
	public function FinalizarCron($datos)
	{
		if(!$this->ActualizarFechaFinxCroncod($datos))
			return false;
		return true;
	}
	
	
	
	public function IniciarCron($datos,&$codigoinsertado)
	{
		if(!$this->Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	}
	
	
	public function ActualizarCronesEjecucionEnviadoxCronejecucioncod($datos)
	{
		if(!$this->ActualizarCronesEjecucionEnviadoxCronejecucioncod($datos))
			return false;
			
		return true;
	}


	public function EnviarMails()
	{
		$mail = new PHPMailer ();

		$subject = utf8_decode("Error Cron ejecucion - ".PROJECTNAME);

		$datos['ejecutobien']=0;
		$datos['enviado']=0;
		$this->BuscarErroresxEjecutobien($datos,$resultado,$numfilas);
		$texto="";
		$vec = array();
		$datosactualizar['cronejecucioncod'] ="";
		while ($fila = mysql_fetch_assoc($resultado))
		{			 	
			if($datosactualizar['cronejecucioncod']=="")
			 	$datosactualizar['cronejecucioncod'].= $fila['cronejecucioncod']; 
			 else
			 	$datosactualizar['cronejecucioncod'].=",".$fila['cronejecucioncod']; 
					
			 $vec = json_decode($fila['json']);
			 $texto .="<table width='600px' style='color:#FFF;font-size:11px; font-family:Arial,Verdana,sans-serif;text-align:left;' border='0' align='center' cellspacing='0' bordercolor='white' bgcolor='#000'>	";
			 $texto .="<tr><td colspan='2' style='font-size:14px;'>".$fila['nombre']."</td></tr>";
			 $texto .="<tr><td colspan='2' style='font-size:14px;'>".$fila['ubicarchivo']."</td></tr>";
			 $texto .="<tr>";
			 $texto .="<td width='30%'><b>Fecha</b></td>";
			 $texto .="<td width='70%'>".$fila['finicio']."</td>";
			 $texto .="</tr>";
			 foreach ($vec as $campo => $valor)
			 {	
				 
				 $texto .=" <tr style='color:#000000;background-color:#d2d2d2;'>";
				 $texto .=" <td ><b>".$campo."</b></td>";
				 $texto .=" <td width='70%'>".$valor."</td>";
				 $texto .="</tr>";
			 }
			 $texto .="</table>";
			 $texto .="<HR>";	
		}
		$mail -> Subject = "Errores Cron Ejecucion Bulk";
		$mail -> Body = $texto;
		$mail -> IsHTML (true);
		
				
		$mail -> FromName = PROJECTNAME;
		$mail -> AddAddress (EMAILADMIN);
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP(); // establecemos que utilizaremos SMTP
		$mail->SMTPAuth   = true; // habilitamos la autenticación SMTP
		$mail->SMTPSecure = "ssl";  // establecemos el prefijo del protocolo seguro de comunicación con el servidor
		$mail->Host       = "smtp.gmail.com";      // establecemos GMail como nuestro servidor SMTP
		$mail->Port       = 465;                   // establecemos el puerto SMTP en el servidor de GMail
		$mail->Username   = "cmsbigtree@gmail.com";  // la cuenta de correo GMail
		$mail->Password   = "CMSportal";           // password de la cuenta GMail				
			
		if($numfilas>0)
		{
			if (ENVIAREMAIL)
			{
				if(!$mail->Send()) 
				{
					FuncionesPHPLocal::MostrarMensaje("Error al enviar el email de crones de ejecucion.");
					return false;
				}
			}
		
			$datosactualizar['enviado'] = 1;
			if (!$this->ActualizarCronesEjecucionEnviadoxCronejecucioncod($datosactualizar))
				return false;
		
		}
		return true;
	}



}//fin clase	

?>