<?php

declare(strict_types=1);

namespace App\Widgets;

/**
 * Bridges the original widget API to the page builder's WidgetInterface.
 * This keeps saved widget identifiers compatible while exposing consistent
 * metadata and field definitions to the visual builder.
 */
trait LegacyWidgetAdapter
{
    public function getId(): string
    {
        return $this->identifier();
    }

    public function getName(): string
    {
        return $this->label();
    }

    public function getIcon(): string
    {
        return '🧩';
    }

    public function getFields(): array
    {
        $fields = $this->config()['fields'] ?? [];

        return collect($fields)->map(function (array $field, string $name): array {
            $type = $field['type'] ?? 'text';
            $normalized = [
                'name' => $name,
                'type' => $type === 'gallery' ? 'repeater' : $type,
                'label' => $field['label'] ?? str($name)->headline()->toString(),
            ];

            if ($type === 'gallery') {
                $normalized['fields'] = [
                    ['name' => 'url', 'type' => 'image', 'label' => 'Fallback image URL'],
                    ['name' => 'webp', 'type' => 'image', 'label' => 'WebP image URL'],
                    ['name' => 'avif', 'type' => 'image', 'label' => 'AVIF image URL'],
                    ['name' => 'srcset', 'type' => 'text', 'label' => 'Responsive srcset'],
                    ['name' => 'alt', 'type' => 'text', 'label' => 'Descriptive alternative text'],
                ];
            }

            foreach (['default', 'required', 'options'] as $option) {
                if (array_key_exists($option, $field)) {
                    $normalized[$option] = $field[$option];
                }
            }

            if (isset($field['fields']) && is_array($field['fields'])) {
                $normalized['fields'] = collect($field['fields'])
                    ->map(fn (array $child, string $childName): array => [
                        'name' => $childName,
                        'type' => $child['type'] ?? 'text',
                        'label' => $child['label'] ?? str($childName)->headline()->toString(),
                    ])
                    ->values()
                    ->all();
            }

            return $normalized;
        })->values()->all();
    }
}
