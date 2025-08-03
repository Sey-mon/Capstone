<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'welcome_email',
                'category' => 'user',
                'subject' => 'Welcome to {{app_name}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{app_name}}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: {{primary_color}};">Welcome to {{app_name}}</h1>
        </div>
        
        <p>Hello {{user_name}},</p>
        
        <p>Welcome to the BMI Malnutrition Assessment System! Your account has been successfully created.</p>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3>Your Account Details:</h3>
            <p><strong>Email:</strong> {{user_email}}</p>
            <p><strong>Role:</strong> {{user_role}}</p>
            <p><strong>Login URL:</strong> <a href="{{login_url}}" style="color: {{primary_color}};">{{login_url}}</a></p>
        </div>
        
        <p>You can now access the system and start managing patient assessments, inventory, and treatments.</p>
        
        <p>If you have any questions, please contact our support team at {{support_email}}.</p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <p>Best regards,<br>The {{app_name}} Team</p>
        </div>
    </div>
</body>
</html>',
                'styling' => '{"primary_color": "#3B82F6", "secondary_color": "#6B7280"}',
                'variables' => '["app_name", "user_name", "user_email", "user_role", "login_url", "support_email", "primary_color"]',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'password_reset',
                'category' => 'user',
                'subject' => 'Password Reset Request - {{app_name}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset - {{app_name}}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: {{primary_color}};">Password Reset Request</h1>
        </div>
        
        <p>Hello {{user_name}},</p>
        
        <p>You recently requested to reset your password for your {{app_name}} account.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{reset_url}}" style="background-color: {{primary_color}}; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Password</a>
        </div>
        
        <p>If you did not request this password reset, please ignore this email. This link will expire in 60 minutes.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; font-size: 14px;">
            <p><strong>Security Note:</strong> Never share this link with anyone. Our support team will never ask for your password.</p>
        </div>
        
        <p>If you have any questions, please contact our support team at {{support_email}}.</p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <p>Best regards,<br>The {{app_name}} Team</p>
        </div>
    </div>
</body>
</html>',
                'styling' => '{"primary_color": "#3B82F6", "secondary_color": "#6B7280"}',
                'variables' => '["app_name", "user_name", "reset_url", "support_email", "primary_color"]',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'assessment_completed',
                'category' => 'assessment',
                'subject' => 'Nutrition Assessment Completed - {{patient_name}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assessment Completed - {{app_name}}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: {{primary_color}};">Assessment Completed</h1>
        </div>
        
        <p>Hello {{nutritionist_name}},</p>
        
        <p>A nutrition assessment has been completed for patient <strong>{{patient_name}}</strong>.</p>
        
        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3>Assessment Details:</h3>
            <p><strong>Patient Name:</strong> {{patient_name}}</p>
            <p><strong>Assessment Date:</strong> {{assessment_date}}</p>
            <p><strong>BMI Status:</strong> <span style="color: {{bmi_color}};">{{bmi_status}}</span></p>
            <p><strong>Malnutrition Risk:</strong> <span style="color: {{risk_color}};">{{malnutrition_risk}}</span></p>
            <p><strong>Assessed By:</strong> {{assessed_by}}</p>
        </div>
        
        <p>Please review the assessment and create appropriate treatment recommendations if needed.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{assessment_url}}" style="background-color: {{primary_color}}; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Assessment</a>
        </div>
        
        <p>If you have any questions, please contact our support team at {{support_email}}.</p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <p>Best regards,<br>The {{app_name}} Team</p>
        </div>
    </div>
</body>
</html>',
                'styling' => '{"primary_color": "#3B82F6", "bmi_color": "#10B981", "risk_color": "#F59E0B"}',
                'variables' => '["app_name", "nutritionist_name", "patient_name", "assessment_date", "bmi_status", "malnutrition_risk", "assessed_by", "assessment_url", "support_email", "primary_color", "bmi_color", "risk_color"]',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'low_inventory_alert',
                'category' => 'inventory',
                'subject' => 'Low Inventory Alert - {{item_name}}',
                'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Low Inventory Alert - {{app_name}}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: {{warning_color}};">Low Inventory Alert</h1>
        </div>
        
        <p>Hello {{admin_name}},</p>
        
        <p>This is an automated alert to notify you that the following inventory item is running low:</p>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3>Item Details:</h3>
            <p><strong>Item Name:</strong> {{item_name}}</p>
            <p><strong>Category:</strong> {{item_category}}</p>
            <p><strong>Current Stock:</strong> {{current_stock}}</p>
            <p><strong>Minimum Stock Level:</strong> {{min_stock_level}}</p>
            <p><strong>Last Updated:</strong> {{last_updated}}</p>
        </div>
        
        <p>Please consider reordering this item to maintain adequate stock levels for patient care.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{inventory_url}}" style="background-color: {{primary_color}}; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">View Inventory</a>
        </div>
        
        <p>If you have any questions, please contact our support team at {{support_email}}.</p>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
            <p>Best regards,<br>The {{app_name}} Team</p>
        </div>
    </div>
</body>
</html>',
                'styling' => '{"primary_color": "#3B82F6", "warning_color": "#F59E0B"}',
                'variables' => '["app_name", "admin_name", "item_name", "item_category", "current_stock", "min_stock_level", "last_updated", "inventory_url", "support_email", "primary_color", "warning_color"]',
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->insert(array_merge($template, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
} 