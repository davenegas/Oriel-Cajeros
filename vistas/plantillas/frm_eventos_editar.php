<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Detalle de Evento</title>
        <?php require_once 'frm_librerias_head.html';?>
        <script language="javascript">
            $(document).ready(function() {
                $(".botonExcel").click(function(event) {
                    $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).html());
                    $("#FormularioExportacion").submit();
                });
            });
            //Valida un solo click en formulario
            var cuenta=0;
            function enviado() { 
                if (cuenta == 0){
                    cuenta++;
                    return true;
                } else {
                    //alert(", muchas gracias.");
                    return false;
                }
            }
        </script>
    </head>
    <body>
        <?php require_once 'encabezado.php';?>
        <div class="container animated fadeIn">
            <div class="row well">
                <h1 align="center">Detalle de Evento</h1>
                <table class="table">
                    <thead> 
                        <tr>
                            <th style="text-align:center">Fecha</th>
                            <th style="text-align:center">Hora</th>
                            <th style="text-align:center">Lapso</th>
                            <th style="text-align:center">Provincia</th>
                            <th style="text-align:center">Tipo Punto</th>
                            <th style="text-align:center">Punto BCR</th>
                            <th style="text-align:center">Tipo de Evento</th>
                            <th style="text-align:center">Estado Actual</th>
                            <th style="text-align:center">Ingresado Por</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tam=count($params2);
                        for ($i = 0; $i <$tam; $i++) { ?>
                            <tr>
                                <?php
                                $fecha_evento = date_create($params2[$i]['Fecha']);
                                $fecha_actual = date_create(date("d-m-Y"));
                                $dias_abierto= date_diff($fecha_evento, $fecha_actual);
                                ?>
                                <td align="center"><?php echo date_format($fecha_evento, 'd/m/Y');?></td>
                                <td align="center"><?php echo $params2[$i]['Hora'];?></td>
                                <td align="center"><?php echo $dias_abierto->format('%a días');?></td>
                                <td align="center"><?php echo $params2[$i]['Nombre_Provincia'];?></td>
                                <td align="center"><?php echo $params2[$i]['Tipo_Punto'];?></td>
                                <td align="center"><u><b><?php echo $params2[$i]['Nombre'];?></b></u></td>
                                <td align="center"><?php echo $params2[$i]['Tipo_Evento'];?></td>
                                <td align="center"><?php echo $params2[$i]['Estado_Evento'];?></td>
                                <td align="center"><?php echo $params2[$i]['Nombre_Usuario']." ".$params2[$i]['Apellido'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($params[0]['ID_Estado_Evento']==1 || $params[0]['ID_Estado_Evento']==2 ||
                    $params[0]['ID_Estado_Evento']==4) { ?>    
            <div class="row well">
                <h3  align="center" class="icon-caret-right" data-toggle="collapse" data-target="#Historial_seguimiento_evento"><span class="glyphicon glyphicon-chevron-right"></span>Agregar nuevo seguimiento</h3>
                <form class="collapse" id="Historial_seguimiento_evento" role="form" enctype="multipart/form-data" onSubmit="return enviado()" method="POST" action="index.php?ctl=guardar_seguimiento_evento&id=<?php echo trim($ide);?>">
                    <?php if ($_SESSION['modulos']['Adjuntar archivos- Seguimientos Bitácora']==1){ ?>
                        <div class="row espacio-abajo">
                            <div class="col-xs-12">
                                <label for="archivo_adjunto">Adjuntar Archivo: </label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                                <input type="file" name="archivo_adjunto" id="seleccionar_archivo" class="btn btn-default">
                            </div>   
                        </div>
                    <?php } ?>
                    <div class="row espacio-abajo">
                        <div class="col-xs-6">
                            <label for="Fecha">Fecha Seguimiento</label>
                            <input type="date" required=”required” class="form-control" id="Fecha" name="Fecha" value="<?php echo date("Y-m-d");?>">
                        </div>
                        <div class="col-xs-6">
                            <label for="Hora">Hora Seguimiento</label>
                            <input type="time" required=”required” class="form-control" id="Hora" name="Hora" value="<?php echo date("H:i", time());?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <label for="DetalleSeguimiento">Detalle del Seguimiento</label>
                            <textarea type="text" required=”required” class="form-control" id="DetalleSeguimiento" name="DetalleSeguimiento" value="" maxlength="500" minlength="5" placeholder="Máximo 500 caracteres por seguimiento"></textarea>
                        </div>
                        <div class="col-xs-6 espacio-abajo">
                            <label for="estado_del_evento">Estado del Evento</label>
                            <select class="form-control espacio-abajo" id="estado_del_evento" name="estado_del_evento" required=”required”> 
                                <?php
                                $tam = count($estadoEventos);
                                for($i=0; $i<$tam;$i++){
                                    if ($estadoEventos[$i]['Estado_Evento']!="Solicitar Cierre"){
                                        if ($estadoEventos[$i]['Estado_Evento']==$estado_evento){ ?>  
                                           <option value="<?php echo $estadoEventos[$i]['ID_Estado_Evento']?>" selected="selected" ><?php echo $estadoEventos[$i]['Estado_Evento']?></option>   
                                        <?php }else{?> 
                                           <option value="<?php echo $estadoEventos[$i]['ID_Estado_Evento']?>" ><?php echo $estadoEventos[$i]['Estado_Evento']?></option>   
                                        <?php }
                                    }
                                } ?> 
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default">Guardar Seguimiento</button>
                    <a href="index.php?ctl=frm_eventos_listar" class="btn btn-default" role="button">Cancelar</a>
                </form>
            </div>
            <?php } ?>
            
            <div class="row well">
                <h3 align="center">Seguimientos asociados</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th align="center">Fecha de Seguimiento</th>
                            <th align="center">Hora de Seguimiento</th>
                            <th align="center">Detalle del Seguimiento</th>
                            <th align="center">Ingresado Por</th>
                            <th align="center">Adjunto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tam=count($detalleEvento);
                        for ($i = 0; $i <$tam; $i++) { ?>
                            <tr>
                                <?php
                                $fecha_evento = date_create($detalleEvento[$i]['Fecha']);
                                $fecha_actual = date_create(date("d-m-Y"));
                                $dias_abierto= date_diff($fecha_evento, $fecha_actual); ?>
                                <td><?php echo date_format($fecha_evento, 'd/m/Y');?></td>
                                <td><?php echo $detalleEvento[$i]['Hora'];?></td> 
                                <td><?php echo $detalleEvento[$i]['Detalle'];?></td>
                                <td><?php echo $detalleEvento[$i]['Nombre_Usuario']." ".$detalleEvento[$i]['Apellido'] ?></td>
                                <?php if (strlen($detalleEvento[$i]['Adjunto'])==3){ ?>
                                    <td><?php echo $detalleEvento[$i]['Adjunto'];?></td>
                                <?php }else { ?>
                                    <td><a href="../../../Adjuntos_Bitacora/<?php echo $detalleEvento[$i]['Adjunto'];?>" download="<?php echo $detalleEvento[$i]['Adjunto'];?>"><img src="vistas/Imagenes/Descargar.png" class="img-rounded" alt="Cinque Terre" width="15" height="15"></a></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table> 
            </div>
            <div class="row">
                <a href="index.php?ctl=bitacora_digital_cajeros" class="btn btn-default" role="button">Volver</a>
            </div>
        </div>
        <?php require 'vistas/plantillas/pie_de_pagina.php'?>
    </body>
</html>