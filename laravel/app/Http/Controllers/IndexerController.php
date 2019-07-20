<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\Deps_Peoples;
use App\Deps_Temporal;
use App\User;
use App\Dep;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Config;
use Illuminate\Support\Facades\Validator;


class IndexerController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function structloader() {
        $record_counter =   0;
        $processed_counter  =   0;
        $counter_added      =   0;

        $xmlstring  =   Storage::disk('public')->get('/xml/org-all.xml');
        $olddict    =   simplexml_load_string($xmlstring);
        if(($olddict->count() > 0) &&  ($olddict->children()->count()   >   0)) {
            $items = $olddict->children()->children();
            //первый проход - заполняем данные старого справочника
            foreach ($items as $item) {

                $record_counter++;
                $record = null;
                //создавать ли новую запись
                $nocreate = false;
                if (isset($item->id) && !empty($item->id)) {
                    $record = new Deps_Temporal();
                    $record->source_id  =   $item->id;
                    if (isset($item->parent1->value) && !empty($item->parent1->value)) {
                        $record->parent_id  =   $item->parent1->value;
                    }
                    if (isset($item->name->value) && !empty($item->name->value)) {
                        $record->name  =   $item->name->value;
                    }

                    $record->save();
                }
            }
        }

        print("Загружено $record_counter    подразделений\r\n");
    }

    public function dirloader() {


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}