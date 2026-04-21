<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GroomingPackage;
use App\Models\RoomType;
use App\Models\GalleryPhoto;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data untuk ditampilkan di landing page
        $groomingPackages = GroomingPackage::where('is_active', true)
            ->orderBy('price')
            ->limit(3)
            ->get();

        $roomTypes = RoomType::where('is_active', true)
            ->limit(3)
            ->get();

        $galleryPhotos = GalleryPhoto::where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('public.home', compact('groomingPackages', 'roomTypes', 'galleryPhotos'));
    }

    public function services()
    {
        $groomingPackages = GroomingPackage::where('is_active', true)
            ->orderBy('price')
            ->get();

        $roomTypes = RoomType::where('is_active', true)->get();

        return view('public.services', compact('groomingPackages', 'roomTypes'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function gallery()
    {
        $photos = GalleryPhoto::where('is_active', true)->latest()->paginate(12);
        return view('public.gallery', compact('photos'));
    }
}
