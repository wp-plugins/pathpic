<?php

/*
  Plugin Name: pathpic
  Plugin URI: https://bitbucket.org/jorgelsbustamante-admin/pathpic/downloads/pathpic.zip
  Description: Show path lists as an image or a tree of text representing the path hierarchies with a file system manager's style.
  Version: 0.8.3
  Author: jlscwpplugins
  Author URI: www.blogtucompu.wordpress.com
  License: GPL2
 */
add_shortcode('pathpic', 'pathpic_mostrar_treeview');
add_action('init', 'pathpic_agregar_cabecera');

$version = "0.8.3";
//a efectos de usar las clases, agregamos un nuevo path a include path de php.ini
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(dirname(__FILE__)),
            get_include_path(),
        )));


include_once 'Controller/ControladorGrafo.php';

// página de configuración - Sin usar- 
function pathpic_config_page() {
    
    add_menu_page('Config banner', 'Administrar pathpic', 'administrator', 'pathpic.php', 'pathpic_options_plugin');
}

//opciones de configuración
function pathpic_options_plugin() {
    //  require_once('../wp-config.php');
}

//Funcion princiapal de este plugin
function pathpic_mostrar_treeview($atts, $content = '') {

    //Recoleccion de parametros

    extract(shortcode_atts(array(
                'style' => 'Netbeans',
                'mode' => 'image',
                'os' => 'Windows',
                'files' => '',
                'highlights' => ''
                    ), $atts));

    //La lista de paths contenidos por el shortcode de este plugin
    //se le aplica strip_tags para remover algun codigo html como por ejemplo 
    // un <br> entre el inicio y fin de un shortcode .
    if (get_magic_quotes_gpc() )
        //se le agrega este addslashes para que tenga concordancia con 
        //la funcion uniformizarPaths de la clase validaVertice 
        //que se usa cuando se obtiene la entrada desde un textarea
        //y ademas con la opcion magic_quotes_gpc en ON
        $listaRutas = strip_tags(addslashes ($content));
    else
        $listaRutas = strip_tags($content);
    //string con la lista de numeros de lineas ,separados por comas,de paths que seran mostrados como archivos 
    $listaFiles0 = $files;
    //string con la lista de numeros de lineas ,separados por comas,de paths que seran mostrados como resaltados 
    $listaHighLights0 = $highlights;

    $params = array();
    
    //Valores por defecto de parametros
    //Estilo de explorador  0 = XP, 1 = W7, 2=Nautilus (Ubuntu), 3 = Netbeans, 4 = Mac
    //Estilo Texto 0 = TotalTerminal MAC, 1 = Guake, 2 = Yaquake
    $params[0] = 'Netbeans';
    //tipo vista : text ó image
    $params[1] = 'image'; // "text";
    //contorno , 1 = con contorno
    $params[2] = 0;
    //path format "Windows", "Linux"
    //formato de los paths
    $params[3] = $os; //$_POST['pathformat'];
    //Validar que se use correctamente los modos con el estilo de treeview
    if (strcasecmp($mode, "image") == 0) {
        $params[1] = 'image';
        if (strcasecmp($style, "XP") == 0)
            $params[0] = 0;

        if (strcasecmp($style, "W7") == 0)
            $params[0] = 1;

        if (strcasecmp($style, "Ubuntu") == 0)
            $params[0] = 2;

        if (strcasecmp($style, "Netbeans") == 0)
            $params[0] = 3;
    }
    else {
        $params[1] = 'text';
        if (strcasecmp($style, "TotalTerminal") == 0)
            $params[0] = 0;

        if (strcasecmp($style, "Guake") == 0)
            $params[0] = 1;

        if (strcasecmp($style, "Yaquake") == 0)
            $params[0] = 2;
    }


    $myApp = new ControladorGrafo($listaRutas, $params, $listaFiles0, $listaHighLights0);

    $myApp->crearGrafo();
    $out = "";
    //tamaño de fuente 
    $tamFont=14;
    //tamaño de linea 
    $tamInterLin=$tamFont+2;
    //future use
    $archOut="sample_name";
    //Ya que las dimensiones de la salida se han calculado por separado, tambien se obtienen por separado
    $myApp->procesarGrafo($tamInterLin,$archOut);
    $ancho = $myApp->getWidth();
    $alto = $myApp->getHeight();
    //Se puede aplicar un Zoom
    /*$ancho = $ancho / 2;
    $alto = $alto / 2;
    $tamFont=$tamFont/2;
    $tamInterLin=$tamFont+2;
*/
    if ($myApp->getViewMode() == 'image') {

        //Obtiene la imagen (PNG) convertida en una cadena Base64
        $imagenB64Str = $myApp->getViewImage();

        //mostrar el grafo        
                
        //$out = '<div id="tshot" style=" background : rgb(255,255,255); width:' . $ancho . 'px; height:' . $alto . 'px; border-color : rgb(81,238,246); border-width: 1px; border-style: solid; font-family : times,verdana; text-align:left; padding: 2px 2px 2px 2px; margin:0 auto;border-radius:10px;-moz-border-radius: 10px; -webkit-border-radius : 10px; ">';       
        $out = '<div class="image_background" style="width:' . $ancho . 'px; height:' . $alto . 'px;">';       
        $out.= "<img width=\"{$ancho}px\" height=\"{$alto}px\" src=\"data:image/png;base64,{$imagenB64Str}\" alt=\"image\"/>";
        $out.= '</div>';
        //Una vez creado el objeto image y haber dibujado en el , se puede guardar en 3 formatos PNG, JPG y GIF    
    }

    if ($myApp->getViewMode() == 'text') {        
        //añadimos un margen sup e inf
        /*$alto= $alto +20;
        $fondoDiv = 0;*/
        
        switch ($myApp->getStyle()) {
            case 0 : $fondoDiv = "text_TotalTerminal"; //TotalTerminal
                break;
            case 1 : $fondoDiv = "text_Guake"; //Guake
                break;
            case 2 : $fondoDiv = "text_Yaquake"; //Yaquake
                break;
            default : $fondoDiv = "text_Yaquake"; //Yaquake
                break;
        }
        
        $out = '<div class="'.$fondoDiv.'" style="font-size : '.$tamFont.'px; line-height : '.$tamInterLin.'px; width:' . $ancho . 'px; height:' . $alto . 'px; ">';
        //nl2br : convierte los 'new line' al tag <br>  de HTML
        $out.= nl2br($myApp->getViewText());
        $out.= '</div>';
    }

    unset($myApp);
    return $out;
}

function pathpic_agregar_cabecera() {

    $src1 = plugins_url('css/pathpic.css', __FILE__);
      wp_register_style('pathtv', $src1);
      wp_enqueue_style('pathtv'); 
    
      wp_enqueue_script('jquery');
}

?>
