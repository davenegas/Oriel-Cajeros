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
            $this->volver_oriel();
            //header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
            //require __DIR__ . '/../vistas/plantillas/inicio_sesion.php';
        }
    }
    
    public function volver_oriel(){
        try {
            header ("10.170.5.92:8080/ORIEL/index.php?ctl=iniciar_sesion");
        } catch (Exception $e){
            header ("location:/ORIEL/index.php?ctl=iniciar_sesion");
        }
    }
    ////////////////////////////////////////////////////////////////////////////
    //////////////////BITACORA DIGITAL CAJEROS//////////////////////////////////
    
    public function bitacora_digital_cajeros(){
        if(isset($_SESSION['nombre'])){
            $obj_eventos = new cls_evento();
            
            $obj_eventos->setCondicion("T_EventoCajero.ID_Estado_Evento<>3 AND T_EventoCajero.ID_Estado_Evento<>5");
            $obj_eventos->obtiene_todos_los_eventos();
            $params = $obj_eventos->getArreglo();
            
            //Saca el tamaño del vector de registros 
            $tam=count($params);
            $todos_los_seguimientos_juntos[]="";
            //verifica que hayan eventos devueltos en la consulta.
            if (count($params)>0){
                //Empieza a recorrer registro por registro
                for ($i = 0; $i <$tam; $i++) {
                    $obj_eventos->setId($params[$i]['ID_Evento']);

                    //Criterio de busqueda que permite traer todos los seguimientos del evento en cuestion
                    $obj_eventos->setCondicion("T_DetalleEvento.ID_Evento=".$params[$i]['ID_Evento']." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc");
                    //Obtiene los seguimientos del evento seleccionado, si los hubiere
                    $obj_eventos->obtiene_detalle_evento();

                    //Verifica que hayan seguimientos
                    if(count($obj_eventos->getArreglo())>0){
                        //Construye el vector de seguimientos asociados al evento que se está analizando.
                        if ($i==0){
                            $todos_los_seguimientos_juntos=$obj_eventos->getArreglo();
                        }else{
                            print_r($todos_los_seguimientos_juntos);
                            $todos_los_seguimientos_juntos = array_merge($todos_los_seguimientos_juntos,$obj_eventos->getArreglo());                 
                        }
                    }

                    //Trae el seguimiento asociado a un evento en especifico, solo el mas viejo, lo cual permite determinar quien hizo el ultimo seguimiento en el evento.
                    $obj_eventos->setCondicion("T_DetalleEvento.ID_Evento=".$params[$i]['ID_Evento']." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc limit 0,1");
                    //Obtiene los seguimientos del evento seleccionado, si los hubiere
                    $obj_eventos->obtiene_detalle_evento();
                    //asigna el resultado de la consulta aun objeto de tipo arreglo
                    $ultimo_seguimiento_asociado= $obj_eventos->getArreglo();
                    //Verifica si existen seguimientos asociados al evento actual
                    if(count($ultimo_seguimiento_asociado)>0){
                        //Arma el vector de seguimientos asociados a un evento en específico
                        if ($i==0){
                            //Arma el vector con el detalle y el ultimo usuario que registro un seguimiento en el evento de bitacora
                            $detalle_y_ultimo_usuario= array(array('Detalle'=>"Último seguimiento ingresado-->Fecha: ".date_format(date_create($ultimo_seguimiento_asociado[0]['Fecha']), 'd/m/Y').".Hora: ".$ultimo_seguimiento_asociado[0]['Hora'].". ".$ultimo_seguimiento_asociado[0]['Detalle'],'Usuario'=>$ultimo_seguimiento_asociado[0]['Nombre_Usuario']." ".$ultimo_seguimiento_asociado[0]['Apellido']));
                        }else{
                            //Concatena al vector la nueva linea de información del seguimiento.
                            $detalle_y_ultimo_usuario = array_merge($detalle_y_ultimo_usuario,array(array('Detalle'=>"Último seguimiento ingresado-->Fecha: ".date_format(date_create($ultimo_seguimiento_asociado[0]['Fecha']), 'd/m/Y').".Hora: ".$ultimo_seguimiento_asociado[0]['Hora'].". ".$ultimo_seguimiento_asociado[0]['Detalle'],'Usuario'=>$ultimo_seguimiento_asociado[0]['Nombre_Usuario']." ".$ultimo_seguimiento_asociado[0]['Apellido'])));  
                        }
                    }else{
                        //En caso de que no hayan seguimientos asociados, procede a registrar las validación correspondiente.
                        if ($i==0){
                            //Con el primer elemento del vector, utiliza esta linea de codigo
                            $detalle_y_ultimo_usuario= array(array('Detalle'=>"No hay seguimientos asociados a este evento. Para agregar uno oprima el link:'Gestionar Seguimiento de la fila respectiva.'",'Usuario'=>$params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido']));
                        }else{
                            //Con el resto de lineas del vector, usa esta otra programación.
                            $detalle_y_ultimo_usuario = array_merge($detalle_y_ultimo_usuario,array(array('Detalle'=>"No hay seguimientos asociados a este evento. Para agregar uno oprima el link:'Gestionar Seguimiento de la fila respectiva.'",'Usuario'=>$params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido'])));
                        }
                    }
                }
            } 
            
            require __DIR__ . '/../vistas/plantillas/frm_eventos_cajeros_lista.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            $this->volver_oriel();
        } 
    }
    
    public function evento_agregar(){
        if(isset($_SESSION['nombre'])){
            $obj_eventos = new cls_evento();
            $obj_tipo_evento = new cls_tipo_evento();
            $obj_gerencia_seguridad = new cls_datos_gerencia_seguridad();
            
            //Obtiene los tipos de eventos activos
            $obj_tipo_evento->setCondicion("Estado=1");
            $obj_tipo_evento->obtener_tipo_evento();
            $lista_tipos_de_eventos= $obj_tipo_evento->getArreglo();
            
            //Obtiene las provincias de bd_gerencia_seguridad
            $obj_gerencia_seguridad->setCondicion("Estado=1");
            $obj_gerencia_seguridad->obtener_todas_las_provincias();
            $lista_provincias= $obj_gerencia_seguridad->getArreglo();
            
            //Obtiene los tipos de eventos de bd_gerencia_seguridad
            $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto in (2,3,4,12) AND Estado=1");
            $obj_gerencia_seguridad->obtener_todos_los_tipos_de_puntos_BCR();
            $lista_tipos_de_puntos_bcr=$obj_gerencia_seguridad->getArreglo();
            
            //Obtiene los ATM de oficinas del San José
            $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto=2 and Estado=1");
            $obj_gerencia_seguridad->obtener_puntos_bcr_por_provincia_y_tipo_de_punto();
            $lista_puntos_bcr_sj=$obj_gerencia_seguridad->getArreglo();
            
            //Obtiene los estados de los eventos
            $obj_eventos->setCondicion("Estado=1");
            $obj_eventos->obtener_estado_evento();
            $estado_evento = $obj_eventos->getArreglo();
            
            //Identificador del punto BCR, default 0
            $ide=0;
            
            require __DIR__ . '/../vistas/plantillas/frm_evento_nuevo.php';
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            $this->volver_oriel();
        } 
    }
    
    //Metodo utilizado desde javascript para el pintado de eventos relacionados a un sitio bcr, en el momento de ingresar un nuevo
    //evento de bitacora digital. Esto con el fin de que el usuario pueda valorar la historia de un sitio antes de ingresar la información.
    public function dibuja_tabla_eventos_relacionados_a_punto_bcr(){    
        //valida si fue enviado el id del punto bcr mediante el evento post del formulario html
        if(isset($_POST['id_punto_bcr'])){
            //Validación para verificar si el usuario está logeado en el sistema
            if(isset($_SESSION['nombre'])){
                //Creacion de una instancia de la clase eventos
                $obj_eventos= new cls_evento();
                //Establece la condición de búsqueda de acuerdo al id del sitio.
                $obj_eventos->setCondicion("T_EventoCajero.ID_PuntoBCR=".$_POST['id_punto_bcr']);
                //ejecuta la sentencia SQL
                $obj_eventos->obtiene_todos_los_eventos();
                //Obtiene el resultado en una variable 
                $params=$obj_eventos->getArreglo();

                //Verifica si la consulta produjo resultados
                if (count($params)>0){
                    
                    //Establece La cabecera de la tabla
                    $html="<thead>";   
                    //Linea de los titulos de las columnas
                    $html.="<tr>";
                    //Columna fecha 
                    $html.="<th align='center'>Fecha</th>";
                    //Definición de las columnas de la tabla de acuerdo a la consulta SQL
                    $html.="<th>Hora</th>";
                    $html.="<th>Lapso</th>";
                    $html.="<th>Provincia</th>";
                    $html.="<th>Tipo Punto</th>";
                    $html.="<th>Punto BCR</th>";
                    $html.="<th>Tipo de Evento</th>";
                    $html.="<th>Estado del Evento</th>";
                    $html.="<th>Ingresado Por</th>";
                    $html.="<th>Consulta</th>";
                    //Cierra la fila
                    $html.="</tr>";
                    // Cierre de las cabeceras
                    $html.="</thead>";
                    //Cierre del cuerpo de la tabla
                    $html.="<tbody>";
                    
                    //Obtiene el tamaño de la variable parametros que almacena la consulta
                    $tam=count($params);

                    //Bucle que permite recorrer el vector que almacena la consulta de registros.
                    for ($i = 0; $i <$tam; $i++) {
           
                        //Creacion de una nueva linea en la tabla
                        $html.="<tr>";
           
                        //Campos de fecha dentro de la tabla
                        $fecha_evento = date_create($params[$i]['Fecha']);
                        $fecha_actual = date_create(date("d-m-Y"));
                        //Diferencia de dias entre una fecha y otra
                        $dias_abierto= date_diff($fecha_evento, $fecha_actual);
            
                        //Definición de los campos de la tabla, con respecto al vector que almacena los datos.
                        $html.="<td align='center'>".date_format($fecha_evento, 'd/m/Y')."</td>";
                        $html.="<td>".$params[$i]['Hora']."</td>";
                        $html.="<td align='center'>".$dias_abierto->format('%a')."</td>";
                        $html.="<td>".$params[$i]['Nombre_Provincia']."</td>";
                        $html.="<td>".$params[$i]['Tipo_Punto']."</td>";
                        $html.="<td>".$params[$i]['Nombre']."</td>";
                        $html.="<td>".$params[$i]['Tipo_Evento']."</td>";
                        $html.="<td>".$params[$i]['Estado_Evento']."</td>";
                        $html.="<td>".$params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido']."</td>";
                        //Link que muestra el detalle de los eventos
                        $html.="<td align='center'><a onclick='seguimiento_evento(".$params[$i]['ID_Evento'].")'>Ver detalle</a></td>";
                        //Culmina la linea  de datos
                        $html.="</tr>";
                    }
                    //Culmina el cuerpo de la tabla
                    $html.="</tbody>";
                    
                    //Imprime en pantalla el html construido
                    echo $html;
                    //sale del metodo
                    exit;
                }else{
                    // En caso de que no hayan resultados, muestra en pantalla la información
                    $html="<h4>No se encontraron eventos para este sitio.</h4>";
                    //Imprime la variable html construida
                    echo $html;
                    //Sale del metodo
                    exit;
                }    

            }else {
                /*
                * Esta es la validación contraria a que la sesión de usuario esté definida y abierta.
                * Lo cual quiere decir, que si la sesión está cerrada, procede  a enviar la solicitud
                * a la pantalla de inicio de sesión con el mensaje de warning correspondiente.
                * En la última línea llama a la pagina de inicio de sesión.
                */
               $tipo_de_alerta="alert alert-warning";
               $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
               //Llamada al formulario correspondiente de la vista
               require __DIR__ . '/../vistas/plantillas/inicio_sesion.php';
            }
        } else {
            //Imprime nulo en caso de no cumplir con las validaciones  de id correspondientes
            echo "";
            //Sale del metodo
            exit;
        }     
    }
    
    public function dibuja_tabla_seguimiento_evento(){
        if(isset($_POST['id_punto_bcr'])){
            //Obtiene el id del evento en cuestión mediante el metodo post del url
            $ide=$_POST['id_punto_bcr'];
            //Crea una instancia de la clase eventos
            $obj_eventos = new cls_evento();

            $condicion_seguimientos="T_DetalleEvento.ID_Evento=".$ide;
            //Obtiene los seguimientos del evento
            $obj_eventos->setCondicion($condicion_seguimientos." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc");
            //Obtiene los detalles del evento seleccionado
            $obj_eventos->obtiene_detalle_evento();
            //Obtiene el arreglo de resultados
            $params= $obj_eventos->getArreglo();
            
            if (count($params)>0){
                //Establece La cabecera de la tabla
                $html="<thead>";   
                //Linea de los titulos de las columnas
                $html.="<tr>";
                $html.="<th align='center'>Fecha de Seguimiento</th>";
                $html.="<th align='center'>Hora de Seguimiento</th>";
                $html.="<th align='center'>Detalle del seguimiento</th>";
                $html.="<th align='center'>Ingresado por</th>";
                //Cierra la fila
                $html.="</tr>";
                // Cierre de las cabeceras
                $html.="</thead>";
                //Cierre del cuerpo de la tabla
                $html.="<tbody>";

                //Obtiene el tamaño de la variable parametros que almacena la consulta
                $tam=count($params);

                //Bucle que permite recorrer el vector que almacena la consulta de registros.
                for ($i = 0; $i <$tam; $i++) {
                    //Creacion de una nueva linea en la tabla
                    $html.="<tr>";
                    $fecha_evento = date_create($params[$i]['Fecha']);
                    //Definición de los campos de la tabla, con respecto al vector que almacena los datos.
                    $html.="<td align='center'>".date_format($fecha_evento, 'd/m/Y')."</td>";
                    $html.="<td align='center'>".$params[$i]['Hora']."</td>";
                    $html.="<td align='center'>".$params[$i]['Detalle']."</td>";
                    $html.="<td align='center'>".$params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido']."</td>";
                    $html.="</tr>";
                }
                //Culmina el cuerpo de la tabla
                $html.="</tbody>";

                //Imprime en pantalla el html construido
                echo $html;
                //sale del metodo
                exit;
            }else{
                // En caso de que no hayan resultados, muestra en pantalla la información
                $html="<h4>No se encontraron eventos para este sitio.</h4>";
                //Imprime la variable html construida
                echo $html;
                //Sale del metodo
                exit;
            }    
        } else {
            echo "No entra";
        }
    }
    
    public function actualiza_en_vivo_punto_bcr(){
        if(isset($_SESSION['nombre'])){
            
            $obj_gerencia_seguridad =new cls_datos_gerencia_seguridad();
            $id_tipo_punto_bcr= $_POST['id_tipo_punto_bcr'];
            $id_provincia= $_POST['id_provincia'];
            $html="";
            //$obj_ev->setTipo_punto($id_tipo_punto_bcr);
            //$obj_ev->setProvincia($id_provincia);
            $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto=".$id_tipo_punto_bcr." AND T_Provincia.ID_Provincia=".$id_provincia);
            if($id_tipo_punto_bcr==0){
                $obj_gerencia_seguridad->setCondicion("t_Provincia.ID_Provincia=".$id_provincia);
                $html='<option value="0">Todos</option>';
            }
            if($id_provincia==0){
                $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto=".$id_tipo_punto_bcr);
                $html='<option value="0">Todos</option>';
            }
            
            $obj_gerencia_seguridad->filtra_sitios_bcr_bitacora();
            $sitios=$obj_gerencia_seguridad->getArreglo(); 
            $tam = count($sitios);
            
            $html='<option value="0">Todos</option>';
            for($i=0; $i<$tam;$i++){
                $html .= '<option value="'.$sitios[$i]['ID_PuntoBCR'].'">'.$sitios[$i]['Nombre'].'</option>';            
            }        
            echo $html;
        }else{
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            $this->volver_oriel();
        }
    }
    
    //Metodo que permite notificar al usuario en pantalla cuando va a ingresar un tipo de evento en un punto bcr que ya se encuentra abierto
    public function alerta_en_vivo_mismo_punto_bcr_y_evento(){
        //Verifica que los parametros del metodo post estén definidos y hayan sido enviados al metodo
        if(isset($_POST['id_punto_bcr'])&& (isset($_POST['id_tipo_evento']))){
            //Validación para verificar si el usuario está logeado en el sistema
            if(isset($_SESSION['nombre'])){
                // Creacion de una instancia de la clase eventos
                $obj_evento= new cls_evento();
            
                //Verifica que no exista este tipo de evento abierto para este punto bcr
                if ($obj_evento->existe_abierto_este_tipo_de_evento_en_este_sitio($_POST['id_tipo_evento'],$_POST['id_punto_bcr'])){
                    //Mensaje de notificacion en pantalla
                    echo "Ya existe abierto este tipo de evento para este punto BCR. Proceda a cerrarlo o ingrese un seguimiento!!!";
                    exit;
                }else {
                    exit;
                }
            } else {
                $tipo_de_alerta="alert alert-warning";
                $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
                $this->volver_oriel();
            }
        //En caso de que no estén definidos los parámetros, procede a sacarlo del metodo de ejecución.
        } else {
            exit;
        }
    }

    public function guardar_evento_cajero(){
        //Validación para verificar si el usuario está logeado en el sistema
        if(isset($_SESSION['nombre'])){
            //Creacion de una instancia de la clase eventos
            $obj_eventos= new cls_evento();
            //Verifica que la información enviada sea por medio del metodo post de html
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Formatea la fecha, para definir este atributo correctamente en el objeto de la clase.
                $fecha_seguimiento = strtotime($_POST['fecha']);
                $fecha_seguimiento = date("Y-m-d", $fecha_seguimiento);

                //Validaciones de la fecha ingresada para el evento, caso negativo muestra una advertencia en pantalla
                if ($fecha_seguimiento >  date("Y-m-d")){
                    //Muestra modal en pantalla
                    echo "<script type=\"text/javascript\">alert('No es posible ingresar eventos futuros!!!!');history.go(-1);</script>";;
                    //Sale del metodo
                    exit();
                    //Verifica que la fecha sea de hoy
                } if($fecha_seguimiento == date("Y-m-d")){
                    $hora_seguimiento = strtotime($_POST['hora']);
                    $hora_seguimiento = date("H:i", $hora_seguimiento);

                    //Valida que no se ingresen eventos en tiempo futuro
                    if ($hora_seguimiento >  date("H:i", time())){
                        //Muestra mensaje en pantalla para advertir al usuario
                        echo "<script type=\"text/javascript\">alert('No es posible ingresar eventos futuros!!!!');history.go(-1);</script>";;
                        //Sale del metodo
                        exit();
                    }
                }
                //Establece los atributos de la clase para el ingreso del evento
                $obj_eventos->setFecha($_POST['fecha']); 
                $obj_eventos->setHora($_POST['hora']);
                $obj_eventos->setTipo_evento($_POST['tipo_evento']);
                $obj_eventos->setProvincia($_POST['nombre_provincia']); 
                $obj_eventos->setTipo_punto($_POST['tipo_punto']); 
                $obj_eventos->setPuntobcr($_POST['punto_bcr']);
                $obj_eventos->setEstado($_POST['estado_evento']);
                $obj_eventos->setUsuario($_SESSION['id']);
                $obj_eventos->setEstado(1);
               
                //Verifica que no exista este tipo de evento abierto para este punto BCR
                if (!$obj_eventos->existe_abierto_este_tipo_de_evento_en_este_sitio($_POST['tipo_evento'],$_POST['punto_bcr'])){
                    //Ingresa el evento mediante el metodo de la clase
                    $obj_eventos->ingresar_evento();
                   
                    //Si el evento trae algun seguimiento procede a guardarlo tambien
                    if(isset($_POST['seguimiento'])&&($_POST['seguimiento']!="")){
                        //Establece los atributos de la clase, con la información que viene desde el formulario
                        $obj_eventos->setDetalle($_POST['seguimiento']);
                        $obj_eventos->setId2(0);
                        //Obtiene el id del ultimo seguimiento para incluirlo en el nuevo
                        $obj_eventos->obtiene_id_ultimo_evento_ingresado(); 
                        //Establece el id correspondiente
                        $obj_eventos->setId($obj_eventos->getId_ultimo_evento_ingresado());
                        $obj_eventos->setAdjunto("N/A");
                        //Ingresa el seguimiento
                        $obj_eventos->ingresar_seguimiento_evento();  
                        //echo "3 guarda seguimiento";
                    }
                    //Llama al listado principal de eventos abiertos o pendientes
                    header ("location:/ORIEL-Cajeros/index.php?ctl=bitacora_digital_cajeros");
                }else{
                    //Alerta al usuario en pantalla mediante un modal de que este tipo de evento ya está abierto para este punto bcr
                    echo "<script type=\"text/javascript\">alert('Ya existe este evento abierto para este punto BCR. Proceda a cerrarlo o agregue un seguimiento!!!');history.go(-1);</script>";
                    //Sale del metodo
                    exit;   
                }
            }
        }else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Validación para verificar si el usuario está logeado en el sistema
            $this->volver_oriel();
        }
    }
    
    //Permite agregar un seguimiento para un evento o cerrar uno
    public function evento_cajero_editar(){
        //Validación para verificar si el usuario está logeado en el sistema
        if(isset($_SESSION['nombre'])){
            //Controlador de errores
            try {
                //Obtiene el id del evento en cuestión mediante el metodo post del url
                $ide=$_GET['id'];
                //Crea una instancia de la clase eventos
                $obj_eventos = new cls_evento();
                
                //Establece la condición para buscar la información del evento en la tabla de bd
                $obj_eventos->setCondicion("ID_Evento=$ide");
                //Obtiene el evento que se muesta en la ventana
                $obj_eventos->obtiene_todos_los_eventos();
                //Obtiene el arreglo de resultados
                $params= $obj_eventos->getArreglo();
                
                //Obtiene los seguimientos del evento
                $condicion_seguimientos="T_DetalleEvento.ID_Evento=".$ide;
                //Obtiene los seguimientos del evento
                $obj_eventos->setCondicion($condicion_seguimientos." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc");
                //Obtiene los detalles del evento seleccionado
                $obj_eventos->obtiene_detalle_evento();
                //Asigna el resultado a un vector
                $detalleEvento= $obj_eventos->getArreglo();
                //Obtiene el estado del evento
                $obj_eventos->setCondicion("");
                $obj_eventos->obtener_estado_evento();
                $estadoEventos = $obj_eventos->getArreglo();
                
                $condicion_eventos="ID_Evento=".$ide;
                //Obtiene los seguimientos del evento
                $obj_eventos->setCondicion($condicion_eventos." order by T_EventoCajero.Fecha desc,T_EventoCajero.Hora desc");
                //Obtiene el evento que se muesta en la ventana
                $obj_eventos->obtiene_todos_los_eventos();
                //Obtiene el arreglo de resultados
                $params2= $obj_eventos->getArreglo();
                
                require __DIR__ . '/../vistas/plantillas/frm_eventos_editar.php';
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        } else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Llamada al formulario correspondiente de la vista
            $this->volver_oriel();
        }
    }
    
    //Metodo del contralador que permite listar los eventos cerrados en pantalla de la bitacora digital.
    public function eventos_cajeros_cerrados(){
        //Validación para verificar si el usuario está logeado en el sistema
        if(isset($_SESSION['nombre'])){
            //Creación de un objeto de clase eventos
            $obj_gerencia_seguridad = new cls_datos_gerencia_seguridad();
            $obj_tipo_evento = new cls_tipo_evento();
            
            //Metodo de la clase que permite obtener todas las provincias que se encuentran listadas en el sistema.
            $obj_gerencia_seguridad->setCondicion("");
            $obj_gerencia_seguridad->obtener_todas_las_provincias();
            //Asigna el resultado a un vector
            $lista_provincias=$obj_gerencia_seguridad->getArreglo();
            
            //Obtiene todos lps tipos de puntos BCR que se encuentran activos en la base de datos
            $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto in (2,3,4,12) AND Estado=1");
            $obj_gerencia_seguridad->obtener_todos_los_tipos_de_puntos_BCR();
            //Asigna el resultado de la consulta a un vector
            $lista_tipos_de_puntos_bcr=$obj_gerencia_seguridad->getArreglo();
            
            $obj_gerencia_seguridad->setCondicion("ID_Tipo_Punto=1 AND t_Provincia.ID_Provincia= 1");
            //Metodo que filtra los puntos BCR para uso de la bitacora digital
            $obj_gerencia_seguridad->filtra_sitios_bcr_bitacora();
            //Obtiene el resultado de la consulta en una variable vector.
            $lista_puntos_bcr_oficinas_sj=$obj_gerencia_seguridad->getArreglo(); 
            
            //Obtiene todos los tipos de eventos
            $obj_tipo_evento->setCondicion("Estado=1");
            $obj_tipo_evento->obtener_tipo_evento();
            $tipo_evento = $obj_tipo_evento->getArreglo();
            
            //Llamada al formulario correspondiente de la vista
            require __DIR__.'/../vistas/plantillas/frm_eventos_cajeros_cerrados.php';
        }
        else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Llamada al formulario correspondiente de la vista
            $this->volver_oriel();
        }
    }
    
    //Muestra en pantalla un reportes de eventos cerrados, de acuerdo a parametros de busqueda específicos, como fecha, sitio, etc.
    public function actualiza_en_vivo_reporte_cerrados(){     
        //Validación para verificar si el usuario está logeado en el sistema
        if(isset($_SESSION['nombre'])){
            //Creación de un nuevo objeto de la clase eventos
            $obj_eventos = new cls_evento();

            //Recibe la fecha inicial del reporte
            $fecha_inicial=$_POST['fecha_inicial'];
            //Recibe la fecha final del reporte
            $fecha_final=$_POST['fecha_final'];
            //Obtiene el id del punto bcr a consultar
            $id_punto_bcr=$_POST['id_punto_bcr'];
            //Obtiene el tipo de punto a consultar
            $id_tipo_evento=$_POST['tipo_evento'];
            //Obtiene el tipo de punto a consultar
            $id_tipo_punto=$_POST['tipo_punto'];
            //Obtiene el tipo de punto a consultar
            $id_provincia=$_POST['provincia'];
            //Establece la condición SQL para definir el rango de fechas del reporte
            $condicion="(T_EventoCajero.Fecha between '".$fecha_inicial."' AND '".$fecha_final."') AND (T_EventoCajero.ID_Estado_Evento=3 OR T_EventoCajero.ID_Estado_Evento=5)";
            if($id_punto_bcr!=0){
                $condicion.=" AND T_Evento.ID_PuntoBCR=".$id_punto_bcr;
            } if($id_tipo_evento!=0){
                $condicion.=" AND T_Evento.ID_Tipo_Evento=".$id_tipo_evento;
            } if($id_tipo_punto!=0){
                $condicion.=" AND T_Evento.ID_Tipo_Punto=".$id_tipo_punto;
            } if($id_provincia!=0){
                $condicion.=" AND T_Evento.ID_Provincia=".$id_provincia;
            }

            //Establece la condicion de la consulta
            $obj_eventos->setCondicion($condicion);
            //Obtiene los eventos de acuerdo a la condicion.
            $obj_eventos ->obtiene_todos_los_eventos(); 
            //Obtiene el arreglo de resultados
            $params= $obj_eventos->getArreglo();
            //Define una variable cadena a vacio
            $todos_los_seguimientos_juntos=null;
            //Obtiene el tamaño del vector de resultados
            $tamano=count($params);

            //Verifica que la consulta haya encontrado algo
            if (count($params)>0){

                //Bucle que recorre la cantidad de registros de la consulta uno por uno
                for ($x = 0; $x <$tamano; $x++) {
                    //Esta condicion trae los seguimientos del evento en cuestion, para pintarlos ocultos en el HTML
                    $obj_eventos->setCondicion("T_DetalleEvento.ID_Evento=".$params[$x]['ID_Evento']." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc");
                    //Obtiene los seguimientos del evento seleccionado, si los hubiere
                    $obj_eventos->obtiene_detalle_evento();
                    //Verifica que la consulta haya traido resultados
                    if(count($obj_eventos->getArreglo())>0){
                        if ($x==0){
                            //Va concatenando los resultados de la consulta de seguimientos, en una variable 
                            $todos_los_seguimientos_juntos=$obj_eventos->getArreglo();
                        }else{
                            //En caso de que ya tenga datos, adjunta el vector con lo que tenga actualmente
                            $todos_los_seguimientos_juntos = array_merge($todos_los_seguimientos_juntos,$obj_eventos->getArreglo());
                        }
                    }
                    //Obtiene la fecha y usuario del ultimo seguimiento que tenga el evento
                    $obj_eventos->setCondicion("T_DetalleEvento.ID_Evento=".$params[$x]['ID_Evento']." order by T_DetalleEvento.Fecha desc,T_DetalleEvento.Hora desc limit 0,1");
                    //Obtiene los seguimientos del evento seleccionado, si los hubiere
                    $obj_eventos->obtiene_detalle_evento();
                    //Obtiene los datos del ultimo seguimiento asociado
                    $ultimo_seguimiento_asociado= $obj_eventos->getArreglo();

                    //Verifica si existen seguimientos asociados al evento actual
                    if(count($ultimo_seguimiento_asociado)>0){
                        if ($x==0){
                            //Agrega el resultado de la consulta a una variable específica
                            $detalle_y_ultimo_usuario= array(array('Detalle'=>"Fecha: ".date_format(date_create($ultimo_seguimiento_asociado[0]['Fecha']), 'd/m/Y').".Hora: ".$ultimo_seguimiento_asociado[0]['Hora'].". ".$ultimo_seguimiento_asociado[0]['Detalle'],'Usuario'=>$ultimo_seguimiento_asociado[0]['Nombre_Usuario']." ".$ultimo_seguimiento_asociado[0]['Apellido']));
                        }else{
                            //En caso de que la variable ya contenga datos, procede a concatenar el resultado obtenido
                            $detalle_y_ultimo_usuario = array_merge($detalle_y_ultimo_usuario,array(array('Detalle'=>"Fecha: ".date_format(date_create($ultimo_seguimiento_asociado[0]['Fecha']), 'd/m/Y').".Hora: ".$ultimo_seguimiento_asociado[0]['Hora'].". ".$ultimo_seguimiento_asociado[0]['Detalle'], 'Usuario'=>$ultimo_seguimiento_asociado[0]['Nombre_Usuario']." ".$ultimo_seguimiento_asociado[0]['Apellido'])));   
                        }
                    }else{
                        //En caso de que no existan registros, agrega la validación correspondiente y un mensaje informativo al respecto para el usuario
                        if ($x==0){
                            $detalle_y_ultimo_usuario= array(array('Detalle'=>"No hay seguimientos asociados a este evento. Para agregar uno oprima el link:'Gestionar Seguimiento de la fila respectiva.'",'Usuario'=>$params[$x]['Nombre_Usuario']." ".$params[$x]['Apellido']));
                        }else{
                            //En caso de que la variable ya contenga información, procede a concatenar el vector de resultados
                            $detalle_y_ultimo_usuario = array_merge($detalle_y_ultimo_usuario,array(array('Detalle'=>"No hay seguimientos asociados a este evento. Para agregar uno oprima el link:'Gestionar Seguimiento de la fila respectiva.'",'Usuario'=>$params[$x]['Nombre_Usuario']." ".$params[$x]['Apellido'])));
                        }
                    }   

                }
            }
            //verifica que hayan resultados en la consulta, para empezar a pintar la tabla HTML que se mostrará en pantalla al formulario
            if (count($params)>0){
                //Creación de la tabla
                $html="<table id='tabla' class='display2'>";
                //Creación de la cabecera de la tabla
                $html.="<thead>";
                //Creación de la fila de títulos de la tabla
                $html.="<tr>";
                //Columna id evento, la cual está oculta en la tabla
                $html.="<th hidden='true'>ID_Evento</th>";
                //Resto de columnas de la tabla, de acuerdo a lo requerido en la consulta SQL
                $html.="<th>Fecha</th>";
                $html.="<th>Hora</th>";
                $html.="<th>Provincia</th>";
                $html.="<th>Tipo Punto</th>";
                $html.="<th>Punto BCR</th>";
                $html.="<th>Codigo</th>";
                $html.="<th>Tipo de Evento</th>";
                $html.="<th>Estado del Evento</th>";
                $html.="<th>Cerrado Por</th>";
                //Dependiendo del rol del usuario en cuestión, mostrará el botón de gestión de los eventos.
                if ($_SESSION['modulos']['Recuperar Eventos Cerrados']==1){  
                    $html.="<th>Gestión</th>";
                }
                //Resto de columnas
                $html.="<th>Consulta</th>";
                //Columna para agregar la tabla de seguimientos de cada evento
                $html.="<th hidden='true'>Seguimientos</th> ";
                //termina la fila de cabeceras
                $html.="</tr>";
                //termina la cabecera de la tabla
                $html.="</thead>";

                //Inicializa el cuerpo de la tabla
                $html.="<tbody id='cuerpo'>";
                //Retorna el tamaño del vector que almacena la consulta sql
                $tam=count($params);

                //Vector que recorre registro por registros de la consulta SQL
                for ($i = 0; $i <$tam; $i++) {
                    //Agrega a la fila de cada evento, un comentario interno con el detalle del último seguimiento
                    $html.="<tr data-toggle='tooltip' title='".$detalle_y_ultimo_usuario[$i]['Detalle']."'>";

                    //Establece la fecha del evento
                    $fecha_evento = date_create($params[$i]['Fecha']);
                    //Establece la fecha actual
                    $fecha_actual = date_create(date("d-m-Y"));
                    //Establece la diferencia en dias entre ambas fechas
                    $dias_abierto= date_diff($fecha_evento, $fecha_actual);

                    //Pinta y oculta el id del evento 
                    $html.="<td hidden='true'>".$params[$i]['ID_Evento']."</td>";
                    //Pinta las columnas correspondientes al reporte de eventos
                    $html.="<td>".date_format($fecha_evento, 'd/m/Y')."</td>";   
                    $html.="<td>".$params[$i]['Hora']."</td>";
                    $html.="<td>".$params[$i]['Nombre_Provincia']."</td>";
                    $html.="<td>".$params[$i]['Tipo_Punto']."</td>";
                    $html.="<td>".$params[$i]['Nombre']."</td>";
                    $html.="<td>".$params[$i]['Codigo']."</td>";
                    $html.="<td>".$params[$i]['Tipo_Evento']."</td>";
                    $html.="<td>".$params[$i]['Estado_Evento']."</td>";       
                    //Muestra el último usuario que realizó seguimiento en el evento
                    $html.="<td>".$detalle_y_ultimo_usuario[$i]['Usuario']."</td>";

                    //Dependiendo del rol del usuario, muestra en pantalla la opción de recuperar eventos
                    if ($_SESSION['modulos']['Recuperar Eventos Cerrados']==1){  
                        //Asigna la función de javascript que ejecuta la recuperación en vivo del evento, para que sea reabierto
                        $html.="<td align='center'><a onclick='recuperar_evento(".$params[$i]['ID_Evento'].",".$params[$i]['ID_PuntoBCR'].",".$params[$i]['ID_Tipo_Evento'].")'>Recuperar Evento</a></td>";
                    }   
                    //Link para ver el detalle de un evento
                    $html.="<td align='center'><a onclick='seguimiento_evento(".$params[$i]['ID_Evento'].")'>Ver detalle</a></td>";

                    //Saca el tamaño del vector que tiene todos los seguimientos de los eventos
                    $tama=count($todos_los_seguimientos_juntos);
                    //Inicializa la variable cadena
                    $cadena="";
                    //Bucle que recorre el vector de seguimientos
                    for ($j = 0; $j <$tama; $j++) {
                        //Extrae la fecha del evento
                        $fecha_evento = date_create($todos_los_seguimientos_juntos[$j]['Fecha']);
                        //Extrae la fecha actual
                        $fecha_actual = date_create(date("d-m-Y"));
                        // Define los días abiertos que tiene el evento
                        $dias_abierto= date_diff($fecha_evento, $fecha_actual);
                        //extrae los seguimiento por cada evento, y los concatena en una variable para colocarlos en una de las columnas de la tabla
                        if ($params[$i]['ID_Evento']==$todos_los_seguimientos_juntos[$j]['ID_Evento']){
                            //va concatenando cada seguimiento en una variable
                            $cadena.=date_format($fecha_evento, 'd/m/Y')." ".$todos_los_seguimientos_juntos[$j]['Detalle']."\n";
                        }
                    }
                    //Esconde y pinta la columna correspondiente
                    $html.="<td hidden='true'>".$cadena."</td>";

                    //Cierra la fila del registro del evento en cuestión.
                    $html.="</tr>";
                }

                //Finaliza el cuerpo de la tabla
                $html.="</tbody>";

                //Culmina la tabla
                $html.=" </table>";

                //Imprime en pantalla el codigo html estructurado en este metodo
                echo $html;
                //Sale del metodo
                exit;
            }else{
                //En caso de que no hayan resultados, muestra la información correspondiente.
                $html="<h4>No se encontraron eventos para este filtro.</h4>";
                //Imprime la variable html
                echo $html;
                //Sale del metodo
                exit;
            }    
            //Imprime la variable html y sale del metodo
            echo $html;
        }else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Llamada al formulario correspondiente de la vista
            $this->volver_oriel();
        }
    }
    
    //Metodo que permite recuperar un evento en estado cerrado o abierto por error.
    public function eventos_cajeros_recuperar(){
        //Validación para verificar si el usuario está logeado en el sistema
        if(isset($_SESSION['nombre'])){
            //Crea una instancia de la clase eventos
            $obj_eventos= new cls_evento();
            //Verifica que el envio de datos se haya realizado mediante el metodo post de html
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                //Establece los atributos del objeto evento, con la información del formulario HTML
                $obj_eventos->setFecha(date("Y-m-d")); 
                $obj_eventos->setHora(date("H:i", time()));
                $obj_eventos->setTipo_evento($_POST['id_tipo_evento']);
                $obj_eventos->setPuntobcr($_POST['id_puntobcr']);
                $obj_eventos->setUsuario($_SESSION['id']);
                $obj_eventos->setEstado(1);
                  
                //Verifica si el evento no está abierto para proceder con el tramite 
                if (!$obj_eventos->existe_abierto_este_tipo_de_evento_en_este_sitio()){
                   //Agrega un detalle para registrar el procedimiento mediante un seguimiento al evento
                    $obj_eventos->setDetalle("Evento re-abierto (recuperado) por ".$_SESSION['name']." ".$_SESSION['apellido']);
                    //Estable el id a cero para agregar un nuevo seguimiento
                    $obj_eventos->setId2(0);
                    $obj_eventos->setId($_POST['id_evento']);
                    $obj_eventos->edita_estado_evento("1");
                    $obj_eventos->setAdjunto("N/A");
                    //Inserta en bd un nuevo seguimiento
                    $obj_eventos->ingresar_seguimiento_evento();  
                    //Llama a la pantalla del listado de eventos cerrados
                    //header ("location:/ORIEL/index.php?ctl=frm_eventos_lista_cerrados");
                    //Devuelve un cero en la variable de impresión para javascript, para validar lo que se hizo en el metodo
                    echo "0";
                }else{
                    //Devuelve un uno en la variable de impresión para javascript, para validar lo que se hizo en el metodo
                    echo "1";
                    //Sale del metodo de la clase.
                    exit;
                }
            }
        }else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Validación para verificar si el usuario está logeado en el sistema
            $this->volver_oriel();
        }
    }
    
    public function guardar_seguimiento_evento(){
        if(isset($_SESSION['nombre'])){
            $obj_eventos = new cls_evento();
            $obj_eventos->setId($_GET['id']);
            $obj_eventos->setId2(0);
            
            $fecha_seguimiento = strtotime($_POST['Fecha']);
	    $fecha_seguimiento = date("Y-m-d", $fecha_seguimiento);
            
            if ($fecha_seguimiento >  date("Y-m-d")){
                echo "<script type=\"text/javascript\">alert('No es posible ingresar eventos futuros!!!!');history.go(-1);</script>";;
                exit();
            }if($fecha_seguimiento == date("Y-m-d")){
                $hora_seguimiento = strtotime($_POST['Hora']);
                $hora_seguimiento = date("H:i", $hora_seguimiento);
                
                if ($hora_seguimiento >  date("H:i", time())){
                   echo "<script type=\"text/javascript\">alert('No es posible ingresar eventos futuros!!!!');history.go(-1);</script>";;
                   exit();
                }
            }
             
            $obj_eventos->setFecha(($_POST['Fecha']));
            $obj_eventos->setHora(($_POST['Hora']));
            //Validación de informacion en detalle de evento, elimina algunos caracteres especiales
            $detalle = $_POST['DetalleSeguimiento'];
            $detalle= str_replace("'","",$detalle);
            $detalle= str_replace('"','',$detalle);
            $obj_eventos->setDetalle($detalle);
            $obj_eventos->setUsuario($_SESSION['id']);
          
            $recepcion_archivo=$_FILES['archivo_adjunto']['error'];
            
            $date=new DateTime(); //this returns the current date time
            $result = $date->format('Y-m-d-H-i-s');
            //echo $result;
            $krr = explode('-',$result);
            $result = implode("",$krr);
                       
            $raiz=$_SERVER['DOCUMENT_ROOT'];
                       
            if (substr($raiz,-1)!="/"){
                $raiz.="/";
            }
            
            $ruta=  $raiz."Adjuntos_Bitacora_Cajeros/".Encrypter::quitar_tildes($result.$_FILES['archivo_adjunto']['name']);
            //$ruta=  $_SERVER['DOCUMENT_ROOT']."Adjuntos_Bitacora/".$result.$_FILES['archivo_adjunto']['name'];
          
            switch ($recepcion_archivo) {
                case 0:{
                    if (move_uploaded_file($_FILES['archivo_adjunto']['tmp_name'], $ruta)){
                        $obj_eventos->setAdjunto(Encrypter::quitar_tildes($result.$_FILES['archivo_adjunto']['name'])); 
                        $obj_eventos->ingresar_seguimiento_evento();
                        $obj_eventos->edita_estado_evento($_POST['estado_del_evento']);
                        header ("location:/ORIEL-Cajeros/index.php?ctl=bitacora_digital_cajeros");
                    }  else {
                        //echo "<script type=\"text/javascript\">alert('Hubo un problema al subir el archivo al servidor!!!');history.go(-1);</script>";;
                        $obj_eventos->setAdjunto("N/A");
                        $obj_eventos->ingresar_seguimiento_evento();
                        $obj_eventos->edita_estado_evento($_POST['estado_del_evento']);
                        header ("location:/ORIEL-Cajeros/index.php?ctl=bitacora_digital_cajeros");
                        //echo "<script type=\"text/javascript\">alert('No fue seleccionado ningun archivo!!!!');history.go(-1);</script>";;
                    }
                    break;
                }
                case 2:{
                    echo "<script type=\"text/javascript\">alert('El archivo consume mayor espacio del permitido (1 mb) !!!!');history.go(-1);</script>";;
                    break;
                }
                case 4:{ 
                    $obj_eventos->setAdjunto("N/A");
                    $obj_eventos->ingresar_seguimiento_evento();
                    $obj_eventos->edita_estado_evento($_POST['estado_del_evento']);
                    header ("location:/ORIEL-Cajeros/index.php?ctl=bitacora_digital_cajeros");
                    //echo "<script type=\"text/javascript\">alert('No fue seleccionado ningun archivo!!!!');history.go(-1);</script>";;
                    break;
                }
                 case 6:{
                    echo "<script type=\"text/javascript\">alert('El servidor no tiene acceso a la carpeta temporal de almacenamiento!!!!');history.go(-1);</script>";
                    break;
                 } 
                case 7:{
                    echo "<script type=\"text/javascript\">alert('No es posible escribir en el disco duro del servidor!!!!');history.go(-1);</script>";;
                    break;
                }  
                case 8:{
                    echo "<script type=\"text/javascript\">alert('Fue detenida la carga del archivo debido a una extension de PHP!!!!');history.go(-1);</script>";;
                    break;
                }   
            }
        }else {
            $tipo_de_alerta="alert alert-warning";
            $validacion="Es necesario volver a iniciar sesión para consultar el sistema";
            //Llamada al  formulario de la vista.
            $this->volver_oriel();
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
            $this->volver_oriel();
        } 
    }
    
    public function tipo_evento_guardar(){
        if(isset($_SESSION['nombre'])){
            $obj_tipo_evento = new cls_tipo_evento();
            
            $obj_tipo_evento->setTipo_evento($_POST['tipo_evento']);
            $obj_tipo_evento->setObservaciones($_POST['observaciones']); 
            $obj_tipo_evento->setPrioridad($_POST['prioridad']);
            $obj_tipo_evento->setEstado($_POST['estado']);

            if ($_POST['ID_Tipo_Evento']==0){
                $obj_tipo_evento->agregar_tipo_evento();
            } else {
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
            $this->volver_oriel();
        }
    }

}