<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['name' => 'Airport VIP Meet & Greet (Protocol)', "icon" => "features/meet_greet.jpg"],
            ['name' => 'Accomodation', "icon" => "features/Accommodation.jpg"],
            ['name' => 'Transport', "icon" => "features/airport-trans.jpg"],
            ['name' => 'Tourism', "icon" => "features/trusim.jpg"],
            ['name' => 'Shopping Trips', "icon" => "features/shopping.jpg"],
            ['name' => 'Medical Emergency 24/7', "icon" => "features/medical-care.jpg"],
            ['name' => 'Crisis Help 24/7', "icon" => "features/support-pakconnection.jpg"],
            ['name' => 'Security Protection', "icon" => "features/Security Protection.jpg"],
            ['name' => "Children's Activity", "icon" => "features/chil-acitivities.jpg"],
            ['name' => "Personal Requests", "icon" => "features/services.jpg"],
            ['name' => "Tour Guides", "icon" => "features/tour-guid.jpg"],
            ['name' => "Legal Services", "icon" => "features/Legal Services.jpg"],
            ['name' => "Property Buy & Sell", "icon" => "features/buying-a-house-1024x512-1.jpg"],
            ['name' => "Property Maintenance", "icon" => "features/property-maintenance-guide.jpg"],
            ['name' => "Property Security", "icon" => "features/property-security.jpg"],
            ['name' => "Agricultural Land Services", "icon" => "features/agriculture.webp"],
            ['name' => "Property Disputes", "icon" => "features/property-dispute.jpeg"],
            ['name' => "Household Staff", "icon" => "features/household.jpeg"],
            ['name' => "Wedding Planning", "icon" => "features/wedding.jpeg"],
            ['name' => "Personal Assistant 24/7", "icon" => "features/support-pakconnection.jpg"],
            ['name' => "Full VIP Concierge Services", "icon" => "features/Luxury-Concierge-Services.jpg"],
        ];
        foreach ($features as $feature) {
            $service = Feature::updateOrCreate(
                ['name' => $feature['name']],
                [
                    'name' => $feature['name'],
                    'icon' => $feature['icon']
                ]
            );
            $field_ids = Field::inRandomOrder()->limit(10)->pluck("id");
            $service->fields()->sync($field_ids);
        }
    }
}
