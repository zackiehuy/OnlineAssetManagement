<?php


namespace App\Services;


use App\Models\AssetDetail;

class AssetDetailService extends BaseService
{
    public function getAssetDetailById($id)
    {
        $query = AssetDetail::query();
        return $this->getById($id ,$query);
    }

    public function createAssetDetail($request)
    {
        $query = AssetDetail::query();
        return $this->create($query,$request);
    }

    public function updateAssetDetailById($id,$request)
    {
        $query = AssetDetail::query();
        return $this->update($id,$query,$request);
    }

    public function listCreateDetailAsset($request ,$newInsert)
    {
        $list = [];
        foreach ($request->all() as $keys => $value) {
            if (strpos($keys, 'specification') !== false) {
                $specification_id = str_replace('specification', "", $keys);
                $assetDetail = [
                    "specification_id" => $specification_id,
                    "asset_id" => $newInsert->id,
                    "value" => $value
                ];
                array_push($list, $this->createAssetDetail($assetDetail));
            }
        }
        return $list;
    }

    public function listUpdateDetailAsset($request ,$idAsset)
    {
        $list = [];
        foreach ($request->all() as $keys => $value)
        {
            if (strpos($keys, 'specification') !== false) {
                $id = str_replace('specification', "", $keys);
                if(checkId($id, $idAsset))
                {
                    return "You don't have this Asset Detail ID";
                }
                $value = [
                    "value" => $value
                ];
                array_push($list, $this->updateAssetDetailById($id,$value));
            }
        }
        return $list;
    }

    public function checkId($idAssetDetail, $idAsset)
    {
        $listSpecificaiton = $this->getAssetDetailById($idAsset);
        foreach($listSpecificaiton as $rows)
        {
            if($rows == $idAssetDetail)
            {
                return true;
            }
            return false;
        }
    }
}
