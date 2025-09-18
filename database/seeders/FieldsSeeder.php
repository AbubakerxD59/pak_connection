<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            ["name" => "Full Name", "type" => "text"],
            ["name" => "Additional Notes", "type" => "textarea"],
            ["name" => "WhatsApp number", "type" => "number"],
            ["name" => "Type of Legal Work", "type" => "dropdown", "options" => "Commercial,Residential Property,Agricultural Land,Business,Criminal,Power of Attorney,Nadra Services"],
            ["name" => "City", "type" => "text"],
            ["name" => "District", "type" => "text"],
            ["name" => "Type of Property", "type" => "dropdown", "options" => "Residential,Commercial,Agricultural, Minibus ( 7 passengers ; 7 suitcases ; 7 bags )"],
            ["name" => "Size of Land / Property", "type" => "dropdown", "options" => "Marla,kanal,Acres"],
            ["name" => "Number of days", "type" => "text"],
            ["name" => "Type of Security", "type" => "dropdown", "options" => "Single Armed Guard,Single Armed Guard 24/7,Security Vehicle,Security Protocol,Bespoke"],
            ["name" => "Appoint an Approved Property Agent", "type" => "dropdown", "options" => "Yes,No"],
            ["name" => "Book an Advice Appointment", "type" => "dropdown", "options" => "Yes,No"],
            ["name" => "Type of Specific Enquiry", "type" => "dropdown", "options" => "Yes,No"],
            ["name" => "Area name", "type" => "text"],
            ["name" => "Hire a service", "type" => "dropdown", "options" => "Plumber,Electrician,General DIY,Approved Builder,Air Conditioning Suppliers & Fitters,Architect,Interior Designer,Bespoke Requests"],
            ["name" => "KW Size", "type" => "dropdown", "options" => "Hybrid,On Grid"],
            ["name" => "Hours", "type" => "dropdown", "options" => "Part Time,Full Time,Live In Staff"],
            ["name" => "Role", "type" => "dropdown", "options" => "Caretaker,Chef,Cleaner,Driver,Gardener,Care Assistant"],
            ["name" => "Type", "type" => "dropdown", "options" => "Security Guard,Armed Security Guard"],
            ["name" => "How many", "type" => "text"],
            ["name" => "Location", "type" => "text"],
            ["name" => "Number of Guests", "type" => "text"],
            ["name" => "Select Date", "type" => "date"],
            ["name" => "Select Time", "type" => "time"],
        ];
        $order = 1;
        foreach ($fields as $field) {
            Field::updateOrCreate(["name" => $field["name"]], [
                "type" => $field["type"],
                "options" => $field["type"] == "dropdown" ? explode(",", $field["options"]) : null,
                "order" => $order
            ]);
            $order++;
        }
    }
}
