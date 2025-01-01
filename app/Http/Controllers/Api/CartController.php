<?php

namespace App\Http\Controllers\Api;



use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\CardException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\RateLimitException;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{

    //All Cart Iteam

    public function allCartIteam()
    {
        $user = Auth::user();
        $carts = $user->carts()->with('product')->get();
        return response()->json($carts);
    }
    // Add product to cart
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        // Fetch the product by ID
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found!'], 404);
        }

        // Check if the product is already in the user's cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // If the product is already in the cart, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // If the product is not in the cart, add a new cart item
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully!',

            'title' => $product->title,
            'sub_title' => $product->sub_title,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'discount' => $product->discount,
            'description' => $product->description,
            'rating' => $product->rating,
            'quantity' => $cartItem ? $cartItem->quantity : 0
        ]);
    }


    //Cart qty will be  plus 
    public function quantityUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();


        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Product not found in cart!'], 404);
        }


        $cartItem->quantity += $request->quantity;

        $cartItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item quantity updated successfully!',
            'cart_item' => $cartItem
        ]);
    }

    // //Cart qty will be minus
    public function quantityMinus(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Product not found in cart!'], 404);
        }

        if ($cartItem->quantity <= $request->quantity) {
            $cartItem->quantity = 0;
        } else {
            $cartItem->quantity -= $request->quantity;
        }


        $cartItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item quantity updated successfully!',
            'cart_item' => $cartItem
        ]);
    }

    //remove from cart
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['status' => 'error', 'message' => 'Product not found in cart!'], 404);
        }

        $product = $cartItem->product;

        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from cart successfully!',
            'product_details' => [
                'title' => $product->title,
                'sub_title' => $product->sub_title,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'discount' => $product->discount,
                'description' => $product->description,
                'rating' => $product->rating,
            ],
        ]);
    }



    //checkout
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $carts = $user->carts;
        
        $product_ids = [];

        if ($carts) {
            foreach ($carts as $cart) {
                $product_ids[] = $cart->product_id;
            }
        }


        // If no items in cart
        if (!$carts || $carts->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No items in cart.',
            ], 422);
        }

        $total = 0;
        $discount = 0;
        
        foreach ($carts as $cart) {
            $productPrice = $cart->product->price ?? 0; // Fallback to 0 if price is null
            $productDiscountPercent = $cart->product->discount ?? 0; // Fallback to 0 if discount is null
    
            // Log product details for debugging
            Log::info('Product Price: ' . $productPrice);
            Log::info('Product Discount Percentage: ' . $productDiscountPercent);
    
            // Calculate the discount amount as percentage of price
            $discountAmount = ($productPrice * $productDiscountPercent) / 100;
    
            // Ensure that the discount does not exceed the product price
            $discount += min($discountAmount, $productPrice) * $cart->quantity;
    
            // Add to total price
            $total += $productPrice * $cart->quantity;
        }

        // Log total and discount values
        Log::info('Total: ' . $total);
        Log::info('Discount: ' . $discount);

        // Apply discount to total
        $total -= $discount;

        // Log final total after applying discount
        Log::info('Total after discount: ' . $total);

        // Ensure the total is valid
        if ($total <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total amount is invalid.',
            ], 422);
        }

        try {
            // Set the Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Insert payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $total,
                'product_id' => json_encode($product_ids),
                'payment_method' => 'stripe',
                'status' => 'pending',
            ]);

            $redirectUrl = route('order.success') . '?token={CHECKOUT_SESSION_ID}&order=' . $payment->id;
            $cancleURL = route('order.cancel');

            // Create a Stripe Checkout session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $user->name,
                        ],
                        'unit_amount' => (int) round($total * 100), // Convert to cents and ensure integer
                    ],
                    'quantity' => $carts->count(),
                ]],
                'mode' => 'payment',
                'success_url' => $redirectUrl,
                'cancel_url' => $cancleURL,
            ]);
            



            // Return response with the client secret
            return response()->json([
                'status' => 'success',
                'message' => "Stripe Session created. Redirect to this url",
                'url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
