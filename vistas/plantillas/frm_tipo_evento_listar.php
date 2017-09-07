<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Lista de Tipos de Eventos</title>
        <link rel="stylesheet" href="vistas/css/ventanaoculta.css">
        <?php require_once 'frm_librerias_head.html';?>
        <script>
            //Funcion para ocultar ventana de mantenimiento de tipo de evento
            function ocultar_elemento(){
                document.getElementById('ventana_oculta_1').style.display = "none";
            }
            //Valida informacion completa de formulario de tipo de evento
            function check_empty() {
                if (document.getElementById('tipo_evento').value =="" ||document.getElementById('estado').value ==null||document.getElementById('prioridad').value == null) {
                    alert("Digita el tipo de evento, la prioridad y el estado!");
                } else {
                    document.getElementById('ventana').submit();
                    document.getElementById('ventana_oculta_1').style.display = "none";
                }
            }
            //Funcion para agregar un nuevo tipo ip- formulario en blanco
            function mostrar_agregar_tipo_evento() {
                document.getElementById('ID_Tipo_Evento').value="0";
                document.getElementById('tipo_evento').value=null;
                document.getElementById('observaciones').value=null;
                document.getElementById('estado').value=null;
                document.getElementById('prioridad').value=null;
                document.getElementById('ventana_oculta_1').style.display = "block";
            }
            //Funcion para editar informacion de tipo ip
            function edita_tipo_evento(id_tipo_evento,tipo_evento,obser, estado,prioridad){
                document.getElementById('ID_Tipo_Evento').value=id_tipo_evento;
                $("#estado option[value="+estado+"]").attr("selected",true);
                document.getElementById('tipo_evento').value=tipo_evento;
                document.getElementById('observaciones').value=obser;
                document.getElementById('prioridad').value=prioridad
                document.getElementById('ventana_oculta_1').style.display = "block";
            };
        </script>
    </head>
    <body>
        <?php require_once 'encabezado.php';?>
        <div class="container animated bounceInUp">
        <h2>Listado General de Tipos de Eventos</h2>
        <p>A continuación se detallan los diferentes tipos de eventos que están registrados en el sistema:</p>            
        <table id="tabla" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="text-align:center" hidden>ID</th>
                    <th style="text-align:center">Tipo de evento</th>
                    <th style="text-align:center">Observaciones</th>
                    <th style="text-align:center">Prioridad</th>
                    <th style="text-align:center">Estado</th>
                    <th style="text-align:center">Mantenimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $tam=count($params);
                for ($i = 0; $i <$tam; $i++) {  ?>
                    <tr>
                        <td style="text-align:center" hidden><?php echo $params[$i]['ID_Tipo_Evento'];?></td>
                        <td style="text-align:center"><?php echo $params[$i]['Tipo_Evento'];?></td>
                        <td style="text-align:center"><?php echo $params[$i]['Observaciones'];?></td>
                        <!--Cambia el numero de estado por el nombre-->     
                        <?php   if ($params[$i]['Prioridad']==1){    ?>  
                            <td style="text-align:center">1- Alta</td>
                        <?php } else    {?>  
                            <td style="text-align:center">2- Baja</td>
                        <?php } ?>
                        <?php   if ($params[$i]['Estado']==1){    ?>  
                            <td style="text-align:center">Activo</td>
                        <?php } else { ?>  
                            <td style="text-align:center">Inactivo</td>
                        <?php } ?>
                        <td style="text-align:center" ><a role="button" onclick="edita_tipo_evento('<?php echo $params[$i]['ID_Tipo_Evento'];?>','<?php echo $params[$i]['Tipo_Evento'];?>',
                            '<?php echo $params[$i]['Observaciones'];?>','<?php echo $params[$i]['Estado'];?>','<?php echo $params[$i]['Prioridad'];?>')"> Editar</a></td>
                    </tr>     
                <?php } ?>
            </tbody>
        </table>
        <a id="popup" onclick="mostrar_agregar_tipo_evento()" class="btn btn-default espacio-arriba" role="button">Agregar Nuevo Tipo Evento</a>
        </div>
        <?php //require 'vistas/plantillas/pie_de_pagina.php' ?>
        
        <!--agregar o editar tipo punto- Ventana oculta-->
        <div id="ventana_oculta_1"> 
            <div id="popupventana">
                <!--Formulario para direccionamiento de las ip-->
                <form id="ventana" method="POST" name="form" action="index.php?ctl=tipo_evento_guardar">
                    <img id="close" src='vistas/Imagenes/cerrar.png' width="25" onclick ="ocultar_elemento()">
                    <h2>Tipo de evento</h2>
                    <br>
                    <input hidden id="ID_Tipo_Evento" name="ID_Tipo_Evento" type="text">
                    <div class="form-group">
                        <label for="tipo_evento">Tipo de Evento</label>
                        <input class="form-control espacio-abajo" required id="tipo_evento" name="tipo_evento" placeholder="Nombre del tipo de evento" type="text">

                        <label for="observaciones">Observaciones</label>
                        <input type="text" class="form-control espacio-abajo" id="observaciones" name="observaciones" placeholder="Observaciones del tipo de evento"> 
                        
                        <label for="prioridad">Prioridad</label>
                        <select class="form-control" id="prioridad" name="prioridad"> 
                            <option value="0">1- Alta</option>
                            <option value="1">2- Baja</option>
                        </select>  
                        
                        <label for="estado">Estado</label>
                        <select class="form-control" id="estado" name="estado"> 
                            <option value="0">Inactivo</option>
                            <option value="1">Activo</option>
                        </select>               
                    </div>
                    <button><a href="javascript:%20check_empty()" id="submit">Guardar</a></button>
                </form>
            </div>      
        </div>
    </body>
</html>