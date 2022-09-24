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
     *
     * @OA\Post(
     *     path="/transfer",
     *     operationId="transferBetwenWallet",
     *     tags={"Wallet"},
     *     summary="Transfer value between wallets",
     *     description="Transfer value between wallets",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WalletTransferRequest")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Wallet updated successfull"
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
     *                 example="null"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error updating wallet",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error updating wallet."
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
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="No payer with ID 1 | No payee with ID 1"
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
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessible entity",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Wallet not found | insuficient funds | payer cannot be a seller"
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="boolean",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="integer",
     *                  example="422"
     *              ),
     *          )
     *      ),
     * )
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
                return $this->success("Wallet updated successfull", null, 200);
            else
                return $this->error('Error updating wallet', 500);
        } catch (InsufficientFundsException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (InvalidPayerException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (WalletNotFoundException $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
