<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_Config extends Model
{
    //
    protected $table = 'menu_config';

    public static function getLevel($id, $items) {
        if(is_null($id)) {
            $level_items    =   Menu_Config::where('visible',   '=',    1)->whereNull('parent_id')->orderBy('sort',   'desc')->get();
            $items['root']  =   $level_items;
        }
        else {
            $level_items    =   Menu_Config::where('visible',   '=',    1)->where('parent_id',  '=',    $id)->orderBy('sort',   'desc')->get();
            if(count($level_items)) {
                $items[$id]  =   $level_items;
            }
        }

        foreach($level_items as $item) {
            $items= self::getLevel($item->id, $items);
        }

        return $items;
    }
}
