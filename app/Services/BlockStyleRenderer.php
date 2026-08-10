<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Str;

class BlockStyleRenderer
{
    private const ANIMATIONS = ['none', 'fade-up', 'fade-down', 'fade-left', 'fade-right', 'zoom-in'];
    private const ALIGNMENTS = ['left', 'center', 'right'];

    public function wrap(string $html, array $block): string
    {
        $style = is_array($block['style'] ?? null) ? $block['style'] : [];
        $id = $this->anchor($style['anchor_id'] ?? null, (string) ($block['id'] ?? 'block'));
        $classes = ['cms-block'];
        if (!empty($style['hide_mobile'])) $classes[] = 'cms-hide-mobile';
        if (!empty($style['hide_tablet'])) $classes[] = 'cms-hide-tablet';
        if (!empty($style['hide_desktop'])) $classes[] = 'cms-hide-desktop';
        if (($animation = $style['animation'] ?? 'none') !== 'none' && in_array($animation, self::ANIMATIONS, true)) {
            $classes[] = 'cms-animate';
        }
        if ($custom = $this->customClasses($style['css_class'] ?? '')) {
            array_push($classes, ...$custom);
        }

        $css = [];
        foreach (['background_color' => 'background-color', 'text_color' => 'color'] as $key => $property) {
            if ($color = $this->color($style[$key] ?? null)) $css[] = "{$property}:{$color}";
        }
        foreach (['padding_top' => 'padding-top', 'padding_bottom' => 'padding-bottom'] as $key => $property) {
            $css[] = "{$property}:".$this->spacing($style[$key] ?? 0).'px';
        }
        if (in_array($style['text_align'] ?? '', self::ALIGNMENTS, true)) $css[] = 'text-align:'.$style['text_align'];

        $attributes = ' id="'.e($id).'" class="'.e(implode(' ', $classes)).'" style="'.e(implode(';', $css)).'"';
        if (($style['animation'] ?? 'none') !== 'none' && in_array($style['animation'], self::ANIMATIONS, true)) {
            $attributes .= ' data-aos="'.e($style['animation']).'"';
        }
        $innerClass = ($style['container'] ?? 'full') === 'boxed' ? 'cms-block__container' : 'cms-block__full';

        return "<div{$attributes}><div class=\"{$innerClass}\">{$html}</div></div>";
    }

    private function color(mixed $value): ?string
    {
        return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) ? $value : null;
    }

    private function spacing(mixed $value): int
    {
        return max(0, min(200, (int) $value));
    }

    private function anchor(mixed $value, string $fallback): string
    {
        $anchor = Str::slug((string) $value);
        return $anchor !== '' ? $anchor : Str::slug($fallback);
    }

    private function customClasses(mixed $value): array
    {
        if (!is_string($value)) return [];
        return array_values(array_filter(preg_split('/\s+/', trim($value)) ?: [], fn ($class) => preg_match('/^[a-zA-Z][a-zA-Z0-9_-]{0,49}$/', $class)));
    }
}
