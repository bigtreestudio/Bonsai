<?php

require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoConsulta($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oStoredFront=new cStoredFront($conexion);



//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>
		<form action="stored_frontpopup.php" method="post" name="formulario_popup" >
		<table width="100%" class="textotabla">
			<tr>
				<td valign="top" width="100%">
					<span class='textonombreclave'>PHP Generado</span>
				</td>
			</tr>
			<tr>
				<td valign="top" width="100%">
				<textarea cols="50" rows="7" name="Texto"></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top" width="100%">
				<input type="button" name="Seleccionar" value="Seleccionar Todo" onclick="Seleccionartodo(formulario_popup)" >
				</td>
			</tr>
		</table>
		</form>	

<script language="Javascript" type="text/javascript">
<!--
function Seleccionartodo(formulario) {
formulario.Texto.focus()
formulario.Texto.select()
}
//-->
</script>
<script type="text/javascript" language="javascript">

var elSelect = window.opener.document.getElementById('spsqlstring');
var elNombre = window.opener.document.getElementById('spnombre');

var textoencontrado=elSelect.value;
var nombrestore=elNombre.value;
var texto= '$spnombre="'+nombrestore+'";\n';
var textoparametro="";
texto+= "$sparam=array(\n\t";
var i=0;
var abierto=false;
var existe;
var array=new Array (textoencontrado.length);
array=textoencontrado;
while(i<textoencontrado.length)
{
	
	if ((textoencontrado.charAt(i)=="#") && (!abierto))
	{	
		abierto=true;
		texto+="'";
	}
	else 
	{	
		if ((textoencontrado.charAt(i)=="#") && (abierto))
		{	
			abierto=false;
			var j=i+1;
			existe=false;
			while ((j<textoencontrado.length) && (!existe))
			{	
				if (textoencontrado.charAt(j)=="#")
					existe=true;
				j++;	
			}
			if (existe)
			{	
				if (textoparametro=="pultmodfecha")
				{	
					texto+="'=> date(\"Y/m/d H:i:s\"),\n\t";
				}else
				{
					if (textoparametro=="pultmodusuario")
					{
						texto+="'=> $_SESSION['usuariocod'],\n\t";
					}else
					{
						texto+="'=> $datos['"+textoparametro.substr(1,textoparametro.length)+"'],\n\t";
					}	
				}
				textoparametro="";
			}	
			else
			{
				if (textoparametro=="pultmodfecha")
				{	
					texto+="'=> date(\"Y/m/d H:i:s\")\n\t";
				}else
				{
					if (textoparametro=="pultmodusuario")
					{
						texto+="'=> $_SESSION['usuariocod']\n\t";
					}else
					{
						texto+="'=> $datos['"+textoparametro.substr(1,textoparametro.length)+"']\n\t";
					}	
				}
				textoparametro="";	
			}	
		}	
	}
	if ((abierto) && (textoencontrado.charAt(i)!="#"))
	{
		texto+=textoencontrado.charAt(i);
		textoparametro+=textoencontrado.charAt(i);
	}	
	i++;
}


texto+=");";
document.formulario_popup.Texto.value=texto;
document.formulario_popup.Texto.focus()
document.formulario_popup.Texto.select()
</script>

<br /><div align="center"><a href='javascript:window.close();'class='textotabla linkfondoblanco'>Cerrar Ventana</a></div>
<?
$oEncabezados->PieConsulta();
?>
