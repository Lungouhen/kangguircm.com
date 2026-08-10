<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LegalPolicy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class LegalPolicyController extends Controller
{
 public function index(): View{return view('admin.legal.index',['policies'=>LegalPolicy::latest()->get()]);}
 public function store(Request $r): RedirectResponse{$d=$this->data($r);LegalPolicy::create($d);return back()->with('success','Policy created.');}
 public function edit(LegalPolicy $policy): View{return view('admin.legal.edit',compact('policy'));}
 public function update(Request $r,LegalPolicy $policy): RedirectResponse{$policy->update($this->data($r,$policy));return back()->with('success','Policy updated.');}
 public function destroy(LegalPolicy $policy): RedirectResponse{$policy->delete();return back()->with('success','Policy archived.');}
 private function data(Request $r,?LegalPolicy $policy=null): array{$r->merge(['slug'=>Str::slug($r->input('slug')?:$r->input('title','')),'is_published'=>$r->boolean('is_published'),'show_in_footer'=>$r->boolean('show_in_footer')]);return $r->validate(['type'=>['required',Rule::in(LegalPolicy::TYPES)],'title'=>['required','string','max:180'],'slug'=>['required','string','max:180',Rule::unique('legal_policies')->ignore($policy)],'version'=>['required','string','max:40'],'effective_at'=>['required','date'],'content'=>['required','string','max:200000'],'is_published'=>['boolean'],'show_in_footer'=>['boolean']]);}
}
