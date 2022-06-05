<?php

namespace App\Service\Utilities;

class Utils
{
    public static function obj2array (&$Instance) {
        $clone = (array) $Instance;
        $rtn = array ();
        $rtn['___SOURCE_KEYS_'] = $clone;

        foreach ($clone as $key => $value){
            $aux = explode ("\0", $key);
            $newkey = $aux[count($aux)-1];
            $rtn[$newkey] = &$rtn['___SOURCE_KEYS_'][$key];
        }
        return $rtn;
    }
    public static function bless ( &$Instance, $Class ) {
        if ( ! (is_array ($Instance) ) ) {
            return NULL;
        }

        // First get source keys if available
        if ( isset ($Instance['___SOURCE_KEYS_'])) {
            $Instance = $Instance['___SOURCE_KEYS_'];
        }

        // Get serialization data from array
        $serdata = serialize ( $Instance );

        list ($array_params, $array_elems) = explode ('{', $serdata, 2);
        list ($array_tag, $array_count) = explode (':', $array_params, 3 );
        $serdata = "O:".strlen ($Class).":\"$Class\":$array_count:{".$array_elems;

        $Instance = unserialize ( $serdata );
        return $Instance;
    }

}