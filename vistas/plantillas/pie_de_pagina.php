<!Realizar comentarios de una línea en HTML>
<!-- El siguiente código crea el pie de página standard
del sitio web, mediante etiquetas HTML muestra la información
requerida
 --> 
<html>
    <!--<center><img src="vistas/Imagenes/Oriel.jpg" alt=""/></center>-->
    <footer class="container-fluid" align="center">
        <hr/>
        Banco de Costa Rica - Control y Seguimiento de ATM's &copy; <br>
        <?php 
        $hoy = date("F j, Y, g:i a"); ?>
           
        <?php
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
        echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
        echo ", ".date("H:i", time()) . " hrs";
        ?>
    </footer>
</html> 