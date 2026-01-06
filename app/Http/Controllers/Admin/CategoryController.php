<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('admin.category.index', compact('categories'));
    }

     /**
     * Display a listing of the trashed categories.
     *
     * @return \Illuminate\Http\Response
     */

     public function trashed()
    {
        $categories = Category::onlyTrashed()->with('parent')->get();
        return view('admin.category.trashed', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); // Only parent categories
        return view('admin.category.create', compact('categories'));
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
            'description' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
        ]);

          return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $cat = Category::find($id);
        // return $cat;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function edit($id)
{
    $category = Category::findOrFail($id);
    $categories = Category::where('id', '!=', $id)->get(); // Exclude self from parent list
    return view('admin.category.update', compact('category', 'categories'));
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
{
    $category = Category::findOrFail($id); // ✅ Fix: fetch category

    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    $category->update([
        'name' => $request->name,
        'slug' => $request->slug ?? Str::slug($request->name),
        'description' => $request->description,
        'parent_id' => $request->parent_id,
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
}

    /**
     * Soft delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');

      
    }

     /**
     * Restore the specified category in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->back()->with('success', 'Category restored successfully.');
    }

     /**
     * Permanently delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()->back()->with('success', 'Category permanently deleted.');
    }
}
