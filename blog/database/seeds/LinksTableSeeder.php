<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            [
                'link_name' =>'wx1',
                'link_title' => 'test1',
                'link_url' => '416684516@qq.com',
                'link_order' =>1,
            ],
            [
            'link_name' =>'wx2',
            'link_title' => 'test2',
            'link_url' => '416684516@qq.com',
            'link_order' =>2,
            ]
        ]; 
        DB::table('links')->insert($data);
    }
}
