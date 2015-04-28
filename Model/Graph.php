<?php

include 'Vertex.php';
include 'StackX.php';

abstract class Graph {
    const MAX_VERTS=100;                     //define("CONSTANT_NAME", value [, case_sensitivity])

    protected $vertexList = array();             /* array de Vertices */
    protected $adjMat = array();                  /* array que guarda la matriz de adyacencia */
    protected $nVerts;                                 /* nro de Vertices de este Grafo */
    protected $theStack;                             /* array que contiene la pila usada por la busqueda DFS */
    protected $nroLineas;                           /* nro de Lineas de texto con un path cada uno, ingresadas para ser convertidas en Grafo */
    public $distancias = array();                  /* array con las distancias de cada estilo de arbol de salida , distancias pueden ser margen superior e inferior, ancho de columna, etc */
    protected $distanciasXP = array();        /* array con las distancias del estilo de arbol de explorador de Windows XP */
    protected $distanciasW7 = array();
    protected $distanciasN = array();          /* estilo Netbeans */
    protected $distanciasNBJ = array();      /* estilo Netbeans Java */
    protected $distanciasNBP = array();     /* estilo Netbeans PHP */
    protected $distanciasEcJ = array();      /* estilo Eclipse Java */
    protected $distanciasEcP = array();
    protected $distanciasMac = array();
    protected $params = array();                /* array con los parametros que pide la clase principal */
    protected $files = array();                     /* array con los los numeros de linea de la lista de paths marcados como archivos */
    protected $filesIdTree = array();          /* -no usado - */
    protected $highlight = array();             /* array con los los numeros de linea de la lista de paths marcados como resaltados */
    protected $arrayDirs = array();            /* array con los los numeros de linea de la lista de paths marcados como resaltados */
    protected $tamLetra = 9;                     /* tamaño de letra de usado en todos los nodos del arbol de salida sea en texto o imagen */
    protected $rutaArchFont = "C:\Windows\Fonts\segoeui.ttf";   /* ruta del archivo usado para mostrar texto con GD2 */
    protected $rutaArchSalida = "";                        /* ruta de donde se escribira un archivo generado a partir de la imagen generada con GD2 */
    protected $nombreArchSalida = "prueba02";   /* -no usado - nombre del archivo de salida */
    protected $extensionArchSalida = "png";         /* -no usado - extension del archivo de salida */
    protected $php_os;                                          /* El SO del servidor Apache donde corre PHP */
    protected $separadorOS;                                 /* El Separador de directorios del SO donde corre el servidor Apache */
    protected $pathformat;                                     /* El formato de paths segun el SO : Windows o Linux */
    protected $matLineasVerticales = array();       /* array usado para generar las lineas verticales cuando se use modo texto para el arbol */
    protected $verticeMargenDerecho = 0;             /* el nro del vertice del grafo que estara ubicado mas a la derecha */

    function __construct() {
        
    }

    function inicializar() {

        $this->php_os = PHP_OS;
        if (strcmp(PHP_OS, "WIN32") == 0 || strcmp(PHP_OS, "WINNT") == 0) {
            $this->separadorOS = "\\";
        } else {
            $this->separadorOS = "//";
        }

        for ($j = 0; $j < self::MAX_VERTS; $j++) // set adjacency
           $this->vertexList[] = new Vertex('');
        //adjMat = new int[MAX_VERTS][MAX_VERTS];
        $this->nVerts = 0;
        for ($j = 0; $j < self::MAX_VERTS; $j++) // establecer matriz adyacencia adyacencia a 0
            for ($k = 0; $k < self::MAX_VERTS; $k++) {
                $this->adjMat[$j][$k] = 0;
                //Llenar lineas verticales con espacios
                $this->matLineasVerticales[$j][$k] = "&nbsp";
            }

        //echo "<br>Creando Stack<br>";
        $this->theStack = new StackX();

        //Inicializando params
        for ($j = 0; $j < 4; $j++) // establecer matriz params a 0
            $this->params[] = 0;


        //CONFIGURACION PARA W7
        $this->distanciasW7 = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasW7[0][0] = 31;
        //distancia del icono expandido a la columna
        $this->distanciasW7[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasW7[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasW7[0][3] = 0;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasW7[0][4] = 1;
        //ancho de columna
        $this->distanciasW7[0][5] = 10; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasW7[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasW7[1][1] = 5; //17
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasW7[1][2] = 7; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasW7[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasW7[1][4] = 15;
        //altura de fila
        $this->distanciasW7[1][5] = 20; //20
        //CONFIGURACION PARA XP
        $this->distanciasXP = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasXP[0][0] = 31; //8
        //distancia del icono expandido a la columna
        $this->distanciasXP[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasXP[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasXP[0][3] = -4;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasXP[0][4] = -21;
        //ancho de columna
        $this->distanciasXP[0][5] = 19; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasXP[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasXP[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasXP[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasXP[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasXP[1][4] = 14;
        //altura de fila
        $this->distanciasXP[1][5] = 19; //20
        //CONFIGURACION PARA Nautilus
        $this->distanciasN = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasN[0][0] = 31; //8
        //distancia del icono eNandido a la columna
        $this->distanciasN[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasN[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasN[0][3] = -4;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasN[0][4] = -21;
        //ancho de columna
        $this->distanciasN[0][5] = 19; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasN[1][0] = 31;
        //distancia del icono eNandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasN[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasN[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasN[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasN[1][4] = 14;
        //altura de fila
        $this->distanciasN[1][5] = 20; //20
        //CONFIGURACION PARA Netbeans
        $this->distanciasNBJ = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasNBJ[0][0] = 31; //8
        //distancia del icono expandido a la columna
        $this->distanciasNBJ[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasNBJ[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasNBJ[0][3] = 0;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasNBJ[0][4] = -8;
        //ancho de columna
        $this->distanciasNBJ[0][5] = 15; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasNBJ[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasNBJ[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasNBJ[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasNBJ[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasNBJ[1][4] = 15;
        //altura de fila
        $this->distanciasNBJ[1][5] = 20; //20
        //CONFIGURACION PARA Eclipse Java
        $this->distanciasEcJ = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasEcJ[0][0] = 31; //8
        //distancia del icono expandido a la columna
        $this->distanciasEcJ[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasEcJ[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasEcJ[0][3] = -4;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasEcJ[0][4] = -21;
        //ancho de columna
        $this->distanciasEcJ[0][5] = 19; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasEcJ[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcJ[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcJ[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcJ[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasEcJ[1][4] = 14;
        //altura de fila
        $this->distanciasEcJ[1][5] = 19; //20
        //CONFIGURACION PARA Eclipse PHP
        $this->distanciasEcP = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasEcP[0][0] = 31; //8
        //distancia del icono expandido a la columna
        $this->distanciasEcP[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasEcP[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasEcP[0][3] = -4;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasEcP[0][4] = -21;
        //ancho de columna
        $this->distanciasEcP[0][5] = 19; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasEcP[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcP[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcP[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasEcP[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasEcP[1][4] = 14;
        //altura de fila
        $this->distanciasEcP[1][5] = 19; //20
        //CONFIGURACION PARA Eclipse PHP
        $this->distanciasMac = array();
        //distancias horizontales o en X
        //distancia del cuadro envolvente al margen izquierdo
        $this->distanciasMac[0][0] = 31; //8
        //distancia del icono expandido a la columna
        $this->distanciasMac[0][1] = 0; //17
        //distancia del icono contraido a la columna
        $this->distanciasMac[0][2] = 2; //19
        //distancia del icono directorio a la columna
        $this->distanciasMac[0][3] = -4;
        //distancia entre nombre del directorio/archivo y columna
        $this->distanciasMac[0][4] = -21;
        //ancho de columna
        $this->distanciasMac[0][5] = 19; //10
        //distancias verticales o en Y
        //distancia del cuadro envolvente al margen superior
        $this->distanciasMac[1][0] = 31;
        //distancia del icono expandido a la fila (desde la base del cuadro envolvente)
        $this->distanciasMac[1][1] = 3;
        //distancia del icono contraido a la fila (desde la base del cuadro envolvente)
        $this->distanciasMac[1][2] = 1; //19
        //distancia del icono directorio a la fila (desde la base del cuadro envolvente)
        $this->distanciasMac[1][3] = 2;
        //distancia entre nombre del directorio/archivo y fila
        $this->distanciasMac[1][4] = 14;
        //altura de fila
        $this->distanciasMac[1][5] = 19; //20
    }

    public function getTheStack() {
        return $this->theStack;
    }

    public function getNVerts() {
        return $this->nVerts;
    }

    public function setNVerts($nv) {
        $this->nVerts = $nv;
    }

    public function getPathformat() {
        return $this->pathformat;
    }

    public function getPhp_os() {
        return $this->php_os;
    }

    public function getVertexList() {
        return $this->vertexList;
    }

    public function getViewMode() {
        return $this->params[1];
    }

    public function getHighLight() {
        return $this->highlight;
    }

    public function getVerticeMargenDerecho() {
        return $this->verticeMargenDerecho;
    }

    public function setVerticeMargenDerecho($rightVert) {
        $this->verticeMargenDerecho = $rightVert;
    }

    public function setVertexList($_vertexList) {
        $this->vertexList = &$_vertexList;
    }

    public function setAdjMat($_adjMat) {
        $this->adjMat = &$_adjMat;
    }

    public function getAdjMat() {
        return $this->adjMat;
    }

    public function getRutaArchSalida() {
        return $this->rutaArchSalida;
    }

    public function addVertex($lab) {
        $this->vertexList[$this->nVerts++] = new Vertex($lab);
    }

    public function getVertex($v) {
        return $this->vertexList[$v];
    }

    public function getNombreArchSalida() {
        return $this->nombreArchSalida;
    }

    public function getParams() {
        return $this->params;
    }

    public function getNroLineas() {
        return $this->nroLineas;
    }

    public function getStyle() {
        return $this->params[0];
    }

    public function setVertex($id, Vertex $v) {
        $this->vertexList[$id] = $v;
    }

    public function addEdge($start, $end) {
        //Si ya existe la ruta dirname\\basename
        //entonces no se inserta
        $this->adjMat[$start][$end] = 1; // Es un grafo dirigido para crear directorios
        //$this->adjMat[$end][$start] = 1;
    }

    public function setNroLineas($nl) {
        $this->nroLineas = $nl;
    }

    public function dfsBuscar($dirname, $basename, $nivel) { // depth-first search                 // begin at vertex 0
        $enc = -1;

        //echo "<br> Dirname param : $dirname  , Basename param : $basename y Dirname prop : {$this->vertexList[0]->dirname}  ,  Basename prop : {$this->vertexList[0]->basename} <br>";
        //displayVertex(0); // display it
        $enc = $this->procesarVertices(0, $dirname, $basename, 0);
        if ($enc != -1)
            return $enc;
        $this->vertexList[0]->wasVisited = 1;
        $this->theStack->push(0); // push it
        //$enc = 0;
        while (!$this->theStack->isEmpty()) { // until stack empty,
            // get an unvisited vertex adjacent to stack top            
            //echo "estoy en el bucle dfs";
            $t = $this->theStack->peek();
            if (empty($t))
                $t = 0;
            $v = $this->getAdjUnvisitedVertex($t);
            //echo "<br> v : $v <br>"; //Muestra el sgte vertice a visitar desde t
            if (empty($v))
                $v = 0;

            if ($v == -1) // if no such vertex,
                $this->theStack->pop();
            else { // if it exists,
                $this->vertexList[$v]->wasVisited = 1; // mark it
                //echo " estoy en el bucle antes de procesarVertices";
                /* echo "<br>$this->vertexList[$v]<br>";
                  print_r($this->vertexList[$v]);
                  echo "<br> Dirname param : $dirname  , Basename param : $basename y Dirname prop : {$this->vertexList[$v]->dirname}  ,  Basename prop : {$this->vertexList[$v]->basename} <br>";
                 */

                $enc = $this->procesarVertices($v, $dirname, $basename, $nivel);

                if ($enc > 0)
                    break;
                //echo " estoy en el bucle despues de procesarVertices";
                $this->theStack->push($v); // push it
            }
        } // end while
        // stack is empty, so we're done
        $this->theStack = new StackX();
        for ($j = 0; $j < $this->nVerts; $j++) // reset flags
            $this->vertexList[$j]->wasVisited = -1;
        return $enc;
    }

    public function dfsRecorrer() { // depth-first search                 // begin at vertex 0
        $enc = -1;
        //print_r($this->getAdjMat());
        //echo "<br>Basename param : $basename , Dirname param : $dirname y Basename prop : {$this->vertexList[0]->basename} , Dirname prop : {$this->vertexList[0]->dirname} <br>";
        //displayVertex(0); // display it
        $enc = $this->procesarVertices(0, "", "", 0);

        $this->vertexList[0]->wasVisited = 1;
        $this->theStack->push(0); // push it
        //$enc = 0;
        while (!$this->theStack->isEmpty()) { // until stack empty,
            // get an unvisited vertex adjacent to stack top            
            //echo "estoy en el bucle dfs";
            $t = (int) $this->theStack->peek();

            //echo "__Peek__{$t}__";
            if (empty($t)) {
                $t = 0;
                //echo "__Vacio __{$t}__";
            }
            $v = $this->getAdjUnvisitedVertex($t);
            //echo "<br> v : $v <br>"; //Muestra el sgte vertice a visitar desde t
            if (empty($v))
                $v = 0;

            if ($v == -1) // if no such vertex,
                $this->theStack->pop();
            else { // if it exists,
                //Muestra el sgte vertice a visitar desde t                
                //echo " estoy en el bucle antes de procesarVertices";
                $enc = $this->procesarVertices($v, "", "", "");
                $this->vertexList[$v]->wasVisited = 1; // mark it
                //echo " estoy en el bucle despues de procesarVertices";
                $this->theStack->push($v); // push it
            }
        } // end while
        // stack is empty, so we're done
        $this->theStack = new StackX();
        for ($j = 0; $j < $this->nVerts; $j++) // reset flags
            $this->vertexList[$j]->wasVisited = -1;
        return $enc;
    }

    public function obtenerLineasVerticales() { // depth-first search                 // begin at vertex 0
        //Recorrer todo el grafo
        //El nodo mas lejano al que apunta este nodo
        $maxDist = 0;
        for ($i = 0; $i < $this->getNVerts(); $i++) {
            $maxDist = 0;
            for ($j = $i + 1; $j < $this->getNVerts(); $j++) {
                if ($this->adjMat[$i][$j] == 1)
                    $maxDist = $j;
            }
            
            if ($i - $maxDist == 1) {
                $this->matLineasVerticales[$maxDist][$i] = "x";
            } else {
                for ($k = $i + 1; $k <= $maxDist; $k++) {
                    $this->matLineasVerticales[$k][$i] = "|";
                }
            }
           
        }
    }

    // returns an unvisited vertex adj to v

    public function getAdjUnvisitedVertex($v) {
        //echo "<br> Nro V : $this->nVerts <br>";
        //echo "<br> Nro V : {$this->getNVerts()} <br>";

        for ($j = 0; $j < $this->nVerts; $j++)
            if (($this->adjMat[$v][$j] == 1) && ($this->vertexList[$j]->wasVisited == -1))
                return $j;
        return -1;
    }

    public function setParams($p_params) {
        $this->params = &$p_params;
    }

    public function setArrayDirs($p_arrayDirs) {
        $this->arrayDirs = &$p_arrayDirs;        
    }

    public function getArrayDirs() {
        return $this->arrayDirs;
    }

    public function setFilesIdTree($p_filesIdTree) {
        $this->filesIdTree = &$p_filesIdTree;
    }

    public function setFiles($p_files) {
        $this->files = &$p_files;
    }

    public function setHighLight($p_highLight) {
        $this->highlight = &$p_highLight;
    }

    public function getRutaArchFont() {
        return $this->rutaArchFont;
    }

    public function getTamLetra() {
        return $this->tamLetra;
    }

    public function setTamLetra($size) {
        $this->tamLetra = $size;
    }

    public function findHighLight($val) {
        
        //Se obtiene el array de lineas con resaltado
        $enc = -1;
        if (strlen($this->highlight) > 1) {
            if (array_search($val, explode(',', $this->highlight)) !== false)//and $posicInicio >= strlen($value))
                $enc = 1;
        }
        else {
            if (isset($this->highlight[0]) and $val === (int) $this->highlight) //and $posicInicio >= strlen($value))
                $enc = 1;
        }

        return $enc;
    }

    public function findFiles($val) {        
        $enc = -1;
        if (strlen($this->files) > 1) {
            //Se comprueba que ya se haya analizado toda una ruta 
            //todo este grupo de ifs tenian && en vez de and
            if (array_search($val, explode(',', $this->files)) !== false) { // and $posicInicio >= strlen($value)
                $enc = 1;
            }
        } else {
            //Con isset se sabe si listafiles tiene al menos un valor 
            if (isset($this->files[0]) and $val === (int) $this->files) { // and $posicInicio >= strlen($value)
                $enc = 1;
            }
        }
        return $enc;
    }

    public function findLista($nuevaRuta) {
        $enc = -1;
        foreach ($this->arrayDirs as $key => $value) {
            if (strcmp($value, $nuevaRuta) == 0)
                $enc = 1;
        }
        return $enc;
    }

    public function getAnchoImagen() {
        $longMax = 0;
        /*
          for ($i = 0; $i < count($this->vertexList); $i++)
          if (!empty($this->vertexList[$i]->basename))
          if (strlen($this->vertexList[$i]->basename) > $longMax) {
          $maxTextLong = $this->vertexList[$i]->basename;
          $longMax = strlen($maxTextLong);
          }
         */
        $id = $this->getVerticeMargenDerecho();
        $longMax = strlen($this->vertexList[$id]->basename);

        return $this->tamLetra * ($longMax + 2);
    }

    public function getAnchoTexto() {
        $longMax = 0;
        /*
          for ($i = 0; $i < count($this->vertexList); $i++)
          if (!empty($this->vertexList[$i]->basename))
          if (strlen($this->vertexList[$i]->basename) > $longMax) {
          $maxTextLong = $this->vertexList[$i]->basename;
          $longMax = strlen($maxTextLong);
          }
          $maxnivel = 0;
          for ($i = 0; $i < count($this->vertexList); $i++)
          if ($this->vertexList[$i]->nivel > $maxnivel) {
          $maxnivel = $this->vertexList[$i]->nivel;
          }

          $maxnivel++;
         */
        $id = $this->getVerticeMargenDerecho();
        $longMax = strlen($this->vertexList[$id]->basename);

        return $longMax * 10;
        //return $longMax+($maxnivel*10);               
    }

    public function getNivelMax() {
        $nivelMax = 0;
        for ($i = 0; $i < count($this->vertexList); $i++)
            if (($this->vertexList[$i]->nivel) > $nivelMax)
                $nivelMax = $this->vertexList[$i]->nivel;

        return $nivelMax;
    }

    public function setRutaArchSalida($p_rutaArchSalida) {
        $this->rutaArchSalida = $p_rutaArchSalida;
    }

    public function setExtensionArchSalida($p_extensionArchSalida) {
        $this->extensionArchSalida = $p_extensionArchSalida;
    }

    public function setNombreArchSalida($p_nombreArchSalida) {
        $this->nombreArchSalida = $p_nombreArchSalida;
    }

    public function setRutaArchFont($p_rutaArchFont) {
        $this->rutaArchFont = $p_rutaArchFont;
    }

    //$pathformat
    public function setPathformat($p_pathformat) {
        $this->pathformat = $p_pathformat;
    }

    public function setPhp_os($p_php_os) {
        $this->php_os = $p_php_os;
    }

    public function esLetra($car) {
        //ASCII imprimibles 32 ->126
        if ((ord($car) >= 65 and ord($car) <= 90) or (ord($car) >= 97 and ord($car) <= 122))
            return 1;
        else
            return -1;
    }
/*Esta funcion es implementada en todas las clases que procesan el arbol
 * Es llamada cada vez que se cumple una condicion con el valor de un nodo al usar el metodo dfsRecorrer o dfsBuscar
 */
    abstract public function procesarVertices($v, $dirname, $basename, $nivel);
}

