<?php

namespace GuidoCella\Multilingual;

class ValidationTest extends MultilingualTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (new ServiceProvider(app()))->boot();
    }

    public function testFailsWithoutArray()
    {
        $this->assertTrue(validator(
            ['name' => 'This is not cool'],
            ['name' => 'translatable_required']
        )->messages()->has('name'));
    }

    public function testFailsWithMissingTranslations()
    {
        $this->assertTrue(validator(
            ['name' => ['en' => 'One']],
            ['name' => 'translatable_required']
        )->messages()->has('name'));
    }

    public function testFailsWithEmptyTranslations()
    {
        $this->assertTrue(validator(
            ['name' => ['en' => 'One', 'es' => '']],
            ['name' => 'translatable_required']
        )->messages()->has('name'));
    }

    public function testPassesWithAllTranslations()
    {
        $this->assertTrue(validator(
            ['name' => ['en' => 'One', 'es' => 'Uno', 'it' => 'Uno']],
            ['name' => 'translatable_required']
        )->passes());
    }
}
