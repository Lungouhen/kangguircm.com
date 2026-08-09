<?php

namespace App\Widgets;

interface WidgetInterface
{
    /**
     * Unique identifier for the widget
     */
    public function getId(): string;

    /**
     * Display name shown in the builder
     */
    public function getName(): string;

    /**
     * Icon emoji or class for the builder UI
     */
    public function getIcon(): string;

    /**
     * Define configuration fields for the widget
     * Returns array of ['name', 'type', 'label', 'default', 'required']
     */
    public function getFields(): array;

    /**
     * Render the widget HTML with provided data
     */
    public function render(array $data = []): string;
}
