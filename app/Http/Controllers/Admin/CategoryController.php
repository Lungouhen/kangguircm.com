<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View { return view('admin.categories.index', ['categories' => Category::withCount('posts')->with('parent')->orderBy('name')->get()]); }
    public function store(Request $request): RedirectResponse
    {
        $data=$this->validated($request); Category::create($data); return back()->with('success','Category created.');
    }
    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request,$category)); return back()->with('success','Category updated.');
    }
    public function destroy(Category $category): RedirectResponse
    {
        abort_if($category->posts()->exists(),422,'Move posts before deleting this category.');
        $category->delete(); return back()->with('success','Category deleted.');
    }
    private function validated(Request $request, ?Category $category=null): array
    {
        $request->merge(['slug'=>Str::slug($request->input('slug')?:$request->input('name',''))]);
        return $request->validate([
            'name'=>['required','string','max:120'],
            'slug'=>['required','string','max:160',Rule::unique('categories')->ignore($category)],
            'description'=>['nullable','string','max:1000'],
            'parent_id'=>['nullable',Rule::exists('categories','id'),Rule::notIn(array_filter([$category?->id]))],
        ]);
    }
}
