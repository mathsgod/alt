<?php
if (!function_exists("outp")) {
    function outp($o)
    {
        echo "<pre>";
        print_r($o);
        echo "</pre>";
    }
}

function nf($number)
{
    return number_format($number, 2);
}

function tick($value)
{
    if ($value) {
        return "<i class='fa fa-check'></i>";
        return "&#x2713;";
    }
}

function from($class)
{
    return $class::__from();
}

function startsWith($haystack, $needle)
{
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function is_json($string)
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function array_group_by($arr, $key)
{
    if (!is_array($arr)) {
        trigger_error('array_group_by(): The first argument should be an array', E_USER_ERROR);
    }

    // Load the new array, splitting by the target key
    $grouped = [];
    foreach ($arr as $value) {
        if ($key instanceof Closure) {
            $grouped[$key($value)][] = $value;
        } else {
            $grouped[$value[$key]][] = $value;
        }
    }

    // Recursively build a nested grouping if more parameters are supplied
    // Each grouped array value is grouped according to the next sequential key
    if (func_num_args() > 2) {
        $args = func_get_args();

        foreach ($grouped as $key => $value) {
            $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
            $grouped[$key] = call_user_func_array('array_group_by', $parms);
        }
    }

    return $grouped;
}
