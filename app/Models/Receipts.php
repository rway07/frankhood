<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * confraternita\Receipts
 *
 * @property integer $id
 * @property string $date
 * @property integer $customers_id
 * @property integer $rates_id
 * @property float $total
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\confraternita\Customers[] $customers
 * @property-read \confraternita\Rates $rates
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereCustomersId($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereRatesId($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereUpdatedAt($value)
 * @property integer $number
 * @property integer $year
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Receipts whereYear($value)
 * @property int $custom_quotas
 * @property int $payment_type_id
 * @property int $num_people
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts query()
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts whereCustomQuotas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts whereNumPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Receipts wherePaymentTypeId($value)
 * @mixin \Eloquent
 */
class Receipts extends Model
{
    protected $table = 'receipts';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customers()
    {
        return $this->belongsToMany('confraternita\Customers')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rates()
    {
        return $this->belongsTo('confraternita\Rates');
    }
}
