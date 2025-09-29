<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Package;
use App\Models\Price;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $packages = [
            ["name" => "TRAVEL ASSIST", "personal_assistance" => "1", "status" => 1],
            ["name" => "PAK ASSIST", "personal_assistance" => "0", "status" => 1],
            ["name" => "GOLD ASSIST", "personal_assistance" => "1", "status" => 0],
            ["name" => "CORPORATE ASSIST", "personal_assistance" => "1", "status" => 0],
        ];
        // prices
        $prices = [
            "TRAVEL ASSIST" => ["1" => 395, "6" => 995, "12" => 1495],
            "PAK ASSIST" => ["1" => 195, "6" => 495, "12" => 795],
            "GOLD ASSIST" => ["1" => 495, "6" => 1249, "12" => 1895],
            "CORPORATE ASSIST" => ["12" => 4995],
        ];
        // create packages
        foreach ($packages as $package) {
            $check = Package::where("name", $package["name"])->first();
            if (!$check) {
                $stripe_product = $stripe->products->create([
                    'name' => $package["name"],
                    'active' => true,
                ]);
                if ($stripe_product->id) {
                    $newPackage = Package::create([
                        "name" => $package["name"],
                        "personal_assistance" => $package["personal_assistance"],
                        "stripe_product_id" => $stripe_product->id,
                        "status" => $package["status"]
                    ]);
                    $pricing = $prices[$package["name"]];
                    foreach ($pricing as $duration => $price) {
                        $stripe_amount = $stripe->prices->create([
                            'currency' => 'gbp',
                            'active' => true,
                            'product' => $stripe_product->id,
                            'unit_amount_decimal' => $price * 100,
                            'recurring' => [
                                'interval' => "month",
                                'interval_count' => $duration
                            ]
                        ]);
                        if ($stripe_amount->id) {
                            Price::updateOrCreate(["package_id" => $newPackage->id, "type" => $duration], [
                                "price" => $price,
                                "stripe_id" => $stripe_amount->id,
                            ]);
                        }
                    }
                    $feature_ids = Feature::inRandomOrder()->limit(rand(7, 12))->pluck("id");
                    $newPackage->features()->sync($feature_ids);
                }
            }
        }
    }
}
