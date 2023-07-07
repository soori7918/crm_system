<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view products');

        $products = Product::orderBy('created_at', 'desc');

        if($request->search)
        {
            $products = $products->where('name' , 'like' , "%$request->search%");
        }

        $products = $products->paginate();
        $products->appends($request->query());

        return view('panel.inventory.products.index')->with([
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create product');

        $categories = Category::all();
        return view('panel.inventory.products.create')->with([
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            'rent_price' => 'required|numeric',
            "category_id"    => "nullable|array",
            "category_id.*"  => "nullable|exists:categories,id",
            'description' => 'nullable|max:3000',
            'image' => 'nullable|image|max:50000',
        ], [], [
            'name' => 'نام محصول',
            'sale_price' => 'قیمت محصول',
            'rent_price' => 'قیمت اجاره',
            'category_id' => 'دسته بندی',
            'description' => 'توضیحات',
            'image' => 'تصاویر',
        ]);


        $image = '';
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        if ($request->file('image')) {
            $image = $request->file('image')->store("images/products/$year/$month", 'local');
        }

        $product = Product::create([
            'name' => $request->name,
            'sale_price' => $request->sale_price,
            'rent_price' => $request->rent_price,
            'description' => $request->description,
            'image' => $image,
            'created_by' => Auth::id(),
        ]);
        $product->categories()->attach(
            $request->category_id
        );

        return redirect()->route('panel.inventory.products.index')->with([
            'success' => 'محصول شما با موفقیت ثبت شد'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->authorize('view products');

        return view('panel.inventory.products.show')->with([
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $this->authorize('edit product');

        $categories = Category::all();
        $selected_categories = $product->categories->pluck('id')->toArray();

        return view('panel.inventory.products.edit')->with([
            'product' => $product,
            'categories' => $categories,
            'selected_categories' => $selected_categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric',
            "category_id"    => "nullable|array",
            "category_id.*"  => "nullable|exists:categories,id",
            'description' => 'nullable|max:3000',
            'image' => 'nullable|image|max:50000',
        ], [], [
            'name' => 'نام محصول',
            'sale_price' => 'قیمت محصول',
            'rent_price' => 'قیمت اجاره',
            'category_id' => 'دسته بندی',
            'description' => 'توضیحات',
            'image' => 'تصاویر',
        ]);


        $image = $product->image;
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        if ($request->file('image')) {
            $image = $request->file('image')->store("images/products/$year/$month", 'local');
        }

        $product->update([
            'name' => $request->name,
            'sale_price' => $request->sale_price,
            'rent_price' => $request->rent_price,
            'description' => $request->description,
            'image' => $image,
            'updated_by' => Auth::id(),
        ]);
        $product->categories()->detach();
        $product->categories()->attach(
            $request->category_id
        );

        return redirect()->route('panel.inventory.products.index')->with([
            'success' => 'تغییرات شما با موفقیت ثبت شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete product');

        File::delete($product->image);
        $product->delete();
        return redirect()->route('panel.inventory.products.index')->with([
            'success' => 'حذف محصول با موفقیت انجام شد'
        ]);
    }

    public function getProduct(Request $request)
    {
        $product = Product::where('id' , $request->product_id)->first();
        return response($product , 200);
    }
}
