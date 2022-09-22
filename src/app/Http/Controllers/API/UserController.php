<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\Interfaces\UserServiceInterface;
use App\Traits\ResponseAPI;

class UserController extends Controller
{
    use ResponseAPI;

    protected $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success("All Users", $this->userService->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $saved = $this->userService->save($request);
        if ($saved)
            return $this->success("User created", $saved, 201);

        return $this->error('Error creating user.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userService->getById($id);

        if (!$user)
            return $this->error("No user with ID $id", 404);

        return $this->success("User Detail", $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->getById($id);

        if (!$user)
            return $this->error("No user with ID $id", 404);

        $saved = $this->userService->save($request, $id);

        if ($saved)
            return $this->success("User updated", $saved, 200);

        return $this->error('Error updating user.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userService->getById($id);

        if (!$user)
            return $this->error("No user with ID $id", 404);

        $deleted = $this->userService->delete($id);

        if ($deleted)
            return $this->success("User deleted", $deleted);

        return $this->error('Error deleting user.');

    }
}
