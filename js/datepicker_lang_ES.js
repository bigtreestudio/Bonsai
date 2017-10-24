jQuery(function($){
   $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
      weekHeader: 'Sm',
      dateFormat: 'mm/dd/yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
}); 


var i18n = $.extend({}, i18n || {}, {
    datepicker: {
        dateformat: {
            "fulldayvalue": "d/M/yyyy",
            "separator": "/",
            "year_index": 2,
            "month_index": 0,
            "day_index": 1,
            "sun": "Dom",
            "mon": "Lun",
            "tue": "Mar",
            "wed": "Mie",
            "thu": "Jue",
            "fri": "Vie",
            "sat": "Sab",
            "jan": "Ene",
            "feb": "Feb",
            "mar": "Mar",
            "apr": "Abt",
            "may": "May",
            "jun": "Jun",
            "jul": "Jul",
            "aug": "Ago",
            "sep": "Sep",
            "oct": "Oct",
            "nov": "Nov",
            "dec": "Dic",
            "postfix": ""
        },
        ok: " Ok ",
        cancel: "Cancelar",
        today: "Hoy",
        prev_month_title: "anterior mes",
        next_month_title: "siguiente mes"
    }
});

