<?php

namespace App\Http\Controllers\Api;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;

use function Pest\Laravel\json;

class StripePaymentController extends Controller
{


    // $user = auth()->user();

    public function checkoutSuccess(Request $request)
    {

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $user =auth()->user();
            // dd("User with id: " . $user->id . " and name: " . $user->name . " is logged in");

            // saving the session
            $session = $stripe->checkout->sessions->retrieve($request->token);
            // checking all is present
            if (!empty($session) && $session->payment_status == 'paid') {
                DB::beginTransaction();

                $payment = Payment::where('id', $request->order)->first();
                $payment->status = 'succeeded';
                $payment->save();
                // Order Create;

                $order = Order::create([
                    'user_id' => $payment->user_id,
                    'payment_id' => $payment->id,
                    'products' => $payment->product_id,
                    'order_number' => "ORD-" . rand(1000, 9999),
                    'status' => 'pending',
                    'total_amount' => $payment->amount,
                    'receiver_name' => $payment->user->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Cart::where('user_id', $payment->user_id)->delete();



                DB::commit();

                // Use the route from the .env file
                $FRONTEND_ROUTE_URL = env('FRONTEND_ROUTE_URL', '');

                if (empty($FRONTEND_ROUTE_URL)) {
                    $FRONTEND_ROUTE_URL = route('home');
                }

                return redirect($FRONTEND_ROUTE_URL)->with('t-success', 'Purchase completed');
            } else {
                // Use the route from the .env file
                $FRONTEND_ROUTE_URL = env('FRONTEND_ROUTE_URL', '');

                if (empty($FRONTEND_ROUTE_URL)) {
                    $FRONTEND_ROUTE_URL = route('home');
                }

                return redirect($FRONTEND_ROUTE_URL)->with('t-error', 'Transaction Failed...!');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            // Use the route from the .env file
            $FRONTEND_ROUTE_URL = env('FRONTEND_ROUTE_URL', '');

            if (empty($FRONTEND_ROUTE_URL)) {
                $FRONTEND_ROUTE_URL = route('home');
            }

            return redirect($FRONTEND_ROUTE_URL)->with('t-error', 'Order completion failed: ' . $e->getMessage());
        }
    }


    public function checkoutCancel()
    {
        $FRONTEND_ROUTE_URL = env('FRONTEND_ROUTE_URL', '');

        if (empty($FRONTEND_ROUTE_URL)) {
            $FRONTEND_ROUTE_URL = route('home');
        }

        return redirect($FRONTEND_ROUTE_URL)->with('t-error', 'Transaction Failed...!');
    }
}
