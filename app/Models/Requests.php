<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requests extends Model
{
    use SoftDeletes;

    protected $table = 'request';
    protected $fillable = [
        'user_id',
        'assignment_id',
        'asset_category_id',
        'requested_date',
        'response_date',
        'created_by',
        'status'
    ];
    public $timestamps = false;

    public function scopeUserId($query, $request)
    {
        if ($request->has('user_id')) {
            $name = $request->input('user_id');
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }
        return $query;
    }

    public function scopeAssignment($query, $request)
    {
        if ($request->has('assignment_id')) {
            $query->where('assignment_id', $request->input('assignment_id'));
        }
        return $query;
    }
    public function scopeAssetCategory($query, $request)
    {
        if ($request->has('asset_category_id')) {
            $name = $request->input('asset_category_id');
            $query->whereHas('category', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }
        return $query;
    }
    public function scopeRequestedDate($query, $request)
    {
        if ($request->has('requested_date')) {
            $query->where('requested_date', $request->input('requested_date'));
        }
        return $query;
    }
    public function scopeResponseDate($query, $request)
    {
        if ($request->has('response_date')) {
            $query->where('response_date', $request->input('response_date'));
        }
        return $query;
    }
    public function scopeStatus($query, $request)
    {
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        return $query;
    }
    public function scopeCreatedBy($query, $request)
    {
        if ($request->has('created_by')) {
            $query->where('created_by', $request->input('created_by'));
        }
        return $query;
    }
    public function scopeStaffReturnRequest($query, $request)
    {
        if ($request->has('return_request')) {
            $query->with('user')->whereColumn('user_id', 'created_by')
                  ->where('assignment_id', '!=', null);
        }
        return $query;
    }
    public function scopeAdminReturnRequest($query, $request)
    {
        if ($request->has('admin_request')) {
            $query->with('user')
                    ->whereColumn('user_id', '!=', 'created_by')
                    ->where('assignment_id', '!=', null);
        }
        return $query;
    }
    public function scopeStaffRequireRequest($query, $request)
    {
        if ($request->has('require_request')) {
            $query->with('user')->with('category')
                    ->whereColumn('user_id', '=', 'created_by')
                   ->where('asset_category_id', '!=', null);
        }
        return $query;
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function assignment()
    {
        return $this->belongsTo('App\Models\Assignment', 'assignment_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\AssetCategory', 'asset_category_id');
    }
}
