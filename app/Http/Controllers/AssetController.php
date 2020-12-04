<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Services\AssetCategoryService;
use App\Services\AssetDetailService;
use App\Services\AssetService;
use App\Services\CategorySpecificationService;
use App\Services\SpecificationService;
use Illuminate\Http\Request;
use App\Services\LocationService;
use Illuminate\Http\Response;

class AssetController extends Controller
{
    protected $assetService;
    protected $locationService;
    protected $assetCategoryService;
    protected $optionSpecificationService;
    protected $requireSpecificationService;
    protected $assetDetailService;

    public function __construct(
        LocationService $locationService,
        AssetCategoryService $assetCategoryService,
        SpecificationService $optionSpecificationService,
        CategorySpecificationService $requireSpecificationService,
        AssetService $assetService,
        AssetDetailService $assetDetailService
    ) {
        $this->locationService = $locationService;
        $this->assetCategoryService = $assetCategoryService;
        $this->optionSpecificationService = $optionSpecificationService;
        $this->requireSpecificationService = $requireSpecificationService;
        $this->assetService = $assetService;
        $this->assetDetailService = $assetDetailService;
    }

    public function index(Request $request)
    {
        $data = $this->assetService->getAllAssets($request);
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listLocation = $this->locationService->getAllLocation();
        $listCategory = $this->assetCategoryService->getAllCategory();
        $listOptionSpecification = $this->optionSpecificationService->getOptionSpecification();
        $listRequireSpecification = $this->requireSpecificationService->getRequireSpecification();
        $rSpecification = [];
        for ($i = 0; $i < count($listCategory); $i++) {
            array_push($rSpecification, $listCategory[$i]->id = []);
        }
        array_push($rSpecification[1], '1');
        for ($i = 0; $i < count($listRequireSpecification); $i++) {
            array_push($rSpecification[$listRequireSpecification[$i]->asset_category_id - 1], $listRequireSpecification[$i]);
        }
        $list = [
            "category" => $listCategory,
            "location" => $listLocation,
            "RequireSpecification" => $rSpecification,
            "OptionSpecification" => $listOptionSpecification,
        ];
        return $list;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AssetRequest $request)
    {
        $request->validated();
        $asset = [
            "name" => $request->input('name'),
            "location_id" => $request->input('location_id'),
            "asset_category_id" => $request->input('asset_category_id'),
            "installed_date" => date("Y/m/d")
        ];
        $newAsset = $this->assetService->createAsset($asset);
        $this->assetService->createAssetCode($newAsset);
        $this->assetDetailService->listCreateDetailAsset($request, $newAsset);
        $updateAsset = $this->assetService->getAssetById($newAsset->id);
        return $updateAsset;
    }

    /**
     * Display the specified resource.
     *
     * @param Asset $asset
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $data = $this->assetService->getAsset($id);
        return response()->json($data, $data['statusCode']);
    }

    /**
     * Show the form for editing the specified resource.
     *
    <<<<<<< HEAD
     * @param Request $request
    =======
     * @return \Illuminate\Http\Response
     * *@param  int $id
     */
    public function edit($id)
    {
        $asset = $this->assetService->getAssetById($id);
        if ($asset === null) {
            return "Don't have this id";
        }
        $listLocation = $this->locationService->getAllLocation();
        $listCategory = $this->assetCategoryService->getAllCategory();
        $assetDetail = $this->assetDetailService->getAssetDetailById($id);
        $list = [
            "Asset" => $asset,
            "Asset Detail" => $assetDetail,
            "Location" => $listLocation,
            "Category" => $listCategory
        ];
        return $list;
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\AssetRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AssetRequest $request, int $id)
    {
        $data = $this->assetService->updateAsset($id, $request->input());
        return response()->json($data, $data['statusCode']);
        $Asset = [
            "name" => $request->input('name'),
            "location_id" => $request->input('location_id'),
            "asset_category_id" => $request->input('asset_category_id'),
            "installed_date" => date("Y/m/d")
        ];
        $request->Validate();

        $list = $this->assetService->updateAsset($id, $Asset);
        $this->assetDetailService->listUpdateDetailAsset($request, $id);
        return response()->json($list);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $data = $this->assetService->deleteByID($id);
        return response()->json($data, $data['statusCode']);
    }
    public function history($id)
    {
        $asset = $this->assetService->historyAsset($id);
        if ($asset === null)
        {
            return 0;
        }
        $asset[0]['status'] = $this->statusService->getAllStatus();
        $asset[0]['assignmentAsset'] = $this->assignmentService->getAssignmentByAssetId($id,$request);
        return response()->json($asset[0]);
    }
}
