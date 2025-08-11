<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class PublicStaffController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $staff = Staff::query()
            ->when($q, fn($qr) => $qr->where(function($w) use ($q) {
                $w->where('nombre','like',"%$q%")
                  ->orWhere('cargo','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            }))
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('public.staff-index', compact('staff','q'));
    }
}
