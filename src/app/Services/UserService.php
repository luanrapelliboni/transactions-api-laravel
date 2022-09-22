<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }

    public function save(UserRequest $userRequest, $id = null)
    {
        return $this->userRepository->save($userRequest, $id);
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
}
