<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $companySettings = Schema::hasTable('company_settings')
            ? (CompanySetting::query()->first() ?? new CompanySetting(CompanySetting::defaults()))
            : new CompanySetting(CompanySetting::defaults());
        $currencyCode = config('services.flutterwave.currency', 'UGX');
        $socialLinks = $this->socialLinks($companySettings);
        $youtubeEmbedUrl = $this->resolveYoutubeEmbedUrl();

        View::share('companySettings', $companySettings);
        View::share('currencyCode', $currencyCode);
        View::share('socialLinks', $socialLinks);
        View::share('youtubeEmbedUrl', $youtubeEmbedUrl);
    }

    /**
     * @return array<int, array{label: string, icon: string, url: string}>
     */
    private function socialLinks(CompanySetting $companySettings): array
    {
        $whatsappNumber = config('services.social.whatsapp_number') ?: $companySettings->support_phone;

        return collect([
            [
                'label' => 'WhatsApp',
                'icon' => 'bi-whatsapp',
                'url' => $this->whatsappUrl($whatsappNumber),
            ],
            [
                'label' => 'Facebook',
                'icon' => 'bi-facebook',
                'url' => config('services.social.facebook_url'),
            ],
            [
                'label' => 'Instagram',
                'icon' => 'bi-instagram',
                'url' => config('services.social.instagram_url'),
            ],
            [
                'label' => 'Twitter',
                'icon' => 'bi-twitter-x',
                'url' => config('services.social.twitter_url'),
            ],
            [
                'label' => 'YouTube',
                'icon' => 'bi-youtube',
                'url' => config('services.social.youtube_url'),
            ],
        ])
            ->filter(fn (array $link) => filled($link['url']))
            ->values()
            ->all();
    }

    private function whatsappUrl(?string $number): ?string
    {
        if (blank($number)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $number);

        if (! filled($digits)) {
            return null;
        }

        return 'https://wa.me/'.$digits;
    }

    private function resolveYoutubeEmbedUrl(): ?string
    {
        $candidateUrls = [
            config('services.social.youtube_embed_url'),
            config('services.social.youtube_url'),
        ];

        foreach ($candidateUrls as $candidateUrl) {
            $embedUrl = $this->normalizeYoutubeEmbedUrl($candidateUrl);

            if (filled($embedUrl)) {
                return $embedUrl;
            }
        }

        return null;
    }

    private function normalizeYoutubeEmbedUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $path = (string) parse_url($url, PHP_URL_PATH);
        $host = Str::lower((string) parse_url($url, PHP_URL_HOST));
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        $videoId = null;

        if ($host === 'youtu.be') {
            $videoId = trim($path, '/');
        } elseif (filled($query['v'] ?? null)) {
            $videoId = (string) $query['v'];
        } elseif (str_contains($path, '/shorts/')) {
            $videoId = Str::after($path, '/shorts/');
        } elseif (str_contains($path, '/embed/')) {
            $videoId = Str::after($path, '/embed/');
        }

        $videoId = Str::before((string) $videoId, '/');

        return filled($videoId) ? 'https://www.youtube.com/embed/'.$videoId : null;
    }
}
