<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search_logs extends Model
{
    //
    protected $fillable = [
        'term', 'user_id', 'total_res', 'section_results'
    ];
    protected $table = 'search_logs';
}
