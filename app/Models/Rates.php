<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * confraternita\Rates
 *
 * @property integer $id
 * @property string $year
 * @property float $quota
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\confraternita\Receipts[] $receipts
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Rates whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Rates whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Rates whereQuota($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Rates whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Rates whereUpdatedAt($value)
 * @property float $funeral_cost
 * @method static \Illuminate\Database\Eloquent\Builder|Rates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rates query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rates whereFuneralCost($value)
 * @mixin \Eloquent
 */
class Rates extends Model
{
    protected $table = 'rates';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany('confraternita\Receipts');
    }
}
