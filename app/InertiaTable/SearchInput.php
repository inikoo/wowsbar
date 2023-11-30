<?php

namespace App\InertiaTable;

use Illuminate\Contracts\Support\Arrayable;

class SearchInput implements Arrayable
{
    public function __construct(
        public string $key,
        public string $label,
        public ?string $value = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'key'   => $this->key,
            'label' => $this->label,
            'value' => $this->value,
        ];
    }
}
