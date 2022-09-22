<?php

namespace App\Services\Interfaces;

use App\Http\Requests\UserRequest;

interface UserServiceInterface
{
    public function getAll();
    public function getById($id);
    public function save(UserRequest $userRequest, $id = null);
    public function delete($id);
}
