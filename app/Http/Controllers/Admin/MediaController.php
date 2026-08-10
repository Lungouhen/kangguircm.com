<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', ['media' => Media::latest()->paginate(30)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,avif,gif,pdf', 'max:10240'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ]);
        $file = $data['file'];
        $name = Str::uuid().'.'.$file->guessExtension();
        $path = $file->storeAs('media/'.now()->format('Y/m'), $name, 'public');
        Media::create([
            'filename' => $name,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_path' => $path,
            'url' => Storage::disk('public')->url($path),
            'alt_text' => $data['alt_text'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Media uploaded.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        Storage::disk('public')->delete($medium->file_path);
        $medium->delete();
        return back()->with('success', 'Media deleted.');
    }
}
