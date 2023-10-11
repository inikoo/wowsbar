<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 27 Sep 2021 11:07:26 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 4.0
 */

namespace App\Actions\Helpers\Webpage;

use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class ExtractWebpage
{
    use AsAction;

    public array $html;

    public function handle($html): array
    {
        $blocks = [];
        $this->html = $html[0];

        $doc = new \DOMDocument('1.0', 'utf-8');
        @$doc->loadHTML($this->html['html']);

        // return $this->getElementsByClass($doc, 'section', 'wowsbar-block');

        return [
            'css' => $html[0]['css'],
            'js' => $html[0]['js'],
            'blocks' => $blocks
        ];
    }

    public function getElementsByClass(&$parentNode, $tagName, $className): array
    {
        $childNodes = [];
        $childrenNodes = [];

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
            $childNodes = [];
            $temp = $childNodeList->item($i);
            $class = $temp->getAttribute('class');

            if (stripos($class, $className) !== false) {
                $nodes = $temp;
                $children = $nodes->childNodes;
                foreach ($children as $child) {
                    if (Str::contains($class, 'wowsbar-with-sub-blocks')) {
                        $childSubNodeList = $child->getElementsByTagName($tagName);
                        for ($i = 0; $i < $childSubNodeList->length; $i++) {

                            $temp = $childSubNodeList->item($i);
                            $classesSub = $temp->getAttribute('class');

                            if (Str::contains($classesSub, ['wowsbar-sub-block-id-content', 'wowsbar-sub-block-id-title'])) {
                                $nodes = $temp;
                                $childrenSub = $nodes->childNodes;
                                foreach ($childrenSub as $childSub) {
                                    $childrenNodes[] = [
                                        'section' => $classesSub,
                                        'content' => $this->convertToHTML($childSub),
                                    ];
                                }
                            }
                        }
                    }

                    $childNodes[] = [
                        'section' => $class,
                        'content' => [
                            'html' => $this->convertToHTML($child),
                            'css' => $this->html['css'],
                            'js' => $this->html['js']
                        ],
                        'children' => $childrenNodes
                    ];
                }
            }
        }

        return $childNodes;
    }

    public function convertToHTML($child): string
    {
        return $child->ownerDocument->saveXML($child);
    }
}
