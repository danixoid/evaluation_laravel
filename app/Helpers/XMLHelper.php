<?php

if (!function_exists('XML2Array')) {

    function XML2Array(SimpleXMLElement $parent)
    {
        $array = array();

        foreach ($parent as $name => $element) {
            ($node = & $array[$name])
            && (1 === count($node) ? $node = array($node) : 1)
            && $node = & $node[];

            $node = $element->count() ? XML2Array($element) : trim($element);
        }

        return $array;
    }
}
