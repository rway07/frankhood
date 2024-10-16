<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Expenses
 *
 * @property int $id
 * @property string $description
 * @property string $date
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expenses extends Model
{
    protected $table = 'expenses';
}
