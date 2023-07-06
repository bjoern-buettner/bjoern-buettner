<?php

namespace Me\BjoernBuettner;

interface Cache
{
    public function get(string $key): ?string;
    public function set(string $key, string $value): void;
}