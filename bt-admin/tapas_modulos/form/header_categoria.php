<?php  

//print_r($vars);

FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA));

$oCategorias = new cCategorias($vars['conexion']);
if(!$oCategorias->ArmarArbolCategorias("",$arbol))
	die();


function CargarCategorias($arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option value="<?php  echo $fila['catcod']?>"><?php  echo $nivel. FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php  
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}


if (isset($vars['zonamodulocod']))
{
	
	$objDataModel = json_decode($vars['modulodata']);
}
?>
<div style="text-align:left;">
    <div class="caja_agenda">
        <h3>Categorias</h3>
        <div style="margin-left:250px; margin-top:15px; ">
         <h2 style=" float:left;" >Categoria:&nbsp;</h2>
        <select name="catcod" id="catcod"  onchange="doSearch(arguments[0]||event)" style="float:left; padding-left:5px;">
           
            <option value="">Todas</option>
        <?php 
            foreach($arbol as $fila)
            {
                ?>
                <option value="<?php  echo $fila['catcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                <?php  
                if (isset($fila['subarbol']))
                {
                    $nivel = "---";
                    CargarCategorias($fila['subarbol'],$nivel);
                }
            }
            ?>
         </select>

        </div>
    </div>    
    
    <div style="clear:both">&nbsp;</div>

     <div class="menucarga" style="text-align:right">
        <ul>
            <li>
                <a href="javascript:void(0)" onclick="saveModulo()">Guardar y Cerrar</a>
            </li>
        </ul>
    </div>
  </div>
    

<script type="text/javascript">
	function Validar()
	{
		if($("#catcod").val()=='')
		{
			alert("Debe seleccionar una categoria");
			
		}else{
			saveModulo();
		}
		return true;
	}
	
	function ValidarAgregar()
	{
		if($("#catcod").val()=='')
		{
			alert("Debe seleccionar una Categoria");
			
		}else{
			agregarModulo();
		}
		return true;
	}


</script>
