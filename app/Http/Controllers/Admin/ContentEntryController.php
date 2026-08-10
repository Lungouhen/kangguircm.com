<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContentEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
class ContentEntryController extends Controller
{
 public function index(Request $r): View{return view('admin.content.index',['type'=>$r->input('type','service'),'entries'=>ContentEntry::where('type',$r->input('type','service'))->orderBy('sort_order')->latest()->paginate(25),'types'=>ContentEntry::TYPES]);}
 public function create(Request $r): View{return view('admin.content.edit',['entry'=>new ContentEntry(['type'=>$r->input('type','service')]),'types'=>ContentEntry::TYPES]);}
 public function store(Request $r): RedirectResponse{$e=ContentEntry::create($this->data($r));return redirect()->route('admin.content.edit',$e)->with('success','Entry created.');}
 public function edit(ContentEntry $entry): View{return view('admin.content.edit',compact('entry')+['types'=>ContentEntry::TYPES]);}
 public function update(Request $r,ContentEntry $entry): RedirectResponse{$entry->update($this->data($r,$entry));return back()->with('success','Entry updated.');}
 public function destroy(ContentEntry $entry): RedirectResponse{$entry->delete();return back()->with('success','Entry moved to trash.');}
 private function data(Request $r,?ContentEntry $entry=null): array{$r->merge(['slug'=>Str::slug($r->input('slug')?:$r->input('title',''))]);$d=$r->validate(['type'=>['required',Rule::in(ContentEntry::TYPES)],'title'=>['required','string','max:180'],'slug'=>['required','max:200',Rule::unique('content_entries')->ignore($entry)],'summary'=>['nullable','string','max:1000'],'body'=>['nullable','string','max:100000'],'image'=>['nullable','string','max:500'],'data_json'=>['nullable','json','max:50000'],'meta_title'=>['nullable','string','max:60'],'meta_description'=>['nullable','string','max:160'],'status'=>['required','in:draft,published'],'published_at'=>['nullable','date'],'sort_order'=>['nullable','integer','min:0','max:10000']]);$d['data']=json_decode($d['data_json']??'{}',true);unset($d['data_json']);if($d['status']==='published'&&empty($d['published_at']))$d['published_at']=now();return $d;}
}
