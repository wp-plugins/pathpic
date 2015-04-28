<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MuestraVertice
 *
 * @author george
 */
include_once 'Graph.php';

class MuestraVertice extends Graph {

    public $arbolString;
    private $colorRaiz;
    private $colorEsExpandido;
    //Color linea vertical
    private $colorLineaVert;
    //Color linea horizontal
    private $colorLineaHoriz;
    //Color nombre de elemento directorio
    private $colorElemDir;
    //Color nombre de elemento archivo
    private $colorElemArch;
    //Color elemento resaltado
    private $colorElemHightLight;

    //put your code here
    function __construct($estilo=0) {
        $this->inicializar();
       
        //-------------------------------------------------Colores de los elementos---------------------------------------------------------------------      
        
        if ($estilo == 0) {
            //TotalTerminal MAC
            //*color de fondo 51,51,51*/        
            //color de caracter de expandido , Color esContraido = $this->colorLineaVert        
            $this->colorRaiz = "#617F8F";
            $this->colorEsExpandido = "#ffddff";
            //Color linea vertical
            $this->colorLineaVert = "#ffffff";
            //Color linea horizontal
            $this->colorLineaHoriz = "#ffffff";
            //Color nombre de elemento directorio
            $this->colorElemDir = "#DDDCAA"; // "#001b93";
            //Color nombre de elemento archivo
            $this->colorElemArch = "#339988"; //331100
            //Color elemento resaltado
            $this->colorElemHightLight = "#CCEEFF";
        }

        if ($estilo == 1) {
            //Guake Ubuntu o Gnome
            //*color de fondo 59,61,35*/
            //color de caracter de expandido , Color esContraido = $this->colorLineaVert                
            $this->colorRaiz = "#ffffff";
            $this->colorEsExpandido = "#ffddff";
            //Color linea vertical
            $this->colorLineaVert = "#ffffff";
            //Color linea horizontal
            $this->colorLineaHoriz = "#ffffff";
            //Color nombre de elemento directorio
            $this->colorElemDir = "#669FCF"; // "#001b93";
            //Color nombre de elemento archivo
            $this->colorElemArch = "#ffffff";
            //Color elemento resaltado
            $this->colorElemHightLight = "#ff11ff";
        }
        
        if ($estilo == 2) {
            //Yaquake Ubuntu o Gnome
            //*color de fondo 60,50,38*/
            //color de caracter de expandido , Color esContraido = $this->colorLineaVert               
            $this->colorRaiz = "#DCA3A3";
            $this->colorEsExpandido = "#ffddff";
            //Color linea vertical
            $this->colorLineaVert = "#ffffff";
            //Color linea horizontal
            $this->colorLineaHoriz = "#ffffff";
            //Color nombre de elemento directorio
            $this->colorElemDir = "#94BFF3"; // "#001b93";
            //Color nombre de elemento archivo
            $this->colorElemArch = "#7287A3";
            //Color elemento resaltado
            $this->colorElemHightLight = "#333355";
        }
        
        //Fin-----------------------------------------------Colores de los elementos-----------------------------------------------------------      
    }

    public function procesarVertices($v, $dirname, $basename, $nivel) {
        //Application_Model_Vertex($lab);
        $arrVertTemp = $this->getVertex($v);
        $nodoString = "";
        //echo "<br>Mostrando : Imagen -> Vertice : $v | nivel : $arrVertTemp->nivel | dirname : $arrVertTemp->dirname | basename : $arrVertTemp->basename<br>";
        //$strBlankCol = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; //"++++++";
        $strBlankCol = str_repeat("&nbsp;", 12);
        $strCol = "----";
        //$strFil = "&nbsp;|";
        $strOpen = "";
        $margenX = 0;
        $strLineaSalida = str_repeat("&nbsp;", $margenX);
        /* echo ("x $this->pathformat<br>"); OK muestra Windows o Linux */
        for ($i = 0; $i < $arrVertTemp->nroCol - 1; $i++) {
            //$strCol = "------";                                                                        este nbsp es para linear el | con el simbolo +, porque este ocupa mas espacio
            $strLineaSalida = $strLineaSalida . "<FONT COLOR=\"$this->colorLineaVert\">&nbsp;" . $this->matLineasVerticales[$arrVertTemp->nroFila][$i] . "</FONT>" . "<FONT COLOR=\"#ffffff\">" . $strBlankCol . "</FONT>";
        }
        
        //Colocar indicador de directorio expandido/contraido o de archivo a cada vertice
        if ($arrVertTemp->esDirectorio == 1) {
            if ($arrVertTemp->esExpandido == "1")
            $strOpen = "<FONT COLOR=\"$this->colorEsExpandido\">" . "[-]" . "</FONT>";
        else                //                                              este nbsp es para linear el | con el simbolo +, porque este ocupa mas espacio
            $strOpen="<FONT COLOR=\"$this->colorLineaVert\">" . "[+]" . "</FONT>";
        }
        else{
            $strOpen="<FONT COLOR=\"$this->colorLineaVert\">" . "&nbsp;|" . "</FONT>";
        }
        
        if ($v == 0) {
            if (($arrVertTemp->dirname == "\x5C" or $arrVertTemp->dirname == "\x2F") and $arrVertTemp->basename == "") {
                if (strcasecmp($this->pathformat, "Windows") == 0)
                //echo "<br>" . $strLineaSalida . "<FONT COLOR=\"$colorRaiz\">" . "&nbsp;&nbsp;\x5C" . "</FONT>";
                    $nodoString = "\n" . $strLineaSalida . "<FONT COLOR=\"$this->colorRaiz\">" . "&nbsp;&nbsp;\x5C" . "</FONT>";
                else
                // echo "<br>" . $strLineaSalida . "<FONT COLOR=\"$colorRaiz\">" . "&nbsp;&nbsp;\x2F" . "</FONT>";               
                    $nodoString = "\n" . $strLineaSalida . "<FONT COLOR=\"$this->colorRaiz\">" . "&nbsp;&nbsp;\x2F" . "</FONT>";
            } else {
                //echo "<br>" . $strLineaSalida . "<FONT COLOR=\"$colorRaiz\">" . $arrVertTemp->basename . "</FONT>";
                $nodoString = "\n" . $strLineaSalida . "<FONT COLOR=\"$this->colorRaiz\">" . $arrVertTemp->basename . "</FONT>";
            }
        }
        else{
        //echo "<br>" . $strLineaSalida . $strOpen . "<FONT COLOR=\"$this->colorLineaHoriz\">" . $strCol . "</FONT>" . "<FONT COLOR=\"$this->colorElemDir\">" . $arrVertTemp->basename . "</FONT>";
            if ($this->vertexList[$v]->esDirectorio == 1){
            $nodoString = "\n" . $strLineaSalida . $strOpen . "<FONT COLOR=\"$this->colorLineaHoriz\">" . $strCol . "</FONT>" . "<FONT COLOR=\"$this->colorElemDir\">" . $arrVertTemp->basename . "</FONT>";
            }
        else {
            if ($this->vertexList[$v]->esResaltado == 1){
            $nodoString = "\n" . $strLineaSalida . $strOpen . "<FONT COLOR=\"$this->colorLineaHoriz\">" . $strCol . "</FONT>" . "<mark style = \"background-color: ".$this->colorElemHightLight."; color: ".$this->colorElemArch.";\" >" . $arrVertTemp->basename . "</mark>";
            }
            else{
                $nodoString = "\n" . $strLineaSalida . $strOpen . "<FONT COLOR=\"$this->colorLineaHoriz\">" . $strCol . "</FONT>" . "<FONT COLOR=\"$this->colorElemArch\">" . $arrVertTemp->basename . "</FONT>";
            }
        }
            
        }
        $this->arbolString = $this->arbolString . $nodoString;


        return $v;
    }

    public function getArbolString() {
        return $this->arbolString;
    }
    
    function getWidth(){
        
        $margenX = 0;
        $id = $this->getVerticeMargenDerecho();       
        //obtener el vertice de mas a la derecha
        $vertR = $this->getVertex($id);
        
        //echo "<br>Der : ".$vertR->basename."-----tamaño letra----$this->tamLetra--<br>";
        //$GraphWidth = (2 * $margenX) + (strlen($vertR->basename)*($this->tamLetra)) + (($vertR->nroCol-1)*($this->tamLetra));                
        $GraphWidth = ($this->tamLetra/2)*(strlen($vertR->basename) + (($vertR->nroCol-1)*10));   // 10 son los caracteres que forman una columna             
        return $GraphWidth;
        
    }
    
     function getHeight(){
         $margenY = 7;  //diametro de borde redondeado del div      
        //$GraphHeight = 3.5 * $margenY + ($this->objMuestraVertice->getNVerts() * 20);
         //a getNVerts se le suma 2 para que haya un margen inf (1 linea ) para el alto del div
         
         $GraphHeight = 2* $margenY + (($this->getNVerts()+1) *  ($this->tamLetra+2)); //$this->tamLetra+2 = alto de linea
        
        return $GraphHeight;        
    }
    

}

?>
