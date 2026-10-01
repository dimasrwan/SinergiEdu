<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap for public pages.
     */
    public function index(): Response
    {
        $baseUrl = 'https://sinergiedu.com';

        $xml = Cache::remember('dynamic_sitemap_xml', now()->addHours(6), function () use ($baseUrl) {
            $urls = [];

            // 1. Homepage (Landing Page)
            $landingViewPath = resource_path('views/landing.blade.php');
            $landingLastMod = file_exists($landingViewPath)
                ? Carbon::createFromTimestamp(filemtime($landingViewPath))->toAtomString()
                : now()->toAtomString();

            $urls[] = [
                'loc' => $baseUrl . '/',
                'lastmod' => $landingLastMod,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];

            return view('sitemap', compact('urls'))->render();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
