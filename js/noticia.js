
/* modal carousel */
$( document ).ready(function() {
	$('.row-modalcarousel > a').each(function(i) {
	  var $itemTemplate = $('<div class="item"> <img src="{img}" alt="{alt}"><div class="carousel-caption"><p>{caption}</p></div> </div>');
	  $itemTemplate.find('img').attr('src', $(this).attr('href')).attr('alt', $(this).attr('title'));
	  $itemTemplate.find('.carousel-caption p').text($(this).attr('title'));
	  if(i==0){
		$itemTemplate.addClass('active');
	  }
	  $('.modal-carousel .carousel-inner').append($itemTemplate);
	});
	$('#modalCarousel').carousel({ interval:false });
	$('.row-modalcarousel > a').click(function(event){
		var index = parseInt($(this).index());
		$('#carouselModal').modal('show');
		$('#modalCarousel').carousel(index);
		event.preventDefault();
	});
});