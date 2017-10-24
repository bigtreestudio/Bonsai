<form action="<?php  echo $_SERVER['PHP_SELF']; ?>?<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree ($_SERVER['QUERY_STRING'],ENT_QUOTES); ?>" method="post" name="form_paginar">
<div class="paginador">
        <ul>
            <li>
					<?php 
						if ($paganterior<1)
							$paganterior=1;
						$querystring = $_SERVER['QUERY_STRING'];
						if ($querystring!="")
						{	
							$querystring=FuncionesPHPLocal::ArmarQueryString("pagina");
							$direccion = $_SERVER['PHP_SELF']."?".$querystring."&amp;pagina=".$paganterior;
						}else
							$direccion = $_SERVER['PHP_SELF']."?pagina=".$paganterior;
					
					?>
						<a href="<?php  echo $direccion?>" title="Pagina Anterior">
                        	&lt;
                        </a>
			</li>
			<li>
					<?php 
					
						
					for ($i=1;$i<$total_paginas + 1;$i++)
					{
						$querystring = $_SERVER['QUERY_STRING'];
						if ($querystring!="")
						{	
							$querystring=FuncionesPHPLocal::ArmarQueryString("pagina");
							$direccion = $_SERVER['PHP_SELF']."?".$querystring."&amp;pagina=".$i;
						}else
							$direccion = $_SERVER['PHP_SELF']."?pagina=".$i;
						$class = "";
						if (isset($_GET['pagina']) && $_GET['pagina']==$i)
							$class = "class='seleccionado'";
						elseif(!isset($_GET['pagina']) && $i==1)
							$class = "class='seleccionado'";
						
						
						?>
                            <a href="<?php  echo $direccion?>" <?php  echo $class?>  title="Pagina Siguiente">
                                <?php  echo $i?>
                            </a>
                        <?php  	
					}
					?>
			</li>

			<li>
					<?php  
						if ($pagsiguiente>$total_paginas)
							$pagsiguiente=$total_paginas;
						$querystring = $_SERVER['QUERY_STRING'];
						if ($querystring!="")
						{	
							$querystring=FuncionesPHPLocal::ArmarQueryString("pagina");
							$direccion = $_SERVER['PHP_SELF']."?".$querystring."&amp;pagina=".$pagsiguiente;
						}else
							$direccion = $_SERVER['PHP_SELF']."?pagina=".$pagsiguiente;
					
					?>
						<a href="<?php  echo $direccion?>" title="Pagina Siguiente">
                        	&gt;
                        </a>
			</li>
	</ul>
</div>    
</form>
