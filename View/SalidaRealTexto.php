<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SalidaTexto
 *
 * @author george
 */
include_once "SalidaReal.php";

class SalidaRealTexto extends SalidaReal{
    
    public $objMuestraVertice;
    public $objValidaVertice;
    private $tamFont;
    
    function __construct($p_estilo){
        $this->objMuestraVertice= new MuestraVertice($p_estilo);
    }
    
    /*mostrarSalidaGrafo
     * abarca todo el proceso de salida por pantalla del Grafo en modo texto
     * 
     */
    function mostrarSalidaGrafo(){
        
        // Crear objetoGraph
        //$objGraph = new validaVertice();
        //objValidaVertice es establecido despues de creado este objeto desde el Proxy
        $objGraph = clone $this->objValidaVertice;//, 'validaVertice');
        $val = $objGraph->getNVerts();
        //echo "<br>-Nro Vertices : {$objGraph->getNVerts()}-<br>";
        //Pasar datos del objeto ligero;
        $this->objMuestraVertice->setAdjMat($objGraph->getAdjMat());
        
        $this->objMuestraVertice->setVertexList($objGraph->getVertexList());
        
        $this->objMuestraVertice->setNVerts($objGraph->getNVerts());
        
        $this->objMuestraVertice->setPathformat($objGraph->getPathformat());
        
        $this->objMuestraVertice->setPhp_os($objGraph->getPhp_os());
                         
        $this->objMuestraVertice->setHighLight($objGraph->getHighLight());
        
        $this->objMuestraVertice->setVerticeMargenDerecho($objGraph->getVerticeMargenDerecho());
                
        $nodoCero = $this->objMuestraVertice->getVertex(0);
        
        //echo "<br>-{$nodoCero->dirname}-$nodoCero->basename-<br>";
        
        //Previamente llenar una matriz para las lineas verticales
        $this->objMuestraVertice->obtenerLineasVerticales();
        //recorrer el Grafo para llenar el objeto imagen en objMuestraVertice
        
        $this->objMuestraVertice->dfsRecorrer($nodoCero->dirname, $nodoCero->basename, $nodoCero->nivel);
  
    }
    
    function setObjValidaVertice(validaVertice $valV){
        $this->objValidaVertice = $valV;
        
    }
    
    function getObjValidaVertice(){
        return $this->objValidaVertice;
    }
    
    function setObjMuestraVertice(MuestraVertice $valV){
        $this->objMuestraVertice = $valV;
        
    }
    
    function setTamFont($tam){
        $this->tamFont=$tam;
        $this->objMuestraVertice->setTamLetra($tam);
    }
    
    function getObjMuestraVertice(){
        return $this->objMuestraVertice;
    }
    
    function getWidth(){
        /*
        $margenX = 20;
        $id = $this->objMuestraVertice->getVerticeMargenDerecho();       
        //obtener el vertice de mas a la derecha
        $vertR = $this->objMuestraVertice->getVertex($id);
        
        $GraphWidth = (2 * $margenX) + (strlen($vertR->basename)*$this->tamFont) + (($vertR->nroCol +2) *15);        
        //$GraphWidth = (2 * $margenX) + $this->objMuestraVertice->getAnchoTexto() + (($this->objMuestraVertice->getNivelMax() + 2) * 40);        
        return $GraphWidth;
        */
        return $this->objMuestraVertice->getWidth();
        
    }
    
     function getHeight(){
         /*
         $margenY = 7;  //diametro de borde redondeado del div      
         //$GraphHeight = 3.5 * $margenY + ($this->objMuestraVertice->getNVerts() * 20);
         $GraphHeight = 2* $margenY + ($this->objMuestraVertice->getNVerts() *  $this->tamFont);
        
         return $GraphHeight;      
        */         
        return $this->objMuestraVertice->getHeight();
    }
    
    function getNroVertices(){         
        return $this->objMuestraVertice->getNVerts();        
    }
    
    
    function getArbolString(){
        return $this->objMuestraVertice->getArbolString();
    }
    
}

?>
