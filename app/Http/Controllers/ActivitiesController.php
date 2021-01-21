<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ActivitiesController extends Controller
{
    public function index()
    {
        return view('admin/activities');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:32', 'regex:/^[^(|\\]~@0-9!%^&*=};:?><’)]*$/'],
            'price' => 'required',
            'description' => 'required'
        ]);

        $products = new Product;
        $products->name = $request->input('name');
        $products->price = $request->input('price');
        $products->description = $request->input('description');
        $products->save();

        return redirect('admin/activities')->with('message', 'Activiteit gemaakt');
    }

    public function run(){
        $activiteiten = Product::latest()->where('index', null)->get();
        return view('activities', ['activiteiten' => $activiteiten]);
    }
}