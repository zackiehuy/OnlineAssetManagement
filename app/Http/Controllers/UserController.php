<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data = $this->userService->getAllUsers($request);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = $this->userService->deleteUserById($id);
        return response()->json($data);
    }

    public function show(int $id)
    {
        $data = $this->userService->getUserById($id);
        return response()->json($data);
    }
    public function history($id)
    {
        $data = $this->userService->historyById($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $data = $this->userService->updateUserById($id, $request->input());
        return response()->json($data);
    }
    /*
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        $data = $this->userService->createUser($request->input());
        return response()->json($data['message']);
    }
}
