<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Offers
 *
 * @property int $id
 * @property string $description
 * @property string $date
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offers whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Offers extends Model
{
    protected $table = 'offers';
}
