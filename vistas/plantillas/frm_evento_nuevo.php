<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Agregar Evento</title>
        <script language="javascript" src="vistas/js/jquery.js"></script>
        <link rel="stylesheet" href="vistas/css/ventanaoculta.css">
        <?php require_once 'frm_librerias_head.html'; ?>  
        <script>
            $(document).ready(function(){
                $("#tipo_punto").change(function () {
                    $("#tipo_punto option:selected").each(function () {
                        id_tipo_punto_bcr = $(this).val();
                        id_provincia=document.getElementById('nombre_provincia').value;
                        $.post("index.php?ctl=actualiza_en_vivo_punto_bcr", { id_tipo_punto_bcr: id_tipo_punto_bcr,id_provincia:id_provincia }, function(data){
                            $("#punto_bcr").html(data);
                            //console.log(data);
                        });            
                    });
                });
                
                $("#nombre_provincia").change(function () {
                    $("#nombre_provincia option:selected").each(function () {
                        id_provincia = $(this).val();
                        id_tipo_punto_bcr=document.getElementById('tipo_punto').value;
                        console.log(id_tipo_punto_bcr);
                        $.post("index.php?ctl=actualiza_en_vivo_punto_bcr", { id_tipo_punto_bcr: id_tipo_punto_bcr,id_provincia:id_provincia }, function(data){
                            $("#punto_bcr").html(data); 
                            //console.log(data);
                        });            
                    });
                });

                $("#punto_bcr").change(function () {
                    $("#punto_bcr option:selected").each(function () {
                        id_punto_bcr = $(this).val();
                        id_tipo_evento=document.getElementById('tipo_evento').value;
                        $.post("index.php?ctl=alerta_en_vivo_mismo_punto_bcr_y_evento", { id_punto_bcr: id_punto_bcr,id_tipo_evento:id_tipo_evento }, function(data){
                            //$(document).html(data);
                            // alert("_"+data+"_");
                            //console.log(data);
                            var str = data;
                            var n = str.search("BCR");

                            if (n!=-1){
                                alert(data.trim());
                            }
                        }); 
                        $.post("index.php?ctl=dibuja_tabla_eventos_relacionados_a_punto_bcr", { id_punto_bcr: id_punto_bcr}, function(data){
                            $("#table").html(data); 
                            //console.log(data);
                        });
                    });
                });
                
                $("#tipo_evento").change(function () {
                    $("#tipo_evento option:selected").each(function () {
                        id_tipo_evento = $(this).val();
                        if(document.getElementById('punto_bcr').value==""){
                            id_punto_bcr=0;
                        }else{
                            id_punto_bcr=document.getElementById('punto_bcr').value;
                        }          
                        $.post("index.php?ctl=alerta_en_vivo_mismo_punto_bcr_y_evento", { id_punto_bcr: id_punto_bcr,id_tipo_evento:id_tipo_evento }, function(data){
                            //$(document).html(data);
                            //alert("_"+data+"_");
                            var str = data;
                            var n = str.search("BCR");

                            if (n!=-1){
                                alert(data.trim());
                            }
                        });    
//                        $.post("index.php?ctl=actualiza_en_vivo_estado_evento", { id_tipo_evento: id_tipo_evento}, function(data){
//                            $("#estado_evento").html(data);
//                        });
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
        <?php require_once 'encabezado.php'; ?>
        
        <div class="container animated fadeIn">
            <h2>Agregar Evento para Bitácora</h2>
            <hr/> 
            <form class="form-horizontal" role="form" method="POST" action="index.php?ctl=guardar_evento_cajero">
                <div class="row espacio-abajo">
                    <div class="col-xs-4">
                        <label for="fecha">Fecha</label>
                        <input type="date" required=”required” class="form-control" id="fecha" name="fecha" value="<?php echo date("Y-m-d");?>">
                    </div> 
                    <div class="col-xs-4">
                        <label for="hora">Hora</label>
                        <input type="time" required=”required” class="form-control" id="hora" name="hora" value="<?php echo date("H:i", time());?>">
                    </div>         
                    <div class="col-xs-4">
                        <label for="tipo_evento">Tipo de Evento</label>
                        <select class="form-control" required=”required” id="tipo_evento" name="tipo_evento" > 
                            <?php
                            $tam_tipo_eventos = count($lista_tipos_de_eventos);
                            for($i=0; $i<$tam_tipo_eventos;$i++){ ?> 
                                <option value="<?php echo $lista_tipos_de_eventos[$i]['ID_Tipo_Evento']?>"><?php echo $lista_tipos_de_eventos[$i]['Tipo_Evento']?></option>
                            <?php } ?>  
                        </select>
                    </div>
                </div>
                <div class="row espacio-abajo">
                    <div class="col-xs-4">
                        <label for="nombre_provincia">Provincia</label>
                        <select class="form-control" required=”required” id="nombre_provincia" name="nombre_provincia" > 
                            <?php
                            $tam_provincias = count($lista_provincias);

                            for($i=0; $i<$tam_provincias;$i++){
                                if($lista_provincias[$i]['ID_Provincia']==$cantones[$distritos[$params[0]['ID_Distrito']]['ID_Canton']]['ID_Provincia']){ ?> 
                                    <option value="<?php echo $lista_provincias[$i]['ID_Provincia']?>" selected="selected"><?php echo $lista_provincias[$i]['Nombre_Provincia']?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $lista_provincias[$i]['ID_Provincia']?>"><?php echo $lista_provincias[$i]['Nombre_Provincia']?></option>  
                                <?php }
                            } ?>  
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <label for="tipo_punto">Tipo Punto</label>
                        <select class="form-control" required=”required” id="tipo_punto" name="tipo_punto" > 
                            <?php
                            $tam_tipo_punto_bcr = count($lista_tipos_de_puntos_bcr);

                            for($i=0; $i<$tam_tipo_punto_bcr;$i++){
                                if($lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']==$params[0]['ID_Tipo_Punto']){ ?> 
                                    <option value="<?php echo $lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']?>" selected="selected"><?php echo $lista_tipos_de_puntos_bcr[$i]['Tipo_Punto']?></option>
                                <?php } else {?>
                                    <option value="<?php echo $lista_tipos_de_puntos_bcr[$i]['ID_Tipo_Punto']?>"><?php echo $lista_tipos_de_puntos_bcr[$i]['Tipo_Punto']?></option> 
                                <?php }
                            } ?>  
                        </select>
                    </div>
                    <div class="col-xs-4">
                    <label for="punto_bcr">Punto BCR</label>
                    <select class="form-control" required=”required” id="punto_bcr" name="punto_bcr" >
                        <?php if($ide==0){
                            $tam_puntos_bcr=count($lista_puntos_bcr_sj);
                            for($i=0; $i<$tam_puntos_bcr;$i++){
                                if ($i==0){ ?>
                                    <option value="<?php echo $lista_puntos_bcr_sj[$i]['ID_PuntoBCR']?>" selected="selected"><?php echo $lista_puntos_bcr_sj[$i]['Nombre']?></option>                           
                                <?php }else{ ?>
                                    <option value="<?php echo $lista_puntos_bcr_sj[$i]['ID_PuntoBCR']?>"><?php echo $lista_puntos_bcr_sj[$i]['Nombre']?></option>                           
                                <?php }
                            } ?>  
                        <?php } ?>
                    </select>
                </div>
                </div>
                <div class="row espacio-abajo">
                    <div class="col-xs-6">
                        <label for="seguimiento">Detalle del Evento</label>
                        <textarea type="text" class="form-control" id="seguimiento" name="seguimiento" value="" maxlength="500" placeholder="Máximo 500 caracteres por seguimiento"></textarea>
                    </div>
                    <div class="col-xs-6">
                        <label for="estado_evento">Estado Evento</label>
                        <select class="form-control" required=”required” id="estado_evento" name="estado_evento" >
                            <?php if($ide==0){
                                $tam_estado_evento=count($estado_evento);
                                for($i=0; $i<$tam_estado_evento;$i++){ ?>
                                    <option value="<?php echo $estado_evento[$i]['ID_Estado_Evento']?>"><?php echo $estado_evento[$i]['Estado_Evento']?></option>                           
                                <?php } ?>  
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row espacio-abajo">
                    <div>
                        <button type="submit" class="btn btn-default" >Guardar</button>
                        <a href="index.php?ctl=bitacora_digital_cajeros" class="btn btn-default" role="button">Cancelar</a>
                    </div>
                </div>
            </form> 
            
            <h2>Histórico de Eventos Relacionados a este Punto BCR:</h2>
            <table id='table' class="table">
            </table>
        </div>
        <?php require_once 'pie_de_pagina.php' ?>
        
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