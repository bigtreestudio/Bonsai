<?php  
ob_start();
include("./config/include.php");
include(DIR_CLASES."cNoticias.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

$oEncabezados->setTitle("Agenda Digital Argentina");
$oEncabezados->setOgTitle("Agenda Digital Argentina");
$oEncabezados->EncabezadoMenuEmergente();

?>
    <link href="/js/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="/js/fullcalendar/fullcalendar.js"></script>
    <script type="text/javascript" src="/js/fullcalendar/fullcalendar-es.js"></script>
    <script type="text/javascript" src="/js/fullcalendar/gcal.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: { left: 'prev,next today', center: 'title', right: 'agendaDay,agendaWeek,month'},
            allDaySlot: false,
            editable: false,
            draggable:false,
            disableResizing:false,
            selectable: false,
            selectHelper: false,
            monthNames: monthNames, 
            allDayText : TextoTodoElDia,
            monthNamesShort: monthNamesShort,
            dayNames: dayNames,
            dayNamesShort: dayNamesShort,
            buttonText: buttonText,
            axisFormat: 'HH:mm',
            timeFormat: 'H:mm{ - H:mm}',
            eventSources: [
            {
                url: 'https://www.google.com/calendar/feeds/lninhlu48a6kh7djcd9eha4h20%40group.calendar.google.com/public/full', // use the `url` property
                color: '#1281af',    // an option!
                textColor: '#FFFFFF',  // an option!
                className: "txteventos"
            }],	
            eventClick: function(event) {
                window.open(event.url, 'gcalevent', 'width=700,height=600');
                return false;
            }
        });	
    });
    </script>
    
    <div id="calendar" style="background-color:#FBFBFB; padding:30px; font-size:14px !important;"></div>
<?php  
$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>