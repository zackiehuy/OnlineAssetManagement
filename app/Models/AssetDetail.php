<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_id',
        'specification_id',
        'value'
    ];

    protected $table = 'asset_detail';
    protected $primaryKey = 'id';
}
