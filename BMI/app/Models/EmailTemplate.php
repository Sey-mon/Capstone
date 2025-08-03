<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'styling',
        'is_active',
    ];

    protected $casts = [
        'styling' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the default styling options
     */
    public function getDefaultStyling(): array
    {
        return [
            'primary_color' => '#10b981', // Green
            'secondary_color' => '#064e3b',
            'background_color' => '#f9fafb',
            'text_color' => '#374151',
            'button_color' => '#10b981',
            'button_text_color' => '#ffffff',
            'font_family' => 'Arial, sans-serif',
            'container_width' => '600px',
        ];
    }

    /**
     * Get styling with defaults
     */
    public function getStylingAttribute($value): array
    {
        $styling = json_decode($value, true) ?? [];
        return array_merge($this->getDefaultStyling(), $styling);
    }

    /**
     * Replace placeholders in content
     */
    public function renderContent(array $variables = []): string
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }
        
        return $content;
    }

    /**
     * Replace placeholders in subject
     */
    public function renderSubject(array $variables = []): string
    {
        $subject = $this->subject;
        
        foreach ($variables as $key => $value) {
            $subject = str_replace("{{$key}}", $value, $subject);
        }
        
        return $subject;
    }

    /**
     * Get the active template by name
     */
    public static function getActiveTemplate(string $name): ?self
    {
        return self::where('name', $name)->where('is_active', true)->first();
    }
}
