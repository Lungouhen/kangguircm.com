<?php

declare(strict_types=1);
namespace App\Widgets;
use App\Models\ContentEntry;
class ContentCollectionWidget implements WidgetInterface
{
 public function getId(): string{return 'content_collection';}public function getName(): string{return 'Structured Content Collection';}public function getIcon(): string{return '🗂️';}
 public function getFields(): array{return[['name'=>'title','type'=>'text','label'=>'Section title'],['name'=>'content_type','type'=>'select','label'=>'Content type','options'=>ContentEntry::TYPES],['name'=>'limit','type'=>'number','label'=>'Items','default'=>6]];}
 public function render(array $data=[]): string{$type=in_array($data['content_type']??'',ContentEntry::TYPES,true)?$data['content_type']:'service';$entries=ContentEntry::published()->where('type',$type)->orderBy('sort_order')->limit(max(1,min(24,(int)($data['limit']??6))))->get();return view('public.widgets.content-collection',compact('entries','type','data'))->render();}
}
