<?php

declare(strict_types=1);

namespace App\Controllers;

class LandingController extends BaseController
{
    /**
     * Display Public Landing Page
     */
    public function index()
    {
        // If root '/' is visited by an authenticated user, redirect to dashboard.
        // If '/landing' is visited explicitly, always display the landing page.
        if (uri_string() === '' && session()->get('is_logged_in') && $this->request->getGet('view') !== 'landing') {
            return redirect()->to('/dashboard');
        }

        return view('landing/index');
    }
}
