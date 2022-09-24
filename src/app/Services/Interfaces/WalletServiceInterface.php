<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface WalletServiceInterface
{
    public function transfer(User $payer, User $payee, $value);
}
