<?php

namespace Database\Seeders;

use App\Models\Mail\EmailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO: The layout goes here

        EmailTemplate::create([
            'title' => 'Hello',
            'scope_type' => 'Organisation',
            'scope_id' => 1,
            'data' => '{}',
            'compiled' => '{}',
        ]);
    }
}
