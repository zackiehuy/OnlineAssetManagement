<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{

    public function __construct()
    {
        $this->mapping = collect([
            'UserName' => 'name',
            'UserLocation' => 'location_id',
            'UserGender' => 'gender',
            'UserEmail' => 'email',
            'UserId' => 'id',
        ]);
    }

    public function getAllUsers(Request $request)
    {
        $query = $this->filterColumn($request);
        $data = $this->mapping($request, $this->mapping);
        $result = $this->getSort($query, $data);
        return $result->paginate($data['limit']);
    }

    public function getUserById($id)
    {

        $user = User::find($id);
        if (!$user) {
            return [
                $statusCode = 404,
                $message = "This user doesn't exist"
            ];
        }
        $query = User::with('userLocation')->get();
        return $this->getById($id, $query);
    }

    public function deleteUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return [
                $statusCode = 404,
                $message = "This user doesn't exist"
            ];
        }
        $check = Assignment::where('user_id', $id)
            ->where('status_id', 1)
            ->orwhere('status_id', 2)
            ->get();
        if ($check->count() <> 0) {
            foreach ($check as $item) {
                $item->update(['status_id' => 4]);
            }
        }

        $query = User::query();
        try {
            $this->delete($id, $query);
        } catch (\Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        return [
            'status' => 200,
            'message' => "Deleted successfully!"
        ];
    }

    public function historyById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return [
                $statusCode = 404,
                $message = "This user doesn't exist"
            ];
        }
        $assign = Assignment::where('user_id', $id)
            ->join('asset', 'asset.id', '=', 'assignment.asset_id')
            ->select('assignment.*', 'asset.name')
            ->get();
        return $assign;
    }

    private function filterColumn(Request $request)
    {
        $users = User::with('userLocation')
            ->name($request)
            ->staffCode($request)
            ->email($request)
            ->location($request)
            ->dateOfBirth($request)
            ->joinedDate($request)
            ->gender($request)
            ->type($request);
        return $users;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserById($id, $request)
    {
        $query = User::query();
        return $this->update($id, $query, $request);
    }

    public function createUser($request)
    {
        $query = User::query();
        $request['staff_code'] = $this->generateCode();
        $request['password'] = Hash::make($request['password']);
        return $this->create($query, $request);
    }

    public function generateCode()
    {
        $id = User::select('id')->max('id') + 1;
        $result = strval($id);
        $id = 'NT' . str_pad($result, 4, 0, STR_PAD_LEFT);
        return $id;
    }
}
