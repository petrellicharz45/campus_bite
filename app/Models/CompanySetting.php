<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'support_email',
        'support_phone',
        'support_location',
        'operating_hours',
        'logo_path',
    ];

    protected $appends = [
        'logo_url',
    ];

    public static function defaults(): array
    {
        return [
            'company_name' => 'Campus Bites and Canteen',
            'support_email' => 'hello@campusbites.test',
            'support_phone' => '+256776980496',
            'support_location' => 'Main Campus Canteen, Student Centre',
            'operating_hours' => "Monday - Friday: 7:00 AM - 9:00 PM\nSaturday - Sunday: 9:00 AM - 6:00 PM",
            'logo_path' => null,
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (! $this->logo_path) {
            return null;
        }

        return Storage::url($this->logo_path);
    }
}
