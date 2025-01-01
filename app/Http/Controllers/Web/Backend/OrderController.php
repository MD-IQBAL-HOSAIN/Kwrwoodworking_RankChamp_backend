<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();       

        return view('backend.layout.order.index', compact('orders'));
    }


    

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        // Return a JSON response for the AJAX request
        return response()->json(['message' => 'Order status updated successfully!']);
    }    
}
