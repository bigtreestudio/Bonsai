/*
	Global values
*/

    function initMenu() {
		$('#main_menu ul').hide();
		$('#main_menu li a').click(
			function() {
				var checkElement = $(this).next();
				if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
					$('#main_menu ul:visible').slideUp('normal');
					return false;
				}
				if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
					$('#main_menu ul:visible').slideUp('normal');
					checkElement.slideDown('normal');
					return false;
				}
			}
		);
    }
	
	
	function SeleccionarGrupoModulo()
	{
		$('#grupo_modulo_'+grupomodulo+" ul").slideDown(0);
	}
	
    $(document).ready(function() {
		
		initMenu();
		SeleccionarGrupoModulo();
	});

