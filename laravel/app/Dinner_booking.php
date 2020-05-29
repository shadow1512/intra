<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use cijic\phpMorphy\Facade\Morphy;

class Dinner_booking extends Model
{
    //
    use SoftDeletes;
    //
    protected $table = 'dinner_booking';

    public static function getRecordByUserAndDate($user_id,    $date_record) {
        return Dinner_booking::whereDate('date_created',    '=',    $date_record)->where('user_id', '=',    $user_id)->where('banket',  '=',    0)->first();
    }
    public static function getRecordBanketByUserAndDate($user_id,    $date_record) {
        return Dinner_booking::whereDate('date_created',    '=',    $date_record)->where('user_id', '=',    $user_id)->where('banket',  '=',    1)->first();
    }

    public static function getWordInCorrectForm($number,    $word) {
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
            if(in_array($number,    array("5",  "6",    "7",    "8",    "9"))) {
                return $forms[6];
            }
        }
        else {
            if(mb_substr($number,   $length -   2,  1)  ==  1) {
                return $forms[6];
            }
            else {
                if(in_array((mb_substr($number,   $length   -   1,  1)),    array("5",  "6",    "7",    "8",    "9",   "0"))) {
                    return $forms[6];
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
}
