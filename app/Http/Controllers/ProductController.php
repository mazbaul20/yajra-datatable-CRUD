<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        if($request->ajax()){
            $data = Product::all();
            return DataTables::of($data)
            ->addColumn('checkbox',function($row){
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('products.edit', $row->id) . '" class="btn btn-primary">Edit</a>
                        <form action="' . route('products.destroy', $row->id) . '" method="POST" class="delete-form" style="display:inline-block;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <input type="hidden" id="deleteId" name="deleteId" value="' . $row->id . '"/>
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>';
            })
            ->rawColumns(['action','checkbox'])
            ->make(true);
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric'
        ]);
        Product::insert([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'price'=>$request->input('price'),
        ]);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id)
    {
        $product = Product::find($id);
        return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric'
        ]);
        Product::where('id',$id)->update([
            'name'=>$request->input('name'),
            'description'=>$request->input('description'),
            'price'=>$request->input('price'),
        ]);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
