<?php
    
    function DOMToArray($document) {
        $return = array();
        switch ($document->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $return = trim($document->textContent);
                break;
            case XML_TEXT_NODE:
                $return = trim($document->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($count = 0, $childNodeLength = $document->childNodes->length; $count < $childNodeLength; $count++) {
                    $child = $document->childNodes->item($count);
                    $childValue = DOMToArray($child);
                    if (isset($child->tagName)) {
                        $tag = $child->tagName;
                        if(!isset($return[$tag])) {
                            $return[$tag] = array();
                        }
                        $return[$tag][] = $childValue;
                    } elseif($childValue || $childValue === '0') {
                        $return = (string) $childValue;
                    }
                }
                if ($document->attributes->length && !is_array($return)) {
                    $return = array('@content'=>$return);
                }
                if (is_array($return)) {
                    if ($document->attributes->length) {
                        $attributes = array();
                        foreach ($document->attributes as $name => $node) {
                            $attributes[$name] = (string) $node->value;
                        }
                        $return['@attributes'] = $attributes;
                    }
                    foreach ($return as $key => $value) {
                        if (is_array($value) && count($value) == 1 && $key != '@attributes') {
                            $return[$key] = $value[0];
                        }
                    }
                }
                break;
        }
        return $return;
    }
    
    function readXML ($file) {
        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->load($file);
        return DOMToArray($xml->documentElement);
    }

    function toJSON ($xml) {
        return json_encode($xml);
    }

    function writeFile ($name, $text) {
        if (!$handle = fopen($name, 'w')) {
            echo "Não foi possível abrir o arquivo ($name)";
            exit;
        }        
        if (fwrite($handle, $text) === FALSE) {
            echo "Não foi possível escrever no arquivo ($name)";
            exit;
        }     
        fclose($handle);
        echo 'Write ' . $name . '<br>';                
    }

    function readJSON ($file) {
        return json_decode(file_get_contents($file), true);
    }

?>