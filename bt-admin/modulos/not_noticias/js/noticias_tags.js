//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	AutocompleteTags();
});

function split( val ) {
            return val.split( /,\s*/ );
        }
function extractLast( term ) {
	return split( term ).pop();
}
		
var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');

function fnFormatResult(value, data, currentValue) {
        var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
        return value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>');
}		
function AutocompleteTags()
{
	jQuery("#noticiatags").autocomplete({
		minLength: 0,
		delay : 0,
		position: { my : "right top", at: "right bottom" },
		source: function(request, response) {
			jQuery.ajax({
			   url:      "not_noticias_tags_busqueda_autocomplete.php",
			   data:  {
						mode : "ajax",
						component : "1",
						searcharg : "titulo",
						task : "titulo",
						limit : 15,
						term : extractLast(request.term)
				},
			   dataType: "json",
			   success: function(data)  {
				 response(data);
			  }
 
			})
	   },
	   focus: function() {
			// prevent value inserted on focus
			return false;
	   }, 
	   select: function( event, ui ) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a>" + item.label + "(" + item.cantidad + ")</a>" )
				.appendTo( ul );
		};
}
