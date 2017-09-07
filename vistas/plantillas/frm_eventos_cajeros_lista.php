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
                <a href="index.php?ctl=frm_eventos_agregar&id=0" class="btn btn-default espacio-abajo" role="button">Agregar evento nuevo</a>
                <a href="index.php?ctl=frm_eventos_lista_cerrados" class="btn btn-default espacio-abajo quitar-float" role="button">Eventos Cerrados</a> 
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
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $tama=count($params);
                        for ($i = 0; $i <$tama; $i++) { ?>
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
                                <td style="text-align:center"><a>Editar</a></td>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>
        </div>
        <?php require 'vistas/plantillas/pie_de_pagina.php' ?> 
    </body>
</html>