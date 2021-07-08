<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use Exception;
use JsonException;

abstract class CreateTranslationProcessorTestCase extends TranslationProcessorTestCase
{
    /**
     * Default test case
     *
     * @return void
     * @throws JsonException
     * @throws Exception
     */
    public function testItCanCreateTranslationsForExistingEntity(): void
    {
        $this->execute(true, false);
        $this->assertTranslationsExists();
    }

    /**
     * Default test case
     *
     * @return void
     * @throws Exception
     */
    public function testItCanCreateTranslationsForDefunctEntity(): void
    {
        $this->execute(false, false);
        $this->assertTranslationsExists();
    }

    /**
     * Default test case
     *
     * @return void
     * @throws Exception
     */
    public function testItCannotUpdateTranslationIfExists(): void
    {
        $this->execute(true, true);
        $this->assertTranslationsMissing();
    }
}
