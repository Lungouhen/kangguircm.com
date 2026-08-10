<?php

declare(strict_types=1);
namespace App\Http\Controllers;
use App\Models\ContentEntry;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
class SearchController extends Controller
{
 public function __invoke(Request $r): View{$d=$r->validate(['q'=>['required','string','min:2','max:100']]);$q=trim($d['q']);return view('public.search',['query'=>$q,'pages'=>Page::published()->where(fn($x)=>$x->where('title','like',"%{$q}%")->orWhere('meta_description','like',"%{$q}%"))->limit(10)->get(),'posts'=>Post::published()->where(fn($x)=>$x->where('title','like',"%{$q}%")->orWhere('excerpt','like',"%{$q}%"))->limit(10)->get(),'entries'=>ContentEntry::published()->where(fn($x)=>$x->where('title','like',"%{$q}%")->orWhere('summary','like',"%{$q}%"))->limit(20)->get()]);}
}
