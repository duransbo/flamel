<?php

    echo "\n\n\n\n\n//Reset\n";
    metamorfose(readJSON('../json/Reset.json'));
    echo "\n\n\n\n\n//Template\n";
    metamorfose(readJSON('../json/Template.json'));
    echo "\n\n\n\n\n//Header\n";
    metamorfose(readJSON('../json/Header.json'));
    echo "\n\n\n\n\n//Footer\n";
    metamorfose(readJSON('../json/footer.json'));
    echo "\n\n\n\n\n//Home\n";
    metamorfose(readJSON('../json/Home.json'));
    echo "\n\n\n\n\n//Artigo\n";
    metamorfose(readJSON('../json/artigo.json'));
    //echo "\n\n\n\n\n//Lista de Artigos\n";
    //metamorfose(readJSON('json/ListaDeArtigos.json'));

    function readJSON ($file) {
        return json_decode(file_get_contents($file), true);
    }

    function write ($a, $b) {
        if (isset($a)) {
            echo "\t$b: $a;\n";
        }
    }

    function writeArray($a, $b) {        
        if (is_array(@$a)) {
            echo "\t$b:";
            foreach ($a as $n) {
                echo " $n";
            }
            echo ";\n";
        } else {
            write(@$a, $b);
        }
    }

    function dimension ($a) {
        if (isset($a)) {
            write(@$a['width'], 'width');
            write(@$a['height'], 'height');
        }
    }

    function size ($a, $b) {
        if (isset($a)) {
            echo "\t$b: ".(isset($a['width']) ? $a['width'] : '')." ".(isset($a['height']) ? $a['height'] : '').";\n";
        }
    }

    function position ($a, $b) {
        if (isset($a)) {
            echo "\t$b: ".(isset($a['top']) ? $a['top'] : '')." ".(isset($a['left']) ? $a['left'] : '').";\n";
        }
    }

    function side ($a, $b) {
        if (isset($a)) {
            echo "\t$b: ".(isset($a['top']) ? $a['top'] : '0px')." ".(isset($a['left']) ? $a['left'] : '0px')." ".(isset($a['bottom']) ? $a['bottom'] : '0px')." ".(isset($a['right']) ? $a['right'] : '0px').";\n";
        }
    }

    function color ($a, $b) {
        if (isset($a)) {
            echo "\t$b: ";
            switch ($a['case']) {
                case 'hexa':
                    echo '#'.$a['x'];
                    break;
                case 'rgb':
                    echo 'rgba('.$a['x'].','.$a['y'].','.$a['z'].','.(isset($a['a']) ? $a['a'] : 1).')';
                    break;
                case 'hsl':
                    echo 'hsla('.$a['x'].','.$a['y'].','.$a['z'].','.(isset($a['a']) ? $a['a'] : 1).')';
                    break;
                default:
                    echo $a['case'];
                    break;
            }  
            echo ";\n";
        }     
    }

    function decoration ($a, $b) {
        if (isset($a)) {
            echo "\t$b: ";
            switch ($a) {
                case 'line':
                    echo 'line-through';
                    break;
                case 'side':
                    echo 'overline underline';
                    break;
                default:
                    echo $a;
                    break;
            }  
            echo ";\n";     
        }
    }

    function metamorfose ($json) {
        foreach ($json as $key => $element) {
            if ($key == 'imports') {
                if (is_array($element)) {
                    foreach ($element as $import) {
                        echo '@import '. $import.".json\n";
                    }
                } else {
                    echo '@import '. $element.".json\n";
                }
            } else {
                if (isset($element['tags'])) {
                    if (is_array($element['tags'])) {
                        foreach ($element['tags'] as $n => $tag) {
                            echo ($n == 0 ? '' : ",\n").$tag;
                        }
                    } else {
                        echo $element['tags'];
                    }
                } else {
                    echo ".".$key;
                }
                echo " {\n";
                foreach ($element as $attribute => $value) {
                    if ($attribute != 'tags' && $attribute != 'base_Class' && $attribute != 'imports') {
                        switch ($attribute) {   
                            case 'size':
                                echo "\tfont-size: $value;\n";
                                break;  
                            case 'dimension':
                                dimension(@$value['Ini']);
                                dimension(@$value['max']);
                                dimension(@$value['min']);
                                break;   
                            case 'outline':
                                side(@$value['width'], 'outline-width');
                                writeArray(@$value['style'], 'outline-style');
                                color(@$value['color'], 'outline-color');
                                break;    
                            case 'border':
                                side(@$value['radius'], 'border-radius');
                                side(@$value['width'], 'border-width');
                                writeArray(@$value['style'], 'border-style');
                                color(@$value['color'], 'border-color');
                                break;    
                            case 'background':
                                color(@$value['color'], 'background-color');
                                writeArray(@$value['repeat'], 'background-repeat');
                                writeArray(@$value['attachment'], 'background-attchment');
                                size(@$value['size'], 'background-size');
                                position(@$value['position'], 'background-size');
                                if (isset($value['image'])) {
                                    echo "\tbackground-image: '".$value['image']."';\n";
                                }
                                break;   
                            case 'margin':
                                side(@$value, 'margin');
                                break;
                            case 'padding':
                                side(@$value, 'padding');
                                break;
                            case 'align':
                                echo "\ttext-align: $value;\n";
                                break; 
                            case 'line':
                                echo "\tline-height: $value;\n";
                                break; 
                            case 'display':
                                echo "\t$attribute: $value;\n";
                                break;
                            case 'box':
                                echo "\tbox-sizing: $value-box;\n";
                                break;
                            case 'transform':
                                echo "\ttext-transform: $value;\n";
                                break;  
                            case 'overflow':
                                write(@$value['x'], 'overflow-x');
                                write(@$value['y'], 'overflow-y');
                                break; 
                            case 'cursor':
                                echo "\t$attribute: $value;\n";
                                break;
                            case 'float':
                                echo "\t$attribute: $value;\n";
                                break;             
                            case 'color':
                                color(@$value, 'color');
                                break;            
                            case 'decoration':
                                decoration(@$value['case'], 'text-decoration-line');
                                color(@$value['color'], 'text-decoration-color');
                                break;           
                            case 'position':
                                write(@$value['case'], 'position');
                                write(@$value['index'], 'index'); 
                                write(@$value['top'], 'top'); 
                                write(@$value['left'], 'left');  
                                break;            
                            case 'flex':
                                write(@$value['wrap'], 'flex-wrap');
                                write(@$value['justify'], 'justify-content'); 
                                write(@$value['items'], 'align-items'); 
                                write(@$value['content'], 'align-content'); 
                                break;             
                            case 'font':  
                                if (isset($value['family'])) {
                                    echo "\tfont-family: '".$value['family']."';\n";
                                }
                                write(@$value['style'], 'font-style'); 
                                write(@$value['size'], 'font-size'); 
                                write(@$value['weight'], 'font-weight');
                                break;         
                            case 'teste':
                                echo "\t$attribute: ";
                                var_dump($value);
                                break;                     
                            default:
                                echo "\t// Erro no atributo  $attribute\n";
                                break;
                        }
                    }
                }
                echo "}\n\n";
            }
        }
    }

?>