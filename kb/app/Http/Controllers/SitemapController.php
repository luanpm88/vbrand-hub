<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;

class SitemapController extends Controller
{
    public function index(SitemapService $sitemapService)
    {
        return response($sitemapService->generate(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
