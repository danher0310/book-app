<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $publisher = new Publisher();
        $publisher->name = "Santillana";
        $publisher->website = 'santillana.com';
        $publisher->country = 'Venezuela';
        $publisher->email = 'info@santillana.com';
        $publisher->description = "Editorial venezolana con mas de 30 años en el mercado, apoyando a los escritores latinoamericanos";
        $publisher->save(); 


    }
}
