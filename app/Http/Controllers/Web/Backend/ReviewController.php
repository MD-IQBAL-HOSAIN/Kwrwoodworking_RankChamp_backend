<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::paginate(5);
        return view('backend.layout.cms.review.index', compact('reviews'));
    }

    public function create()
    {
        return view('backend.layout.cms.review.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->description);
        Review::create($data);

        return redirect()->route('review.index')->with('t-success', 'Review created successfully.');
    }

    public function edit(string $slug)
    {
        $review = Review::where('slug', $slug)->firstOrFail();
        return view('backend.layout.cms.review.edit', compact('review'));
    }

    public function update(Request $request, string $slug)
    {
        $reviews = Review::where('slug', $slug)->firstOrFail();
        $reviews->update($request->all());

        return redirect()->route('review.index')->with('t-success', 'Reviews updated successfully.');
    }

  
    public function toggleStatus(Request $request, $id)
    {
        $reviews = Review::findOrFail($id);
        $reviews->status = $reviews->status === 'active' ? 'inactive' : 'active';
        $reviews->save();

        return response()->json(['status' => $reviews->status]);
    }


    public function destroy(string $slug)
    {
        $reviews = Review::where('slug', $slug)->firstOrFail();
        $reviews->delete();

        return redirect()->route('review.index')->with('t-success', 'Review deleted successfully.');
    }
}
