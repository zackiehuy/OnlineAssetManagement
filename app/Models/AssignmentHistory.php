<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentHistory extends Model
{
    use SoftDeletes;

    protected $table = 'assignment_history';
    protected $primaryKey = 'id';
    protected $dateFormat = 'd-m-Y';
}
