<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Faq;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::paginate(5);
        return view('backend.layout.cms.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('backend.layout.cms.faq.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->answer); // Generate slug
        Faq::create($data);

        return redirect()->route('faq.index')->with('t-success', 'Faq created successfully.');
    }

    public function edit(string $slug)
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();
        return view('backend.layout.cms.faq.edit', compact('faq'));
    }

    public function update(Request $request, string $slug)
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();
        $faq->update($request->all());

        return redirect()->route('faq.index')->with('t-success', 'Faq updated successfully.');
    }

  
    public function toggleStatus(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->status = $faq->status === 'active' ? 'inactive' : 'active';
        $faq->save();

        return response()->json(['status' => $faq->status]);
    }


    public function destroy(string $slug)
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();
        $faq->delete();

        return redirect()->route('faq.index')->with('t-success', 'Faq deleted successfully.');
    }
}
