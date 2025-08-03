<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmailTemplateController extends Controller
{
    /**
     * Display email templates management
     */
    public function index(): View
    {
        $templates = EmailTemplate::all();
        
        return view('admin.email-templates', compact('templates'));
    }

    /**
     * Show edit form for email template
     */
    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('admin.email-template-edit', compact('emailTemplate'));
    }

    /**
     * Update email template
     */
    public function update(Request $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'button_color' => 'required|string|max:7',
            'button_text_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:100',
            'container_width' => 'required|string|max:10',
        ]);

        $styling = [
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'background_color' => $request->background_color,
            'text_color' => $request->text_color,
            'button_color' => $request->button_color,
            'button_text_color' => $request->button_text_color,
            'font_family' => $request->font_family,
            'container_width' => $request->container_width,
        ];

        $emailTemplate->update([
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
            'styling' => $styling,
        ]);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully!');
    }

    /**
     * Preview email template
     */
    public function preview(EmailTemplate $emailTemplate)
    {
        $sampleData = [
            'app_name' => 'BMI Monitoring System',
            'user_name' => 'John Doe',
            'verification_url' => 'http://127.0.0.1:8000/email/verify/123/sample-token',
            'font_family' => $emailTemplate->styling['font_family'],
            'container_width' => $emailTemplate->styling['container_width'],
            'primary_color' => $emailTemplate->styling['primary_color'],
            'secondary_color' => $emailTemplate->styling['secondary_color'],
            'background_color' => $emailTemplate->styling['background_color'],
            'text_color' => $emailTemplate->styling['text_color'],
            'button_color' => $emailTemplate->styling['button_color'],
            'button_text_color' => $emailTemplate->styling['button_text_color'],
        ];

        $renderedContent = $emailTemplate->renderContent($sampleData);

        return response($renderedContent)->header('Content-Type', 'text/html');
    }

    /**
     * Create default email verification template
     */
    public function createDefault(): RedirectResponse
    {
        EmailTemplate::updateOrCreate(
            ['name' => 'email_verification'],
            [
                'subject' => 'Verify Your Email Address - {{app_name}}',
                'content' => '
                    <div style="font-family: {{font_family}}; max-width: {{container_width}}; margin: 0 auto; background-color: {{background_color}}; padding: 20px;">
                        <div style="background: white; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <h1 style="color: {{primary_color}}; font-size: 28px; margin: 0;">{{app_name}}</h1>
                                <p style="color: {{secondary_color}}; margin: 10px 0 0 0;">Smart Nutrition for Kids</p>
                            </div>
                            
                            <div style="margin-bottom: 30px;">
                                <h2 style="color: {{text_color}}; font-size: 24px; margin-bottom: 20px;">Hello {{user_name}}!</h2>
                                <p style="color: {{text_color}}; line-height: 1.6; margin-bottom: 20px;">
                                    Thank you for registering with {{app_name}}. We are excited to have you join our community dedicated to child nutrition and health.
                                </p>
                                <p style="color: {{text_color}}; line-height: 1.6; margin-bottom: 30px;">
                                    To complete your registration, please verify your email address by copying and pasting the link below into your browser:
                                </p>
                                
                                <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0;">
                                    <p style="color: {{text_color}}; margin: 0; word-break: break-all;">
                                        {{verification_url}}
                                    </p>
                                </div>
                                
                                <p style="color: {{text_color}}; line-height: 1.6; margin: 20px 0;">
                                    This verification link will expire in 60 minutes for security purposes.
                                </p>
                                
                                <p style="color: {{text_color}}; line-height: 1.6;">
                                    If you did not create an account, no further action is required and you can safely ignore this email.
                                </p>
                            </div>
                            
                            <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 30px;">
                                <p style="color: {{secondary_color}}; font-size: 14px; margin: 0; text-align: center;">
                                    Best regards,<br>
                                    The {{app_name}} Team
                                </p>
                            </div>
                        </div>
                    </div>
                ',
                'styling' => [
                    'primary_color' => '#10b981',
                    'secondary_color' => '#064e3b',
                    'background_color' => '#f9fafb',
                    'text_color' => '#374151',
                    'button_color' => '#10b981',
                    'button_text_color' => '#ffffff',
                    'font_family' => 'Arial, sans-serif',
                    'container_width' => '600px',
                ],
                'is_active' => true,
            ]
        );

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Default email verification template created successfully!');
    }
}
