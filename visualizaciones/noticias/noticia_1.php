<?php  


?>
<nav role="navigation">
          <ol class="breadcrumb">
            <li><a href="/">Inicio</a></li>
            <li><a href="<? echo $datosNoticia["catdominio"]?>"><? echo utf8_decode($datosNoticia["catnom"])?></a></li>
            <li class="active"><? echo utf8_decode($datosNoticia["noticiatitulo"])?></li>
          </ol>
</nav>
<div class="row">
          <article class="col-md-8">
            <header>
              <h1><? echo utf8_decode($datosNoticia["noticiatitulo"])?></h1>
              <p class="lead"><? echo utf8_decode($datosNoticia["noticiacopete"])?></p>
              <div class="article-social col-md-6 col-sm-12 col-xs-12">
                <ul class="mini-social">
                  <li><a class="social-fb" href="http://www.facebook.com/sharer.php?u=<? echo DOMINIOGENERAL.$datosNoticia["catdominio"]."/".$datosNoticia["noticiaurl"]?>" target="_blank" title="Compartir en Facebook"></a></li>
                  <li><a class="social-tw" href="http://twitter.com/share" data-url="<? echo DOMINIOGENERAL.$datosNoticia["catdominio"]."/".$datosNoticia["noticiaurl"]?>" data-count="horizontal" data-via="GCBA" data-lang="es" target="_blank" title="Compartir en Twitter"></a></li>
                  <li><a class="social-gp" href="https://plus.google.com/share?url=<? echo DOMINIOGENERAL.$datosNoticia["catdominio"]."/".$datosNoticia["noticiaurl"]?>" target="_blank" title="Compartir en Google+"></a></li>
                  <? /*<li class="social-fav"><a href="#" title="Guardar página en mis favoritos"><span class="glyphicon glyphicon-heart"></span></a></li>*/?>
                </ul>
                <? /*<span class="social-shares">Compartido <strong>24</strong> veces</span>*/?>
             </div>
             <div class="col-md-6 col-sm-12 col-xs-12 pagina_fecha">
                <time pubdate datetime="2014-09-12"><? echo FuncionesPHPLocal::ReemplazarTextoFechas(date("l",strtotime($datosNoticia["noticiafecha"])))." ".date("d",strtotime($datosNoticia["noticiafecha"]))." de ".FuncionesPHPLocal::ReemplazarTextoFechas(date("F",strtotime($datosNoticia["noticiafecha"])))." de ".FuncionesPHPLocal::ReemplazarTextoFechas(date("Y",strtotime($datosNoticia["noticiafecha"]))) ?></time>
             </div>
             <div class="clearboth">&nbsp;</div>
            </header>
            <section>
             <? echo $datosNoticia["noticiacuerpoprocesado"]?>
            </section>
            <? /*
            <footer>
              <section class="list-group list-group-content">
                <h2>Últimas noticias del área</h2>
                <div class="row row-list-pagina">
                  <div class="col-md-6 col-sm-12">
                    <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                      <div style="background-image:url(../docs/carousel1.jpg);"></div>
                      <h4>Parque de los Patricios: Tu barrio y vos</h4>
                      <p>Encuentro con los vecinos de Parque Patricios.</p>
                    </a>
                    <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                      <div style="background-image:url(../docs/jumbo5.jpg);"></div>
                      <h4>Arroyo Maldonado: Consultas Públicas</h4>
                      <p>Charlamos con los vecinos sobre las obras de los ramales secundarios.</p>
                    </a>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                      <div style="background-image:url(../docs/jumbo4.jpg);"></div>
                      <h4>Parque de los Patricios: Tu barrio y vos</h4>
                      <p>Encuentro con los vecinos de Parque Patricios.</p>
                    </a>
                    <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                      <div style="background-image:url(../docs/carousel1.jpg);"></div>
                      <h4>Arroyo Maldonado: Consultas Públicas</h4>
                      <p>Charlamos con los vecinos sobre las obras de los ramales secundarios.</p>
                    </a>
                  </div>
                </div>
                <div class="row row-btn">
                  <button class="btn btn-default btn-lg btn-block">Ver todas las notas</button>
                </div>
              </section>
            </footer>
			*/ ?>
          </article>
          <aside>
            <div class="col-md-4 col-sm-12">
              
            	<? if (count($imagenes)>0){?>
                    <section>
                        <h2>Im&aacute;genes</h2>
                        <div class="row row-modalcarousel">
                          <? foreach($imagenes as $imagen){
							  
                              $imgurl=DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(960, 640, $imagen["url"]);
                              $imgthumb=DOMINIO_SERVIDOR_MULTIMEDIA.$oMultimedia->GetImagenStatic(155, 103, $imagen["url"], true,1);
                            //echo  $imgthumb;					  
                              ?>
                              <a class="col-xs-6" href="<? echo $imgurl?>" title="<? echo utf8_decode($imagen["titulo"])?>">
                                <img class="img-responsive thumbnail" src="<? echo $imgthumb?>">
                              </a>
                          <? }?>
                        </div>
                        
                        <div class="modal modal-carousel" id="carouselModal" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <button class="close" type="button" data-dismiss="modal">×</button>
                              <div id="modalCarousel" class="carousel slide">
                                <div class="carousel-inner" role="listbox"></div>
                                <a class="left carousel-control" href="#modalCarousel" role="button" data-slide="prev">
                                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                  <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#modalCarousel" role="button" data-slide="next">
                                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                  <span class="sr-only">Next</span>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                    </section>
              <? }?>
              
              <? if (count($archivos)>0){?>
                  <section>
                    <h2>Descargas</h2>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Archivo</th>
                          <th>Descargar</th>
                        </tr>
                      </thead>
                      <tbody>
                      	<? 
						
						foreach($archivos as $archivo){?>
                            <tr>
                              <td><? echo $archivo["nombre_archivo"]?></td>
                              <td>
                              	<a href="<? echo DOMINIO_SERVIDOR_MULTIMEDIA.$archivo["url"]?>" class="btn btn-blue btn-block btn-sm" target="_blank" download=""><span class="glyphicon glyphicon-arrow-down"></span></a>
                              </td>
                            </tr>
                        <? }?>
                      </tbody>
                    </table>
                  </section>
              <? }?>
              <? /*
              <section class="pagina-enlaces">
                <h2>Enlaces</h2>
                <div class="list-group list-group-content list-group-multi">
                  <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                    <h4>Consultá tus infracciones</h4>
                    <p>Toda la información que necesitás para salir del paso.</p>
                  </a>
                  <a href="#" class="list-group-item list-thumb" data-original-title="" title="">
                    <h4>Ministerio de Cultura - BAmúsica</h4>
                    <p>Encuentro con los vecinos de Parque Patricios.</p>
                  </a>
                </div>
              </section>*/ ?>
            </div>
          </aside>
        </div>