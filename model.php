<?php

    function scanElements ($document) {
        $elements = array();
        $i = 0;
        foreach ($document['uml:Model']['packagedElement'] as $element) {
            if (isset($element['@attributes'])) {
                if ($element['@attributes']['type'] == 'uml:Class') {
                    $elements[$i]['name'] = $element['@attributes']['name'];
                    $elements[$i]['id'] = $element['@attributes']['id'];
                    $i++;
                }
            } else {
                if ($element['type'] == 'uml:Class') {
                    $elements[$i]['name'] = $element['name'];
                    $elements[$i]['id'] = $element['id'];
                    $i++;
                }
            }
        }
        return $elements;
    }

    function linkElementAttributes ($elements, $document) {
        foreach (array_slice($document,1,-1) as $stereotype) {
            if (isset($stereotype['@attributes'])) {
                $elements[array_search($stereotype['@attributes']['base_Class'], array_column($elements, 'id'))]['attributes'] = $stereotype; 
            } else {
                foreach ($stereotype as $class) {
                    $elements[array_search($class['@attributes']['base_Class'], array_column($elements, 'id'))]['attributes'] = $class; 
                }
            }
        }
        return $elements;
    }

    function elementsSimple ($elements) {
        $clean = array();
        foreach ($elements as $element) {
            if (isset($element['attributes'])) {
                foreach ($element['attributes'] as $key => $attribute) {
                    $clean[$element['name']][$key] = $attribute; 
                }
            }
        }
        return $clean;
    }

    function cleanArray ($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = cleanArray($value);
                if ($key === '@attributes') {
                    foreach ($array[$key] as $k => $v) {
                        $array[$k] = $v;
                    }
                }
                if (array_key_exists('unity', $value)) {
                    $array = ($value['unity'] != 'auto' ? floatval(@$value['number']) : '') . ($value['unity'] == 'percent' ? '%' : $value['unity']);    
                    break;
                }
            }
            if ($key === '@attributes' || $key === 'type' || $key === 'id' || empty($array[$key])) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    function clearDocument ($document) {
        return cleanArray(elementsSimple(linkElementAttributes(scanElements($document), $document)));
    }

?>