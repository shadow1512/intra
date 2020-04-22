<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_Config extends Model
{
    //
    protected $table = 'menu_cofig';

    public static function getLevel($id, $items) {
        if(is_null($id)) {
            $level_items    =   Menu_Config::where('hidden',   '=',    0)->whereNull('parent_id')->orderBy('sort',   'desc')->get();
            $items['root']  =   $level_items;
        }
        else {
            $level_items    =   Menu_Config::where('hidden',   '=',    0)->where('parent_id',  '=',    $id)->orderBy('sort',   'desc')->get();
            $items[$id]  =   $level_items;
        }

        foreach($level_items as $item) {
            $items= self::getLevel($item->id, $items);
        }

        return $items;
    }
}
