<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FindGraph
 *
 * @author george
 */
include_once 'Graph.php';

class validaVertice extends Graph {

//put your code here
    function __construct() {

        $this->inicializar();
    }

    /* Funcion que busca un vertice que ya forme parte del Grafo
     */

    public function procesarVertices($v, $dirname, $basename, $nivel) {

        //Se obtiene el vertice a procesar desde la lista de vertices del Grafo
        $arrVertTemp = $this->getVertex($v);
        $out = -1;
//echo "<br>Encontrado : Texto -> Vertice : $v | nivel : $arrVertTemp->nivel | dirname : $arrVertTemp->dirname | basename : $arrVertTemp->basename<br>";
        //Si coincide el dirname, el basename y el nro de columna , se devuelve el valor $v , sino se devuelve -1
        if ((strcasecmp($arrVertTemp->dirname, $dirname) == 0) and (strcasecmp($arrVertTemp->basename, $basename) == 0) and $arrVertTemp->nroCol == $nivel) {//$arrVertTemp->nivel == $nivel) {
            $out = $v;
        }
        else
            $out= - 1;

        return $out;
    }

    /* Procesa toda la lista de paths (en crudo) para obtener un conjunto de
     * vertices que conformaran  el Grafo.
     * Cada vertice guardara la informacion de un path como su nombre, su directorio padre
     * su posicion en el arbol de directorios, si es archivo, si es resaltado, etc.
     */

    public function procesarPaths($listaRutas, $pathFormat, $listaFiles0, $listaHighLights0) {

        $arrayDirsUp = array();

        //Matriz de filas y columnas para indicar
        //la posicion del vertice en el arbol de directorios
        $fila = 0;
        $col = 0;
        $resp = 0;
        $vertRightest = 0;

        //Asignar el string de archivos a esta clase
        $this->setFiles($listaFiles0);

        //Asignar el string de resaltados a esta clase
        $this->setHighLight($listaHighLights0);

        $arrayDirsUp = $this->uniformizarPaths($listaRutas, $pathFormat);
        //guarda el numero de lineas  de la lista de paths original
        $this->setNroLineas(count($arrayDirsUp));

        //- vertices del grafo : con addVertex(label,# nodo)
        //Establecer el separador de directorios del Sistema de Archivos del Sistema Operativo
        // del Servidor
        //Si el SO Servidor es Windows
        if (strcmp(PHP_OS, "WIN32") == 0 || strcmp(PHP_OS, "WINNT") == 0)
            $sepDirServ = "\\";
        else
            $sepDirServ = "/";

        //Obtener el primer token de la primera linea de la lista , la linea 0
        $token = strtok($arrayDirsUp[0], $sepDirServ);

        //Se obtiene el directorio padre, si lo hay
        if (strcmp(PHP_OS, "WIN32") == 0 || strcmp(PHP_OS, "WINNT") == 0)
            $dirRoot = $this->getDirRelTokenWindows($arrayDirsUp[0], $token, 0, 0);
        else
            $dirRoot = $this->getDirRelTokenLinux($arrayDirsUp[0], $token, 0, 0);

        //Si el directorio padre no esta conformado solo por letras
        if ($dirRoot != -1) {
            //Si el directorio padre es igual al separador de directorio del SO del Servidor
            //y los paths estan en formato Linux, se agrega el / como Root
            if ($dirRoot == $sepDirServ and strcasecmp($this->getPathformat(), "Linux") == 0) {
                //agregar vertice raiz
                $this->addVertex("A" . 0); // 0 (start for dfs)                
                //Establecer datos de vertice directorio
                $this->setVerticeDirectorio(0, 0, "", $dirRoot);
            } else {
                //agregar vertice simple
                //aunque el primer caracter del path comience por \
                //En Windows no se veria bien que haya un Root \ 
                $this->addVertex("A" . 0); // 0 (start for dfs)                
                //Establecer datos de vertice directorio
                $this->setVerticeDirectorio(0, 0, $token, $dirRoot);
            }
        } else {
            //agregar vertice raiz de solo letras
            $this->addVertex("A" . 0); // 0 (start for dfs)            
            //Establecer datos de vertice directorio
            $this->setVerticeDirectorio(0, 0, $token, ".");
        }

        unset($token);
        unset($dirRoot);

        // $key es el numero de fila de la matriz de rutas
        // $value es el valor de cada ruta (fila) de la matriz de rutas
        foreach ($arrayDirsUp as $key => $value) {
            //Cada iteracion procesara un path con indice $key de la lista de paths original
            $posicInicio = 0;
            $nuevaRuta = "";
            //Si se da el caso que el primer path es el mismo que el separador de directorios del 
            //SO del Servidor
            if ($arrayDirsUp[0][0] == $sepDirServ and strcasecmp($this->getPathformat(), "Linux") == 0)
                $col = 1;
            else
                $col = 0;

            //Por cada linea de path se obtendra todos los tokens (directorios y si lo hay un archivo al final ) de los 
            //que esta conformado
            //Se iterara hasta que ya no se obtenga un token            
            for ($token = strtok($arrayDirsUp[$key], $sepDirServ); $token !== FALSE; $token = strtok($sepDirServ)) {
                //skip empty tokens
                //echo "<br>Analizando token en  $arrayDirsUp[$key] => $token <br>";                
                $pathDirEval = "";
                $dirRoot = "";
                $resp = "";

                if ($token != "") {
                    if (strcmp(PHP_OS, "WIN32") == 0 || strcmp(PHP_OS, "WINNT") == 0)
                        $dirRoot = $this->getDirRelTokenWindows($arrayDirsUp[$key], $token, $key, $col);
                    else
                        $dirRoot = $this->getDirRelTokenLinux($arrayDirsUp[$key], $token, $key, $col);

                    //Si el directorio padre obtenido es vacio , en el caso del token root de este arbol de directorios
                    //se avanzara una columna 
                    if ($dirRoot == -1) {
                        $col++;
                        continue;
                    }

                    //Si el directorio padre es el separador de directorios del 
                    //SO del Servidor, se asigna al path a evaluar
                    if ($dirRoot == $sepDirServ)
                        $pathDirEval = $sepDirServ;
                    else
                        $pathDirEval = $dirRoot;

                    //Se busca en el conjunto de vertices del Grafo si ya existe el
                    //vertice con el path a evaluar
                    $resp = $this->dfsBuscar($pathDirEval, $token, $col);

                    //Si el vertice con el path evaluado no existe aun
                    //se agrega al Grafo
                    if ($resp == -1) {
                        //Se incrementa el "indice" de vertices del Grafo
                        $fila++;
                        //Se añade el vertice al Grafo
                        $this->addVertex("A" . $fila); // 0 (start for dfs)
                        //Inicialmente establecer datos de vertice como directorio
                        $this->setVerticeDirectorio($fila, $col, $token, $pathDirEval);

                        //Crear arco

                        $pathInfoNR = pathinfo($pathDirEval);
                        //Se busca el vertice que sera el padre de este vertice 
                        //en base al dirname, el basename y el nivel o columna
                        $resp2 = $this->dfsBuscar($pathInfoNR['dirname'], $pathInfoNR['basename'], $col - 1);
                        //Si existe el vertice 
                        if ($resp2 != -1) {
                            //se crea un arco entre el vertice $resp y el nuevo vertice
                            $this->addEdge($resp2, $fila);
                            //ActualizarPropiedades del directorio                            
                            $vert = $this->getVertex($resp2);
                            //porque ya no esta vacio y ademas sera expandido    
                            $vert->esExpandido = 1;
                            $vert->vacio = -1;
                            $this->setVertex($resp2, $vert);
                        }

                        //Obtener el vertice de mas a la derecha 
                        if ($vertRightest < ((3 * $col) + strlen($token))) {
                            $this->setVerticeMargenDerecho($fila);
                            $vertRightest = ((3 * $col) + strlen($token));
                        }

                        //
                        //   Se usa <pre> para mostrar correctamente la informacion con print_r          
                        /* echo"<br><pre>";
                          print_r($this->getVertexList());
                          echo"</pre><br>"; */
                    }
                }
                $col++;
            }// fin de for para obtener token de cada linea
            //Los Archivos siempre estan al final de una path
            //Por eso al terminar el for que obtiene los tokens por linea
            //validamos si el ultimo token pertenece a un path marcado como File
            //Marcar Files
            $vert = $this->getVertex($fila);
            if ($this->findFiles($key) != -1) { // se reemplazo $key con $file para el caso de un solo path
                //Establecer datos de vertice archivo
                //$this->setVerticeArchivo($fila, $col, $token, $pathDirTmp);                        
                //Actualizar del ultimo nodo del treeview
                $vert->esExpandido = -1;
                $vert->vacio = 1;
                $vert->esDirectorio = -1;
                $this->setVertex($fila, $vert);
            } else {
                $vert->esDirectorio = 1;
                $vert->filename = null;
                $this->setVertex($fila, $vert);
            }

            //Marcar HighLights
            //ver si es una ruta marcada como resaltado
            if ($this->findHighLight($key) != -1) {
                $vert->esResaltado = 1;
                $this->setVertex($fila, $vert);
            }

            unset($vert);
        }//fin de for a array de paths         
        //Si solo se ingreso una linea , 
        //usamos el valor de fila para modificar el ultimo nodo del treeview
        //echo "<br>Nro nodo Padre : $resp2 <br>";
        if ($key == 0) {
            $vert = $this->getVertex($fila);
            if ($this->findFiles($key) != -1) {
                //Actualizar del ultimo nodo del treeview
                $vert->esExpandido = -1;
                $vert->vacio = 1;
                $vert->esDirectorio = -1;
                $this->setVertex($fila, $vert);
            }
        }

        /* echo"<br><pre>";
          print_r($this->getVertexList());
          echo"</pre><br>"; */
    }

    public function pruebaVerAdj() {
        $array = $this->getVertexList();
        print_r($array);
    }

    public function uniformizarPaths($listaRutas, $pathFormat) {
        $arraydirsTmp = '';
        //Establecer el separador de directorios del Servidor 
        if ((PHP_OS == "WIN32" ) || (PHP_OS == "WINNT" )) {
            $sepDirsServ = "\x5C";
            //echo "Separador de dirs Serv Windows<br>";
        } else {
            $sepDirsServ = "\x2F";
            //echo "Separador de dirs Serv Linux<br>";
        }

//Separador input , si son identicos ,devuelve cero
        if (strcasecmp($pathFormat, "Windows") == 0)
            if (get_magic_quotes_gpc()) {
                $sepDirsCli = "\x5C\x5C";
//echo "Separador de dirs Cli dobles Windows<br>";
            } else {
                $sepDirsCli = "\x5C";
//echo "Separador de dirs Cli simple Windows<br>";
            } else {
            $sepDirsCli = "\x2F";
//echo "Separador de dirs Cli Linux<br>";
        }

        $total = 0;

//Nueva linea de aplicacion Cliente
        $eolCli = "\r\n";

//obtener todas las rutas del textarea, una por linea
        for ($token = strtok($listaRutas, $eolCli); $token !== FALSE; $token = strtok($eolCli)) {
//skip empty tokens
            if ($token != "") {
//echo "token :" . $token . "<br>";
//Reemplazar al formato de rutas del SO del Servidor
//Si no encuentra que reemplazar devuelve la cadena original : token
                $nuevoToken = str_ireplace($sepDirsCli, $sepDirsServ, $token);
                $arraydirsTmp[] = $nuevoToken;
            }
        }
        return $arraydirsTmp;
    }

    function setVerticeArchivo($id, $col, $nombre, $dirSup) {
//Agregar datos por defecto de archivo
        $vertList = new Vertex("A");
        $vertList->esDirectorio = -1;
        $vertList->nivel = $col;
        $vertList->nroFila = $id;
        $vertList->nroCol = $col;
        $vertList->esExpandido = -1;
        $vertList->vacio = 1;
        $vertList->wasVisited = -1;
        $vertList->dirname = $dirSup;
        $vertList->basename = $nombre;
        $vertList->extension = null;
        $vertList->filename = $nombre;
        $this->setVertex($id, $vertList);
    }

    function setVerticeDirectorio($id, $col, $nombre, $dirSup) {
        $vertList = new Vertex("A");
        $vertList->esDirectorio = 1;
        $vertList->nivel = $col;
        $vertList->nroFila = $id;
        $vertList->nroCol = $col;
        $vertList->esExpandido = -1;
        $vertList->vacio = 1;
        $vertList->wasVisited = -1;
        $vertList->dirname = $dirSup;
        $vertList->basename = $nombre;
        $vertList->extension = null;
        $vertList->filename = null;
        $this->setVertex($id, $vertList);
    }
    
    function getDirRelTokenWindows($arrayDirsUp, $token, $fila, $col) {

        //Obtener la info de $arrayDirsUp que esta en la lista de paths
        $pathInfo = pathinfo($arrayDirsUp);
        $reldir = "";
        $pos = 0;

        //escapeshellcmd Para validar nombres de directorios o archivos
        //quotemeta Para validar nombres de directorios o archivos
        //El directorio relativo a la posicion token actual

        if ($this->esLetra($arrayDirsUp[0]) == 1) {
            if ($arrayDirsUp[1] == ":" and strpos($arrayDirsUp, $token) <= 3) {
                $reldir = $arrayDirsUp[0] . ":\\";
                //echo "---------if--------dir----$reldir----$col-----token-----$token <br>";
            } else {

                for ($i = 0; $i <= $this->getNVerts(); $i++) {
                    $pos = strpos($arrayDirsUp, $token, $pos);
                    $pathDirTmp = substr($arrayDirsUp, 0, $pos);
                    $vert = $this->getVertex($i);
                    //if (strcmp($vert->dirname."\\". $vert->basename."\\".$token, $pathDirTmp."\\".$token) == 0 and $vert->col == $col) {
                    if (strcmp($vert->dirname, $pathDirTmp) == 0 and $vert->nroCol == $col) {
                        break;
                    }
                }

                //$pathDirTmp = substr($arrayDirsUp, 0, $pos);
                //echo "---------else--------pos----$pos---$col---<br> ";
                //echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; //<1>
                if (empty($pathDirTmp))
                    return -1;
                $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp) - 1);
            }
        }
        else {

            for ($i = 0; $i <= $this->getNVerts(); $i++) {
                $pos = strpos($arrayDirsUp, $token, $pos);
                $pathDirTmp = substr($arrayDirsUp, 0, $pos);
                $vert = $this->getVertex($i);
                if (strcmp($vert->dirname, $pathDirTmp) == 0 and $vert->nroCol == $col) {
                    break;
                }
            }

            if ($pos == 1) {
                return "\\";
            }

            //$pathDirTmp = substr($arrayDirsUp, 0, $pos);
            //echo "--no letra -------else---------pos----$pos---col $col--- ";
            //echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; <1>
            if (empty($pathDirTmp))
                return -1;
            $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp) - 1);
        }
        return $reldir;
    }

    function getDirRelTokenWindows3($arrayDirsUp, $token, $fila, $col) {

        //Obtener la info de $arrayDirsUp que esta en la lista de paths
        $pathInfo = pathinfo($arrayDirsUp);
        $reldir = "";
        $pos = 0;


        //escapeshellcmd Para validar nombres de directorios o archivos
        //quotemeta Para validar nombres de directorios o archivos
        //El directorio relativo a la posicion token actual

        if ($this->esLetra($arrayDirsUp[0]) == 1) {
            $i = 0;
            
            do {
                $pos = strpos($arrayDirsUp, "\\", $pos+1);                
                $i++;
            } while ($i <= $col);
            
            if ($arrayDirsUp[1] == ":" and $posS <= 3) {

                $reldir = $arrayDirsUp[0] . ":\\";
                //echo "---------if--------dir----$reldir----$col-----token-----$token <br>";
            } else {

                $pathDirTmp = substr($arrayDirsUp, 0, $pos);
                echo "---------else--------pos----$pos---$col---<br> ";
                echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; //<1>
                if (empty($pathDirTmp))
                    return -1;
                $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp)-1);
            }
        }
        else {

            $i = 0;
             $pos = 0;
            do {
                $pos = strpos($arrayDirsUp, "\\", $pos+1);                
                $i++;
            } while ($i <= $col);
            
            if ($pos == 0) {
                return "\\";
            }

            $pathDirTmp = substr($arrayDirsUp, 0, $pos);
            //echo "--no letra -------else---------pos----$pos---col $col--- ";
            //echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; <1>
            if (empty($pathDirTmp))
                return -1;
            $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp) - 1);
        }
        return $reldir;
    }
    
    

    function getDirRelTokenLinux($arrayDirsUp, $token, $fila, $col) {
        //Obtener la info de $arrayDirsUp que esta en la lista de paths
        $pathInfo = pathinfo($arrayDirsUp);
        $dirUp = $pathInfo['dirname'];

        $reldir = "";

        $pos = strpos($arrayDirsUp, $token);

        //El directorio relativo a la posicion token actual
        if ($this->esLetra($arrayDirsUp[0]) == 1) {

            $pathDirTmp = substr($arrayDirsUp, 0, $pos);
            //echo "---------else--------pos----$pos---$col--- ";
            //echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; <1>
            if (empty($pathDirTmp))
                return -1;
            $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp) - 1);
        }
        else {
            if ($pos == 1) {
                return "/";
            }
            $pathDirTmp = substr($arrayDirsUp, 0, $pos);
            //echo "--no letra -------else---------pos----$pos---col $col--- ";
            //echo " <br>$token => $arrayDirsUp => $pathDirTmp<br>"; <1>
            if (empty($pathDirTmp))
                return -1;
            $reldir = substr($pathDirTmp, 0, strlen($pathDirTmp) - 1);
        }
        return $reldir;
    }

}

?>