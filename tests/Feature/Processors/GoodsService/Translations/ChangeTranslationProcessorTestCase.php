<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use Exception;
use JsonException;

abstract class ChangeTranslationProcessorTestCase extends TranslationProcessorTestCase
{
    /**
     * It can update translations if entity exists with translations
     *
     * @return void
     * @throws JsonException
     * @throws Exception
     */
    public function testItCanUpdateTranslationsForExistingEntity(): void
    {
        $this->execute(true, true);
        $this->assertTranslationsExists();
    }

    /**
     * It can update translations if entity not exists, but translations exist
     *
     * @return void
     * @throws Exception
     */
    public function testItCanUpdateTranslationsForDefunctEntity(): void
    {
        $this->execute(false, true);
        $this->assertTranslationsExists();
    }

    /**
     * Default test case
     *
     * @return void
     * @throws Exception
     */
    public function testItCannotCreateTranslations(): void
    {
        $this->execute(true, false);
        $this->assertTranslationsMissing();
    }
}
