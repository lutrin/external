<?php
class arrayToXML {
    public static function toXML( $data, $rootNodeName = 'root', &$xml=null ) {

        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) //$xml = simplexml_load_string( "" );
            $xml = simplexml_load_string("<?xml version='1.0' encoding='UTF-8'?><?xml-stylesheet type='text/xsl' href='cms/transformation/main.xsl'?><$rootNodeName />");

        // loop through the data passed in.
        foreach( $data as $key => $value ) {

            $numeric = false;
            
            // no numeric keys in our xml please!
            if ( is_numeric( $key ) ) {
                $numeric = 1;
                $key = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recrusively call this function
            if ( is_array( $value ) ) {
                $node = self::isAssoc( $value ) || $numeric ? $xml->addChild( $key ) : $xml;

                // recrusive call.
                if ( $numeric ) $key = 'anon';
                self::toXml( $value, $key, $node );
            } else {

                // add single node.
                $xml->addChild( $key, $value );
            }
        }

        // pass back as XML
        return $xml->asXML();

    // if you want the XML to be formatted, use the below instead to return the XML
        /*$doc = new DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML( $xml->asXML() );
        $doc->formatOutput = true;
        return $doc->saveXML();*/
    }

    public static function isAssoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}
