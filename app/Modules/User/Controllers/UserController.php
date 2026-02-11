<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\UpdatePasswordRequest;
use App\Modules\User\Requests\UserRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $data = $this->service->index();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $data = $this->service->create($request->all());
            
            return response()->json($data, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UserRequest $request)
    {
        try {
            return response()->json($this->service->updateProfile(auth()->user(), $request->validated()));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        return response()->json(
            $this->service->updatePassword(auth()->user(), $request->validated())
        );
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->service->delete($id);
            if (!$deleted) {
                return response()->json(['error' => 'Not Found'], 404);
            }
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
