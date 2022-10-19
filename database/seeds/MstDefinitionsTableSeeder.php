<?php

use Illuminate\Database\Seeder;
use App\Models\MstDefinition;

class MstDefinitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MstDefinition::create([
            'title'=>'Fixed Asset',
        ]);
        MstDefinition::create([
            'title'=>'Liquid Asset',
        ]);
        MstDefinition::create([
            'title'=>'Current/Operating Asset',
        ]);
        MstDefinition::create([
            'title'=>'Current Liability',
        ]);

        MstDefinition::create([
            'title'=>'Owners Investment',
        ]);
        MstDefinition::create([
            'title'=>'Long Term Liability',
        ]);

        MstDefinition::create([
            'title'=>'Sales Turnover',
        ]);

        MstDefinition::create([
            'title'=>'Sell of Asset',
        ]);


        MstDefinition::create([
            'title'=>'Rent Income',
        ]);

        MstDefinition::create([
            'title'=>'Other Income',
        ]);


        MstDefinition::create([
            'title'=>'Cost of Sales / Goods Sold',
        ]);
        MstDefinition::create([
            'title'=>'Administrative Expense',
        ]);
        MstDefinition::create([
            'title'=>'Marketing, Advertising, and Promotion',
        ]);
        MstDefinition::create([
            'title'=>'Salaries, Benefits and Wages',
        ]);
        MstDefinition::create([
            'title'=>'Utility Expenses',
        ]);

        MstDefinition::create([
            'title'=>'Rent and Insurance',
        ]);

        MstDefinition::create([
            'title'=>'Depreciation and Amortization',
        ]);
        MstDefinition::create([
            'title'=>'Property Investment',
        ]);

        MstDefinition::create([
            'title'=>'Financial Expenses',
        ]);

        MstDefinition::create([
            'title'=>'Accumulated Depreciation & Amortization',
        ]);

    }
}
