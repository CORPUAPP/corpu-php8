<?php

/**
 * Clase de utilidades varias
 * @copyright Consellería do Medio Rural e do Mar
 * @package   app.Lib
 */
class AppUtil
{

    /**
     * Ejecuta la SQL
     * @param string $sql SQL a ejecutar
     * @return Resultado de la SQL
     */
    public static function printStackTrace()
    {
        $trace = debug_backtrace();
        $res = '';
        for ($i = 1; $i < count($trace); $i++) {
            $t = $trace[$i];
            $params = '';
            foreach ($t['args'] as $p) {
                $params .= ', ' . static::printParam($p);
            }
            $clase = !empty($t['class']) ? $t['class'] : '';
            $funcion = !empty($t['function']) ? $t['function'] : '';
            $fichero = !empty($t['file']) ? $t['file'] : '';
            $linea = !empty($t['line']) ? $t['line'] : '';
            $res .= "\n" . $clase . "->" . $funcion . "(" . substr($params, 2) . ")   ---   " . $fichero . " [line:" . $linea . "]";
        }
        return $res . "\n";
    }

    /**
     * Ejecuta la SQL
     * @param string $sql SQL a ejecutar
     * @return Resultado de la SQL
     */
    public static function printParam($p)
    {
        if (is_array($p)) {
            $str = '';
            foreach ($p as $k => $v) {
                $str .= ",$k=>" . static::printParam($v);
            }
            return '[' . substr($str, 1) . ']';
        } elseif (is_object($p)) {
            return '#' . get_class($p);
        } else {
            return "'" . $p . "'";
        }
    }

    /**
     * Convierte un valor a boolean 1/0
     * @param {mixed} $value Valor
     * @return 0 si no existe (isset), 1 si existe
     */
    public static function toBoolean($value)
    {
        return isset($value) ? '1' : '0';
    }

    /**
     * Indica si el índice de un array está establecido
     * @param {array} $array Array
     * @param {mixed} $index Indice del array
     * @return 0 si no existe (isset), 1 si existe
     */
    public static function isIndexSet($array, $index)
    {
        return isset($array[$index]) ? '1' : '0';
    }

    public static function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== false;
    }

    public static function  escapeObject($obj)
    {
        if (is_array($obj)) {
            foreach ($obj as $k => $d) {
                $obj[$k] = static::escapeObject($d);
            }
        } else {
            $obj = htmlentities($obj);
        }
        return $obj;
    }

    static function buidJsonEncode($value)
    {
        if (!is_array($value)) {
            return utf8_encode($value);
        } else {
            foreach ($value as $k => $v) {
                $value[$k] = static::buidJsonEncode($v);
            }
            return $value;
        }
    }


    /************************************
     *                ARRAYS
	/************************************

 	/**
     * Devuelve los valores de una clave dada en un array multidimensional
     * @param type $array Array en el que se va a buscar
     * @param string $key clave a buscar
     * @return type Array con los valores de las claves encontradas
     */
    public static function getValores($array, $key)
    {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key])) {
                $results[] = $array[$key];
            }
            foreach ($array as $subarray) {
                $results = array_merge($results, static::getValores($subarray, $key));
            }
        }
        return $results;
    }

    /**
     * Devuelve coincidencias en un array multidimensional para un valor en una clave dada
     * @param type $array Array en el que se va a buscar
     * @param type $key clave a buscar
     * @param type $value valor a buscar
     * @return type Array con las coincidencias
     */
    public static function search($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, static::search($subarray, $key, $value));
            }
        }

        return $results;
    }


    static function mergeValidationErrors($array1, $array2)
    {
        $merge = array();

        foreach ($array1 as $campo => $errores) {
            if (array_key_exists($campo, $array2)) {
                foreach ($array2[$campo] as $error) {
                    $errores[] = $error;
                }
                unset($array2[$campo]);
            }
            $merge[$campo] = $errores;
        }

        foreach ($array2 as $campo => $errores) {
            $merge[$campo] = $errores;
        }

        return $merge;
    }

    static function reemplazarCaracteresEspeciales($string)
    {
        $unwanted_array = array(
            'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A',
            'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O',
            'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B',
            'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o',
            'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y'
        );

        return strtr($string, $unwanted_array);
    }

    public static function escaparParametros($parametro, $full_control = false)
    {
        $valorRetorno = str_replace("'", "", $parametro);
        if ($full_control) {
            $parametro = str_replace("\"", "", $parametro);
            $parametro = str_replace(" ", "", $parametro);
            $valorRetorno = addslashes($parametro);
            $valorRetorno = strip_tags($valorRetorno);
            $valorRetorno = htmlspecialchars($valorRetorno);
        }

        return $valorRetorno;
    }

}
