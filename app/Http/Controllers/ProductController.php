<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // get data products
        $products = DB::table('products')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%'.$name.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('pages.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product succesfully created');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('pages.products.edit', compact('product'));
    }

    // update product
    public function update(UpdateProductRequest $request, $id)
    {
        // dd($request->all());
        $data = $request->all();
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = $filename;
        } else {
            // keep the old image if no new image is uploaded
            $data['image'] = $product->image;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', "{$product->name} succesfully updated");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', "{$product->name} succesfully deleted");
    }
}
