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
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsers",
     *      tags={"Users"},
     *      summary="Get list of users",
     *      description="Returns list of users",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="All users"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                 property="results",
     *                 type="array",@OA\Items(ref="#/components/schemas/User"),
     *              ),
     *          )
     *      )
     * )
     */
    public function index()
    {
        return $this->success("All users", $this->userService->getAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/users",
     *     operationId="storeUser",
     *     tags={"Users"},
     *     summary="Store new user",
     *     description="Returns user",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserReqyest")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                 property="results",
     *                 type="object",
     *                 ref="#/components/schemas/User",
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error store user",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error creating user."
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="500"
     *              )
     *          )
     *      ),
     * )
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUserById",
     *      tags={"Users"},
     *      summary="Get user by id",
     *      description="Returns user specified",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User detail"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                 property="results",
     *                 type="object",
     *                 ref="#/components/schemas/User",
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="No user with ID 1"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="404"
     *              )
     *          )
     *      ),
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getById($id);

        if (!$user)
            return $this->error("No user with ID $id", 404);

        return $this->success("User detail", $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UserRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Put(
     *      path="/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update user by id",
     *      description="Update user specified",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserReqyest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User updated"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                 property="results",
     *                 type="object",
     *                 ref="#/components/schemas/User",
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="No user with ID 1"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="404"
     *              )
     *          ),
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error update user",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error updating user."
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="500"
     *              )
     *          )
     *      ),
     * )
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *      path="/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Delete user by id",
     *      description="Delete user specified",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User deleted"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="200"
     *              ),
     *              @OA\Property(
     *                 property="results",
     *                 type="object",
     *                 ref="#/components/schemas/User",
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="No user with ID 1"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="404"
     *              )
     *          ),
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="Error delete user",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error deleting user."
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="500"
     *              )
     *          )
     *      ),
     * )
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
