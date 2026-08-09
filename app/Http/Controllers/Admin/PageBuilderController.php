<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\WidgetRegistry;
use Illuminate\Http\Request;

class PageBuilderController extends Controller
{
    protected WidgetRegistry $widgetRegistry;

    public function __construct(WidgetRegistry $widgetRegistry)
    {
        $this->widgetRegistry = $widgetRegistry;
    }

    /**
     * Show the visual page builder interface
     */
    public function edit(Page $page)
    {
        $widgets = $this->widgetRegistry->getAll();
        
        return view('admin.pages.builder', compact('page', 'widgets'));
    }

    /**
     * Save the page layout and blocks
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'blocks' => 'required|array'
        ]);

        // Save the JSON structure of blocks
        $page->update([
            'content' => json_encode($request->blocks)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Page layout saved successfully!'
        ]);
    }

    /**
     * Preview a widget with specific data
     */
    public function previewWidget(Request $request)
    {
        $widgetId = $request->input('widget_id');
        $data = $request->input('data', []);

        $html = $this->widgetRegistry->render($widgetId, $data);

        return response()->json(['html' => $html]);
    }
}
