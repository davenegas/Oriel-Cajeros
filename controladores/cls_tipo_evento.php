<?php

class cls_tipo_evento{
    public $id;
    public $tipo_evento;
    public $prioridad;
    public $observaciones;
    public $estado;
    public $obj_data_provider;
    public $arreglo;
    private $condicion;
   
    function getPrioridad() {
        return $this->prioridad;
    }

    function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }

    function getId() {
        return $this->id;
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
        $this->tipo_evento="";
        $this->prioridad="";
        $this->observaciones="";
        $this->estado="";
        $this->obj_data_provider=new Data_Provider();
        $this->arreglo;
        $this->condicion="";
    }
    
    function edita_tipo_evento(){
        $this->obj_data_provider->conectar();
        //Llama al metodo para editar los datos correspondientes
        $this->obj_data_provider->edita_datos("T_TipoEvento","Tipo_Evento='".$this->tipo_evento."',Prioridad='".$this->prioridad."',Observaciones='".$this->observaciones."', Estado=".$this->estado,$this->condicion);
        //Metodo de la clase data provider que desconecta la sesión con la base de datos
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=$this->obj_data_provider->getResultado_operacion();
    }
    
    function agregar_tipo_evento(){
        $this->obj_data_provider->conectar();
        $this->obj_data_provider->inserta_datos("T_TipoEvento", "Tipo_Evento, Prioridad, Observaciones, Estado", "'".$this->tipo_evento."','".$this->prioridad."','".$this->observaciones."','".$this->estado."'");
        $this->obj_data_provider->desconectar();
    }
    
    function obtener_tipo_evento(){
        $this->obj_data_provider->conectar();
        if($this->condicion==""){
            $this->arreglo=$this->obj_data_provider->trae_datos("T_TipoEvento", "*", "");
        } else {
            $this->arreglo=$this->obj_data_provider->trae_datos("T_TipoEvento", "*", $this->condicion);
        } 
        $this->arreglo=$this->obj_data_provider->getArreglo();
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true; 
    }
    
}