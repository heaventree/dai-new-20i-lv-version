<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;

class ServicesController extends Controller
{
    public function index()
    {
        $services = CmsPage::where('slug', 'like', 'service-%')
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return view('public.services', compact('services'));
    }

    public function show(string $slug)
    {
        $service = CmsPage::where('slug', 'service-' . $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('public.service-show', compact('service'));
    }
}
