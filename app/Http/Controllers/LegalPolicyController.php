<?php

declare(strict_types=1);
namespace App\Http\Controllers;
use App\Models\LegalPolicy;
use Illuminate\View\View;
class LegalPolicyController extends Controller
{
 public function show(string $slug): View{$policy=LegalPolicy::where('slug',$slug)->where('is_published',true)->whereDate('effective_at','<=',today())->firstOrFail();return view('public.legal',compact('policy'));}
}
