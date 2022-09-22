<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InsufficientFundsException;
use App\Exceptions\InvalidPayerException;
use App\Exceptions\WalletNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\WalletTransferRequest;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Interfaces\WalletServiceInterface;
use App\Traits\ResponseAPI;

class WalletController extends Controller
{
    use ResponseAPI;

    protected $userService;
    protected $walletService;

    public function __construct(UserServiceInterface $userService, WalletServiceInterface $walletService)
    {
        $this->userService = $userService;
        $this->walletService = $walletService;
    }

    /**
     * Transfer values between wallets.
     *
     * @param  App\Http\Requests\WalletTransferRequest $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(WalletTransferRequest $request)
    {
        $payer = $this->userService->getById($request->payer);
        $payee = $this->userService->getById($request->payee);

        if (!$payer)
            return $this->error("No payer with ID $request->payer", 404);

        if (!$payee)
            return $this->error("No payee with ID $request->payee", 404);

        try {
            if ($this->walletService->transfer($payer, $payee, $request->value))
                return $this->success("Wallet updated successfull", 200);
            else
                return $this->error('An error occurred', 500);
        } catch (InsufficientFundsException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (InvalidPayerException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (WalletNotFoundException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
