<?

class siteProperties{

    private static $class_path = 'include/php/class/';
    private static $root_path = '/var/www/find-spots.com/';
    private static $script_path = 'sh/';

    static function getClassPath(){
        return self::getRootPath() . self::$class_path;
    }    

    static function getRootPath(){
        return self::$root_path;
    }    

    static function getScriptPath(){
        return self::getRootPath() . self::$script_path;
    }    

}


class siteTools{

    function generateAnchorAttributes($arguments_array){
        
        if(array_key_exists('href', $arguments_array['attributes'])){
            // Set the URL accordingly to the
            // type of navigation the user has
            // chosen.
            $arguments_array['attributes']['rel'] = $arguments_array['attributes']['href'];
            $arguments_array['attributes']['href'] = '?' . $arguments_array['attributes']['href'];
            if(userSettings::getNavigationType() == 'ajax'){
                // Add the AJAX page navigation function
                // to the "onclick" event.
                $arguments_array['attributes']['onclick'] .= 'window.timer = new Date();window.loadWithHistoryListener = false;loadPage(this.rel);return false;';
            }else{
            }
        }

        return self::generateMLAttributes(array('attributes' => $arguments_array['attributes']));
    }

    function generateMLAttributes($arguments_array){
    // Generate all the attributes for any
    // markup language from an array.
        $attributes = '';
        foreach($arguments_array['attributes'] as $attributeName => $attributeValue){
            $attributes .= ' ' . str_replace('"', '', $attributeName) . '="' . str_replace('"', '', $attributeValue) . '"';
        }

        return $attributes;
    }

    function arrayToXml($arguments_array){
        $xml = '';

        // Set the header for the document.
        if($arguments_array['declaration'] != ''){
            $xml .= '<?xml version="1.0" ?>';
        }

        // Set the top level element with the chosen
        // name, otherwise that element is set with
        // the default value.
        if($arguments_array['rootElement'] != ''){
            $xml .= '<' . $arguments_array['rootElement'] . '>' . self::arrayToXmlElements(array('elements' => $arguments_array['elements'])) . '</' . $arguments_array['rootElement'] . '>';
        }else{
            $xml .= self::arrayToXmlElements(array('elements' => $arguments_array['elements']));
        }

        return $xml;
    }

    function arrayToXmlElements($arguments_array){
        $xml = '';
        foreach($arguments_array['elements'] as $index => $cell){
            if(is_numeric($index)){
                $index = '_' . $index;
            }

            $xml .= '<' . $index . '>';

            // Process the array recursively.
            if(is_array($cell)){
                $xml .= self::arrayToXml(array('elements' => $cell));
            }else{
            // Put the content of the cell in the XML
            // element but escape it with the CDATA
            // wrapper if there are conflicting characters.
                if(eregi("<|>|&|'|\"", $cell)){
                    $cell = str_replace(']]>', '', $cell);
                    $cell = '<![CDATA[' . $cell . ']]>';
                }
                $xml .= $cell;
            }
            $xml .= '</' . $index . '>';
        }
        return $xml;
    }
}

?>
