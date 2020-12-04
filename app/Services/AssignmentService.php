<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AssignmentService extends BaseService
{
    public function __construct()
    {
        $this->mapping = collect([
            'User' => 'user_id',
            'Status' => 'status_id',
            'Asset' => 'asset_id',
            'StartedDate' => 'started_date',
            'EndDate' => 'end_date',
            'CreateBy' => 'create_by'
        ]);
    }

    public function getAllAssignments(Request $request)
    {
        if (User::isAdmin()) {
            $query = $this->filterColumn($request);
            $data = $this->mapping($request, $this->mapping);
            $result = $this->getSort($query, $data);
            return $result->paginate($data['limit']);
        } else {
            return ["message:" => "You don't have permission"];
        }
    }
    public function createAssignment($request)
    {

        if (User::isAdmin()) {
            $request['started_date'] = Carbon::now();
            $request['create_by'] = Auth::user()->id;
            $query = Assignment::query();
            return $this->create($query, $request);
        } else {
            return ["message:" => "You don't have permission"];
        }
    }
    private function filterColumn(Request $request)
    {
        $requests = Assignment::userId($request)
            ->assetId($request)
            ->startedDate($request)
            ->endDate($request)
            ->createBy($request)
            ->status($request);
        return $requests;
    }
    public function updateAssignmentById($id, $request)
    {
        $query = Assignment::query();
        return $this->update($id, $query, $request);
    }
	public function getAssignmentByAssetId($id, Request $request)
    {
        $query = null;
        if (User::isAdmin()) {
            $query = Assignment::with('assignBy')
            ->where('assignment.asset_id', $id)
            ->join('users', 'users.id', '=', 'assignment.user_id')
            ->join('status', 'status.id', '=', 'assignment.status_id')
                ->assignTo($request)
                ->assignBy($request)
                ->startDate($request)
                ->endDate($request)
                ->status($request)
                ->select(
                    'assignment.id as id',
                    'users.name as assign_to',
                    'assignment.started_date as started_date',
                    'assignment.end_date as end_date',
                    'status.name as status',
                    'assignment.create_by',
                );
        }
        elseif (User::isStaff()) {
            $query =  Assignment::with('assignBy')->
            where(['assignment.asset_id' => $id, 'assignment.user_id' => Auth::user()->id])
                ->join('users', 'users.id', '=', 'assignment.user_id')
                ->join('status', 'status.id', '=', 'assignment.status_id')
                ->assignTo($request)
                ->assignBy($request)
                ->startDate($request)
                ->endDate($request)
                ->status($request)
                ->select(
                    'assignment.id as id',
                    'users.name as assign_to',
                    'assignment.started_date as started_date',
                    'assignment.end_date as end_date',
                    'status.name as status',
                    'assignment.create_by as create_by',
                );
        }
        $end = $query;
        $start = $query;
        $end_date = $end->distinct('end_date')->get()->pluck('end_date');
        $started_date = $start->distinct('started_date')->get()->pluck('started_date');
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        $result = $result->paginate($data['limit']);
        $startedDate = [];
        foreach($started_date as $date)
        {
            $time = strtotime($date);
            array_push($startedDate,date('Y-m',$time));
        }
        $startedDate = array_unique($startedDate);
        $endDate = [];
        foreach($end_date as $date)
        {
            if($date != null)
            {
                $time = strtotime($date);
                array_push($endDate,date('Y-m',$time));
            }
        }
        $endDate = array_unique($endDate);
        if (!$result->isEmpty()) {
            $assignment = [
                "assignment" => $result,
                "started_date" => $startedDate,
                "end_date" => $endDate,
            ];

            return $assignment;
        } else {
            if (User::isAdmin()){
                return null;
            }
            elseif (User::isStaff()) {
                return 1;
            }
        }
    }
}
