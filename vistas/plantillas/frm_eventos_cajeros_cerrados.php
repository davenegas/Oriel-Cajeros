<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Lista de Eventos Cerrados</title>
        <script language="javascript" src="vistas/js/jquery.js"></script>
        <link rel="stylesheet" href="vistas/css/ventanaoculta.css">
        <?php require_once 'frm_librerias_head.html'; ?>    
        <script>
            function recuperar_evento(id_e,id_pbcr,id_tevento){
                id_evento=id_e;
                id_punto=id_pbcr;
                id_tipo=id_tevento;

                $.confirm({
                    title: 'Confirmación!',
                    content: 'Desea recuperar este evento?',
                    confirm: function(){
                        //alert (prueba+" "+ prueba2 + " " + prueba3 );
                        $.post("index.php?ctl=eventos_cajeros_recuperar", { id_evento: id_evento,id_puntobcr:id_punto,id_tipo_evento:id_tipo },function(data){
                            var srt = data;
                            var n= srt.search("0");
                            if(n<0){
                                $.alert({
                                    title: 'Información!',
                                    content: 'Ya existe este evento abierto para este punto BCR. Proceda a cerrarlo o agregue un seguimiento!!!',
                                });
                            }else{
                                $.alert({
                                    title: 'Información!',
                                    content: 'Evento recuperado con éxito!!!',
                                });
                                location.reload();  
                            }
                        });  
                        //location.reload();  
                    },
                    cancel: function(){
                    //$.alert('Canceled!')
                    }
                });
            }  
             
            function hacer_click(){
                $('#cuerpo').html('<center><img align="center" src="vistas/Imagenes/loading.gif"/></center>');
                $('#cuerpo').html('<center><img align="center" src="vistas/Imagenes/loading.gif"/></center>');

                fecha_inicial=document.getElementById('fecha_inicial').value;
                fecha_final=document.getElementById('fecha_final').value;
                id_punto_bcr=document.getElementById('punto_bcr').value;
                tipo_evento = document.getElementById('tipo_evento').value;
                provincia = document.getElementById('nombre_provincia').value;
                tipo_punto = document.getElementById('tipo_punto').value;
                //tabla=document.getElementById('tabla_afectada').value;

                $.post("index.php?ctl=actualiza_en_vivo_reporte_cerrados", {fecha_inicial: fecha_inicial,fecha_final:fecha_final,id_punto_bcr:id_punto_bcr, tipo_evento:tipo_evento, provincia:provincia, tipo_punto:tipo_punto}, function(data){
                    $("#titulo").html("Eventos de acuerdo a parámetros:");  
                    $("#tabla").html(data);   
                    $("#tabla").dataTable().fnDestroy();
                    $("#tabla").DataTable().draw();
                });
            }
            
            $(document).ready(function(){
                $("#tipo_punto").change(function () {
                    $("#tipo_punto option:selected").each(function () {
                        id_tipo_punto_bcr = $(this).val();
                        id_provincia=document.getElementById('nombre_provincia').value;
                        $.post("index.php?ctl=actualiza_en_vivo_punto_bcr", { id_tipo_punto_bcr: id_tipo_punto_bcr,id_provincia:id_provincia }, function(data){
                            $("#punto_bcr").html(data);
                        });            
                    });
                });
                $("#nombre_provincia").change(function () {
                    $("#nombre_provincia option:selected").each(function () {
                        id_provincia = $(this).val();
                        id_tipo_punto_bcr=document.getElementById('tipo_punto').value;
                        $.post("index.php?ctl=actualiza_en_vivo_punto_bcr", { id_tipo_punto_bcr: id_tipo_punto_bcr,id_provincia:id_provincia }, function(data){
                            $("#punto_bcr").html(data); 
                        });            
                    });
                });
            });
            
            function ocultar_elemento(){
                document.getElementById('popupventana2').className = "animated zoomOut";
                setTimeout(function() {
                    document.getElementById('popupventana2').className = "animated zoomIn";
                    document.getElementById('ventana_oculta_1').style.display = "none";
                }, 500);
            }
            
            //Funcion para agregar un nuevo tipo ip- formulario en blanco
            function seguimiento_evento(id) {
                id_punto_bcr=id;
                $.post("index.php?ctl=dibuja_tabla_seguimiento_evento", { id_punto_bcr: id_punto_bcr}, function(data){
                    $("#tabla_seguimiento").html(data); 
                    //console.log(data);
                });
                document.getElementById('ventana_oculta_1').style.display = "block";
            }
        </script>
    </head>
    <body>
        <?php require_once 'encabezado.php';?>
        
        <!--<center><img src="vistas/Imagenes/loading.gif" alt=""/></center>-->
        <!--<img src="../Imagenes/notas.png" alt=""/>-->
        <div class="container animated fadeIn quitar-float">
            <h2>Generar Reporte de Eventos Cerrados del Sistema</h2> 
            <h4 class="espacio-arriba">Escoger parámetros del filtro:</h4>

            <div class="col-xs-2">
                <label for="fecha_inicial">Fecha Inicial:</label>
                <input type="date" required=”required” class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date("Y-m-d");?>">
            </div> 
            <div class="col-xs-2">
                <label for="fecha_final">Fecha Final:</label>
                <input type="date" required=”required” class="form-control" id="fecha_final" name="fecha_final" value="<?php echo date("Y-m-d");?>">
            </div> 
            <div class="col-xs-2">
                <label for="nombre_provincia">Provincia</label>
                <select class="form-control" required=”required” id="nombre_provincia" name="nombre_provincia" >
                    <option value="0">Todas</option>
                    <?php
                    $tam_provincias = count($lista_provincias);
                    for($i=0; $i<$tam_provincias;$i++) {
                        if($lista_provincias[$i]['ID_Provincia']==$cantones[$distritos[$params[0]['ID_Distrito']]['ID_Canton']]['ID_Provincia']){ ?> 
                            <option value="<?php echo $lista_provincias[$i]['ID_Provincia']?>" selected="selected"><?php echo $lista_provincias[$i]['Nombre_Provincia']?></option>
                        <?php } else { ?>
                            <option value="<?php echo $lista_provincias[$i]['ID_Provincia']?>" ><?php echo $lista_provincias[$i]['Nombre_Provincia']?></option>  
                        <?php } 
                    } ?>  
                </select>
            </div>
            <div class="col-xs-2">
                <label for="tipo_punto">Tipo Punto</label>
                <select class="form-control" required=”required” id="tipo_punto" name="tipo_punto" >
                    <option value="0">Todos</option>
                    <?php
                    $tam_tipo_punto_bcr = count($lista_tipos_de_puntos_bcr);
                    for($i=0; $i<$tam_tipo_punto_bcr;$i++){
                        if($lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']==$params[0]['ID_Tipo_Punto']){ ?> 
                            <option value="<?php echo $lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']?>" selected="selected"><?php echo $lista_tipos_de_puntos_bcr[$i]['Tipo_Punto']?></option>
                        <?php }else { ?>
                            <option value="<?php echo $lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']?>"><?php echo $lista_tipos_de_puntos_bcr[$i]['Tipo_Punto']?></option> 
                        <?php }
                    } ?>  
                </select>
            </div>
            <div class="col-xs-2">
                <label for="punto_bcr">Punto BCR</label>
                <select class="form-control" required=”required” id="punto_bcr" name="punto_bcr" >
                    <option value="0">Todos</option>
                    <?php  if($params[0]['ID_PuntoBCR']!=0){ ?>
                        <option value="<?php echo $params[0]['ID_PuntoBCR']?>"><?php echo $params[0]['Nombre']?></option>
                    <?php } ?>
                        
                    <?php
                    $tam_puntos_bcr=count($lista_puntos_bcr_oficinas_sj);
                    for($i=0; $i<$tam_puntos_bcr;$i++){?>
                        <option value="<?php echo $lista_puntos_bcr_oficinas_sj[$i]['ID_PuntoBCR']?>"><?php echo $lista_puntos_bcr_oficinas_sj[$i]['Nombre']?></option>                           
                    <?php  } ?> 
                </select>
            </div>
            <div class="col-xs-2">
                <label for="tipo_evento">Tipo Evento</label>
                <select class="form-control" required=”required” id="tipo_evento" name="tipo_evento" >
                    <option value="0">Todos</option>                        
                    <?php 
                    if(isset($tipo_evento)){
                        $tam=count($tipo_evento);
                        for($i=0; $i<$tam;$i++){ ?>
                            <option value="<?php echo $tipo_evento[$i]['ID_Tipo_Evento']?>"><?php echo $tipo_evento[$i]['Tipo_Evento']?></option>                           
                        <?php }  
                    } ?>
                </select>
            </div>
            <!--<button value="esto es un boton" onclick="mi_funcion()"/>-->
            <a class="btn btn-default espacio-arriba" role="button" id="prueba" name="prueba" onclick="hacer_click()">Generar Reporte</a>
            <a href="index.php?ctl=bitacora_digital_cajeros" class="btn btn-default espacio-arriba" role="button">Cancelar</a>

            <div class="container animated fadeIn">
                <h2 id="titulo">Listado de Eventos Cerrados del día de hoy:</h2>
                <table id="tabla" class="display2">
                    <thead>   
                        <tr>
                            <th hidden="true">ID_Evento</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Provincia</th>
                            <th>Tipo Punto</th>
                            <th>Punto BCR</th>
                            <th>Código</th>
                            <th>Tipo de Evento</th>
                            <th>Estado del Evento</th>
                            <th>Cerrado Por</th>
                            <?php if ($_SESSION['modulos']['Módulo Cajeros-Bitácora Digital']==1){ ?>  
                                <th>Gestión</th>
                            <?php } ?>  
                            <th>Consulta</th>
                            <th hidden="true">Seguimientos</th>       
                        </tr>
                    </thead>
                    <tbody id="cuerpo">
                    <?php 
                    if(isset($params)){
                    $tam=count($params);
                    for ($i = 0; $i <$tam; $i++) { ?>
                        <tr data-toggle="tooltip" title="<?php echo $detalle_y_ultimo_usuario[$i]['Detalle'];?>">
                            <?php
                            $fecha_evento = date_create($params[$i]['Fecha']);
                            $fecha_actual = date_create(date("d-m-Y"));
                            $dias_abierto= date_diff($fecha_evento, $fecha_actual);
                            ?>
                            <td hidden="true"><?php echo $params[$i]['ID_Evento'];?></td>
                            <td><?php echo date_format($fecha_evento, 'd/m/Y');?></td>
                            <td><?php echo $params[$i]['Hora'];?></td>
                            <!--<td align="center"><?php echo $dias_abierto->format('%a');?></td>-->
                            <td><?php echo $params[$i]['Nombre_Provincia'];?></td>
                            <td><?php echo $params[$i]['Tipo_Punto'];?></td>
                            <td><?php echo $params[$i]['Nombre'];?></td>
                            <td><?php echo $params[$i]['Codigo'];?></td>
                            <td><?php echo $params[$i]['Evento'];?></td>
                            <td><?php echo $params[$i]['Estado_Evento'];?></td>
                            <td><?php echo $detalle_y_ultimo_usuario[$i]['Usuario'] ?></td>
                            <!--<td><?php echo $params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido'] ?></td>-->
                            <?php if ($_SESSION['modulos']['Módulo Cajeros-Bitácora Digital']==1){ ?>  
                                <td align="center"><a onclick="recuperar_evento(<?php echo $params[$i]['ID_Evento'];?>,<?php echo $params[$i]['ID_PuntoBCR'];?>,<?php echo $params[$i]['ID_Tipo_Evento'];?>);">Recuperar Evento</a></td>
                            <?php } ?>
                            <td align="center"><a href="index.php?ctl=frm_eventos_editar&accion=consulta_cerrados&id=<?php echo $params[$i]['ID_Evento']?>">Ver detalle</a></td>
                            
                            <?php 
                            $cadena="";
                            $tama=count($todos_los_seguimientos_juntos);
                            for ($j = 0; $j <$tama; $j++){
                                $fecha_evento = date_create($todos_los_seguimientos_juntos[$j]['Fecha']);
                                $fecha_actual = date_create(date("d-m-Y"));
                                $dias_abierto = date_diff($fecha_evento, $fecha_actual);
                                if ($params[$i]['ID_Evento']==$todos_los_seguimientos_juntos[$j]['ID_Evento']){
                                    $cadena.=date_format($fecha_evento, 'd/m/Y')." ".$todos_los_seguimientos_juntos[$j]['Detalle']."\n";
                                }
                            } ?>
                            <td hidden="true"><?php echo $cadena;?></td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
            </div>

        </div>
        
        <?php require 'vistas/plantillas/pie_de_pagina.php' ?>
        
        <!--Ver seguimiento evento- Ventana oculta-->
        <div id="ventana_oculta_1"> 
            <div id="popupventana2" class="animated zoomIn">
                <!--Formulario para direccionamiento de las ip-->
                <div id="ventana2">
                    <img id="close" src='vistas/Imagenes/cerrar.png' width="25" onclick ="ocultar_elemento()">
                    <h2 align="center">Seguimiento de evento seleccionado</h2>
                    <br>
                    <table id='tabla_seguimiento' class="table ">
                    </table>
                </div>
            </div>      
        </div>
    </body>
</html>