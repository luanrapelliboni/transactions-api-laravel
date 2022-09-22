<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Interfaces\UserRepositoryInterface;
use DB;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        try {
            return User::with('wallet')->get();
        } catch(\Exception $e) {
            \Log::error('Error' . $e->getMessage() . ' - ' . $e->getCode());
        }
    }

    public function getById($id)
    {
        try {
            return User::with('wallet')->find($id);
        } catch(\Exception $e) {
            \Log::error('Error' . $e->getMessage() . ' - ' . $e->getCode());
        }
    }

    public function save(UserRequest $userRequest, $id = null)
    {
        DB::beginTransaction();
        try {
            $user = $id ? User::find($id) : new User;

            $user->name = $userRequest->name;
            $user->document = preg_replace('/\.|-|\//', '', strtolower($userRequest->document));
            $user->email = preg_replace('/\s+/', '', strtolower($userRequest->email));
            $user->password = \Hash::make($userRequest->password);
            $user->type = $userRequest->type;
            $user->save();

            $balance =  $userRequest->balance ? : 0;

            if (!$id) {
                $wallet = new Wallet(['balance' => $balance]);
                $user->wallet()->save($wallet);
            } else {
                $user->wallet()->update(['balance' => $balance]);
            }
            $user->save();

            DB::commit();

            return $user;

        } catch(\Exception $e) {
            DB::rollBack();
            \Log::error('Error' . $e->getMessage() . ' - ' . $e->getCode());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->delete();
            DB::commit();
            return $user;
        } catch(\Exception $e) {
            DB::rollBack();
            \Log::error('Error' . $e->getMessage() . ' - ' . $e->getCode());
        }
    }

    public function updateBalance($id, $balance)
    {
        DB::beginTransaction();
        try {
            $user = User::with('wallet')->find($id);
            $user->wallet()->update(['balance' => $balance]);
            $user->save();

            DB::commit();

            return $user;
        } catch(\Exception $e) {
            DB::rollBack();
            \Log::error('Error' . $e->getMessage() . ' - ' . $e->getCode());
        }
    }
}
