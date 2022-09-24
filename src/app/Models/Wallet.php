<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="Wallet",
 *  title="Wallet schema",
 * 	@OA\Property(
 * 		property="balance",
 * 		type="string",
 *      example="90.00"
 * 	),
 * 	@OA\Property(
 * 		property="created_at",
 * 		type="string",
 *      example="2022-09-22T23:00:41.000000Z"
 * 	),
 * 	@OA\Property(
 * 		property="updated_at",
 * 		type="string",
 *      example="2022-09-22T23:00:41.000000Z"
 * 	)
 * )
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
