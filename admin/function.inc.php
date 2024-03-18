<?php

if (!function_exists('pr')) {
    function pr($arr)
    {
        echo '<pre>';
        print_r($arr);
    }
}

if (!function_exists('prx')) {
    function prx($arr)
    {
        echo '<pre>';
        print_r($arr);
        die();
    }
}

if (!function_exists('get_safe_value')) {
    function get_safe_value($con, $str)
    {
        if ($str != '') {
            return mysqli_real_escape_string($con, $str);
        }
    }
}

if (!function_exists('getCategoryName')) {
    function getCategoryName($con, $categoryId)
    {
        $categoryQuery = "SELECT name FROM categories WHERE id = $categoryId";
        $categoryResult = mysqli_query($con, $categoryQuery);

        if ($categoryResult) {
            $category = mysqli_fetch_assoc($categoryResult);
            return $category['name'];
        } else {
            return "Unknown Category";
        }
    }
}
?>