<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SeoRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class RedirectController extends Controller
{
 public function index(): View{return view('admin.redirects.index',['redirects'=>SeoRedirect::latest()->paginate(30)]);}
 public function store(Request $r): RedirectResponse{$d=$r->validate(['source_path'=>['required','string','max:500','starts_with:/','different:destination_path'],'destination_path'=>['required','string','max:500','starts_with:/'],'status_code'=>['required','in:301,302']]);SeoRedirect::updateOrCreate(['source_path'=>$d['source_path']],$d+['is_active'=>true]);return back()->with('success','Redirect saved.');}
 public function destroy(SeoRedirect $redirect): RedirectResponse{$redirect->delete();return back()->with('success','Redirect deleted.');}
}
