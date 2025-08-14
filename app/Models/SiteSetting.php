<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'site_logo',
        'site_favicon',
        'site_facebook_url',
        'site_twitter_url',
        'site_linkedin_url',
        'site_instagram_url',
        'site_youtube_url',
        'site_working_hours',
        'site_maps_embed',
        'site_seo_title',
        'site_seo_description',
        'site_seo_keywords',
        'is_maintenance',
    ];
}
