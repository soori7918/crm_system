<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view category');

        if (request('search')) {
            $categories = Category::where('name', 'LIKE', '%' . request('search') . '%');
            $categories = $categories->paginate();
            $categories->appends(request()->query());
        } else {
            $list = [];
            getProductCategoriesList($list);
            $list = collect($list);
            $categories = paginate($list);
            $categories->appends(request()->query());
            $categories->withPath(request()->url());
        }
        return view('panel.inventory.categories.index')->with([
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return  view('panel.inventory.categories.create')->with([
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

        $this->authorize('create category');
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|numeric',
            'parent_id' => 'nullable|exists:categories,id',

        ], [], [
            'name' => 'عنوان ',
            'order' => 'ترتیب نمایش ',
            'parent_id' => 'دسته بندی',
        ]);

        Category::create([
            'name' => $request->name,
            'order' => $request->order,
            'parent_id' => $request->parent_id,
        ]);

        return \redirect()->route('panel.inventory.categories.index')->with([
            'success' => 'دسته بندی جدید با موفقیت ثبت شد'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $this->authorize('view category');
        return view('panel.inventory.category.show')->with([
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
       
        $this->authorize('edit category');
        $categories = Category::all();
        return response([
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('edit category');
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|numeric',
            'parent_id' => 'nullable|exists:categories,id',
        ], [], [
            'name' => 'عنوان ',
            'order' => 'ترتیب نمایش',
            'parent_id' => 'دسته بندی',
        ]);


        if ($category->parent_id != $request->parent_id) {
            if ($request->parent_id) {
                if ($request->parent_id == $category->id) {
                    return back()->withInput()->withErrors(['parent' => 'دسته بندی والد انتخاب شده نامعتبر است']);
                }
                $parent = Category::find($request->parent_id);
                if ($parent->hasParent($category->id)) {
                    return back()->withInput()->withErrors(['parent' => 'دسته بندی والد انتخاب شده نامعتبر است']);
                }
            }
        }


        $category->update([
            'name' => $request->name,
            'order' => $request->order,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('panel.inventory.categories.index')->with([
            'success' => 'دسته بندی مورد نظر با موفقیت ویرایش شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete category');
        if ($category->parent_id == null) {
            $category->delete();
            return redirect()->back()->with([
                'danger' => 'دسته بندی مورد نظر با موفقیت حذف شد'
            ]);
        } else {
            return redirect()->back()->with([
                'danger' => 'دسته بندی مورد نظر دارای دسته بندی دیگر است  ابتدا دسته های دیگر را حذف کنید'
            ]);
        }
    }

    public function editData(Request $request)
    {
        return $request->all();
    }
}
