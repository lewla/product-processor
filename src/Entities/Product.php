<?php

namespace Lewla\ProductProcessor\Entities;

class Product {
    public string $make;
    public string $model;
    public ?string $colour;
    public ?string $capacity;
    public ?string $network;
    public ?string $grade;
    public ?string $condition;

    public int $count = 0;

    public function generateHash(): string {
        return md5(
            $this->make .
            $this->model .
            $this->colour .
            $this->capacity .
            $this->network .
            $this->grade .
            $this->condition
        );
    }

    public function incrementCount(): void {
        $this->count++;
    }
}
