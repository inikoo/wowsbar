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
        $this->html = $html[0];

        $doc = new \DOMDocument();
        $doc->encoding = 'utf-8';
        @$doc->loadHTML(mb_convert_encoding($this->html['html'], 'HTML-ENTITIES', 'UTF-8'));

        $blocks = $this->getBlocks($doc);

        return [
            'css' => $html[0]['css'],
            'js' => $html[0]['js'],
            'blocks' => $blocks
        ];
    }


    public function getBlocks(&$parentNode)
    {
        $childNodeList = $parentNode->getElementsByTagName('section');
        $blocks = [];

        for ($i = 0; $i < $childNodeList->length; $i++) {
            $childNode = $childNodeList->item($i);
            $classes = explode(' ', $childNode->getAttribute('class'));

            if (in_array('wowsbar-block', $classes)) {
                $blocks[] = [
                    'id' => $childNode->getAttribute('data-id'),
                    'group' => $childNode->getAttribute('data-group'),
                    'type' => $childNode->getAttribute('data-type'),
                    'content' => match ($childNode->getAttribute('data-type')) {
                        'html' => $this->extractHtml($childNode),
                        'appointment' => $this->extractAppointment($childNode),
                        default => null
                    }
                ];
            }
        }


        return $blocks;
    }

    public function extractHtml($node): string
    {
        $blocks = '';
        $children = $node->childNodes;

        foreach ($children as $child) {
            $blocks .= $this->convertToHTML($child);
        }

//        dd($blocks);

        return $blocks;
    }

    public function extractAppointment($node): array
    {
        $childNodeList = $node->getElementsByTagName('section');
        $subBlocks = [];

        for ($i = 0; $i < $childNodeList->length; $i++) {
            $childNode = $childNodeList->item($i);
            $classes = explode(' ', $childNode->getAttribute('class'));

            if (in_array('wowsbar-appointment-block', $classes)) {
                $subBlocks[$childNode->getAttribute('data-id')] = $this->extractHtml($childNode);
            }
        }

        return $subBlocks;
    }

    public function getElementsByClass(&$parentNode, $tagName, $className): array
    {
        $childNodes = [];
        $childrenNodes = [];

        $childNodeList = $parentNode->getElementsByTagName($tagName);
        for ($i = 0; $i < $childNodeList->length; $i++) {
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
        return $child->ownerDocument->saveHTML($child);
    }
}
