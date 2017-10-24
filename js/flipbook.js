/****************************** VARIABLES ******************************/
var factor = .1;
/****************************** VARIABLES ******************************/
var AnchoTotalFlip = 950;
var AltoTotalFlip = 680;

/****************************** FUNCIONES GENERALES ******************************/
function bookHeightCheck()
{
	if($('#book').height() > $(window).height()-80)
	{
		higherThanWindow = true;
	}
	else
	{
		higherThanWindow = false;
	}
	return higherThanWindow;
}

function calculate_zoom_factor(arg)
{
	if(arg == true)
	{
		// Default:
		zoom_factor = $('#page').height() * factor;
	}
	else
	{
		zoom_factor = default_book_height * factor;
	}
}


function close_overlay()
{
	$('.overlay').removeClass('active');
}

function isiPhone()
{
	return((navigator.platform.indexOf('iPhone') != -1) || (navigator.platform.indexOf('iPod') != -1));
}
/****************************** FUNCIONES GENERALES ******************************/
var Book = 
{
	// Arrows:
	arrows: function()
	{
		$('.nav_arrow.prev').click(function()
		{
			$('#book').turn('previous');
		});
		$('.nav_arrow.next').click(function()
		{
			$('#book').turn('next');
		});
	},

	// Book Grab:
	book_grab: function()
	{
		if($.browser.webkit)
		{
			$('#page').css('cursor', '-webkit-grab');
		}
		if($.browser.mozilla)
		{
			$('#page').css('cursor', '-moz-grab');
		}
		if($.browser.msie)
		{
			$('#page').css('cursor', 'url(https://mail.google.com/mail/images/2/openhand.cur)');
		}
	},
	// Book Grabbing:
	book_grabbing: function()
	{
		if($.browser.webkit)
		{
			$('#page').css('cursor', '-webkit-grabbing');
		}
		if($.browser.mozilla)
		{
			$('#page').css('cursor', '-moz-grabbing');
		}
		if ($.browser.msie)
		{
			$('#page').css('cursor', 'pointer');
		}
	},
	// Book Position:
	book_position: function()
	{
		book_height = $('#page').height();
		book_width = $('#page').width();
		half_height = (book_height/2)+30;
		half_width = book_width/2;
		$('#page').css({ left: '50%', top: '50%', margin: '-'+half_height+'px auto 0 -'+half_width+'px' });
	},
	// Drag:
	
	drag: function(e)
	{
		$el = $(this);
		$dragged = $el.addClass('draggable');
		$('#page').unbind('mousemove', Book.book_grab);
		$('#page').bind('mousemove', Book.book_grabbing);
		d_h = $dragged.outerHeight(),
		d_w = $dragged.outerWidth(),
		pos_y = $dragged.offset().top + d_h - e.pageY,
		pos_x = $dragged.offset().left + d_w - e.pageX;
		$dragged.parents().bind('mousemove', function(e)
		{
			$('.draggable').offset(
			{
				top:e.pageY + pos_y - d_h,
				left:e.pageX + pos_x - d_w
			});
		});
		e.preventDefault();
	},
	// Drop:
	drop: function()
	{
		Book.book_grab();
		$('#page').bind('mousemove', Book.book_grab);
		$('#page').removeClass('draggable');
	},
	// Drag & Drop Init:
	dragdrop_init: function()
	{
		bookHeightCheck();
		if (higherThanWindow == false)
		{
			$('#page').unbind('mousedown', Book.drag);
			$('#page').unbind('mouseup', Book.drop);
			$('#page').unbind('mousemove', Book.book_grab);
			$('#page').unbind('mousemove', Book.book_grabbing);
			$('#page').css('cursor', 'default');
		}
		else
		{
			$('#page').bind('mousedown', Book.drag);
			$('#page').bind('mouseup', Book.drop);
			$('#page').bind('mousemove', Book.book_grab);
		}
	},
	// Init:
	init: function()
	{
		default_book_width = AnchoTotalFlip;
		default_book_height = AltoTotalFlip;
		default_page_width = AnchoTotalFlip;
		default_page_height = AltoTotalFlip;
		window_height = $(window).height();
		window_width = $(window).width();
		zoom_steps = 6;
		current_zoom_step = 0;
		dbl_clicked = false;
		on_start = true;
		self = this;
		$('#book').turn(
		{
			display: 'double',
			acceleration: true,
			gradients: !$.isTouch,
			elevation:50,
			pages:cantPaginas,
			autoCenter: false,
			when:
			{
				first: function(e, page)
				{
					$('.nav_arrow.prev').hide();
				},
				turned: function(e, page)
				{
					if(page > 1)
					{
						$('.nav_arrow.prev').fadeIn();
					}
					if(page < $(this).turn('pages'))
					{
						$('.nav_arrow.next').fadeIn();
					}
					if (cantPaginasCarga<cantPaginas)
					{
						Book.addPage(cantPaginasCarga+1, $(this));
						cantPaginasCarga++;
					}
					if ((cantPaginasCarga)<cantPaginas)
					{
						Book.addPage(cantPaginasCarga+1, $(this));
						cantPaginasCarga++;
					}
					
				},
				turning: function(e, page)
				{

				},
				last: function(e, page)
				{
					$('.nav_arrow.next').hide();
				}
			}
		});
		Book.arrows();
	},
	
	addPage: function(page, book)
	{
		if (!book.turn('hasPage', page)) 
		{
			// Create an element for this page
			var element = $('<div/>').html('<div class="loadingTab">Cargando...</div>');
			// Add the page
			book.turn('addPage', element, page);
			// Get the data for this page	
			$.ajax({url: $("#magazineurlsig").val()+page}).done(function(data) {
					element.html(data);
					if(page<cantPaginas)
					{
						$('.nav_arrow.next').fadeIn();
					}

			});
	   	}		
		
	},
	
	// Scale Horizontal:
	scaleHorizontal: function()
	{
		new_width = $(window).width()-100;
		ratio = new_width / $('#page').width();
		new_height = $('#page').height() * ratio;
		$('#page').css({ width: new_width, height: new_height });
		$('#book').turn('size', new_width, new_height);
	},
	// Scale Start:
	scaleStart: function()
	{
		if(on_start == true)
		{
			bookHeightCheck();
			if( higherThanWindow == true)
			{
				Book.scaleVertical();
				if($('#page').width() > $(window).width())
				{
					Book.scaleHorizontal();
				}
			}
			else
			{
				Book.scaleHorizontal();
			}
			on_start = false;
		}
	},
	// Scale Vertical:
	scaleVertical: function()
	{
		new_height = $(window).height() - 116;
		ratio = new_height / $('#page').height();
		new_width = $('#page').width() * ratio;
		$('#page').css({ width: new_width, height: new_height });
		$('#book').turn('size', new_width, new_height);
	},
	// Zoom Auto:
	zoom_auto: function()
	{
		dbl_clicked = false;
		current_zoom_step = 0;
		calculate_zoom_factor(true);
		screen_height = $(window).height();
		book_width = $('#book').width();
		screen_width = $(window).width()-100;
		book_height = $('#book').height();
		if(isiPhone())
		{
			var new_height = screen_height - 100;
			var ratio = new_height / book_height;
			var new_width = book_width * ratio;
			$('#page').css({ width: new_width, height: new_height });
			$('#book').turn('size', new_width, new_height);
		}
		else
		{
			Book.scaleStart();
			current_window_width = $(window).width();
			current_window_height = $(window).height();
			if(current_window_width != window_width)
			{
				if($('#page').height() < ($(window).height() - 96))
				{
					Book.scaleVertical();
				}
				if($('#page').width() > ($(window).width() - 100))
				{
					Book.scaleHorizontal();
				}
			}
			
			if(current_window_height != window_height)
			{
				if($('#page').width() < ($(window).width() - 100))
				{
					Book.scaleVertical();
				}
				
				if($('#page').height() > ($(window).height() - 96))
				{
					Book.scaleVertical();
				}
			}
			if(($(window).width() > AnchoTotalFlip) && ($(window).height() > AltoTotalFlip))
			{
				$('#page').css({ width: AnchoTotalFlip, height: AltoTotalFlip });
				$('#book').turn('size', AnchoTotalFlip, AltoTotalFlip);
			}
		}
	},
	// Zoom In:
	zoom_in: function(dbl)
	{
		if(dbl_clicked == false)
		{
			if(dbl == true)
			{
				zoom_factor = $('#book').height() * (factor*3);
			}
			current_zoom_step ++;
			book_height = $('#book').height();
			book_width = $('#book').width();
			new_height = book_height + zoom_factor;
			ratio = new_height / book_height;
			new_width = book_width * ratio;
			$('#page').css({ width: new_width, height: new_height });
			$('#book').turn('size', new_width, new_height);
			Book.dragdrop_init();
		}
	},
	// Zoom Out:
	zoom_out: function()
	{
		if(dbl_clicked == false)
		{
			current_zoom_step --;
			book_height = $('#book').height();
			book_width = $('#book').width();
			new_height = book_height - zoom_factor;
			ratio = new_height / book_height;
			new_width = book_width * ratio;
			$('#page').css({ width: new_width, height: new_height });
			$('#book').turn('size', new_width, new_height);
			Book.dragdrop_init();
		}
		else
		{
			Book.zoom_auto();
		}
	}
}
/****************************** FUNCIONES - BOOK ******************************/


/****************************** FUNCIONES - NAVIGATION ******************************/
var Navigation = 
{
	// Init:
	init: function()
	{
		self = this;
		// Double Click:
		$('#page').dblclick(function()
		{
			current_zoom_step = 0;
			if(dbl_clicked == true)
			{
				$('#page').css('cursor', 'default');
				Book.zoom_auto();
				Book.dragdrop_init();
				dbl_clicked = false;
				calculate_zoom_factor(true);
			}
			else
			{
				Book.book_grab();
				Book.zoom_auto();
				Book.zoom_in(true);
				dbl_clicked = true;
			}
			Book.book_position();
		});
		// Home:
		$('nav .home').click(function()
		{
			$('#book').turn('page', 1);
		});
		// Zoom Original:
		$('nav .zoom_original').click(function()
		{
			current_zoom_step = 0;
			$('#page').css({ width: default_page_width, height: default_page_height });
			$('#book').turn('size', default_book_width, default_book_height);
			Book.book_position();
			Book.dragdrop_init();
		});
		// Zoom Auto:
		$('nav .zoom_auto').click(function()
		{
			Book.zoom_auto();
			Book.book_position();
			Book.dragdrop_init();
		});
		// Zoom In:
		$('nav .zoom_in').click(function()
		{
			if(current_zoom_step < zoom_steps)
			{
				Book.zoom_in();
				Book.book_position();
			}
		});
		// Zoom Out:
		$('nav .zoom_out').click(function()
		{
			if(current_zoom_step > -zoom_steps)
			{
				Book.zoom_out();
				Book.book_position();
			}
		});

	}
}
/****************************** FUNCIONES - NAVIGATION ******************************/


/****************************** INICIO ******************************/
// Keydown:
$(window).bind('keydown', function(e)
{
	if(e.keyCode == 37)
	{
		$('#book').turn('previous');
	}
	else if(e.keyCode == 39)
	{
		$('#book').turn('next');
	}
});
// Load:
$(window).load(function()
{
	Book.init();
	if(!isiPhone())
	{
		Book.zoom_auto();
		Book.book_position();
		Book.dragdrop_init();
	}
	Navigation.init();
	calculate_zoom_factor();
});
// Resize:
$(window).resize(function()
{
	if(!isiPhone())
	{
		Book.book_position();
		Book.zoom_auto();
		Book.dragdrop_init();
	}
	calculate_zoom_factor();
});
// Resize Detect:
function resizeDetect()
{
	var rtime = new Date(1, 1, 1, 1,00,00);
	var timeout = false;
	var delta = 200;
	$(window).resize(function()
	{
		rtime = new Date();
		if(timeout === false)
		{
			timeout = true;
			setTimeout(resizeend, delta);
		}
	});
	function resizeend()
	{
		if(new Date() - rtime < delta)
		{
			setTimeout(resizeend, delta);
		}
		else
		{
			timeout = false;
			window_width = $(window).width();
			window_height = $(window).height();
			if($(window).width() > $(window).height())
			{
				//Book.scaleVertical();
			}
			else
			{
			}
		}
	}
}
resizeDetect();
/****************************** INICIO ******************************/

/*
 * $ Easing v1.3 - http://gsgd.co.uk/sandbox/$/easing/
 *
 * Uses the built in easing capabilities added In $ 1.1
 * to offer multiple easing options
*/
$.easing["jswing"]=$.easing["swing"];$.extend($.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return $.easing[$.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;if(!g)g=e*.3*1.5;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;if((b/=e/2)<1)return d/2*b*b*(((f*=1.525)+1)*b-f)+c;return d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-$.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){if((b/=e)<1/2.75){return d*7.5625*b*b+c}else if(b<2/2.75){return d*(7.5625*(b-=1.5/2.75)*b+.75)+c}else if(b<2.5/2.75){return d*(7.5625*(b-=2.25/2.75)*b+.9375)+c}else{return d*(7.5625*(b-=2.625/2.75)*b+.984375)+c}},easeInOutBounce:function(a,b,c,d,e){if(b<e/2)return $.easing.easeInBounce(a,b*2,0,d,e)*.5+c;return $.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}})