<?php

namespace App\JsonApi;

use Illuminate\Support\Collection;

class Document extends Collection
{
    // protected array $document;

    // public function __construct($data) {
    //     $this->document = $data;
    // }

    public static function type(string $type): Document
    {
        return new self([
            'data' => [
                'type' => $type
            ],
        ]);
    }

    public function id($id): Document
    {
        $this->items['data']['id'] = (string)$id;
        return $this;
    }

    public function attributes(array $attributes): Document
    {
        $this->items['data']['attributes'] = $attributes;
        return $this;
    }

    public function links(array $links): Document
    {
        $this->items['data']['links'] = $links;
        return $this;
    }

    // public function toArray() {
    //     return $this->items;
    // }
}
