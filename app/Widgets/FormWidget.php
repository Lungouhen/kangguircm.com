<?php

declare(strict_types=1);
namespace App\Widgets;
use App\Models\Form;
class FormWidget implements WidgetInterface
{
 public function getId(): string{return 'form';} public function getName(): string{return 'No-Code Form';} public function getIcon(): string{return '📝';}
 public function getFields(): array{return[['name'=>'form_id','type'=>'number','label'=>'Form ID','required'=>true],['name'=>'title','type'=>'text','label'=>'Section title'],['name'=>'description','type'=>'textarea','label'=>'Introduction']];}
 public function render(array $data=[]): string{$form=Form::with('fields')->where('is_active',true)->find($data['form_id']??null);return $form?view('public.widgets.form',compact('form','data'))->render():'<!-- Form unavailable -->';}
}
