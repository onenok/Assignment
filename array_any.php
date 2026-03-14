<?php
if (!function_exists('array_any')) {
    function array_any(array $array, callable $callback): bool
    {
        foreach ($array as $value) {
            if ($callback($value)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('get_attr_from_db_json')) {
    function get_attr_from_db_json($json, $attr) {
        $data = json_decode($json, true);
        return $data[$attr] ?? $data;
    }
}

if (!function_exists('get_attr_for_input')) {
    function get_attr_for_input($json, $attr) {
        $data = get_attr_from_db_json($json, $attr);
        return htmlspecialchars($attr.'='.$data, ENT_QUOTES, 'UTF-8');
    }
}
