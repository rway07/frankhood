<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * confraternita\Customers
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $alias
 * @property string $cf
 * @property string $birth_date
 * @property string $birth_place
 * @property string $birth_province
 * @property string $gender
 * @property string $death_date
 * @property string $revocation_date
 * @property string $address
 * @property string $CAP
 * @property string $municipality
 * @property string $province
 * @property string $email
 * @property string $phone
 * @property string $mobile_phone
 * @property boolean $priorato
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $enrollment_year
 * @property-read \Illuminate\Database\Eloquent\Collection|\confraternita\Receipts[] $owners
 * @property-read \Illuminate\Database\Eloquent\Collection|\confraternita\Receipts[] $receipts
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereCf($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereBirthDate($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereBirthPlace($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereBirthProvince($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereDeathDate($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereRevocationDate($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereCAP($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereMunicipality($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereProvince($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereMobilePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers wherePriorato($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\confraternita\Customers whereEnrollmentYear($value)
 * @mixin \Eloquent
 */
class Customers extends Model
{
    protected $table = 'customers';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function owners()
    {
        return $this->hasMany('confraternita\Receipts');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function receipts()
    {
        return $this->belongsToMany('confraternita\Receipts')->withTimestamps();
    }
}
