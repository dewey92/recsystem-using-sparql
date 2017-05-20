<?php

namespace App\Transformer;

class Serializer
{
    public static function serialize($data) {
        return array_map(function ($datum) {
            return str_replace(' ', '_', $datum);
        }, $data);
    }

    public static function deserialize($data) {
        return array_map(function ($datum) {
            return str_replace('_', ' ', $datum);
        }, $data);
    }
}