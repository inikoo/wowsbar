<?php

namespace Database\Seeders;

use App\Models\Shipper;
use App\Models\ShipperProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipperProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'JNE',
                'slug' => 'jne',
                'data' => [
                    'api_url' => 'https://apiv2.jne.co.id:10443',
                ]
            ],
            [
                'name' => 'J&T',
                'slug' => 'jnt',
                'data' => [
                    'api_url' => 'https://apiv2.jne.co.id:10443',
                ]
            ],
            [
                'name' => 'SiCepat',
                'slug' => 'sicepat',
                'data' => [
                    'api_url' => 'https://apiv2.jne.co.id:10443',
                ]
            ],
            [
                'name' => 'Shopee Express',
                'slug' => 'shopee_express',
                'data' => [
                    'api_url' => 'https://apiv2.jne.co.id:10443',
                ]
            ]
        ];

        foreach ($data as $item) {
            if(!ShipperProvider::where('slug',$item['slug'])->exists()) {
                $provider = ShipperProvider::create($item);

                Shipper::updateOrCreate([
                    'slug' => $provider->slug,
                    'name' => $provider->name,
                    'country_id' => 1,
                    'provider_type' => ShipperProvider::class,
                    'provider_id' => $provider->id,
                    'data' => '{}'
                ]);
            }
        }
    }
}
