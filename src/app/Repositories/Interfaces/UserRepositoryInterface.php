<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\UserRequest;

interface UserRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function save(UserRequest $userRequest, $id = null);
    public function delete($id);
    public function updateBalance($id, $balance);
}
