<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'level_number',
    'title',
    'desc',
    'expected_symbol',
    'slot_prompt',
    'slot_condition_text',
    'slot_text_inside',
    'hint_text',
    'success_title',
    'success_desc',
    'layout_type',
    'config_data',
    'order',
    'is_active',
])]
class Simulation extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level_number' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
            'config_data' => 'array',
        ];
    }

    /**
     * Get extracted rule text without HTML.
     */
    public function getHintRuleTextAttribute(): string
    {
        if (preg_match('/<strong[^>]*>.*?<\/strong>(.*?)<\/div>/is', $this->hint_text ?? '', $m)) {
            return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return '';
    }

    /**
     * Get extracted tip text without HTML.
     */
    public function getHintTipTextAttribute(): string
    {
        if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $this->hint_text ?? '', $m)) {
            return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        $withoutDiv = preg_replace('/<div[^>]*>.*?<\/div>/is', '', $this->hint_text ?? '');

        return trim(html_entity_decode(strip_tags((string) $withoutDiv), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
