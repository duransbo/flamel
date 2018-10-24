<?php
    
    include_once('manipulate.php');
    include_once('model.php');
    include_once('file.php');

    function transform ($elements) {
        foreach ($elements as $x => $file) {
            if (isset($file['path']) && file_exists('../modelo/'.$file['path'])) {
                $json = clearDocument(readXML('../modelo/'.$file['path']));
                if (isset($file['imports'])) {
                    foreach ($file['imports'] as $y => $import) {
                        foreach ($elements as $name => $imported) {     
                            if ($import == $imported['base_Class']) {
                                $json['imports'][$y] = $name;
                            }
                        }
                    }
                }
                writeFile('../json/'.$x.'.json', toJSON($json));
            }
        }
    }

    function goldenTouch ($diagram) {
        transform(cleanArray(filesSimple(linkFilePath(scanFiles(readXML($diagram)), readXML($diagram)))));
    }

?>