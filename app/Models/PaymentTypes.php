<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaymentTypes
 *
 * @property int $id
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentTypes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentTypes extends Model
{
    protected $table = 'payment_types';
}
