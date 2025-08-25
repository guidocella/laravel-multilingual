<?php

namespace GuidoCella\Multilingual;

trait Translatable
{
    public function getAttribute($key)
    {
        if (in_array($key, $this->translatable ?? [])) {
            return $this->getValueOfCurrentLocaleForKey($key);
        }

        $translatableKey = str_replace('Translations', '', $key);
        if (in_array($translatableKey, $this->translatable ?? [])) {
            return new TranslationsManager(
                $this->getAttributeValue($translatableKey)
            );
        }

        return parent::getAttribute($key);
    }

    public function getValueOfCurrentLocaleForKey(string $key)
    {
        $translations = $this->getAttributeValue($key);

        return $translations[config('app.locale')]
            ?? $translations[config('multilingual.fallback_locale')] ?? null;
    }

    public function getCasts(): array
    {
        return array_merge(
            array_fill_keys($this->translatable ?? [], 'json:unicode'),
            parent::getCasts()
        );
    }
}
