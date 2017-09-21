<?php
session_start();

require_once __DIR__ . '/modelos/Data_Provider.php';
require_once __DIR__ . '/modelos/Encrypter.php';
require_once __DIR__ . '/controladores/cls_tipo_evento.php';
require_once __DIR__ . '/controladores/cls_evento.php';
require_once __DIR__ . '/controladores/cls_datos_gerencia_seguridad.php';
require_once __DIR__ . '/modelos/Controller.php';

// enrutamiento, se enlistan cada una de las funcionalidades del controller entre comillas, tanto al inicio como al final de la línea (deben de coincidir)
$map = array(
    //Controlador General
    'inicio' => array('controller' =>'Controller', 'action' =>'inicio'),
    'listar' => array('controller' =>'Controller', 'action' =>'listar'),
    
    //Controlador Tipo eventos
    'tipo_evento_listar' => array('controller' =>'Controller', 'action' =>'tipo_evento_listar'),
    'tipo_evento_guardar' => array('controller' =>'Controller', 'action' =>'tipo_evento_guardar'),
    
    //Controlador bitácora Cajeros
    'bitacora_digital_cajeros' => array('controller' =>'Controller', 'action' =>'bitacora_digital_cajeros'),
    'guardar_evento_cajero' => array('controller' =>'Controller', 'action' =>'guardar_evento_cajero'),
    'dibuja_tabla_eventos_relacionados_a_punto_bcr' => array('controller' =>'Controller', 'action' =>'dibuja_tabla_eventos_relacionados_a_punto_bcr'),
    'dibuja_tabla_seguimiento_evento' => array('controller' =>'Controller', 'action' =>'dibuja_tabla_seguimiento_evento'),
    'eventos_cajeros_recuperar' => array('controller' =>'Controller', 'action' =>'eventos_cajeros_recuperar'),
    'guardar_seguimiento_evento' => array('controller' =>'Controller', 'action' =>'guardar_seguimiento_evento'),
    'evento_cajero_editar' => array('controller' =>'Controller', 'action' =>'evento_cajero_editar'),
    'eventos_cajeros_cerrados' => array('controller' =>'Controller', 'action' =>'eventos_cajeros_cerrados'),
    'actualiza_en_vivo_reporte_cerrados' => array('controller' =>'Controller', 'action' =>'actualiza_en_vivo_reporte_cerrados'),
    'actualiza_en_vivo_punto_bcr' => array('controller' =>'Controller', 'action' =>'actualiza_en_vivo_punto_bcr'),
    'alerta_en_vivo_mismo_punto_bcr_y_evento' => array('controller' =>'Controller', 'action' =>'alerta_en_vivo_mismo_punto_bcr_y_evento'),
    'evento_agregar' => array('controller' =>'Controller', 'action' =>'evento_agregar')
);
 

if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {
        header('Status: 404 Not Found');
        echo '<html><body><h1>Error 404: No existe la ruta <i>' .
                $_GET['ctl'] .
                '</p></body></html>';
        exit;
    }
} else {
    $ruta = 'inicio';
}

// Una vez verificado lo que se ocupa mostrar en pantalla, asigna la posición del vector correspondiente a la variable controlador
// Esto para llamar al evento respectivo.
$controlador = $map[$ruta];
// Ejecución del controlador asociado a la ruta

/*
 * Utlizando la verificacion reservada de PHP (method_exists), valida que el metodo que se ocupa llamar exista en el 
 * componente Controller.php, si es así lo ejecuta por medio de la funcionalidad reservada de PHP llamada call_user_func
 * de lo contrario mostrará un mensaje de error en pantalla.
 */

if (method_exists($controlador['controller'],$controlador['action'])) {
    call_user_func(array(new $controlador['controller'], $controlador['action']));
} else {
    //Muestra error en pantalla para indicar que no encontró el elemento
    header('Status: 404 Not Found');
    //Arma el mensaje de error, mostrando posteriormente en pantalla.
    echo '<html><body><h1>Error 404: El controlador <i>' .
            $controlador['controller'] .
            '->' .
            $controlador['action'] .
            '</i> no existe</h1></body></html>';
}