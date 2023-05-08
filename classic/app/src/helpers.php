<?php

namespace classic\app\src;

class Helpers
{
    public static function getHttpStatusOk(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
        curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode == 200) {
            return true;
        }
        return false;
    }

    public static function setTitle(string $title)
    {
        $matches = array();
        $formatedTitle = $title;
        $formatedTitle = str_replace(" - ", " ", $formatedTitle);
        $formatedTitle = str_replace(":", "", $formatedTitle);
        $formatedTitle = str_replace("!", "", $formatedTitle);
        $formatedTitle = preg_replace('/(\s\()([0-9a-zA-Z\,\s)(.]+)/', "", $formatedTitle);
        preg_match('/, ([0-9a-zA-Z]{1,3})/', $formatedTitle, $matches);
        $formatedTitle = preg_replace('/, ([A-Za-z])([A-Za-z])([A-Za-z])?/', "", $formatedTitle);
        if(isset($matches[1])) {
            $formatedTitle = $matches[1] . " " . $formatedTitle;
        }
        return trim($formatedTitle);
    }

}
