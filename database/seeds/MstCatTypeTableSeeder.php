<?php

use App\MstCatType;
use Illuminate\Database\Seeder;

class MstCatTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MstCatType::create([
            'title'=>'Asset',
            'value' => '100'
        ]);

        MstCatType::create([
            'title'=>'Liability',
            'value' => '200'
        ]);

        MstCatType::create([
            'title'=>'Income',
            'value' => '300'
        ]);

        MstCatType::create([
            'title'=>'Expense',
            'value' => '400'
        ]);

        MstCatType::create([
            'title'=>'Reserve',
            'value' => '0'
        ]);
    }
}
