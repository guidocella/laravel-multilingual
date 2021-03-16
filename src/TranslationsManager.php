<?php

namespace GuidoCella\Multilingual;

class TranslationsManager
{
    protected ?array $translations;

    public function __construct(?array $translations)
    {
        $this->translations = $translations;
    }

    public function __get(string $key): ?string
    {
        return $this->translations[$key] ?? null;
    }

    public function toArray(): array
    {
        return $this->translations;
    }
}
