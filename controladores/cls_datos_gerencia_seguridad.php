<?php
class cls_datos_gerencia_seguridad {
    public $obj_data_provider;
    public $arreglo;
    private $condicion;
    
    function getObj_data_provider() {
        return $this->obj_data_provider;
    }

    function getArreglo() {
        return $this->arreglo;
    }

    function getCondicion() {
        return $this->condicion;
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
        $this->obj_data_provider=new Data_Provider();
        $this->arreglo;
        $this->condicion="";
    }
    
    public function obtener_todas_las_provincias(){
        try{
            $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos("bd_gerencia_seguridad.T_Provincia", "*", "Estado=1");
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        }  catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
    
    public function obtener_puntos_bcr_por_provincia_y_tipo_de_punto(){
        try{
            $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos("bd_gerencia_seguridad.T_PuntoBCR", "ID_PuntoBCR, concat(Nombre,' #',Codigo) Nombre", $this->condicion);
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        }  catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
    
    public function obtener_todos_los_tipos_de_puntos_BCR(){
        try{
            $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos("bd_gerencia_seguridad.T_TipoPuntoBCR", "*", $this->condicion);
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        }  catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
    
    public function obtener_puntos_BCR_filtrados(){
        try{
            $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos("bd_gerencia_seguridad.T_PuntoBCR", "*", "Estado=1 AND ".$this->condicion);
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
            $this->resultado_operacion=true;
        }  catch (Exception $exc){
            echo $exc->getTraceAsString();
        }
    }
    
    //Metodo utilizado en el momento que escogen algun tipo de punto o provincia en específico (actualizacione en vivo, en pantalla)
    public function filtra_sitios_bcr_bitacora(){
        try{
        $this->obj_data_provider->conectar();
            $this->arreglo=$this->obj_data_provider->trae_datos("bd_gerencia_seguridad.t_puntobcr "
                    . "INNER JOIN bd_gerencia_seguridad.t_Distrito ON t_PuntoBCR.ID_Distrito=t_Distrito.ID_Distrito "
                    . "INNER JOIN bd_gerencia_seguridad.t_Canton ON t_Distrito.ID_Canton=t_Canton.ID_Canton "
                    . "INNER JOIN bd_gerencia_seguridad.t_Provincia ON t_Canton.ID_Provincia=t_Provincia.ID_Provincia", 
                "t_PuntoBCR.ID_PuntoBCR, concat(t_puntobcr.Nombre,' #',t_puntobcr.Codigo) Nombre",
                $this->condicion." ORDER BY t_PuntoBCR.Nombre ASC");
            $this->arreglo=$this->obj_data_provider->getArreglo();
            $this->obj_data_provider->desconectar();
        $this->resultado_operacion=true;
        }   catch (Exception $e){}
    }
}
