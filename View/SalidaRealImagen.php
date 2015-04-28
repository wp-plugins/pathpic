<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SalidaImagen
 *
 * @author george
 */
include_once "SalidaReal.php";

class SalidaRealImagen extends SalidaReal{
    //put your code here
    public $objMuestraVertice;
    public $objValidaVertice;
    
    function __construct($p_estilo){
        $this->objMuestraVertice= new MuestraVerticeImagen($p_estilo);
    }
    function mostrarSalidaGrafo(){
        
        // Crear objetoGraph
        //$objGraph = new validaVertice();
        $objGraph = clone $this->objValidaVertice;//, 'validaVertice');
        $val = $objGraph->getNVerts();
        //echo "<br>-Nro Vertices : {$objGraph->getNVerts()}-<br>";
        //Pasar datos del objeto ligero;
        $this->objMuestraVertice->setParams($objGraph->getParams());
        
        $this->objMuestraVertice->setAdjMat($objGraph->getAdjMat());
        
        $this->objMuestraVertice->setVertexList($objGraph->getVertexList());
        
        $this->objMuestraVertice->setNVerts($objGraph->getNVerts());
        
        $this->objMuestraVertice->setNombreArchSalida($objGraph->getNombreArchSalida());
        
        $this->objMuestraVertice->setNroLineas($objGraph->getNroLineas());                
        
        $this->objMuestraVertice->setHighLight($objGraph->getHighLight());
        
        $this->objMuestraVertice->setRutaArchFont($objGraph->getRutaArchFont());        
        
        $this->objMuestraVertice->setPathformat($objGraph->getPathformat());
        
        $this->objMuestraVertice->setVerticeMargenDerecho($objGraph->getVerticeMargenDerecho());
        
        //$this->objMuestraVertice->setTamLetra($objGraph->getTamLetra());
                                
        $dxArchSalida = pathinfo( $this->objMuestraVertice->getRutaArchSalida());
        
        //Se crea el grafico
        
        
        $this->objMuestraVertice->pintarLineasVerticales();
                
        $nodoCero = $this->objMuestraVertice->getVertex(0);
        
        //echo "<br>-{$nodoCero->dirname}-$nodoCero->basename-<br>";        
        
        $this->objMuestraVertice->dfsRecorrer($nodoCero->dirname, $nodoCero->basename, $nodoCero->nivel);
        
            }
    
    public function guardarArchivosImagenes(){
        //Crear Archivo en PNG
        $this->objMuestraVertice->guardarArchivoImagen('png');
        
        //Crear Archivo en JPG
        $this->objMuestraVertice->guardarArchivoImagen('jpg');
        
        //Crear Archivo en GIF
        $this->objMuestraVertice->guardarArchivoImagen('gif');
    }
    
    function getImageB64(){
        return $this->objMuestraVertice->getImagenBase64();
    }
    
    function getWidth(){
        return $this->objMuestraVertice->getWidth();
    }
    
     function getHeight(){          
         
        return $this->objMuestraVertice->getHeight();
    }
    
    function getNroVertices(){         
        return $this->objMuestraVertice->getNVerts();        
    }
        
    function getStyle(){        
        
        return $this->objMuestraVertice->getStyle();
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
    
    function getObjMuestraVertice(){
        return $this->objMuestraVertice;
    }
    
}

?>
