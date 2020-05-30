<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2020
 * Time: 2:56
 */
namespace App;
use cijic\phpMorphy\Facade\Morphy;

function getWordInCorrectForm($number,    $word) {
    $word   =   mb_strtoupper($word,    "UTF-8");
    $length =   mb_strlen($number,  "UTF-8");
    $forms  =   Morphy::getAllForms($word);
    if($length  ==  1) {
        if($number  ==  "1") {
            return $forms[0];
        }
        if(in_array($number,    array("2",  "3",    "4"))) {
            return $forms[1];
        }
        if(in_array($number,    array("5",  "6",    "7",    "8",    "9",    "0"))) {
            if(mb_substr($word,   mb_strlen($word,  "UTF-8")   -   1,  1)   ==  "О") {
                return $forms[5];
            }
            else {
                return $forms[6];
            }

        }
    }
    else {
        if(mb_substr($number,   $length -   2,  1)  ==  1) {
            if(mb_substr($word,   mb_strlen($word,  "UTF-8")   -   1,  1)   ==  "О") {
                return $forms[5];
            }
            else {
                return $forms[6];
            }
        }
        else {
            if(in_array((mb_substr($number,   $length   -   1,  1)),    array("5",  "6",    "7",    "8",    "9",   "0"))) {
                if(mb_substr($word,   mb_strlen($word,  "UTF-8")   -   1,  1)   ==  "О") {
                    return $forms[5];
                }
                else {
                    return $forms[6];
                }
            }
            if(in_array((mb_substr($number,   $length   -   1,  1)),    array("1"))) {
                return $forms[0];
            }
            if(in_array((mb_substr($number,   $length   -   1,  1)),    array("2", "3",    "4"))) {
                return $forms[1];
            }
        }
    }
}

function allForms($word) {
    $word   =   mb_strtoupper($word,    "UTF-8");
    var_dump(Morphy::getAllForms($word));
}