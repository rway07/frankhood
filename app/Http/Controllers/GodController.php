<?php

namespace App\Http\Controllers;
use DB;

class GodController extends Controller
{
    public function normalizeReceiptsNumPeople()
    {
        $data = DB::select(
            'select receipts.number, receipts.year,
                    count(customers_receipts.id) as num_people
                from receipts
                join customers_receipts on customers_receipts.year = receipts.year
                   and customers_receipts.number = receipts.number
                join payment_types on receipts.payment_type_id = payment_types.id
                group by receipts.year, receipts.number'
        );

        foreach ($data as $d) {
            print 'updating receipt n.' . $d->number . '/' . $d->year;
            DB::update(
                'update receipts
                set num_people = ?
                where year = ? and number = ?;',
                [
                    $d->num_people,
                    $d->year,
                    $d->number
                ]
            );
        }
    }

    public function normalizeEnrollmentYear()
    {
        $data = DB::select(
            'select id, enrollment_year, birth_date
            from customers
            order by enrollment_year desc;'
        );

        foreach ($data as $d) {
            if ($d->enrollment_year == '') {
                $year = date('Y', strtotime($d->birth_date)) + 12;

                DB::update(
                    'update customers
                    set enrollment_year = ?
                    where id = ?;',
                    [
                        $year,
                        $d->id
                    ]
                );
            }
        }
        print 'Done!';
    }

    public function normalizeNames()
    {
        $data = DB::select(
            'select id, first_name, last_name, alias
                    from customers
                    order by first_name, last_name asc;'
        );

        foreach ($data as $d) {
            $firstName = ucwords(strtolower($d->first_name));
            $lastName = ucwords(strtolower($d->last_name));
            $alias = ucwords(strtolower($d->alias));

            print "Normalizing name " . $firstName . " " . $lastName . "<br>";
            DB::update(
                'update customers
                set first_name = ?, last_name = ?, alias = ?
                where id = ?',
                [
                    $firstName,
                    $lastName,
                    $alias,
                    $d->id
                ]
            );
        }
        print "Done!";
    }
}
