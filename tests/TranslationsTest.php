<?php

namespace GuidoCella\Multilingual;

use GuidoCella\Multilingual\Models\Planet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslationsTest extends MultilingualTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('planets', fn (Blueprint $table) => $table->string('name')->nullable());
    }

    public function testTranslatableAttributeIsValueOfCurrentLocale()
    {
        config(['app.locale' => 'es']);
        $this->assertSame('Mercurio', Planet::create([
            'name' => [
                'en' => 'Mercury',
                'es' => 'Mercurio',
            ],
        ])->name);
    }

    public function testTranslatableAttributeIsNullIfTranslationsAreMissing()
    {
        $this->assertNull(Planet::create()->name);
    }

    public function testTranslatableAttributeIsFallbackValueIfCurrentLocaleValueIsMissing()
    {
        config(['multilingual.fallback_locale' => 'es']);
        config(['app.locale' => 'ar']);
        $this->assertSame('Mercurio', Planet::create([
            'name' => [
                'en' => 'Mercury',
                'es' => 'Mercurio',
            ],
        ])->name);
    }

    public function testTranslatableAttributeIsFallbackValueIfCurrentLocaleValueIsNull()
    {
        config(['multilingual.fallback_locale' => 'es']);
        config(['app.locale' => 'en']);
        $this->assertSame('Mercurio', Planet::create([
            'name' => [
                'en' => null,
                'es' => 'Mercurio',
            ],
        ])->name);
    }

    public function testTranslatableAttributeIsNullIfCurrentAndFallbackLocaleValuesAreNull()
    {
        config(['multilingual.fallback_locale' => 'es']);
        config(['app.locale' => 'en']);
        $this->assertNull(Planet::create([
            'name' => [
                'en' => null,
                'es' => null,
                'it' => 'Mercurio',
            ],
        ])->name);
    }

    public function testArrayOfAllTranslations()
    {
        config(['app.locale' => 'en']);
        $planetNames = [
            'en' => 'Mercury',
            'es' => 'Mercurio',
        ];
        $this->assertSame($planetNames, Planet::create(['name' => $planetNames])->nameTranslations->toArray());
    }

    public function testValueOfSpecificLocale()
    {
        config(['app.locale' => 'en']);
        $this->assertSame('Mercurio', Planet::create(['name' => [
            'en' => 'Mercury',
            'es' => 'Mercurio',
        ]])->nameTranslations->es);
    }

    public function testValueOfSpecificLocaleIsNullIfTranslationIsMissing()
    {
        config(['app.locale' => 'en']);
        $this->assertNull(Planet::create(['name' => [
            'en' => 'Mercury',
            'es' => 'Mercurio',
        ]])->nameTranslations->it);
    }
}
