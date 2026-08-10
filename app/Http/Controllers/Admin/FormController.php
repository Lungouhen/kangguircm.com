<?php

declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
class FormController extends Controller
{
 public function index(): View{return view('admin.forms.index',['forms'=>Form::withCount(['fields','submissions'])->latest()->paginate(20)]);}
 public function store(Request $r): RedirectResponse{$d=$r->validate(['name'=>['required','string','max:120'],'purpose'=>['required','in:general,contact,lead']]);$f=Form::create(['name'=>$d['name'],'slug'=>Str::slug($d['name']).'-'.Str::lower(Str::random(5)),'purpose'=>$d['purpose'],'create_lead'=>$d['purpose']==='lead','success_message'=>'Thank you. Your submission has been received.','consent_text'=>'I agree to be contacted. Do not include patient or protected health information.']);return redirect()->route('admin.forms.edit',$f);}
 public function edit(Form $form): View{return view('admin.forms.edit',['form'=>$form->load('fields')]);}
 public function update(Request $r,Form $form): RedirectResponse{$d=$r->validate(['name'=>['required','string','max:120'],'slug'=>['required','max:160',Rule::unique('forms')->ignore($form)],'purpose'=>['required','in:general,contact,lead'],'description'=>['nullable','string','max:1000'],'submit_label'=>['required','string','max:80'],'success_message'=>['required','string','max:500'],'consent_text'=>['nullable','string','max:1000'],'policy_version'=>['nullable','string','max:40'],'is_active'=>['nullable','boolean'],'consent_required'=>['nullable','boolean'],'create_lead'=>['nullable','boolean']]);$d['slug']=Str::slug($d['slug']);foreach(['is_active','consent_required','create_lead'] as $key)$d[$key]=$r->boolean($key);$form->update($d);return back()->with('success','Form settings updated.');}
 public function destroy(Form $form): RedirectResponse{$form->delete();return back()->with('success','Form moved to trash.');}
 public function storeField(Request $r,Form $form): RedirectResponse{$d=$this->fieldData($r);$d['sort_order']=((int)$form->fields()->max('sort_order'))+1;$form->fields()->create($d);return back()->with('success','Field added.');}
 public function updateField(Request $r,FormField $field): RedirectResponse{$field->update($this->fieldData($r,$field));return back()->with('success','Field updated.');}
 public function destroyField(FormField $field): RedirectResponse{$field->delete();return back()->with('success','Field removed.');}
 public function submissions(Form $form): View{return view('admin.forms.submissions',['form'=>$form,'submissions'=>$form->submissions()->latest()->paginate(30)]);}
 public function showSubmission(FormSubmission $submission): View{return view('admin.forms.submission',['submission'=>$submission->load('form.fields')]);}
 public function export(Form $form): StreamedResponse{return response()->streamDownload(function()use($form){$fields=$form->fields()->pluck('label','name');$out=fopen('php://output','wb');fputcsv($out,array_merge(['Submitted','Status'],array_values($fields->all())));$form->submissions()->oldest()->chunk(200,function($rows)use($out,$fields){foreach($rows as $row){$values=[];foreach($fields as $name=>$label){$value=$row->payload[$name]??'';$values[]=is_array($value)?implode(', ',$value):$value;}fputcsv($out,array_merge([$row->created_at->toIso8601String(),$row->status],$values));}});fclose($out);},$form->slug.'-submissions.csv',['Content-Type'=>'text/csv']);}
 public function updateSubmission(Request $r,FormSubmission $submission): RedirectResponse{$d=$r->validate(['status'=>['required','in:new,read,contacted,archived,spam']]);$submission->update($d);return back()->with('success','Submission updated.');}
 private function fieldData(Request $r,?FormField $field=null): array{$d=$r->validate(['name'=>['required','string','max:80','regex:/^[a-z][a-z0-9_]*$/',Rule::unique('form_fields')->where('form_id',$field?->form_id??$r->route('form')?->id)->ignore($field)],'label'=>['required','string','max:160'],'type'=>['required',Rule::in(FormField::TYPES)],'placeholder'=>['nullable','string','max:255'],'help_text'=>['nullable','string','max:500'],'options_text'=>['nullable','string','max:5000'],'is_required'=>['nullable','boolean'],'min_length'=>['nullable','integer','min:0','max:10000'],'max_length'=>['nullable','integer','min:1','max:10000'],'sort_order'=>['nullable','integer','min:0','max:1000'],'width'=>['required','integer','min:1','max:12']]);$d['is_required']=$r->boolean('is_required');$d['options']=array_values(array_filter(array_map('trim',preg_split('/\r?\n/',$d['options_text']??'')?:[])));unset($d['options_text']);return $d;}
}
