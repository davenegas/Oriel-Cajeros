<?php

class cls_evento{
    public $id;
    public $id2;
    public $fecha;
    public $hora;
    public $usuario;
    public $provincia;
    public $tipo_punto;
    public $puntobcr;
    public $tipo_evento;
    public $observaciones;
    public $detalle;
    public $Id_ultimo_evento_ingresado;
    public $adjunto;
    public $estado;
    public $obj_data_provider;
    public $arreglo;
    private $condicion;
   
    function getAdjunto() {
        return $this->adjunto;
    }

    function setAdjunto($adjunto) {
        $this->adjunto = $adjunto;
    }

    function getId_ultimo_evento_ingresado() {
        return $this->Id_ultimo_evento_ingresado;
    }

    function setId_ultimo_evento_ingresado($Id_ultimo_evento_ingresado) {
        $this->Id_ultimo_evento_ingresado = $Id_ultimo_evento_ingresado;
    }

    function getDetalle() {
        return $this->detalle;
    }

    function setDetalle($detalle) {
        $this->detalle = $detalle;
    }

    function getId() {
        return $this->id;
    }

    function getId2() {
        return $this->id2;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getHora() {
        return $this->hora;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getProvincia() {
        return $this->provincia;
    }

    function getTipo_punto() {
        return $this->tipo_punto;
    }

    function getPuntobcr() {
        return $this->puntobcr;
    }

    function getTipo_evento() {
        return $this->tipo_evento;
    }

    function getObservaciones() {
        return $this->observaciones;
    }

    function getEstado() {
        return $this->estado;
    }

    function getObj_data_provider() {
        return $this->obj_data_provider;
    }

    function getArreglo() {
        return $this->arreglo;
    }

    function getCondicion() {
        return $this->condicion;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setId2($id2) {
        $this->id2 = $id2;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setProvincia($provincia) {
        $this->provincia = $provincia;
    }

    function setTipo_punto($tipo_punto) {
        $this->tipo_punto = $tipo_punto;
    }

    function setPuntobcr($puntobcr) {
        $this->puntobcr = $puntobcr;
    }

    function setTipo_evento($tipo_evento) {
        $this->tipo_evento = $tipo_evento;
    }

    function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setObj_data_provider($obj_data_provider) {
        $this->obj_data_provider = $obj_data_provider;
    }

    function setArreglo($arreglo) {
        $this->arreglo = $arreglo;
    }

    function setCondicion($condicion) {
        $this->condicion = $condicion;
    }

        
    public function __construct() {
        $this->id="";
        $this->id2="";
        $this->fecha="";
        $this->hora="";
        $this->usuario="";
        $this->provincia="";
        $this->tipo_punto="";
        $this->puntobcr="";
        $this->tipo_evento="";
        $this->observaciones="";
        $this->estado="";
        $this->obj_data_provider=new Data_Provider();
        $this->arreglo;
        $this->condicion="";
    }
    
    //Este metodo realiza la modificación del estado del modulo, de activo a inactivo o viceversa en la bd
    function edita_estado_evento($nuevo_estado){
        $this->obj_data_provider->conectar();
        //Llama al metodo para editar los datos correspondientes
        $this->obj_data_provider->edita_datos("T_EventoCajero","ID_Estado_Evento=".$nuevo_estado,"ID_Evento=".$this->id);
        //Metodo de la clase data provider que desconecta la sesión con la base de datos
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=$this->obj_data_provider->getResultado_operacion();
    }
  
    //Este metodo realiza la modificación del estado del modulo, de activo a inactivo o viceversa en la bd
    function edita_notas_supervision_evento(){
        $this->obj_data_provider->conectar();
        //Llama al metodo para editar los datos correspondientes
        $this->obj_data_provider->edita_datos("T_EventoCajero","Observaciones_Evento='".$this->observaciones."',Fecha_Observaciones='".$this->fecha."'","ID_Evento=".$this->id);
        //Metodo de la clase data provider que desconecta la sesión con la base de datos
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=$this->obj_data_provider->getResultado_operacion();
    }
    
    //Obtener el último id de evento para saber que se debe ingresar
    function obtiene_id_ultimo_evento_ingresado(){
        //Establece la conexión con la bd
        $this->obj_data_provider->conectar();
        $this->obj_data_provider->trae_datos("T_EventoCajero","max(ID_Evento) ID_Evento","ID_Usuario=".$this->usuario." AND ID_Tipo_Evento=".$this->tipo_evento." AND ID_PuntoBCR=".$this->puntobcr);
        $this->arreglo=$this->obj_data_provider->getArreglo();
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;

        if (count($this->arreglo)>0){
            $this->setId_ultimo_evento_ingresado($this->arreglo[0]['ID_Evento']);
        } else {
            $this->setId_ultimo_evento_ingresado(1);
        }    
    }
    
    //Eventos de Bitacora
    public function obtiene_todos_los_eventos(){
        $this->obj_data_provider->conectar();
        if($this->condicion==""){
            $this->arreglo=$this->obj_data_provider->trae_datos(
                "T_EventoCajero
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_Provincia ON T_EventoCajero.ID_Provincia = bd_gerencia_seguridad.T_Provincia.ID_Provincia
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_TipoPuntoBCR ON T_EventoCajero.ID_Tipo_Punto = bd_gerencia_seguridad.T_TipoPuntoBCR.ID_Tipo_Punto
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_PuntoBCR ON T_EventoCajero.ID_PuntoBCR = bd_gerencia_seguridad.T_PuntoBCR.ID_PuntoBCR
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_Usuario ON T_EventoCajero.ID_Usuario = bd_gerencia_seguridad.T_Usuario.ID_Usuario
                    LEFT OUTER JOIN T_TipoEvento ON T_EventoCajero.ID_Tipo_Evento = T_TipoEvento.ID_Tipo_Evento
                    LEFT OUTER JOIN T_EstadoEvento ON T_EventoCajero.ID_Estado_Evento = T_EstadoEvento.ID_Estado_Evento", 
                "T_EventoCajero.ID_Evento, T_EventoCajero.Fecha, T_EventoCajero.Hora, T_EventoCajero.Observaciones_Evento, 
                    T_EventoCajero.Fecha_Observaciones,
                    T_Provincia.Nombre_Provincia, T_Provincia.ID_Provincia,
                    T_TipoPuntoBCR.Tipo_Punto, T_TipoPuntoBCR.ID_Tipo_Punto ,
                    T_PuntoBCR.Nombre, T_PuntoBCR.ID_PuntoBCR,T_PuntoBCR.Codigo,
                    T_TipoEvento.Tipo_Evento, T_TipoEvento.ID_Tipo_Evento,
                    T_EstadoEvento.ID_Estado_Evento, T_EstadoEvento.Estado_Evento, T_Usuario.ID_Usuario,
                    T_Usuario.Nombre Nombre_Usuario,T_Usuario.Apellido",
                "");
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        } else {
            $this->arreglo=$this->obj_data_provider->trae_datos(
                "T_EventoCajero
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_Provincia ON T_EventoCajero.ID_Provincia = bd_gerencia_seguridad.T_Provincia.ID_Provincia
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_TipoPuntoBCR ON T_EventoCajero.ID_Tipo_Punto = bd_gerencia_seguridad.T_TipoPuntoBCR.ID_Tipo_Punto
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_PuntoBCR ON T_EventoCajero.ID_PuntoBCR = bd_gerencia_seguridad.T_PuntoBCR.ID_PuntoBCR
                    LEFT OUTER JOIN bd_gerencia_seguridad.T_Usuario ON T_EventoCajero.ID_Usuario = bd_gerencia_seguridad.T_Usuario.ID_Usuario
                    LEFT OUTER JOIN T_TipoEvento ON T_EventoCajero.ID_Tipo_Evento = T_TipoEvento.ID_Tipo_Evento
                    LEFT OUTER JOIN T_EstadoEvento ON T_EventoCajero.ID_Estado_Evento = T_EstadoEvento.ID_Estado_Evento", 
                "T_EventoCajero.ID_Evento, T_EventoCajero.Fecha, T_EventoCajero.Hora, T_EventoCajero.Observaciones_Evento, T_EventoCajero.Fecha_Observaciones,
                    T_Provincia.Nombre_Provincia, T_Provincia.ID_Provincia,
                    T_TipoPuntoBCR.Tipo_Punto, T_TipoPuntoBCR.ID_Tipo_Punto ,
                    T_PuntoBCR.Nombre, T_PuntoBCR.ID_PuntoBCR,T_PuntoBCR.Codigo,
                    T_TipoEvento.Tipo_Evento, T_TipoEvento.ID_Tipo_Evento,
                    T_EstadoEvento.ID_Estado_Evento, T_EstadoEvento.Estado_Evento, T_Usuario.ID_Usuario,
                    T_Usuario.Nombre Nombre_Usuario,T_Usuario.Apellido",
                $this->condicion);
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        }
    }
    
    //Detalles de bitacora
    public function obtiene_detalle_evento(){
        try{
        $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos(
                "T_DetalleEvento "
                    . "inner join bd_gerencia_seguridad.T_Usuario on T_DetalleEvento.ID_Usuario=bd_gerencia_seguridad.T_Usuario.ID_Usuario "
                    . "inner join bd_control_seguimiento_cajero.T_EventoCajero on T_EventoCajero.ID_Evento=T_DetalleEvento.ID_Evento "
                    . "inner join bd_gerencia_seguridad.T_PuntoBCR on bd_gerencia_seguridad.T_PuntoBCR.ID_PuntoBCR=T_EventoCajero.ID_PuntoBCR "
                    . "inner join bd_control_seguimiento_cajero.T_TipoEvento on T_TipoEvento.ID_Tipo_Evento=T_EventoCajero.ID_Tipo_Evento", 
                "T_DetalleEvento.*,T_Usuario.Nombre Nombre_Usuario,T_Usuario.Apellido,"
                    . "concat(concat(concat(T_PuntoBCR.Nombre,' ['),T_TipoEvento.Tipo_Evento),']') as PuntoBCR_TipoEvento",
                $this->condicion);
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;
        }   catch (Exception $e){
            //
        }
    }
    
    //Metodo utilizado en el momento que escogen algun tipo de punto o provincia en específico (actualizacione en vivo, en pantalla)
    public function filtra_sitios_bcr_bitacora(){
        try{
        $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos(
                "t_puntobcr INNER JOIN t_Distrito ON t_PuntoBCR.ID_Distrito=t_Distrito.ID_Distrito INNER JOIN t_Canton ON t_Distrito.ID_Canton=t_Canton.ID_Canton INNER JOIN t_Provincia ON t_Canton.ID_Provincia=t_Provincia.ID_Provincia", 
                "t_PuntoBCR.ID_PuntoBCR, t_PuntoBCR.Nombre",
                $this->condicion." ORDER BY t_PuntoBCR.Nombre ASC");
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;
        }   catch (Exception $e){
        }
    }
    
    //Valida que no se ingrese el mismo tipo de evento en un sitio, si ya hay uno pendiente
    function existe_abierto_este_tipo_de_evento_en_este_sitio($id_tipo_evento, $id_puntobcr){
        //Establece la conexión con la bd
        $this->obj_data_provider->conectar();
        $this->obj_data_provider->trae_datos("T_EventoCajero","*",
            "ID_Tipo_Evento=".$id_tipo_evento." AND ID_PuntoBCR=".$id_puntobcr." AND ID_Estado_Evento<>3"." AND ID_Estado_Evento<>5");
        $this->arreglo=$this->obj_data_provider->getArreglo();
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;

        if (count($this->arreglo)>0){
            return true;
        } else {
            return false;
        }    
    }
 
    function obtener_estado_evento(){
        $this->obj_data_provider->conectar();
        if($this->condicion==""){
            $this->obj_data_provider->trae_datos("T_EstadoEvento","*","");
        } else {
            $this->obj_data_provider->trae_datos("T_EstadoEvento","*",$this->condicion);
        }
        $this->arreglo=$this->obj_data_provider->getArreglo();
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;
    }
    
    public function ingresar_evento(){
        try{
            $this->obj_data_provider->conectar();
            $this->obj_data_provider->inserta_datos("T_EventoCajero","Fecha, Hora, ID_Usuario, ID_Provincia, ID_Tipo_Punto, ID_PuntoBCR, ID_Tipo_Evento, ID_Estado_Evento",
                "'".$this->fecha."','".$this->hora."','".$this->usuario."','".$this->provincia."','".$this->tipo_punto."','".$this->puntobcr."','".$this->tipo_evento."','".$this->estado."'");
            $this->obj_data_provider->desconectar();
        } catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
    
    public function ingresar_seguimiento_evento(){
        try{
            $this->obj_data_provider->conectar();
            $this->obj_data_provider->inserta_datos("T_DetalleEvento","ID_Evento, ID_Usuario, Fecha, Hora, Detalle, Adjunto",
                    "'".$this->id."','".$this->usuario."','".$this->fecha."','".$this->hora."','".$this->detalle."','".$this->adjunto."'");
            $this->obj_data_provider->desconectar();
        }  catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
}