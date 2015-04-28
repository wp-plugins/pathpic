<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControladorGrafo
 *
 * @author george
 */
//include_once plugins_url('Model/validaVertice.php', __FILE__);
include_once 'Model/validaVertice.php';
include_once 'Model/MuestraVertice.php';
include_once 'View/ProxySalida.php';

class 
ControladorGrafo {

    //Modelo 
    private $graph;
    private $listaRutas;
    private $pathformat;
    private $files;
    private $highlights;
    //Vista 
    private $salida;

    function __construct($_listaRutas, $params, $_files, $_highlights) {
        $this->graph = new validaVertice(); //newGraph();
        $this->graph->setParams($params);
        $this->graph->setPathformat($params[3]);

        //se crea el objeto Proxy (Apoderado)
        $this->salida = new ProxySalida();
        $this->salida->setEstilo($params[0]);
        //Objeto buscador
        //$this->buscadorVertices = new validaVertice();
        //en formato linea
        $this->listaRutas = $_listaRutas;
        $this->files = $_files;
        $this->highlights = $_highlights;
    }

    //put your code here
    function setModel() {
        
    }

    function setView() {
        
    }

    /*Crea la estructura de grafo
     * que almacenara los datos de cada path 
     */
    function crearGrafo() {

        $this->graph->procesarPaths($this->listaRutas, $this->graph->getPathformat(), $this->files, $this->highlights);
        //$this->graph->pruebaVerAdj();
        //version anterior
        //$this->procesar($this->listaRutas, $this->graph->getPathformat(), $this->files, $this->highlights);
    }

    /*Encargado de obtener la estructura visible a partir de la estructura modelo "graph"
     */
    public function procesarGrafo($tamFont,$_archOut=null ) {

        //asignamos a la vista "salida" el modelo "graph" 
        $this->salida->setGrafoEval($this->graph);

        //Obtener el tipo de vista : text o image de la lista de parametros        
        if ($this->graph->getViewMode() == 'image') {
            if ((PHP_OS == "WIN32" ) || (PHP_OS == "WINNT" )) {
                $rutaFont = realpath(dirname(__FILE__) . '\\..\\fonts\\segoeui.ttf');
                $this->graph->setRutaArchFont($rutaFont);
            } else {
                $rutaFont = realpath(dirname(__FILE__) . '/../fonts/segoeui.ttf');
                //$rutaFont = "/usr/share/fonts/truetype/ttf-dejavu/DejaVuSansMono-Bold.ttf";//OK
                //$rutaFont = "/usr/lib/X11/fonts/segoeui.ttf";//OK
                //$rutaFont = "/var/data/public/pathpic3.1/fonts/segoeui.ttf";//OK
                $this->graph->setRutaArchFont($rutaFont);
            }

            $this->salida->procesarGrafo($tamFont,'image');
            $this->graph->setNombreArchSalida($_archOut);
        }

        if ($this->graph->getViewMode() == 'text')
            $this->salida->procesarGrafo($tamFont,'text');
    }

    public function guardarImagenes() {
        $this->salida->guardarImagenes();
    }

    public function getViewImage() {
        return $this->salida->getImageB64();
    }

    public function getViewText() {
        return $this->salida->getArbolString();
    }

    public function getWidth() {
        return $this->salida->getWidth();
    }

    public function getHeight() {
        return $this->salida->getHeight();
    }
        
    public function getViewMode() {
        return $this->graph->getViewMode();
    }

    public function getStyle() {
        return $this->graph->getStyle();
    }

}

?>
