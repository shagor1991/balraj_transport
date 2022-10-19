<?php

use App\Models\MstACType;
use Illuminate\Database\Seeder;

class MstACTypesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        MstACType::create([
            'title'=>'ASSET',
        ]);
        MstACType::create([
            'title'=>'LIABILITY',
        ]);

        MstACType::create([
            'title'=>'CAPITAL INCOME',
        ]);

        MstACType::create([
            'title'=>'REVENUE INCOME',
        ]);



        MstACType::create([
            'title'=>'CAPITAL EXPENSE',
        ]);



        MstACType::create([
            'title'=>'REVENUE EXPENSE',
        ]);

        MstACType::create([
            'title'=>'CAPITAL RESERVE',
        ]);

        MstACType::create([
            'title'=>'RESERVE',
        ]);



    }
}
