<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Lista de Eventos</title>
        <?php require_once 'frm_librerias_head.html'; ?>    
<!--        <link rel="stylesheet" href="vistas/css/ventanaoculta.css"> 
        <script language="javascript" src="vistas/js/refresca_pagina_automaticamente.js"></script>  -->
    </head>
    <body>
        <?php require_once 'encabezado.php';?>
        <div class="container animated fadeIn col-xs-10 quitar-float">
            <div class="col-md-5">
                <h2>Listado de Eventos</h2>
                <a href="index.php?ctl=evento_agregar" class="btn btn-default espacio-abajo" role="button">Agregar evento nuevo</a>
                <a href="index.php?ctl=eventos_cajeros_cerrados" class="btn btn-default espacio-abajo quitar-float" role="button">Eventos Cerrados</a> 
            </div>
            
            <table id="tabla" class="display">
                <thead>
                    <tr>
                        <th hidden="true">ID_Evento</th>
                        <th style="text-align:center">Fecha</th>
                        <th style="text-align:center">Hora</th>
                        <th style="text-align:center">Lapso</th>
                        <th style="text-align:center">Provincia</th>
                        <th style="text-align:center">Tipo Punto</th>
                        <th style="text-align:center">Punto BCR</th>
                        <th style="text-align:center">Código</th>
                        <th style="text-align:center">Tipo de Evento</th>
                        <th style="text-align:center">Estado del Evento</th>
                        <th style="text-align:center">Usuario</th>
                        <th style="text-align:center">Editar Evento</th>
                        <th hidden="">seguimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i <count($params); $i++) { ?>
                        <tr>
                            <td hidden><?php echo $params[$i]['ID_Evento'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Fecha'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Hora'];?></td>
                            <td style="text-align:center">Pend</td>
                            <td style="text-align:center"><?php echo $params[$i]['Nombre_Provincia'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Tipo_Punto'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Nombre'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Codigo'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Tipo_Evento'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Estado_Evento'];?></td>
                            <td style="text-align:center"><?php echo $params[$i]['Nombre_Usuario']." ".$params[$i]['Apellido'];?></td>
                            <td style="text-align:center"><a href="index.php?ctl=evento_cajero_editar&id=<?php echo $params[$i]['ID_Evento']?>">Editar</a></td>
                            <td style="text-align:center" id="<?php echo $params[$i]['ID_Evento'];?>" hidden="hidden">
                                <table class="table-condensed" id="tbl<?php echo $params[$i]['ID_Evento'];?>">
                                    <thead>
                                        <tr>
                                            <th>Fecha de Seguimiento</th>
                                            <th>Hora de Seguimiento</th>
                                            <th>Detalle del Seguimiento</th>
                                            <th>Ingresado Por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $tama=count($todos_los_seguimientos_juntos);
                                        for ($j = 0; $j <$tama; $j++) { 
                                            if(isset($todos_los_seguimientos_juntos[$j]['Fecha'])){ ?>
                                                <tr>
                                                    <?php
                                                    $fecha_evento = date_create($todos_los_seguimientos_juntos[$j]['Fecha']);
                                                    $fecha_actual = date_create(date("d-m-Y"));
                                                    $dias_abierto= date_diff($fecha_evento, $fecha_actual);
                                                    if ($params[$i]['ID_Evento']==$todos_los_seguimientos_juntos[$j]['ID_Evento']){  ?>
                                                        <td><?php echo date_format($fecha_evento, 'd/m/Y');?></td>
                                                        <td><?php echo $todos_los_seguimientos_juntos[$j]['Hora'];?></td>
                                                        <td><?php echo $todos_los_seguimientos_juntos[$j]['Detalle'];?></td>
                                                        <td><?php echo $todos_los_seguimientos_juntos[$j]['Nombre_Usuario']." ".$todos_los_seguimientos_juntos[$j]['Apellido'] ?></td>               

                                                    <?php } ?>
                                                </tr>
                                            <?php } 
                                        } ?>
                                    </tbody>
                                </table>  
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php require 'vistas/plantillas/pie_de_pagina.php' ?> 
    </body>
</html>