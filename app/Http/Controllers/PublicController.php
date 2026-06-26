<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiry;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{
    public function home()
    {
        $categories = Category::where('status', true)
            ->withCount(['products as approved_count' => fn ($q) => $q->where('status', 'approved')])
            ->get();

        $featuredProducts = Product::approved()
            ->with(['category', 'seller'])
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'products'   => Product::approved()->count(),
            'sellers'    => User::where('role', 'seller')->where('status', 'active')->count(),
            'buyers'     => User::where('role', 'buyer')->where('status', 'active')->count(),
            'categories' => Category::where('status', true)->count(),
        ];

        return view('public.home', compact('categories', 'featuredProducts', 'stats'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSend(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'mobile'  => 'nullable|string|max:15',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        // Store enquiry in database
        Inquiry::create($data);

        try {
            Mail::to(config('mail.from.address'))
                ->send(new ContactInquiry(
                    senderName:     $data['name'],
                    senderEmail:    $data['email'],
                    senderMobile:   $data['mobile'] ?? '',
                    inquirySubject: $data['subject'],
                    body:           $data['message'],
                ));
        } catch (\Throwable $e) {
            // Mail failure is non-fatal — inquiry is still registered
            Log::error('Contact inquiry mail sending failed: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $data['email']
            ]);
        }

        return back()->with('success', 'Thank you for reaching out! Our team will respond within 24 hours.');
    }

    // ── Policy pages ────────────────────────────────────────────────────

    public function privacy()
    {
        return view('public.legal.privacy');
    }

    public function terms()
    {
        return view('public.legal.terms');
    }

    public function refund()
    {
        return view('public.legal.refund');
    }

    public function shipping()
    {
        return view('public.legal.shipping');
    }

    // ── Sitemap ─────────────────────────────────────────────────────────

    public function sitemap()
    {
        $categories = Category::where('status', true)->get(['id', 'updated_at']);
        $products   = Product::approved()->get(['id', 'updated_at']);

        return response()->view('public.sitemap', compact('categories', 'products'))
            ->header('Content-Type', 'application/xml');
    }
}
