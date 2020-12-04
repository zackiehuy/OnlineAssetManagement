<?php

namespace App\Services;

use App\Models\Requests;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestService extends BaseService
{
    public function __construct()
    {
        $this->mapping = collect([
            'Assignment' => 'assignment_id',
            'Status' => 'status',
            'User' => 'user_id',
            'RequestedDate' => 'requested_date',
            'ResponseDate' => 'response_date',
            'CreatedBy' => 'created_by'

        ]);
    }
    public function getAllRequests(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        if (User::isAdmin()) {
            return $result->paginate($data['limit']);
        } elseif (User::isStaff()) {
            $id_user = Auth::user()->id;
            $result = $result->where(['user_id' => $id_user, 'created_by' => $id_user]);
            return $result->paginate($data['limit']);
        }
    }


    public function getRequestById($id)
    {
        $query = Requests::query();
        $request = Requests::where('id', $id)->first();
        if (empty($request)) {
            return "This request does not exist";
        } else {
            if (User::isAdmin()) {
                return $this->getById($id, $query);
            } elseif (User::isStaff()) {
                if ($request->user_id == Auth::user()->id) {
                    return $this->getById($id, $query);
                } else {
                    return "You are not allowed";
                }
            }
        }
    }
    private function filterColumn(Request $request)
    {
        $requests = Requests::userId($request)
            ->assignment($request)
            ->assetCategory($request)
            ->requestedDate($request)
            ->responseDate($request)
            ->createdBy($request)
            ->status($request)
            ->staffReturnRequest($request)
            ->adminReturnRequest($request)
            ->staffRequireRequest($request);
        return $requests;
    }
//    public function getRequestById($id)
//    {
//        $query = Requests::query();
//        return $this->getById($id, $query);
//    }
    public function createRequest($request)
    {
        $request['requested_date'] = Carbon::now();
        $request['created_by'] = Auth::user()->id;
        $query = Requests::query();
        return $this->create($query, $request);
    }
    public function updateRequestById($id, $request)
    {
        $query = Requests::query();
        $request['response_date'] = Carbon::now();
        return $this->update($id, $query, $request);
    }
}
