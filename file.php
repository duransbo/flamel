<?php    

    function scanFiles ($document) {
        $elements = array();
        $i = 0;
        foreach ($document['uml:Model']['packagedElement'] as $element) {
            if ($element['@attributes']['type'] == 'uml:Class') {
                $elements[$i]['name'] = $element['@attributes']['name'];
                $elements[$i]['id'] = $element['@attributes']['id'];
                if (isset($element['ownedAttribute'])) {
                    foreach ($element['ownedAttribute'] as $import) {
                        $elements[$i]['imports'][] = $import['@attributes']['type'];
                    }
                }
                if (isset($element['generalization'])) {
                    $elements[$i]['in'] = $element['generalization']['@attributes']['general'];
                }
                $i++;
            }
        }
        return $elements;
    }

    function linkFilePath ($elements, $document) {
        foreach (array_slice($document,1,-1) as $stereotype) {
            foreach ($stereotype as $class) {
                $elements[array_search($class['@attributes']['base_Class'], array_column($elements, 'id'))]['attributes'] = $class; 
            }
        }
        return $elements;
    }

    function filesSimple ($elements) {        
        $clean = array();
        foreach ($elements as $element) {
            $clean[$element['name']]['imports'] = @$element['imports'];
            foreach ($element['attributes'] as $key => $attribute) {
                $clean[$element['name']][$key] = $attribute; 
            }
            if (isset($element['in'])) {
                foreach ($elements as $import) {
                    if ($element['in'] == $import['id']) {
                        $clean[$import['name']]['implements'][] = $element['name'];
                    }
                }
            }
        }
        return $clean;
    }

?>