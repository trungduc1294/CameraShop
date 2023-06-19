<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index()
    {
        $product = Product::paginate(10);
        $comments = Comment::all();
        $replies = Reply::orderBy('id', 'desc')->get();
        return view('home.userpage', compact('product', 'comments', 'replies'));
    }

    public function redirect()
    {
        $usertype = Auth::user()->usertype;

        if ($usertype == '1') {
            $total_products = Product::all()->count();
            $total_orders = Order::all()->count();
            $total_users = User::all()->count();
            $total_revenue = Order::all()->sum('price');
            $total_delivered = Order::where('delivery_status', 'delivered')->count();
            $total_processing = Order::where('delivery_status', 'processing')->count();

            return view('admin.home',
                compact('total_products', 'total_orders', 'total_users', 'total_revenue', 'total_delivered',
                    'total_processing'));
        } else {
            $product = Product::paginate(10);
            $comments = Comment::all();
            $replies = Reply::orderBy('id', 'desc')->get();
            return view('home.userpage', compact('product', 'comments', 'replies'));
        }
    }

    public function product_details($id)
    {
        $product = Product::find($id);
        return view('home.product_details', compact('product'));
    }

    public function add_cart(Request $request, $id)
    {
        if (Auth::id()) {
            $user = Auth::user();
            $product = Product::find($id);
            $product_exist_id = Cart::where('product_id', $id)->where('user_id', $user->id)->get('id')->first();

            if ($product_exist_id) {
                $cart = Cart::find($product_exist_id)->first();
                $cart->quantity = $cart->quantity + $request->quantity;

                if ($product->discount_price) {
                    $cart->price = $product->discount_price * $cart->quantity;
                } else {
                    $cart->price = $product->price * $cart->quantity;
                }

                $cart->save();
                Alert::success('Product Added successfully', 'We have added your product to cart');
                return redirect()->back();
            }
            else {
                $cart = new Cart();
                $cart->name = $user->name;
                $cart->email = $user->email;
                $cart->phone = $user->phone;
                $cart->address = $user->address;
                $cart->user_id = $user->id;

                $cart->product_title = $product->title;

                if ($product->discount_price) {
                    $cart->price = $product->discount_price * $request->quantity;
                } else {
                    $cart->price = $product->price * $request->quantity;
                }
                $cart->product_id = $product->id;
                $cart->image = $product->image;
                $cart->quantity = $request->quantity;
                $cart->save();
                Alert::success('Product Added successfully', 'We have added your product to cart');
                return redirect()->back();
            }

        } else {
            return redirect()->route('login');
        }
    }

    public function show_cart()
    {
        if (Auth::id()) {
            $cart = Cart::where('user_id', Auth::user()->id)->get();
            return view('home.show_cart', compact('cart'));
        } else {
            return redirect()->route('login');
        }

    }

    public function remove_cart($id)
    {
        $cart = Cart::find($id);
        $cart->delete();
        Alert::success('Product Removed successfully', 'We have removed your product from cart');
        return redirect()->back();
    }

    public function cash_order()
    {
        $data = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($data as $item) {
            $order = new Order();
            $order->name = $item->name;
            $order->email = $item->email;
            $order->phone = $item->phone;
            $order->address = $item->address;
            $order->user_id = $item->user_id;

            $order->product_title = $item->product_title;
            $order->price = $item->price;
            $order->quantity = $item->quantity;
            $order->image = $item->image;
            $order->product_id = $item->product_id;

            $order->payment_status = 'cash on delivery';
            $order->delivery_status = 'processing';

            $order->save();

            $cart_id = $item->id;
            $cart = Cart::find($cart_id);
            $cart->delete();
        }
        Alert::success('Order Placed successfully', 'We have received your order. We will contact with you soon.');
        return redirect()->back();
    }

    public function stripe($totalPrice)
    {
        return view('home.stripe', compact('totalPrice'));
    }

    public function stripePost(Request $request, $totalPrice)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create([
            "amount" => $totalPrice * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Thanks for payment."
        ]);

        $data = Cart::where('user_id', Auth::user()->id)->get();

        foreach ($data as $item) {
            $order = new Order();
            $order->name = $item->name;
            $order->email = $item->email;
            $order->phone = $item->phone;
            $order->address = $item->address;
            $order->user_id = $item->user_id;

            $order->product_title = $item->product_title;
            $order->price = $item->price;
            $order->quantity = $item->quantity;
            $order->image = $item->image;
            $order->product_id = $item->product_id;

            $order->payment_status = 'Paid';
            $order->delivery_status = 'processing';

            $order->save();

            $cart_id = $item->id;
            $cart = Cart::find($cart_id);
            $cart->delete();
        }

        Session::flash('success', 'Payment successful!');

        return back();
    }

    public function show_order()
    {
        if (Auth::id()) {
            $orders = Order::where('user_id', Auth::user()->id)->get();
            return view('home.show_order', compact('orders'));
        } else {
            return redirect()->route('login');
        }

    }

    public function cancel_order($id)
    {
        $order = Order::find($id);
        if ($order->delivery_status == 'delivered') {
            return redirect()->back()->with('message', 'You can not cancel this order.');
        } else {
            $order->delivery_status = 'canceled';
            $order->save();
            return redirect()->back()->with('message', 'Order canceled successfully.');
        }
    }

    public function add_comment(Request $request)
    {
        if (Auth::id()) {
            $comment = new Comment();
            $comment->user_id = Auth::user()->id;
            $comment->name = Auth::user()->name;
            $comment->comment = $request->comment;
            $comment->save();
            return redirect()->back();
        } else {
            return redirect()->route('login');
        }
    }

    public function add_reply(Request $request)
    {
        if (Auth::id()) {
            $reply = new Reply();
            $reply->user_id = Auth::user()->id;
            $reply->name = Auth::user()->name;
            $reply->reply = $request->reply;
            $reply->comment_id = $request->commentId;
            $reply->save();
            return redirect()->back();
        } else {
            return redirect()->route('login');
        }
    }

    public function product_search(Request $request)
    {
        $search = $request->search;
        $product = Product::where('title', 'LIKE', "%{$search}%")
            ->orWhere('catagory', 'LIKE', "%{$search}%")
            ->paginate(10);
        $comments = Comment::all();
        $replies = Reply::all();
        return view('home.userpage', compact('product', 'comments', 'replies'));
    }

    public function products()
    {
        $product = Product::paginate(10);
        $comments = Comment::all();
        $replies = Reply::orderBy('id', 'desc')->get();
        return view('home.all_products', compact('product', 'comments', 'replies'));
    }

    public function search_product(Request $request)
    {
        $search = $request->search;
        $product = Product::where('title', 'LIKE', "%{$search}%")
            ->orWhere('catagory', 'LIKE', "%{$search}%")
            ->paginate(10);
        $comments = Comment::all();
        $replies = Reply::all();
        return view('home.all_products', compact('product', 'comments', 'replies'));
    }
}
