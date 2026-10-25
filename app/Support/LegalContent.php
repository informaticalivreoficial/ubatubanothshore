<?php

namespace App\Support;

use Illuminate\Support\Str;

class LegalContent
{
    public static function format(?string $html): array
    {
        $html = (string) $html;
        $sections = [];
        $usedIds = [];

        $html = preg_replace_callback(
            '/<p(?:\s[^>]*)?>\s*(\d+(?:\.\d+)*\.?)\s*<strong>(.*?)<\/strong>\s*<\/p>/is',
            function (array $matches) use (&$sections, &$usedIds) {
                $number = trim($matches[1]);
                $title = trim(strip_tags($matches[2]));
                $baseId = 'secao-'.Str::slug($number.'-'.$title);
                $id = $baseId;
                $suffix = 2;

                while (in_array($id, $usedIds, true)) {
                    $id = $baseId.'-'.$suffix;
                    $suffix++;
                }

                $usedIds[] = $id;
                $sections[] = [
                    'id' => $id,
                    'number' => rtrim($number, '.'),
                    'title' => $title,
                ];

                return '<h2 id="'.$id.'"><span>'.e($number).'</span>'.e($title).'</h2>';
            },
            $html
        );

        $html = preg_replace('/<p[^>]*>\s*(?:<br\s*\/?>|&nbsp;|\s)*<\/p>/i', '', $html);
        $html = preg_replace('/href="(www\.[^"]+)"/i', 'href="https://$1"', $html);

        return [
            'html' => $html,
            'sections' => $sections,
        ];
    }
}
