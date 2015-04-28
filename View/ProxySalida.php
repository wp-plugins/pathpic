<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApoderadoSalida
 *
 * @author george
 */
include_once "VistaGrafo.php";
include_once "SalidaRealTexto.php";
include_once "SalidaRealImagen.php";

class ProxySalida extends VistaGrafo {
    
    protected $grafoEval;
    protected $salidaReal;
    protected $estilo;
    
    function __construct() {
        //parent::__construct();
        $this->grafoEval= new validaVertice();     
    }
    
    function procesarGrafo($tamFont, $tipoSalida) {
        if ($tipoSalida == 'text') {
            $this->salidaReal = new SalidaRealTexto($this->estilo);        
             $this->salidaReal->setTamFont($tamFont);
            $this->salidaReal->setObjValidaVertice($this->grafoEval);
            $this->salidaReal->mostrarSalidaGrafo();
        }

        if ($tipoSalida == 'image') {
            $this->salidaReal = new SalidaRealImagen($this->estilo);             
            $this->salidaReal->setObjValidaVertice($this->grafoEval);
            $this->salidaReal->mostrarSalidaGrafo();
            
        }
    }
    
    function getWidth() {        
        return $this->salidaReal->getWidth();
    }
    
    function getHeight() {
        return $this->salidaReal->getHeight();
    }
    
    function getNroVertices() {
        return $this->salidaReal->getNroVertices();
    }
    
    function getStyle(){
        return $this->salidaReal->getStyle();
    }
    
    function guardarImagenes(){
        $this->salidaReal->guardarArchivosImagenes();        
    }
    
    function getImageB64(){
        return $this->salidaReal->getObjMuestraVertice()->getImagenBase64();
    }
    
    function getArbolString(){
        return $this->salidaReal->getObjMuestraVertice()->getArbolString();
    }
   
    
    function setGrafoEval(validaVertice $grafo){        
        $this->grafoEval = $grafo;
        
    }
    
    function getGrafoEval(){        
        return $this->grafoEval;
        
    }
    
    function setSalidaReal(SalidaReal $out){
        $this->salidaReal = $out;
        
    }
    
    function getEstilo(){
        return $this->estilo;        
    }
    
    function setEstilo($p_estilo){
        $this->estilo=$p_estilo;        
    }
    
    function actualizar() {
        
    }
    
}

?>
