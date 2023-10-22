<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Currencies data
        $currencies = [
            ['Dollars', 'USD', '$'],
            ['Central African CFA franc', 'XAF', 'FCFA'],
            ['Euro', 'EUR', '€']
        ];

        // Loop through the currencies and insert them into the table
        foreach ($currencies as $currency) {
            $name = $currency[0];
            $code = $currency[1];
            $symbol = $currency[2];

            // Check if the currency with the same code already exists
            $existingCurrency = DB::table('currencies')->where('code', $code)->first();

            if (!$existingCurrency) {
                // Insert the currency if it doesn't exist
                DB::table('currencies')->insert([
                    'uuid' => Str::orderedUuid(),
                    'name' => $name,
                    'code' => $code,
                    'symbol' => $symbol,
                    'status' => true
                ]);
            }
        }
    }
}
