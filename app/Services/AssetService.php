<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AssetService extends BaseService
{
    public function __construct()
    {
        $this->mapping = collect([
            'AssetName' => 'name',
            'AssetLocation' => 'location_id',
            'AssetCategory' => 'asset_category_id',
            'AssetCode' => 'asset_code',
            'AssetInstalledDate' => 'installed_date',
        ]);
    }

    public function getAllAssets(Request $request)
    {
        //$query = Asset::query();
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        //If user is staff
        if (Auth::user()->is_admin === 0) {
            $id_user = Auth::user()->id;
            $result->whereHas('assignment', function ($query) use ($id_user) {
                return $query->where('user_id', '=', $id_user);
            });
        }
        return $result->paginate($data['limit']);
    }

    private function filterColumn(Request $request)
    {
        $result = Asset::with('assetDetail')
            ->with('assignment')
            ->with('assetCategory')
            ->with('assetLocation')
            ->with('assignment.userAssignment')
            ->category($request)
            ->name($request)
            ->install($request)
            ->location($request)
            ->createby($request)
            ->isdelete($request)
            ->spec($request)
            ->status($request);
        return $result;
    }

    public function getAsset(int $id)
    {
        $asset = new Asset();
        $statusCode = 404;
        $data = null;
        $asset = $asset->with('assignment.assignmentHistory')
            ->with('assetDetail')
            ->with('assignment.userAssignment')
            ->find($id);
        if ($asset) {
            $statusCode = 200;
            $data = $asset;
        }

        return [
            'statusCode' => $statusCode,
            'data' => $data
        ];
    }

    public function getAssetById($id)
    {
        $query = Asset::query();
        return $this->getById($id, $query);
    }

    public function createAsset($request)
    {
        $query = Asset::query();
        return $this->create($query, $request);
    }

    public function updateAsset($id, $data)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            $statusCode = 404;
            $message = "Asset not found!";
        } elseif ($asset->assignment()->exists()) {
            $statusCode = 409;
            $message = "Cannot update because this asset is currently assigned!";
        } else {
            $asset->update($data);
            $statusCode = 200;
            $message = "Update success!";
        }
        return [
            'statusCode' => $statusCode,
            'message' => $message
        ];
    }

    public function deleteByID($id)
    {
        $asset = Asset::find($id);
        if (!$asset) {
            $statusCode = 404;
            $message = "Asset not found!";
        } elseif ($asset->assignment()->exists()) {
            $statusCode = 409;
            $message = "Cannot delete because this asset is currently assigned!";
        } else {
            $asset->delete();
            $statusCode = 200;
            $message = "Delete success!";
        }
        return [
            'statusCode' => $statusCode,
            'message' => $message
        ];
    }

    public function createAssetCode($newInsert)
    {
        $codeFirstCharacter = 'A';
        $query = Asset::query();
        return $this->createCode($query, $codeFirstCharacter, $newInsert);
    }

    public function historyAsset($id)
    {
        $asset = Asset::where('asset.id', $id)
            ->join('location', 'location.id', '=', 'asset.location_id')
            ->join('asset_category', 'asset_category.id', '=', 'asset.asset_category_id')
            ->select(
                'asset.asset_code as asset_code',
                'asset.name as name',
                'location.name as location',
                'asset_category.name as category',
                'asset.create_by as create_by',
                'asset.created_at as create_at'
            )
            ->get();
        if($asset->isEmpty())
        {
            return null;
        }
        return $asset;
    }
}
