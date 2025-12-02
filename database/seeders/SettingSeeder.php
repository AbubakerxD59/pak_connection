<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            ['key' => 'app_name', 'value' => 'Pak Connections'],
            ['key' => 'company_name', 'value' => 'Pak Connections'],
            ['key' => 'company_email', 'value' => 'info@pakconnections.com'],
            ['key' => 'company_phone', 'value' => '+44 203 375 3337'],
            ['key' => 'company_phone_number', 'value' => '+44 203 375 3337'],
            ['key' => 'company_address', 'value' => 'London, United Kingdom'],
            ['key' => 'company_logo', 'value' => 'assets/img/site_logo.jpg'],
            ['key' => 'company_tagline', 'value' => 'Your Personal Assistant in Pakistan'],
            
            // Contact Information
            ['key' => 'support_email', 'value' => 'support@pakconnections.com'],
            ['key' => 'support_phone', 'value' => '+44 203 375 3337'],
            ['key' => 'whatsapp_number', 'value' => '+92 320 5023407'],
            
            // Application Settings
            ['key' => 'app_version', 'value' => '1.0.0'],
            ['key' => 'app_timezone', 'value' => 'UTC'],
            ['key' => 'app_locale', 'value' => 'en'],
            ['key' => 'currency', 'value' => 'GBP'],
            ['key' => 'currency_symbol', 'value' => '£'],
            
            // Pagination & Display
            ['key' => 'items_per_page', 'value' => '25'],
            ['key' => 'item_per_page', 'value' => '25'],
            ['key' => 'results_per_page', 'value' => '10'],
            
            // Date & Time Formats
            ['key' => 'date_format', 'value' => 'jS M, Y'],
            ['key' => 'time_format', 'value' => 'h:i a'],
            ['key' => 'date_time_format', 'value' => 'jS M, Y h:i a'],
            ['key' => 'timezone_display', 'value' => 'Europe/London'],
            
            // Email Settings
            ['key' => 'mail_from_address', 'value' => 'noreply@pakconnections.com'],
            ['key' => 'mail_from_name', 'value' => 'Pak Connections'],
            
            // Feature Flags
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'allow_registration', 'value' => '1'],
            ['key' => 'email_verification_required', 'value' => '1'],
            ['key' => 'show_notifications', 'value' => '1'],
            
            // Business Hours
            ['key' => 'business_hours', 'value' => 'Monday - Friday: 9:00 AM - 6:00 PM GMT'],
            ['key' => 'support_hours', 'value' => '24/7 Support Available'],
            
            // SEO Settings
            ['key' => 'meta_description', 'value' => 'Pak Connections provides comprehensive services for your needs in Pakistan'],
            ['key' => 'meta_keywords', 'value' => 'pakistan, connections, services, concierge'],
            ['key' => 'meta_author', 'value' => 'Pak Connections'],
            
            // File Upload Settings
            ['key' => 'max_upload_size', 'value' => '5120'],  // 5MB in KB
            ['key' => 'allowed_file_types', 'value' => 'jpg,jpeg,png,pdf,doc,docx'],
            
            // Security Settings
            ['key' => 'session_lifetime', 'value' => '120'],  // minutes
            ['key' => 'password_min_length', 'value' => '8'],
            
            // Package Prices (existing)
            ['key' => 'package_prices', 'value' => json_encode([
                1 => 'Monthly',
                2 => 'Quarterly', 
                3 => 'Yearly'
            ])],
        ];
        
        try {
            if (count($settings) > 0) {
                foreach ($settings as $setting) {
                    $key = isset($setting['key']) ? $setting['key'] : '';
                    $getSetting = DB::table('settings')->where('key', $key)->first();
                    if (empty($getSetting)) {
                        // Don't add timestamps - settings table doesn't have them
                        DB::table('settings')->insert($setting);
                    }
                }
            }
            
            $this->command->info("✓ Settings seeded successfully! Total settings: " . count($settings));
        } catch (\Exception $exception) {
            $this->command->error("✗ Error seeding settings: " . $exception->getMessage());
        }
    }
}
