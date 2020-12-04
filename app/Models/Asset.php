<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Asset extends Model
{
    use SoftDeletes;

    protected $table = 'asset';
    protected $guarded = [];

    protected $fillable  = [
        'id',
        'name',
        'asset_code',
        'location_id',
        'asset_category_id',
        'description',
        'installed_date',
        'is_delete'
    ];


    public function assetDetail()
    {
        return $this->hasMany('App\Models\AssetDetail', 'asset_id');
    }

    public function assignment()
    {
        return $this->hasOne('App\Models\Assignment', 'asset_id');
    }
    public function assetCategory()
    {
        return $this->belongsTo('App\Models\AssetCategory', 'asset_category_id');
    }
    public function assetLocation()
    {
        return $this->belongsTo('App\Models\Location', 'location_id');
    }
    public function scopeName($query, $request)
    {
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%')
                ->orWhere('asset_code', 'LIKE', '%' . $request->input('name') . '%');
        }
        return $query;
    }

    public function scopeCategory($query, $request)
    {
        if ($request->has('asset_category_id')) {
            $query->where('asset_category_id', $request->input('asset_category_id'));
        }
        return $query;
    }

    public function scopeInstall($query, $request)
    {
        if ($request->has('installed_date')) {
            $query->where('installed_date', $request->input('installed_date'));
        }
        return $query;
    }



    public function scopeByCreateBy($query, $request)
    {
        if ($request->has('is_delete')) {
            $query->where('is_delete', $request->input('is_delete'));
        }
        return $query;
    }
    public function scopeLocation($query, $request)
    {
        if ($request->has('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }
        return $query;
    }
    public function scopeCreateBy($query, $request)
    {
        if ($request->has('create_by')) {
            $query->where('create_by', $request->input('create_by'));
        }
        return $query;
    }
    public function scopeIsDelete($query, $request)
    {
        if ($request->has('is_delete')) {
            $query->where('is_delete', $request->input('is_delete'));
        }
        return $query;
    }
    public function scopeSpec ( $query, $request) {
        if($request->has('spec')){
            $name = $request->input('spec');
            $query->whereHas('assetDetail', function ($q) use ($name) {
                $q->where('value', 'LIKE', '%' . $name . '%');
            });
        }
        return $query;
    }
    public function scopeStatus ( $query, $request) {
        if($request->has('status')){
            if($request->input('status') == 1){
                $query->has('assignment', '!=' ,0);
            }
            else{
                $query->has('assignment', '=' ,0);
            }
        }
        return $query;
    }

    public function scopeOfId($id)
    {
        return DB::table('asset')
            ->join('location', 'location.id', '=', 'asset.location_id')
            ->join('asset_category', 'asset.asset_category_id', '=', 'asset_category.id')
            ->select('asset.*','asset.id as id', 'location.name as location', 'asset_category.name as category')
            ->where('id','=', $id);
    }

}
