<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // App Branding
            [
                'category' => 'app',
                'key_name' => 'app_name',
                'value' => 'BMI Malnutrition Assessment System',
                'data_type' => 'string',
                'description' => 'Application name displayed in the header and title',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'app',
                'key_name' => 'app_short_name',
                'value' => 'BMI System',
                'data_type' => 'string',
                'description' => 'Short application name for mobile displays',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'app',
                'key_name' => 'app_description',
                'value' => 'Comprehensive BMI and Malnutrition Assessment System for Healthcare Facilities',
                'data_type' => 'string',
                'description' => 'Application description for SEO and metadata',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            
            // Theme Colors
            [
                'category' => 'theme',
                'key_name' => 'primary_color',
                'value' => '#3B82F6',
                'data_type' => 'string',
                'description' => 'Primary color for buttons and links',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'theme',
                'key_name' => 'secondary_color',
                'value' => '#6B7280',
                'data_type' => 'string',
                'description' => 'Secondary color for secondary buttons',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'theme',
                'key_name' => 'accent_color',
                'value' => '#10B981',
                'data_type' => 'string',
                'description' => 'Accent color for success states and highlights',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'theme',
                'key_name' => 'danger_color',
                'value' => '#EF4444',
                'data_type' => 'string',
                'description' => 'Danger color for error states and warnings',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'theme',
                'key_name' => 'warning_color',
                'value' => '#F59E0B',
                'data_type' => 'string',
                'description' => 'Warning color for alerts and notifications',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            
            // Logo and Branding
            [
                'category' => 'branding',
                'key_name' => 'logo_url',
                'value' => '/images/logo.png',
                'data_type' => 'string',
                'description' => 'URL to the application logo',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'branding',
                'key_name' => 'favicon_url',
                'value' => '/favicon.ico',
                'data_type' => 'string',
                'description' => 'URL to the favicon',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            
            // Contact Information
            [
                'category' => 'contact',
                'key_name' => 'support_email',
                'value' => 'support@bmi-system.com',
                'data_type' => 'string',
                'description' => 'Support email address',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            [
                'category' => 'contact',
                'key_name' => 'admin_email',
                'value' => 'admin@bmi-system.com',
                'data_type' => 'string',
                'description' => 'Administrator email address',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            [
                'category' => 'contact',
                'key_name' => 'phone_number',
                'value' => '+63 912 345 6789',
                'data_type' => 'string',
                'description' => 'Contact phone number',
                'is_public' => true,
                'is_encrypted' => false,
            ],
            
            // System Configuration
            [
                'category' => 'system',
                'key_name' => 'maintenance_mode',
                'value' => 'false',
                'data_type' => 'boolean',
                'description' => 'Enable maintenance mode',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            [
                'category' => 'system',
                'key_name' => 'session_timeout',
                'value' => '120',
                'data_type' => 'integer',
                'description' => 'Session timeout in minutes',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            [
                'category' => 'system',
                'key_name' => 'max_login_attempts',
                'value' => '5',
                'data_type' => 'integer',
                'description' => 'Maximum login attempts before lockout',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            
            // Feature Flags
            [
                'category' => 'features',
                'key_name' => 'enable_registration',
                'value' => 'true',
                'data_type' => 'boolean',
                'description' => 'Enable user registration',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            [
                'category' => 'features',
                'key_name' => 'enable_email_notifications',
                'value' => 'true',
                'data_type' => 'boolean',
                'description' => 'Enable email notifications',
                'is_public' => false,
                'is_encrypted' => false,
            ],
            [
                'category' => 'features',
                'key_name' => 'enable_audit_logging',
                'value' => 'true',
                'data_type' => 'boolean',
                'description' => 'Enable audit logging',
                'is_public' => false,
                'is_encrypted' => false,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->insert(array_merge($setting, [
                'updated_at' => now(),
            ]));
        }
    }
} 