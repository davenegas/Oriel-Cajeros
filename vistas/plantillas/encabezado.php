<html lang="en"> 
    <head>
        <link rel="stylesheet" href="vistas/css/main.css">
        <!--<script src="vistas/js/jquery-1-4-2-min.js"></script>-->
        <style>
            .dropdown-submenu {
                position: relative;
            }
            .dropdown-submenu .dropdown-menu {
                top: 0;
                left: 100%;
                margin-top: -1px;
            }
        </style>
    </head>
    <center><img src="vistas/Imagenes/Banner_Centro_de_Control.jpg" alt=""/></center>
    <nav class="navbar navbar-default" >
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php?ctl=inicio"><b>Control y Seguimiento de ATM's</b></a>
            </div>
            
            <ul class="nav navbar-nav">
                <?php 
                //************************************************Pinta Menu de Catalogos***************************************************************
                if (($_SESSION['modulos']['Catálogos Cajeros-Tipo Evento']==1)){  ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Catálogos
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['modulos']['Catálogos Cajeros-Tipo Evento']==1){ ?>
                                <li><a href="index.php?ctl=tipo_evento_listar">Tipo Evento</a></li>
                            <?php } ?>
                        </ul>
                    </li>

                <?php }; ?>

                <?php 
                //************************************************Pinta Menu de Reportes***************************************************************
                if (($_SESSION['modulos']['Reportes Cajeros-Eventos']==1)){ ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reportes
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu multi-level" role="menu">
                            <?php if ($_SESSION['modulos']['Reportes Cajeros-Eventos']==1){ ?>
                                <li><a href="">Bitácora</a></li> 
                            <?php } ?>
                        </ul>
                    </li>

                <?php } ?>
                <?php 
                //************************************************Pinta Menu de Módulos***************************************************************
                if (($_SESSION['modulos']['Módulo Cajeros-Bitácora Digital']==1)){?>

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Módulos
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['modulos']['Módulo Cajeros-Bitácora Digital']==1){ ?>
                                <li><a href="index.php?ctl=bitacora_digital_cajeros">Bitácora Digital</a></li>
                            <?php } ?>
                            
                        </ul>
                    </li>

                <?php } ?>

                <?php 
                //************************************************Pinta Menu de Proyectos***************************************************************
                if (($_SESSION['modulos']['Módulo-PuntosBCR']==1)){ ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Proyectos
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            
                            <li><a href="http://10.170.5.92:8080/Oriel/index.php?ctl=principal">Principal Oriel</a></li> 
                            
                            <?php if ($_SESSION['modulos']['Módulo-PuntosBCR']==1){ ?>
                                <li><a href="http://10.170.5.92:8080/Oriel/index.php?ctl=puntos_bcr_listar">Puntos BCR</a></li> 
                            <?php } ?>      
                        </ul>
                    </li>
                <?php } ?>
                    
                <?php 
                //************************************************Pinta Menú de Ayuda***************************************************************
                if (($_SESSION['modulos']['Ayuda']==1)){ ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Ayuda
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            
                            <li><a href="http://10.170.5.92:8080/Oriel/index.php?ctl=manual_ayuda_privado&manual=Usuario_Inicial">Manual Usuario Inicial</a></li>
                            
                            <?php if ($_SESSION['modulos']['Módulo Cajeros-Bitácora Digital']==1){ ?>
                                <li><a href="http://10.170.5.92:8080/Oriel/index.php?ctl=manual_ayuda_privado&manual=Bitacora_Digital_Cajeros">Manual Bitácora Digital Cajeros</a></li>
                            <?php }; ?>  
                        </ul>
                    </li>
                <?php } ?>       
            </ul>  

            <ul class="nav navbar-nav navbar-right">    
                <li><a href="index.php?ctl=inicio"><span class="glyphicon glyphicon-user"></span><?php echo $_SESSION['name']." ".$_SESSION['apellido'];?></a></li>
                <li><a href="http://10.170.5.92:8080/Oriel/index.php?ctl=cerrar_sesion"><span class="glyphicon glyphicon-log-in"></span>Cerrar Sesión</a></li>    
            </ul>
      </div>
    </nav>
    
    <script>
        $(document).ready(function(){
            $('.dropdown-submenu a.multilevel').on("click", function(e){
                $(this).next('ul').toggle();
                e.stopPropagation();
                e.preventDefault();
            });
        });
    </script>
</html>