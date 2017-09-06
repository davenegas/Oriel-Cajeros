<?php

class cls_estado_evento{
    public $id;
    public $estado_evento;
    public $prioridad;
    public $estado;
    public $obj_data_provider;
    public $arreglo;
    private $condicion;
   
    function getId() {
        return $this->id;
    }

    function getEstado_evento() {
        return $this->estado_evento;
    }

    function getPrioridad() {
        return $this->prioridad;
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

    function setEstado_evento($estado_evento) {
        $this->estado_evento = $estado_evento;
    }

    function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
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
        $this->estado_evento="";
        $this->prioridad="";
        $this->estado="";
        $this->obj_data_provider=new Data_Provider();
        $this->arreglo;
        $this->condicion="";
    }
    
    function edita_estado_evento(){
        $this->obj_data_provider->conectar();
        //Llama al metodo para editar los datos correspondientes
        $this->obj_data_provider->edita_datos("T_EstadoEvento","Estado_Evento='".$this->estado_evento."',Prioridad='".$this->prioridad."', Estado=".$this->estado,$this->condicion);
        //Metodo de la clase data provider que desconecta la sesión con la base de datos
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=$this->obj_data_provider->getResultado_operacion();
    }
    
    function agregar_estado_evento(){
        $this->obj_data_provider->conectar();
        $this->obj_data_provider->inserta_datos("T_EstadoEvento", "Estado_Evento, Prioridad, Estado", "'".$this->estado_evento."','".$this->prioridad."','".$this->estado."'");
        $this->obj_data_provider->desconectar();
    }
    
    function obtener_estado_evento(){
        $this->obj_data_provider->conectar();
        if($this->condicion==""){
            $this->arreglo=$this->obj_data_provider->trae_datos("T_EstadoEvento", "*", "");
        } else {
            $this->arreglo=$this->obj_data_provider->trae_datos("T_EstadoEvento", "*", $this->condicion);
        } 
        $this->arreglo=$this->obj_data_provider->getArreglo();
        $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true; 
    }
    
}