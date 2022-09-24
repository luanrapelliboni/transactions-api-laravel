<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *  schema="User",
 *  title="User schema",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string",
 *      example="John Doe"
 * 	),
 * 	@OA\Property(
 * 		property="document",
 * 		type="string",
 *      example="11122233344"
 * 	),
 * 	@OA\Property(
 * 		property="email",
 * 		type="string",
 *      example="john@doe.example.com"
 * 	),
 * 	@OA\Property(
 * 		property="password",
 * 		type="string",
 *      example="$2y$10$vIHysOTa403cI29EipFMe.090X.eaascDyyXIx72srPGtggpr97cy"
 * 	),
 * 	@OA\Property(
 * 		property="type",
 * 		type="string"
 * 	),
 *  @OA\Property(
 *      property="wallet",
 *      type="object",
 *      ref="#/components/schemas/Wallet"
 *  ),
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
class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'email',
        'password',
        'type'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
