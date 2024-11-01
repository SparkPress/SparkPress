<?php

namespace App\Services;

class StyleService
{
    protected array $tabs = [
        "layout" => [ "title" => "Layout", "blocks" => [] ],
        "typography" => [ "title" => "Typography", "blocks" => [] ],
        "background" => [ "title" => "Background", "blocks" => [] ],
    ];

    protected array $sections = [
        "spacing" => [ "title" => "SPACING", "tab" => "layout", "blocks" => [] ],
        "sizing" => [ "title" => "SIZING", "tab" => "layout", "blocks" => [] ],
        "positioning" => [ "title" => "POSITIONING", "tab" => "layout", "blocks" => [] ],
    ];

    protected array $fields = [
        "margin" => [ "title" => "Margin", "section" => "spacing", "type" => "space", "blocks" => [] ],
        "padding" => [ "title" => "Padding", "section" => "spacing", "type" => "space", "blocks" => [] ],
        "width" => [ "title" => "Width", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "height" => [ "title" => "Height", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "min_width" => [ "title" => "Min Width", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "min_height" => [ "title" => "Min Height", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "max_width" => [ "title" => "Max Width", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "max_height" => [ "title" => "Max Height", "section" => "sizing", "type" => "size", "blocks" => [] ],
        "position" => [ "title" => "Position", "section" => "positioning", "type" => "select", "options" => [ "static", "relative", "absolute", "fixed" ], "default" => "static", "blocks" => [] ],
        "top" => [ "title" => "Top", "section" => "positioning", "type" => "number", "blocks" => [] ],
        "right" => [ "title" => "Right", "section" => "positioning", "type" => "number", "blocks" => [] ],
        "bottom" => [ "title" => "Bottom", "section" => "positioning", "type" => "number", "blocks" => [] ],
        "left" => [ "title" => "Left", "section" => "positioning", "type" => "number", "blocks" => [] ],
        "z_index" => [ "title" => "Z-Index", "section" => "positioning", "type" => "number", "default" => 0, "blocks" => [] ],
    ];

    public function getTabs(string $block = ''): array {
        return array_filter($this->tabs, function($tab) use ($block) {
            return empty($tab['blocks']) || in_array($block, $tab['blocks']);
        });
    }

    public function getSections(string $block = ''): array {
        return array_filter($this->sections, function($section) use ($block) {
            return empty($section['blocks']) || in_array($block, $section['blocks']);
        });
    }

    public function getFields(string $block = ''): array {
        return array_filter($this->fields, function($field) use ($block) {
            return empty($field['blocks']) || in_array($block, $field['blocks']);
        });
    }

    public function getStyle(string $block): array {
        $data = [];

        foreach ($this->getTabs($block) as $tabKey => $tabData) {
            $data[$tabKey] = $tabData + ["sections" => []];
        }

        // Assign sections to their respective tabs
        foreach ($this->getSections($block) as $sectionKey => $sectionData) {
            $tabKey = $sectionData['tab'];

            if (isset($data[$tabKey])) {
                $data[$tabKey]["sections"][$sectionKey] = $sectionData + ["fields" => []];
            }
        }

        // Assign fields to their respective sections
        foreach ($this->getFields($block) as $fieldKey => $fieldData) {
            $sectionKey = $fieldData['section'];

            foreach ($data as $tabKey => $tabData) {
                if (isset($tabData["sections"][$sectionKey])) {
                    $data[$tabKey]["sections"][$sectionKey]["fields"][$fieldKey] = $fieldData;
                }
            }
        }

        return $data;
    }

    public function addTab(string $key, string $title, array $blocks = []): void
    {
        $this->tabs[$key] = ["title" => $title, "blocks" => $blocks];
    }

    public function addSection(string $key, string $title, string $tab, array $blocks = []): void
    {
        $this->sections[$key] = [
            "title" => $title,
            "tab" => $tab,
            "blocks" => $blocks
        ];
    }

    public function addField(string $key, string $title, string $section,
                             string $type, array $options = [], mixed $default = null,
                             array $blocks = []
    ): void
    {
        $this->fields[$key] = [
            "title" => $title,
            "section" => $section,
            "type" => $type,
            "options" => $options,
            "default" => $default,
            "blocks" => $blocks
        ];
    }
}
