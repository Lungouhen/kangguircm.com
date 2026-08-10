<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('admin.menus.index', [
            'menus' => Menu::with(['items.page', 'items.children.page'])->get(),
            'pages' => Page::query()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'location' => ['required', Rule::in(['header', 'footer'])],
        ]);
        Menu::query()->updateOrCreate(['location' => $data['location']], ['name' => $data['name'], 'is_active' => true]);

        return back()->with('success', 'Menu saved.');
    }

    public function storeItem(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['page', 'url'])],
            'page_id' => ['nullable', 'required_if:type,page', Rule::exists('pages', 'id')],
            'url' => ['nullable', 'required_if:type,url', 'string', 'max:500'],
            'target' => ['required', Rule::in(['_self', '_blank'])],
            'parent_id' => ['nullable', Rule::exists('menu_items', 'id')->where('menu_id', $menu->id)],
        ]);
        $data['sort_order'] = ((int) $menu->items()->max('sort_order')) + 1;
        $menu->items()->create($data);

        return back()->with('success', 'Menu item added.');
    }

    public function updateItem(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $menuItem->update($data);

        return back()->with('success', 'Menu item updated.');
    }

    public function destroyItem(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->delete();
        return back()->with('success', 'Menu item removed.');
    }
}
