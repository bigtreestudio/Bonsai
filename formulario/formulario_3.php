<?php  
include("formulario_3_data.php");
$formulariotelefono = "";
$TemaSel = "";
$OrganismoSel = "";
$OtroOrganismo="";
if (isset($_SESSION['formulario']))
{
	if (isset($_SESSION['formulario']['formulariotelefono']))
		$formulariotelefono	= $_SESSION['formulario']['formulariotelefono'];
	if (isset($_SESSION['formulario']['Tema']))
		$TemaSel	= $_SESSION['formulario']['Tema'];
	if (isset($_SESSION['formulario']['Organismo']))
		$OrganismoSel = $_SESSION['formulario']['Organismo'];
	if (isset($_SESSION['formulario']['OtroOrganismo']))
		$OtroOrganismo = $_SESSION['formulario']['OtroOrganismo'];

}

?>
<div class="inputright">
    <label class="Label" for="formulariotelefono">Tel&eacute;fono</label>
    <div class="input_font">
        <input type="text" name="formulariotelefono" value="<?php  echo $formulariotelefono?>" id="formulariotelefono" size="20" maxlength="20" />
   </div>  
   <?php  if (isset($_SESSION['error']) && $_SESSION['error']== 9){?><div class="clearboth">&nbsp;</div><div class="msg_error"><?php  echo $_SESSION['accionmsg']?></div><?php  }?>
</div>  
<div class="clearboth">&nbsp;</div>

<div class="inputright">
    <label class="Label" for="Tema">Tema/Asunto</label>
    <div class="input_font">
        <select name="Tema" id="Tema" style="width:350px;">
        	<?php  foreach($arregloTema as $clave=>$Tema){?>
                <option <?php  if ($clave==$TemaSel) echo 'selected="selected"'?> value="<?php  echo $clave?>" <?php  ?>><?php  echo $Tema;?></option>
            <?php  }?>
        </select>
   </div>  
   <?php  if (isset($_SESSION['error']) && $_SESSION['error']== 10){?><div class="clearboth">&nbsp;</div><div class="msg_error"><?php  echo $_SESSION['accionmsg']?></div><?php  }?>
</div>  
<div class="clearboth">&nbsp;</div>

<div class="inputright">
    <label class="Label" for="Organismo">Organismo</label>
    <div class="input_font">
        <select name="Organismo" id="Organismo" style="width:550px;">
        	<option value="">Seleccione un organismo...</option>
        	<?php  foreach($arregloOrganismo as $clave=>$organismo){?>
                <option <?php  if ($clave==$OrganismoSel) echo 'selected="selected"'?>  value="<?php  echo $clave?>"><?php  echo $organismo;?></option>
            <?php  }?>
        </select>
    </div>  
	<?php  if (isset($_SESSION['error']) && $_SESSION['error']== 11){?><div class="clearboth">&nbsp;</div><div class="msg_error"><?php  echo $_SESSION['accionmsg']?></div><?php  }?>
</div>  
<div class="clearboth">&nbsp;</div>

<div id="OtroOrganismo">
    <div class="inputright">
        <label class="Label" for="OtroOrganismo">Nombre del organismo</label>
        <div class="input_font">
            <input type="text" name="OtroOrganismo" value="<?php  echo $OtroOrganismo?>" id="OtroOrganismo" size="20" maxlength="20" />
        </div>  
        <?php  if (isset($_SESSION['error']) && $_SESSION['error']== 12){?><div class="clearboth">&nbsp;</div><div class="msg_error"><?php  echo $_SESSION['accionmsg']?></div><?php  }?>
    </div>  
    <div class="clearboth">&nbsp;</div>
</div>
