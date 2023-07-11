<?php

namespace App\InertiaTable;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Filter implements Arrayable
{
    public function __construct(
        public string $key,
        public string $label,
        public array $options,
        public ?string $value = null,
        public bool $noFilterOption,
        public string $noFilterOptionLabel,
        public string $type
    ) {
    }

    public function toArray()
    {
        $options = $this->options;

        if ($this->noFilterOption) {
            $options = Arr::prepend($options, $this->noFilterOptionLabel, '');
        }

        return [
            'key'     => $this->key,
            'label'   => $this->label,
            'options' => $options,
            'value'   => $this->value,
            'type'    => $this->type,
        ];
    }
}
