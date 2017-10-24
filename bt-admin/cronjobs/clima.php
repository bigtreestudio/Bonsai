<?php 

//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);

include('../Librerias/cWEATHER.php');

$records = cWEATHER::getWeatherInfo();
$id = 1;

$xml = '<?php xml version="1.0" encoding="UTF-8"?>'."\n"
        . '<Ciudades>'."\n";

foreach ($records as $record)
{
	$xml .= "\t".'<Ciudad Id="'.$id.'">'."\n"
	      . "\t\t".'<Nombre><![CDATA['.$record['provincia'].']]></Nombre>'."\n"
	      . "\t\t".'<Temperatura><![CDATA['.$record['temperatura'].'&deg;C'.']]></Temperatura>'."\n"
	      . "\t\t".'<Humedad><![CDATA['.$record['humedad'].'%'.']]></Humedad>'."\n"
	      . "\t\t".'<TemperaturaAlta><![CDATA['.$record['temperatura_maxima'].'&deg;C'.']]></TemperaturaAlta>'."\n"
	      . "\t\t".'<TemperaturaBaja><![CDATA['.$record['temperatura_minima'].'&deg;C'.']]></TemperaturaBaja>'."\n"
	      . "\t\t".'<Imagen>'.cWEATHER::getWeatherIcon($record['icono']).'</Imagen>'."\n"
		  . "\t\t".'<Maniana><![CDATA['.$record['texto_maniana'].']]></Maniana>'."\n"
		  . "\t\t".'<Tarde><![CDATA['.$record['texto_tarde'].']]></Tarde>'."\n"
		  . "\t\t".'<Imagen_maniana>'.cWEATHER::getWeatherIcon($record['icono_maniana']).'</Imagen_maniana>'."\n"
		  . "\t\t".'<Imagen_tarde>'.cWEATHER::getWeatherIcon($record['icono_tarde']).'</Imagen_tarde>'."\n"
		  . "\t\t".'<Fenomenos><![CDATA['.$record['fenomenos'].']]></Fenomenos>'."\n"
		  . "\t\t".'<Visibilidad><![CDATA['.$record['visibilidad'].' Km]]></Visibilidad>'."\n"
		  . "\t\t".'<Sensacion><![CDATA['.$record['sensacion_termica'].']]></Sensacion>'."\n"
		  . "\t\t".'<Viento><![CDATA['.$record['viento'].' km/h]]></Viento>'."\n"
		  . "\t\t".'<Presion><![CDATA['.$record['presion'].' hPa]]></Presion>'."\n"
		  . "\t\t".'<Presion><![CDATA['.$record['presion'].']]></Presion>'."\n"
		  . "\t\t".'<Hora><![CDATA['.$record['hora'].' hs]]></Hora>'."\n"
	      . "\t".'</Ciudad>'."\n";
	$id++;
}

$xml .= '</Ciudades>';

//echo $xml;
file_put_contents(cWEATHER::XML_FILE_PATH, $xml);

exit(0);

?>
