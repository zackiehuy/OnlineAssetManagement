<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    protected $table = 'assignment';
    protected $dateFormat = 'Y-m-d';
    protected $fillable  = [
        'user_id',
        'asset_id',
        'started_date',
        'end_date',
        'status_id',
        'create_by'
    ];
    protected $guarded = [];
    public function assignmentHistory()
    {
        return $this->hasMany('App\Models\AssignmentHistory', 'assignment_id');
    }
    public function request()
    {
        return $this->hasMany('App\Models\Request', 'assignment_id');
    }
    public function asset()
    {
        return $this->belongsTo('App\Models\Asset', 'asset_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function assignBy()
    {
        $list = $this->belongsTo('App\Models\User', 'create_by')
            ;
        //$name = implode('',$list);
        return $list;
    }
    public function scopeStartDate($query, $request)
    {
        if ($request->has('StartedDate')) {
            $query->where('started_date', 'LIKE', '%' . $request->StartedDate . '%');
        }
        return $query;
    }
    public function scopeEndDate($query, $request)
    {
        if ($request->has('EndDate')) {
            $query->where('end_date', 'LIKE', '%' . $request->EndDate . '%');
        }
        return $query;
    }
    public function scopeStatus($query, $request)
    {
        if ($request->has('Status')) {
            $query->where('status_id', '=',$request->Status);
        }
        return $query;
    }
    public function scopeAssignTo($query, $request)
    {
        if ($request->has('AssignTo')) {
            $query->where('users.name', 'LIKE', '%' . $request->AssignTo . '%');
        }
        return $query;
    }
    public function scopeAssignBy($query, $request)
    {
        if ($request->has('AssignBy')) {
            $query->where('create_by', 'LIKE', '%' . $request->AssignBy . '%');
        }
        return $query;
    }
}
