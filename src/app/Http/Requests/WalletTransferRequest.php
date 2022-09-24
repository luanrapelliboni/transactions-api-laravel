<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *  schema="WalletTransferRequest",
 *  title="WalletTransferRequest schema",
 * 	@OA\Property(
 * 		property="payer",
 * 		type="integer",
 *      example="1"
 * 	),
 * 	@OA\Property(
 * 		property="payee",
 * 		type="integer",
 *      example="2"
 * 	),
 * 	@OA\Property(
 * 		property="value",
 * 		type="string",
 *      example="45.90"
 * 	),
 * )
 */
class WalletTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payer' => 'required',
            'payee' => 'required',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }
}
