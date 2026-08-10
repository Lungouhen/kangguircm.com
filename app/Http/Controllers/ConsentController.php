<?php

declare(strict_types=1);
namespace App\Http\Controllers;
use App\Models\LegalPolicy;
use App\Models\VisitorConsent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ConsentController extends Controller
{
 public function store(Request $r): JsonResponse
 {
  $d=$r->validate(['analytics'=>['required','boolean'],'marketing'=>['required','boolean'],'preferences'=>['required','boolean'],'action'=>['required','in:accept_all,reject_optional,customize']]);
  $privacy=LegalPolicy::where('type','privacy')->where('is_published',true)->latest('effective_at')->first();
  $hash=hash_hmac('sha256',implode('|',[$r->ip(),$r->userAgent(),now()->format('Y-m')]),(string)config('app.key'));
  VisitorConsent::create(['visitor_hash'=>$hash,'necessary'=>true,'analytics'=>$d['analytics'],'marketing'=>$d['marketing'],'preferences'=>$d['preferences'],'policy_version'=>$privacy?->version,'action'=>$d['action'],'consented_at'=>now()]);
  $value=json_encode(['analytics'=>$d['analytics'],'marketing'=>$d['marketing'],'preferences'=>$d['preferences'],'version'=>$privacy?->version]);
  return response()->json(['saved'=>true])->withCookie(cookie('cms_consent',$value,60*24*180,'/',null,$r->isSecure(),true,false,'lax'));
 }
}
