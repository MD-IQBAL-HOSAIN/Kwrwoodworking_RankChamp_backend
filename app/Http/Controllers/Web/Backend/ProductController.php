<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Product;
use App\Models\Varient;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Ratings;
use App\Models\VarientImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with('stock', 'variants')->get();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('image', function ($data) {
                    return "<img width='70px' src='" . asset($data->image_url) . "' ></img>";
                })


                ->addColumn('quantity', function ($data) {
                    return $data->stock->quantity ?? 'N/A';
                })

                ->addColumn('color', function ($data) {
                    return $data->variants->pluck('color')->join(', ');
                })

                ->addColumn('action', function ($data) {
                    return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                  <a href="' . route('product.edit',  $data->id) . '" type="button" class="btn btn-primary text-white" title="Edit">
                                  <i class="bi bi-pencil"></i>
                                  </a>
                                  <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" type="button" class="btn btn-danger text-white" title="Delete">
                                  <i class="bi bi-trash"></i>
                                </a>
                                </div>';
                })

                ->addColumn('status', function ($data) {
                    $status = ' <div class="form-check form-switch" style="margin-left:40px;">';
                    $status .= ' <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" getAreaid="' . $data->id . '" name="status"';
                    if ($data->status == "Active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $data->id . '" class="form-check-label" for="customSwitch"></label></div>';

                    return $status;
                })

                ->rawColumns(['action', 'status', 'image',])
                // ->make(true);
                ->toJson();
        }

        return view('backend.layout.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product = Product::all();
        return view('backend.layout.product.create', compact('product'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'required|image|mimes:jpeg,jpg,png,svg,webp|max:30720',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'rating' => 'nullable|integer|max:5',
            'status' => 'required|in:Active,Inactive',
            'quantity' => 'required|numeric|min:0',
            'variants' => 'required|array',
            'variants.*.color' => 'required|string|max:255',
            'variants.*.image_url' => 'required|array',
            'variants.*.image_url.*' => 'image|max:5048', // Multiple images validation
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Handle main product image upload
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = public_path('/product/image/');
                $image->move($imagePath, $imageName);
                $validated['image_url'] = '/product/image/' . $imageName;
            }

            // Create and store the product
            $product = Product::create([
                'title' => $validated['title'],
                'sub_title' => $validated['sub_title'],
                'price' => $validated['price'],
                'image_url' => $validated['image_url'],
                'discount' => $validated['discount'],
                'description' => $validated['description'],
                'rating' => $validated['rating'],
                'status' => $validated['status'],
            ]);

            // Add stock for the product
            $product->stock()->create([
                'quantity' => $validated['quantity'],
            ]);

            // Handle product variants
            foreach ($validated['variants'] as $variantData) {
                // Create a variant
                $variant = Varient::create([
                    'product_id' => $product->id,
                    'color' => $variantData['color'],
                ]);

                // Handle variant-specific image uploads
                foreach ($variantData['image_url'] as $imageFile) {

                    // Generate a unique filename
                    $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                    // Save the file to the public path
                    $imageFile->move(public_path('variant-images'), $imageName);

                    VarientImage::create([
                        'varient_id' => $variant->id,
                        'image_url' =>  'variant-images/' . $imageName,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('product.index')->with('success', 'Product and variants created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollBack();

            return redirect()->route('product.index')->with('error', 'Product creation failed.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $data = Product::find($id);
        $product = Product::with('stock', 'variants', 'variants.variantImages')->findOrFail($id);
        return view('backend.layout.product.edit', compact('product'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.color' => 'required|string|max:255',
            'variants.*.new_images.*' => 'image|max:5048', // Validate new variant images
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            // $product->title = $validated['title'];
            // $product->save();

            // Update product details
            $product->update([
                'title' => $validated['title'],
                'sub_title' => $validated['sub_title'],
                'price' => $validated['price'],
                'discount' => $validated['discount'],
                'description' => $validated['description'],
            ]);

            // Handle new product image
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('/product/image/'), $imageName);
                $product->image_url = '/product/image/' . $imageName;
                $product->save();
            }

            // Update variants
            foreach ($validated['variants'] as $variantId => $variantData) {
                $variant = Varient::find($variantId);

                if ($variant) {
                    $variant->update(['color' => $variantData['color']]);

                    // Handle new images for the variant
                    if (isset($variantData['new_images'])) {
                        foreach ($variantData['new_images'] as $imageFile) {
                            // Generate a unique filename
                        $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();

                        // Save the file to the public path
                        $imageFile->move(public_path('variant-images'), $imageName);

                            VarientImage::updateOrCreate([
                                'varient_id' => $variant->id,
                                'image_url' => 'variant-images/' . $imageName,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('product.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return back()->with('error', 'Error updating product.');
        }
    }


    public function deleteVariantImage($imageId)
    {
        $image = VarientImage::find($imageId);

        if ($image) {
            // Optionally delete the physical file
            if (file_exists(public_path($image->image_url))) {
                unlink(public_path($image->image_url));
            }

            // Delete the record from the database
            $image->delete();

            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = Product::find($id);

        if (!$data) {
            return response()->json(['t-success' => false, 'message' => 'Data not found.']);
        }
        $data->delete();
        return response()->json(['t-success' => true, 'message' => 'Deleted successfully.']);
    }


    /**
     * Toggle the status of a product between 'Active' and 'Inactive'.
     *
     * @param int $id The ID of the product to update.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success, message, and updated product data.
     */
    public function status($id)
    {
        $data = Product::where('id', $id)->first();
        if ($data->status == 'Active') {
            // If the current status is active, change it to inactive
            $data->status = 'Inactive';
            $data->save();

            // Return JSON response indicating success with message and updated data
            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data' => $data,
            ]);
        } else {
            // If the current status is inactive, change it to active
            $data->status = 'Active';
            $data->save();

            // Return JSON response indicating success with a message and updated data.
            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data' => $data,
            ]);
        }
    }
}
