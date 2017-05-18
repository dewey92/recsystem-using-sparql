<?php

namespace App\Transformer;

class RequirementTransformer
{
    public static function transform($data) {
        $normal = array_map(function ($datum) {
            return str_replace('_', ' ', $datum);
        }, $data);

        // $transformed['functional_requirement'] = $normal;

        return $normal;
    }
}