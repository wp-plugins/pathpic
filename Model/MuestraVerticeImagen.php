<?php

/**
 * Clase usada para obtener una imagen a partir de un modelo Grafo
 *
 * @author george
 */
include_once 'Graph.php';

class MuestraVerticeImagen extends Graph {

    public $image;
    public $imageB64;
    public $mostrarLineas;
    public $estilo;
    public $estiloIcono;
    public $colorFondoArr = array();
    public $arrColorHighLight = array();
    public $arrColorTextoHighLight = array();
    public $colorTextoHighLight;
    public $colortext;
    public $colorGrid;
    public $colorHighLight;
    public $colorText;

    //put your code here
    function __construct($p_estilo) {
        $this->inicializar();
    }

    public function procesarVertices($v, $dirname, $basename, $nivel) {

        $posicX = $this->distancias[0][0];
        $posicY = $this->distancias[1][0];


        //Icono expandido

        $rutaicono = realpath(dirname(__FILE__) . '/../images/expandido' . $this->estiloIcono . '.png');
        //$iconoexp = imagecreatefrompng(plugins_url("images/expandido" . $this->estiloIcono . ".png",dirname(__FILE__)));
        $iconoexp = imagecreatefrompng($rutaicono);

        if ($v == 0) {
            // begin at vertex 0
            imagecopy($this->image, $iconoexp, $posicX + $this->distancias[0][1], $posicY + $this->distancias[1][1], 0, 0, imagesx($iconoexp), imagesy($iconoexp));
            //Primera linea horizontal hacia el dir inicial
            if ($this->mostrarLineas == 1)
                imageline($this->image, $posicX + imagesx($iconoexp), $posicY + imagesy($iconoexp), $posicX + 2 * imagesy($iconoexp), $posicY + imagesy($iconoexp), IMG_COLOR_STYLED);
            //Se guardan las coordenadas del punto de inicio de la linea vertical
            $lineaVertX = $posicX;
            $lineaVertY = $posicY;
            //Mostrar en el browser una imagen de acuerdo con el tamaño
            //de la estructura del arbol
            //Aunque se puede preestablecer un tamaño promedio
            //imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
            //Icon directorio
            if (strcmp($this->estiloIcono, "EcJ") == 0) { //| strcmp($this->estiloIcono,"NBJ")==0 Por el momento
                //$iconodir = imagecreatefrompng("images/dirRoot" . $this->estiloIcono . ".png");
                $rutaicono = realpath(dirname(__FILE__) . '/../images/dirRoot' . $this->estiloIcono . '.png');
                $iconodir = imagecreatefrompng($rutaicono);
                //$iconodir = imagecreatefrompng(plugins_url("images/dirRoot" . $this->estiloIcono . ".png",dirname(__FILE__)));
                imagecopy($this->image, $iconodir, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconodir), imagesy($iconodir));
            } else {
                $rutaicono = realpath(dirname(__FILE__) . '/../images/dirOpen' . $this->estiloIcono . '.png');
                $iconodir = imagecreatefrompng($rutaicono);
                //$iconodir = imagecreatefrompng(plugins_url("images/dirOpen" . $this->estiloIcono . ".png",dirname(__FILE__)));
                //$iconodir = imagecreatefrompng("images/dirOpen" . $this->estiloIcono . ".png");
                imagecopy($this->image, $iconodir, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconodir), imagesy($iconodir));
            }
            //Se muestra el primer nodo
            //TextoPrimer nodo
            $nodoCeroText = "";
            /*
              if (strcasecmp($this->getPathformat(), "Windows") == 0) {
              if (!empty($this->vertexList[0]->basename))
              $nodoCeroText = $this->vertexList[0]->basename;
              else
              $nodoCeroText="\x5C";
              }
              else {
              if (!empty($this->vertexList[0]->basename))
              $nodoCeroText = $this->vertexList[0]->basename;
              else
              $nodoCeroText="\x2F";
              } */

            // Si tiene las caracteristicas de ser un vertice Root
            if (($this->vertexList[0]->dirname == "\x5C" or $this->vertexList[0]->dirname == "\x2F") and $this->vertexList[0]->basename == "") {
                if (strcasecmp($this->getPathformat(), "Windows") == 0)
                    $nodoCeroText = "\x5C";
                else
                    $nodoCeroText="\x2F";
            }else {
                $nodoCeroText = $this->vertexList[0]->basename;
            }

            //echo "<br>Texto nodo cero : $nodoCeroText <br> y dir name : ".$this->vertexList[0]->dirname." y base name : ".$this->vertexList[0]->basename;
            //if ($this->findHighLight(0) == 1)
            //$rsF = imageloadfont($this->getRutaArchFont());


            if ($this->vertexList[0]->esResaltado == 1)
                imagettftext($this->image, $this->tamLetra, 0, $posicX + (3 * $this->distancias[0][5]) + $this->distancias[0][4], $posicY + $this->distancias[1][4], $this->colorTextoHighLight, $this->getRutaArchFont(), $nodoCeroText);
            else
                imagettftext($this->image, $this->tamLetra, 0, $posicX + (3 * $this->distancias[0][5]) + $this->distancias[0][4], $posicY + $this->distancias[1][4], $this->colorText, $this->getRutaArchFont(), $nodoCeroText);
            //imagettftext($this->image,$this->tamLetra,0,$posicX + (3*$this->distancias[0][5]) + $this->distancias[0][4],$posicY + $this->distancias[1][4],$colorText,$this->rutaArchFont,"KPS");
            //$this->theStack();
            //$nivelSubDir++;s
        }
        else {
            $fileDibujado = FALSE;

            $posicX = $this->distancias[0][0] + ($this->distancias[0][5] * $this->vertexList[$v]->nivel);
            $posicY = $this->distancias[1][0] + ($this->distancias[1][5] * $this->vertexList[$v]->nroFila);
            //Si el estilo es NetBeans
            if ($this->mostrarLineas == 1)
               imageline($this->image, $posicX + (imagesx($iconoexp) / 2), $posicY + imagesy($iconoexp), $posicX + 1.5 * imagesx($iconoexp), $posicY + imagesy($iconoexp), IMG_COLOR_STYLED);
                //imageline($this->image, $posicX - imagesx($iconoexp), $posicY + imagesy($iconoexp), $posicX + 1.5 * imagesx($iconoexp), $posicY + imagesy($iconoexp), IMG_COLOR_STYLED);
            //Colocar un icono segun sea archivo o directorio
            //Para cada fila hay 3 columnas , 0 = icono exp/contr
            //1 = icono directorio, 2 vacio y 3 = nombre directorio
            $anchoExp = 0;
            if ($this->vertexList[$v]->esDirectorio == 1) {
                if ($this->vertexList[$v]->vacio == -1) {
                    $rutaicono = realpath(dirname(__FILE__) . '/../images/expandido' . $this->estiloIcono . '.png');
                    $iconoexp = imagecreatefrompng($rutaicono);
                    //$iconoexp = imagecreatefrompng(plugins_url("images/expandido" . $this->estiloIcono . ".png",dirname(__FILE__)));
                    //$iconoexp = imagecreatefrompng("images/expandido" . $this->estiloIcono . ".png");
                    imagecopy($this->image, $iconoexp, $posicX, $posicY + $this->distancias[1][1], 0, 0, imagesx($iconoexp), imagesy($iconoexp));
                    $anchoExp = imagesx($iconoexp);
                    if ($this->estiloIcono == "Nautilus")
                    //$iconodir = imagecreatefrompng("images/dirOpen" . $this->estiloIcono . ".png");                            
                        $iconodir = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/dirOpen' . $this->estiloIcono . '.png'));
                    //$iconodir = imagecreatefrompng(plugins_url("images/dirOpen" . $this->estiloIcono . ".png",dirname(__FILE__)));
                    else
                        $iconodir = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/dir' . $this->estiloIcono . '.png'));
                    //$iconodir = imagecreatefrompng(plugins_url("images/dir" . $this->estiloIcono . ".png",dirname(__FILE__)));
                    //$iconodir = imagecreatefrompng("images/dir" . $this->estiloIcono . ".png");
                    //
                    imagecopy($this->image, $iconodir, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconodir), imagesy($iconodir));
                }else {
                    if ($this->estiloIcono == "Nautilus" || $this->estiloIcono == "W7") {
                        $iconoexp = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/contraido' . $this->estiloIcono . '.png'));
                        //$iconoexp = imagecreatefrompng(plugins_url("images/contraido" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        //$iconoexp = imagecreatefrompng("images/contraido" . $this->estiloIcono . ".png");
                        imagecopy($this->image, $iconoexp, $posicX , $posicY + $this->distancias[1][1], 0, 0, imagesx($iconoexp), imagesy($iconoexp));
                        $anchoExp = imagesx($iconoexp);
                        //$iconodir = imagecreatefrompng("images/dir" . $this->estiloIcono . ".png");
                        $iconodir = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/dir' . $this->estiloIcono . '.png'));
                        //$iconodir = imagecreatefrompng(plugins_url("images/dir" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        imagecopy($this->image, $iconodir, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconodir), imagesy($iconodir));
                    } else {
                        
                        $iconoexp = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/contraido' . $this->estiloIcono . '.png'));
                        //$iconoexp = imagecreatefrompng(plugins_url("images/contraido" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        //$iconoexp = imagecreatefrompng("images/contraido" . $this->estiloIcono . ".png");
                        imagecopy($this->image, $iconoexp, $posicX, $posicY + $this->distancias[1][1], 0, 0, imagesx($iconoexp), imagesy($iconoexp));
                        $anchoExp = imagesx($iconoexp);
                         

                        //$iconodir = imagecreatefrompng("images/dir" . $this->estiloIcono . ".png");
                        $iconodir = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/dir' . $this->estiloIcono . '.png'));
                        //$iconodir = imagecreatefrompng(plugins_url("images/dir" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        //
                        imagecopy($this->image, $iconodir, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconodir), imagesy($iconodir));
                        //imageline($this->image, $lineaVertX, $lineaVertY, $lineaVertX-10, $lineaVertY, $colorText);
                    }
                }
            } else {

                if ($this->estiloIcono == "EcJ") {
                    if (strcmp(strtoupper($this->vertexList[$v]->extension), "JAVA") == 0) {
                        //$iconoarch = imagecreatefrompng("images/fileJ" . $this->estiloIcono . ".png");
                        $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/fileJ' . $this->estiloIcono . '.png'));
                        //$iconoarch = imagecreatefrompng(plugins_url("images/fileJ" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                        $fileDibujado = TRUE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
                    }
                    if (strcmp(strtoupper($this->vertexList[$v]->extension), "PHP") == 0) {
                        //$iconoarch = imagecreatefrompng("images/fileP".$this->estiloIcono.".png");
                        //archTxtNB
                        //$iconoarch = imagecreatefrompng("images/archTxtNB.png");
                        $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/archTxtNB' . $this->estiloIcono . '.png'));
                        //$iconoarch = imagecreatefrompng(plugins_url("images/archTxtNB.png",dirname(__FILE__)));
                        imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                        $fileDibujado = TRUE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
                    }
                }

                if ($this->estiloIcono == "NBJ") {
                    if (strcmp(strtoupper($this->vertexList[$v]->extension), "JAVA") == 0) {
                        //$iconoarch = imagecreatefrompng("images/fileJ" . $this->estiloIcono . ".png");
                        $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/fileJ' . $this->estiloIcono . '.png'));
                        //$iconoarch = imagecreatefrompng(plugins_url("images/fileJ" . $this->estiloIcono . ".png",dirname(__FILE__)));
                        //$iconoarch = imagecreatefrompng("images/archTxtNB.png");

                        imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                        $fileDibujado = TRUE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
                    }
                    if (strcmp(strtoupper($this->vertexList[$v]->extension), "PHP") == 0) {
                        //$iconoarch = imagecreatefrompng("fileP".$this->estiloIcono.".png");
                        //archTxtNB
                        //$iconoarch = imagecreatefrompng("images/archTxtNB.png");
                        $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/archTxtNB' . $this->estiloIcono . '.png'));
                        //$iconoarch = imagecreatefrompng(plugins_url("images/archTxtNB.png",dirname(__FILE__)));
                        imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                        $fileDibujado = TRUE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
                    }
                }

                if ($this->estiloIcono == "Mac") {
                    //$iconoarch = imagecreatefrompng("images/file" . $this->estiloIcono . ".png");
                    $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/file' . $this->estiloIcono . '.png'));
                    //$iconoarch = imagecreatefrompng(plugins_url("images/file" . $this->estiloIcono . ".png",dirname(__FILE__)));
                    imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                    $fileDibujado = TRUE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
                }

                if (($this->estiloIcono == "XP" | $this->estiloIcono == "W7" | $this->estiloIcono == "Nautilus" | $this->estiloIcono == "NBJ" | $this->estiloIcono == "EcJ") & $fileDibujado === FALSE) {
                    //$iconoarch = imagecreatefrompng("images/archTxtNB.png");
                    $iconoarch = imagecreatefrompng(realpath(dirname(__FILE__) . '/../images/archTxtNB.png'));
                    //$iconoarch = imagecreatefrompng(plugins_url("images/archTxtNB.png",dirname(__FILE__)));
                    imagecopy($this->image, $iconoarch, $posicX + $this->distancias[0][5] + $this->distancias[0][3], $posicY + $this->distancias[1][3], 0, 0, imagesx($iconoarch), imagesy($iconoarch));
                }
            }
            //echo "<br>Texto de imagen : ".$this->vertexList[$v]->basename." <br>";
            //if ($this->findHighLight($v) == 1)
            if ($this->vertexList[$v]->esResaltado == 1)
                imagettftext($this->image, $this->tamLetra, 0, $posicX + (3 * $this->distancias[0][5]) + $this->distancias[0][4], $posicY + $this->distancias[1][4], $this->colorTextoHighLight, $this->rutaArchFont, $this->vertexList[$v]->basename);
            else
            //imagettftext($this->image,$this->tamLetra,0,$posicX + (3*$this->distancias[0][5]) + $this->distancias[0][4],$posicY + $this->distancias[1][4],$colorText,$this->rutaArchFont,$nodoCeroText);
                imagettftext($this->image, $this->tamLetra, 0, $posicX + (3 * $this->distancias[0][5]) + $this->distancias[0][4], $posicY + $this->distancias[1][4], $this->colorText, $this->rutaArchFont, $this->vertexList[$v]->basename);
            //    $this->theStack->push($v); // push it
        }// enf if v =0
        //imagettftext
    }

    public function pintarLineasVerticales() {
        //Comenzar a Graficar
        //fill in graph parameters
        $GraphFont = 5;
        $margenX = 10;
        $margenY = 30;
        $fileDibujado = FALSE; //Indicador que ya se encontro un tipo de archivo y ya se dibujo su icono respectivo
        $arrColorHighLight = array();

        //Arreglo de directorios contraidos
        //arreglo de nodos archivo
        //params[0] : estilo

        switch ($this->params[0]) {
            case 0:
                $estiloIcono = "XP";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = -1;
                $arrColorHighLight = array(0x31, 0x6A, 0xC5);
                $arrColorTextoHighLight = array(0xFF, 0xFF, 0xFF);
                $this->distancias = &$this->distanciasXP;
                /* for ($i=0;$i<count($this->distanciasXP);$i++){
                  for ($j=0;$j<count($this->distanciasXP[$i]);$j++){
                  $this->distancias[$i][$j]= $this->distanciasXP[$i][$j];
                  }
                  } */

                break;

            case 1:
                $estiloIcono = "W7";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = -1;
                $arrColorHighLight = array(0xD9, 0xE9, 0xFE);
                $arrColorTextoHighLight = array(0x00, 0x00, 0x00);
                $this->distancias = &$this->distanciasW7;

                break;

            case 2:
                $estiloIcono = "Nautilus";
                $colorFondoArr = array(0xF2, 0xF1, 0xF0);
                $mostrarLineas = -1;
                $arrColorHighLight = array(0xED, 0x74, 0x42);
                $arrColorTextoHighLight = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $this->distancias = &$this->distanciasN;

                break;

            case 3:
                $estiloIcono = "NBJ";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = 1;
                $arrColorHighLight = array(0xD9, 0xE9, 0xFE);
                $arrColorTextoHighLight = array(0x00, 0x00, 0x00);
                $this->distancias = &$this->distanciasNBJ;

                break;

            case 4:
                $estiloIcono = "EcJ";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = -1;
                $arrColorHighLight = array(0xD9, 0xE9, 0xFE);
                $arrColorTextoHighLight = array(0x00, 0x00, 0x00);
                $this->distancias = &$this->distanciasEcJ;

                break;

            case 5:
                $estiloIcono = "Mac";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = -1;
                $arrColorHighLight = array(0xD9, 0xE9, 0xFE);
                $arrColorTextoHighLight = array(0x00, 0x00, 0x00);
                $this->distancias = &$this->distanciasMac;

                break;

            default:
                $estiloIcono = "W7";
                $colorFondoArr = array(0xFF, 0xFF, 0xFE); //al ultimo FF se le quita uno para que quede FE y no de problemas al poner bordes transparentes
                $mostrarLineas = -1;
                $arrColorHighLight = array(0xD9, 0xE9, 0xFE);
                $arrColorTextoHighLight = array(0x00, 0x00, 0x00);
                $this->distancias = &$this->distanciasW7;

                break;
        }

        /* Guardar en variables de instancia
         */
        $this->estiloIcono = $estiloIcono;
        $this->colorFondoArr = $colorFondoArr;
        $this->mostrarLineas = $mostrarLineas;
        $this->arrColorHighLight = $arrColorHighLight;
        $this->arrColorTextoHighLight = $arrColorTextoHighLight;

        //Bordes + un ponderado de la cadena mas larga + nro cols*nro niveles
        //$GraphWidth = (2 * $margenX) + parent::getAnchoImagen() + ((parent::getNivelMax() + 2) * $this->distancias[0][5]);
        /*
          $id = $this->getVerticeMargenDerecho();
          //obtener el vertice de mas a la derecha
          $vertR = $this->getVertex($id);

          $GraphWidth = (2 * $margenX) + parent::getAnchoImagen() + (($vertR->nivel +2) * $this->distancias[0][5]);

          //altura = nro filas * altura de fila
          $GraphHeight = (2 * $margenY) + ($this->getNVerts() * $this->distancias[1][5]);
         */
        $GraphWidth = $this->getWidth();

        $GraphHeight = $this->getHeight();

        $GraphScale = 2;
        $this->mostrarLineas = $mostrarLineas;

        //echo "<br>Antes de crear imagen<br>";
        $image = imagecreatetruecolor($GraphWidth, $GraphHeight);
        //echo "<br>Despues de crear imagen<br>";
        //imageantialias($image, TRUE);
        //imagealphablending($image,TRUE);
        //asignar colores a la imagen
        //echo "<br>color High {$arrColorHighLight[0]} <br>";
        $colorGrid = imagecolorallocate($image, 0x00, 0x00, 0x00);
        $colorHighLight = imagecolorallocate($image, $arrColorHighLight[0], $arrColorHighLight[1], $arrColorHighLight[2]);
        $colorTextoHighLight = imagecolorallocate($image, $arrColorTextoHighLight[0], $arrColorTextoHighLight[1], $arrColorTextoHighLight[2]);
        $colorText = imagecolorallocate($image, 0x00, 0x00, 0x01);

        $this->colorGrid = $colorGrid;
        $this->colorHighLight = $colorHighLight;
        $this->colorTextoHighLight = $colorTextoHighLight;
        $this->colorText = $colorText;

        //Pintar el fondo antes de pintar cualquier otro elemento
        //echo "<br>color Fondo {$colorFondoArr[0]} <br>";
        $colorFondo = imagecolorallocate($image, $colorFondoArr[0], $colorFondoArr[1], $colorFondoArr[2]);
        imagefill($image, 0, 0, $colorFondo);

        //Pintado de lineas punteadas del arbol////////////////////////////////////////////////////////////////////
        if ($mostrarLineas == 1) {
            $styleDashed = array_merge(array_fill(0, 1, $colorGrid), array_fill(0, 1, IMG_COLOR_TRANSPARENT));
            imagesetstyle($image, $styleDashed);
            $rutaicono = realpath(dirname(__FILE__) . '/../images/expandidoNBJ.png');
            //$iconoexp = imagecreatefrompng("images/expandidoNBJ.png");
            $iconoexp = imagecreatefrompng($rutaicono);

            //Para el caso de Netbeans se dibujan lineas

            $anchoExp = imagesx($iconoexp);
            $altoExp = imagesy($iconoexp);
            
            $linXIni = $this->distancias[0][0];
            $linYIni = $this->distancias[1][0];
            $linXFin = $this->distancias[0][0];
            $linYFin = $this->distancias[1][0];
            
            for ($i = 0; $i < $this->nVerts; $i++) {
                
                    $linXIni = $this->distancias[0][0] + (($this->vertexList[$i]->nivel) * $this->distancias[0][5]) + $anchoExp / 2;
                
                $linYIni = $this->distancias[1][0] + (($this->vertexList[$i]->nroFila) * $this->distancias[1][5]) + $altoExp;
                $ultVert = 0;

                for ($j = 0; $j <= $this->nVerts; $j++) {
                    if ($this->adjMat[$i][$j] == 1) {
                        $ultVert = $j;
                        //Linea del Nodo expandido a si mismo
                        $linXFin = $this->distancias[0][0] + (($this->vertexList[$j]->nivel) * $this->distancias[0][5]) + $anchoExp / 2;

                        $linYFin = $this->distancias[1][0] + (($this->vertexList[$j]->nroFila) * $this->distancias[1][5]) + $altoExp;

                        //Resaltar la linea actual
                        //if ($this->findHighLight($j) == 1)
                        if ($this->vertexList[$j]->esResaltado == 1)
                            imagefilledrectangle($image, $linXFin, $linYFin - 9, $GraphWidth - 10, $linYFin + 9, $colorHighLight);
                    }
                }

                if (($ultVert > 0) ) 
                    //imageline($image, $linXIni, $linYIni, $linXIni, $linYFin, IMG_COLOR_STYLED);
                imageline($image, $linXFin, $linYIni, $linXFin, $linYFin, IMG_COLOR_STYLED);
                    
                //echo "<br>";
            } //fin de for i
            //En el caso que se ingrese una sola linea
            if ($this->nroLineas == 1 and $this->vertexList[0]->esResaltado == 1) {
                //usar ultimos valores $linXFin, $linYFin
                imagefilledrectangle($image, $linXFin, $linYFin - 9, $GraphWidth - 10, $linYFin + 9, $colorHighLight);
            }
        } else {
            $styleDashed = array_merge(array_fill(0, 1, $colorGrid), array_fill(0, 1, IMG_COLOR_TRANSPARENT));
            imagesetstyle($image, $styleDashed);
            $rutaicono = realpath(dirname(__FILE__) . '/../images/expandidoNBJ.png');
            //$iconoexp = imagecreatefrompng(plugins_url("pathtreeview/images/expandidoNBJ.png",__FILE));
            $iconoexp = imagecreatefrompng($rutaicono);

            //Para el caso de Netbeans se dibujan lineas

            $anchoExp = imagesx($iconoexp);
            $altoExp = imagesy($iconoexp);

            $linXIni = $this->distancias[0][0];
            $linYIni = $this->distancias[1][0];
            $linXFin = $this->distancias[0][0];
            $linYFin = $this->distancias[1][0];
            for ($i = 0; $i < $this->nVerts; $i++) {
                $linXIni = $this->distancias[0][0] + (($this->vertexList[$i]->nivel) * $this->distancias[0][5]) + $anchoExp / 2;
                $linYIni = $this->distancias[1][0] + (($this->vertexList[$i]->nroFila) * $this->distancias[1][5]) + $altoExp;

                $ultVert = 0;

                for ($j = 0; $j < $this->nVerts; $j++) {
                    if ($this->adjMat[$i][$j] == 1) {
                        $ultVert = $j;
                        //Linea del Nodo expandido a si mismo
                        //imageline($image, $linXIni,$linYIni,$linXIni+10,$linYIni, IMG_COLOR_STYLED);

                        $linXFin = $this->distancias[0][0] + (($this->vertexList[$j]->nivel) * ($this->distancias[0][5])); //+$anchoExp/2;
                        $linYFin = $this->distancias[1][0] + (($this->vertexList[$j]->nroFila) * $this->distancias[1][5]) + $altoExp;

                        //Resaltar la linea actual
                        //if ($this->findHighLight($j) == 1)
                        if ($this->vertexList[$j]->esResaltado == 1)
                        //imagefilledrectangle ($image, $linXFin, $linYFin-7, $GraphWidth-10, $linYFin+7, $colorHighLight);
                        //(3*$this->distancias[0][5])+$this->distancias[0][4]
                            imagefilledrectangle($image, $linXFin + (3 * $this->distancias[0][5]) + $this->distancias[0][4], $linYFin - 7, $GraphWidth - 10, $linYFin + 7, $colorHighLight);
                    }
                }//fin de for i
                //En el caso que se ingrese una sola linea
                //if ($this->nroLineas==1 and $this->findHighLight(0) == 1 ){
                if ($this->nroLineas == 1 and $this->vertexList[0]->esResaltado == 1) {
                    //usar ultimos valores $linXFin, $linYFin
                    imagefilledrectangle($image, $linXFin, $linYFin - 9, $GraphWidth - 10, $linYFin + 9, $colorHighLight);
                }
            }
        }

        //actualizar imagen de esta instancia
        $this->image = $image;
    }

    public function guardarArchivoImagen($formato) {
        if (strcmp($formato, 'png') == 0)
            imagepng($this->image, $this->getNombreArchSalida() . ".png");
        if (strcmp($formato, 'jpg') == 0)
            imagejpeg($this->image, $this->getNombreArchSalida() . ".jpg");
        if (strcmp($formato, 'gif') == 0)
            imagegif($this->image, $this->getNombreArchSalida() . ".gif");
    }

    public function getImagenBase64() {

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        /* Borde redondeado con fondo transparente en PNG */

        $radio = 5;
        $diametro = ($radio * 2) + 1;
        $figura = imagecreatetruecolor($diametro, $diametro);
        $fondo = imagecolorallocate($figura, 255, 255, 255);
        imagefill($figura, 0, 0, $fondo);
        $negro = imagecolorallocate($figura, 0, 0, 0);

        //El antialiasing NO es soportado por GD2
        //imageantialias($image, TRUE);
        //imageSmoothArc(resource &$img, int $cx, int $cy, int $w, int $h, array $color, float $start, float $stop)

        imagefilledellipse($figura, $radio, $radio, $diametro, $diametro, $negro);
        imagecolortransparent($figura, $negro);
        //$foto = imagecreatefromstring(file_get_contents($_GET["url"]));
        $foto = $this->image;
        $ancho = imagesX($foto);
        $alto = imagesY($foto);

        imagecopymerge($foto, $figura, 0, 0, 0, 0, $radio, $radio, 100);
        imagecopymerge($foto, $figura, $ancho - $radio, 0, $radio + 1, 0, $radio, $radio, 100);
        imagecopymerge($foto, $figura, 0, $alto - $radio, 0, $radio + 1, $radio, $radio, 100);
        imagecopymerge($foto, $figura, $ancho - $radio, $alto - $radio, $radio + 1, $radio + 1, $radio, $radio, 100);
        $transpa = imagecolorallocate($foto, 255, 255, 255);

        imagecolortransparent($foto, $transpa);
        $this->image = $foto;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /* GD mo provee un metodo para retornar una salida de una imagen como texto,
         * pero se puede hacer un proceso falso con las funciones de output buffering :
         */
        ob_start();
        imagepng($this->image); // sin segundo parametro, lo cual podria hacer que se escriba a un archivo
        $img = ob_get_clean();
        $this->imageB64 = base64_encode($img);
        unset($img);
        return $this->imageB64;
    }

    public function __destruct() {
        imagedestroy($this->image);
    }

    public function getHeight() {
        $margenY = 30;
        $id = $this->getVerticeMargenDerecho();
        //obtener el vertice de mas a la derecha
        $vertR = $this->getVertex($id);


        //altura = nro filas * altura de fila
        $GraphHeight = (2 * $margenY) + ($this->getNVerts() * $this->distancias[1][5]);
        return $GraphHeight;
    }

    public function getWidth() {

        $margenX = 10;

        $id = $this->getVerticeMargenDerecho();
        //obtener el vertice de mas a la derecha
        $vertR = $this->getVertex($id);

        $GraphWidth = (2 * $margenX) + parent::getAnchoImagen() + (($vertR->nivel + 2) * $this->distancias[0][5]);

        return $GraphWidth;
    }

}

?>