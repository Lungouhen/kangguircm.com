<?php

declare(strict_types=1);
namespace App\Http\Controllers;
use App\Models\ContentEntry;
use Illuminate\View\View;
class ContentEntryController extends Controller
{
 public function show(string $type,string $slug): View{$entry=ContentEntry::published()->where('type',$type)->where('slug',$slug)->firstOrFail();return view('public.content-entry',compact('entry'));}
}
