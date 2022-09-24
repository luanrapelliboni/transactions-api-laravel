<?php

namespace App\Services;

use App\Exceptions\InsufficientFundsException;
use App\Exceptions\InvalidPayerException;
use App\Exceptions\WalletNotFoundException;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\WalletServiceInterface;
use App\Traits\ConsumesExternalServices;

class WalletService implements WalletServiceInterface
{
    use ConsumesExternalServices;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Transfer values between wallets
     *
     * @param \App\Models\User $payer
     * @param \App\Models\User $payee
     * @param decimal $value
     * @return boolean
     *
     * @throws InsufficientFundsException
     * @throws InvalidPayerException
     * @throws WalletNotFoundException
     */
    public function transfer(User $payer, User $payee, $value)
    {
        if ($payer->type == "SELLER")
            throw new InvalidPayerException("Payer cannot be a seller.");

        $payerWallet = $payer->wallet()->first();
        $payeeWallet = $payee->wallet()->first();

        if (!$payerWallet)
            throw new WalletNotFoundException('Wallet not found to this payer. (User ID: ' . $payer->id . ')');

        if (!$payeeWallet)
            throw new WalletNotFoundException('Wallet not found to this payee. (User ID: ' . $payee->id . ')');

        if ($payerWallet->balance < $value)
            throw new InsufficientFundsException("Wallet payer insuficient funds.");

        if ($this->authorize()) {
            $payerBalance = $payerWallet->balance;
            $payeeBalance = $payeeWallet->balance;

            if ($this->userRepository->updateBalance($payer->id, $payerBalance - $value) &&
                $this->userRepository->updateBalance($payee->id, $payeeBalance + $value)) {
                if ($this->notify())
                    return true;
            }
        }
        return false;
    }

    private function authorize() {
        $response = $this->makeRequest(env('AUTHORIZE_URL'), 'GET', '/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        $response = json_decode($response);
        if ($response && $response->message == "Autorizado") {
            return true;
        }
        return false;
    }

    private function notify() {
        $response = $this->makeRequest(env('NOTIFY_URL'), 'GET', '/notify');
        $response = json_decode($response);
        if ($response->message && $response->message == "Success") {
            return true;
        }
        return false;
    }
}
