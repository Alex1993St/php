<?php
require 'env.php';

class Curl
{
    public function getData($number, $size)
    {
        $url = URL . '?api_key=' . KEY .'[number]=' . $number . '&page[size]=' . $size;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        return curl_exec($ch);
    }
}

$number = isset($argv[1]) ? $argv[1] : 1;
$size = isset($argv[2]) ? $argv[2] : 30;

$info = new Curl();
$info->getData($number, $size);
