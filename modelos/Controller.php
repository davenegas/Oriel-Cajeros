<?php

//Definición de la clase Controller. Componente principal de la lógica del negocio. 
class Controller{
     
    //Declaración de métodos que envuelven toda la funcionalidad del sistema
    // A través del componente index se llaman cada uno de los eventos de la clase 
    // controller para que sean ejecutados según sea necesario.
     
    //Inicio del sitio web, llamada a la pantalla principal para inicio de sesión
    public function inicio(){
        if(isset($_SESSION['nombre'])){
            //echo "sesion abierta";
            require __DIR__ . '/../vistas/plantillas/frm_principal.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
            //require __DIR__ . '/../vistas/plantillas/inicio_sesion.php';
        }
    }
    
    ////////////////////////////////////////////////////////////////////////////
    //////////////////BITACORA DIGITAL CAJEROS//////////////////////////////////
    
    public function bitacora_digital_cajeros(){
        if(isset($_SESSION['nombre'])){
            //echo "sesion abierta";
            require __DIR__ . '/../vistas/plantillas/frm_principal.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
        } 
    }
    
    //////////////////////////////TIPO EVENTO///////////////////////////////////
    public function tipo_evento_listar(){
        if(isset($_SESSION['nombre'])){
            $obj_tipo_evento = new cls_tipo_evento();
            
            $obj_tipo_evento->obtener_tipo_evento();
            $params = $obj_tipo_evento->getArreglo();
            
            require __DIR__ . '/../vistas/plantillas/frm_tipo_evento_listar.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
        } 
    }
    
    public function tipo_evento_guardar(){
        if(isset($_SESSION['nombre'])){
            $obj_tipo_evento = new cls_tipo_evento();
            
            $obj_tipo_evento->setTipo_evento($_POST['tipo_evento']);
            $obj_tipo_evento->setObservaciones($_POST['observaciones']); 
            $obj_tipo_evento->setEstado($_POST['estado']);

            if ($_POST['ID_Tipo_Evento']==0){
                $obj_tipo_evento->agregar_tipo_evento();
            }else{
                $obj_tipo_evento->setCondicion("ID_Tipo_Evento='".$_POST['ID_Tipo_Evento']."'");
                $obj_tipo_evento->edita_tipo_evento();
            }       
            //Obtiene información de las tipos de eventos guardados
            $obj_tipo_evento->setCondicion("");
            $obj_tipo_evento->obtener_tipo_evento();
            $params = $obj_tipo_evento->getArreglo();
            
            require __DIR__ . '/../vistas/plantillas/frm_tipo_evento_listar.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
        }
    }
}