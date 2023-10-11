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

    public function handle($parentNode, $tagName, $className): array
    {
        return $this->getElementsByClass($parentNode, $tagName, $className);
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
                                    $childNodeList = $parentNode->getElementsByTagName($tagName);
                                }
                            }
                        }
                    }

                    $childNodes[] = [
                        'section' => $class,
                        'content' => $this->convertToHTML($child),
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
