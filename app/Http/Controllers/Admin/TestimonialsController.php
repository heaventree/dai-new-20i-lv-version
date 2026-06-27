<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialsController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'quote'      => 'required|string',
            'name'       => 'required|string|max:100',
            'location'   => 'required|string|max:100',
            'stars'      => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer|min:0',
        ]);
        $data['highlight'] = $request->boolean('highlight');
        Testimonial::create($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $request->validate([
            'quote'      => 'required|string',
            'name'       => 'required|string|max:100',
            'location'   => 'required|string|max:100',
            'stars'      => 'required|integer|min:1|max:5',
            'sort_order' => 'required|integer|min:0',
        ]);
        $data['highlight'] = $request->boolean('highlight');
        $testimonial->update($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
