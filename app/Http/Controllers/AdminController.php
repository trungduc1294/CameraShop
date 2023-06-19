<?php

namespace App\Http\Controllers;

use App\Models\Catagory;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\SendEmailNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function view_catagory()
    {
        if (Auth::id()){
            $data = Catagory::all();
            return view('admin.catagory', compact('data'));
        }
        else{
            return redirect(url('/login'));
        }

    }

    public function add_catagory(Request $request)
    {
        if (Auth::id()){
            $data = new Catagory();
            $data->catagory_name = $request->catagory;
            $data->save();
            return redirect()->back()->with('message','Catagory Added Successfully');
        }
        else{
            return redirect(url('/login'));
        }

    }

    public function delete_catagory($id)
    {
        if (Auth::id()){
            $data = Catagory::find($id);
            $data->delete();
            return redirect()->back()->with('message','Catagory Deleted Successfully');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function view_product()
    {
        if (Auth::id()){
            $catagory = Catagory::all();
            return view('admin.product', compact('catagory'));
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function add_product(Request $request)
    {
        if (Auth::id()){
            $product = new Product();
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->quantity = $request->quantity;
            $product->catagory = $request->catagory;
            $image = $request->image;
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $request->image->move('product',$imagename);
            $product->image = $imagename;

            $product->save();
            return redirect()->back()->with('message','Product Added Successfully');
        }
        else{
            return redirect(url('/login'));
        }

    }

    public function show_product()
    {
        if (Auth::id()){
            $products = Product::all();
            return view('admin.show_product', compact('products'));
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function delete_product($id)
    {
        if (Auth::id()){
            $product = Product::find($id);
            $product->delete();
            return redirect()->back()->with('message','Product Deleted Successfully');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function update_product($id)
    {
        if (Auth::id()){
            $product = Product::find($id);
            $catagory = Catagory::all();
            return view('admin.update_product', compact('product', 'catagory'));
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function update_product_confirm(Request $request, $id)
    {
        if (Auth::id()){
            $product = Product::find($id);

            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->discount_price = $request->discount_price;
            $product->quantity = $request->quantity;
            $product->catagory = $request->catagory;
            $image = $request->image;
            if ($image){
                $imagename = time().'.'.$image->getClientOriginalExtension();
                $request->image->move('product',$imagename);
                $product->image = $imagename;
            }


            $product->save();
            return redirect(url('/show_product'))->with('message','Product Updated Successfully');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function order()
    {
        if (Auth::id()){
            $orders = Order::all();
            return view('admin.order', compact('orders'));
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function delivered($id)
    {
        if (Auth::id()){
            $order = Order::find($id);
            $order->delivery_status = 'delivered';
            $order->payment_status = 'paid';
            $order->save();
            return redirect()->back()->with('message','Order Delivered Successfully');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function print_pdf($id)
    {
        if (Auth::id()){
            $order = Order::find($id);
            $pdf=PDF::loadView('admin.pdf', compact('order'));
            return $pdf->download('order_details.pdf');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function send_email($id){
        if (Auth::id()){
            $order = Order::find($id);
            return view('admin.email_info', compact('order'));
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function send_user_email(Request $request, $id){
        if (Auth::id()){
            $order = Order::find($id);
            $details = [
                'greeting' => $request->greeting,
                'firstline' => $request->firstline,
                'body' => $request->body,
                'button' => $request->button,
                'url' => $request->url,
                'lastline' => $request->lastline,
            ];

            Notification::send($order, new SendEmailNotification($details));
            return redirect()->back()->with('message','Email Sent Successfully');
        }
        else{
            return redirect(url('/login'));
        }
    }

    public function search_data(Request $request){
        if (Auth::id()){
            $searchText = $request->search;
            $orders = Order::where('name', 'like', '%'.$searchText.'%')
                ->orWhere('phone', 'like', '%'.$searchText.'%')
                ->orWhere('product_title', 'like', '%'.$searchText.'%')
                ->get();
            return view('admin.order', compact('orders'));
        }
        else{
            return redirect(url('/login'));
        }
    }
}
