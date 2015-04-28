<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VistaGrafo
 *
 * @author george
 */
include_once 'Model/Graph.php';
include_once 'Model/validaVertice.php';
include_once 'Model/MuestraVerticeImagen.php';

abstract class VistaGrafo{
    //put your code here
        
    protected $salidaReal;
    
    function __construct(){
        
    }
    function procesarGrafo($tamFont,$tipoSalida) {        
    }
    
    abstract function getWidth();
    
    abstract  function getHeight();
    
    abstract function getNroVertices();
}

?>
