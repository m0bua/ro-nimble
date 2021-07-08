<?php

namespace Tests\Unit\Traits\HasTranslations;

use App\Support\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HasTranslationsTest extends TestCase
{
    /**
     * @var Model|Dummy|DummyCustom|DummyBroken
     */
    protected Model $model;

    protected DummyTranslation $translation;

    /**
     * HasTranslationsTest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = new Dummy();
        $this->translation = new DummyTranslation();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase();
        parent::tearDown();
    }

    protected function setUpDatabase(): void
    {
        if (!Schema::hasTable('dummies')) {
            Schema::create('dummies', static function (Blueprint $table) {
                $table->id();
                $table->string('test_column');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('dummy_translations')) {
            Schema::create('dummy_translations', static function (Blueprint $table) {
                $table->id();
                $table->foreignId('dummy_id')->constrained()->onDelete('cascade');
                $table->string('lang', 3);
                $table->string('column');
                $table->string('value');
                $table->timestamps();

                $table->unique([
                    'dummy_id',
                    'lang',
                    'column',
                ]);
            });
        }
    }

    protected function dropDatabase()
    {
        Schema::dropIfExists('dummy_translations');
        Schema::dropIfExists('dummies');
    }

    private function setUpModel(): void
    {
        $this->model
            ->forceFill([
                'test_column' => 'test',
            ])
            ->save();
    }

    /**
     * @test
     */
    public function testItCanDefineRelationship()
    {
        $this->setUpModel();
        $this->assertInstanceOf(HasMany::class, $this->model->translations());
    }

    /**
     * @test
     */
    public function testItCanDefineCustomRelationship()
    {
        $this->model = new DummyCustom();

        $this->assertInstanceOf(HasMany::class, $this->model->translations());
    }

    /**
     * @test
     */
    public function testItCannotDefineRelationshipWithIncorrectNamespace()
    {
        $this->model = new DummyBroken();

        $this->assertNull($this->model->translations());
    }

    /**
     * @test
     */
    public function testItCanCreateSingleTranslation()
    {
        $this->setUpModel();
        $this->model->setTranslation(Language::RU, 'title', 'title_ru');

        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::RU,
            'column' => 'title',
            'value' => 'title_ru'
        ]);
    }

    /**
     * @test
     */
    public function testItCanCreateManyTranslations()
    {
        $this->setUpModel();
        $translations = [
            Language::RU => 'title_ru',
            Language::EN => 'title_en',
            Language::UK => 'title_uk',
        ];

        $this->model->setTranslations('title', $translations);

        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::RU,
            'column' => 'title',
            'value' => 'title_ru'
        ]);
        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::EN,
            'column' => 'title',
            'value' => 'title_en'
        ]);
        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::UK,
            'column' => 'title',
            'value' => 'title_uk'
        ]);
    }

    /**
     * @test
     */
    public function testItCanOverrideSingleTranslation()
    {
        $this->setUpModel();
        $this->model->setTranslation(Language::RU, 'title', 'title_ru');
        $this->model->setTranslation(Language::RU, 'title', 'title_ru_2');

        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::RU,
            'column' => 'title',
            'value' => 'title_ru_2'
        ]);
    }

    /**
     * @test
     */
    public function testItCanOverrideManyTranslations()
    {
        $this->setUpModel();
        $translations = [
            Language::RU => 'title_ru',
            Language::EN => 'title_en',
            Language::UK => 'title_uk',
        ];

        $translationsForOverride = [
            Language::RU => 'title_ru_2',
            Language::EN => 'title_en_2',
        ];

        $this->model->setTranslations('title', $translations);
        $this->model->setTranslations('title', $translationsForOverride);

        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::RU,
            'column' => 'title',
            'value' => 'title_ru_2'
        ]);
        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::EN,
            'column' => 'title',
            'value' => 'title_en_2'
        ]);
        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::UK,
            'column' => 'title',
            'value' => 'title_uk'
        ]);
    }

    /**
     * @test
     */
    public function testItWillReturnSingleTranslation()
    {
        $this->setUpModel();
        $this->model->setTranslation(Language::EN, 'title', 'title_en');

        $this->assertEquals('title_en', $this->model->getTranslation('title', Language::EN));
    }

    /**
     * @test
     */
    public function testItWillReturnManyTranslations()
    {
        $this->setUpModel();
        $translations = [
            Language::RU => 'title_ru',
            Language::EN => 'title_en',
            Language::UK => 'title_uk',
        ];

        $this->model->setTranslations('title', $translations);
        $result = $this->model->getTranslations('title');

        $this->assertEquals($translations, $result);
    }

    /**
     * @test
     */
    public function testItWillReturnNullForUndefinedSingleTranslation()
    {
        $this->setUpModel();

        $this->assertNull($this->model->getTranslation('title', Language::EN));
    }

    /**
     * @test
     */
    public function testItWillReturnEmptyCollectionForUndefinedTranslations()
    {
        $this->setUpModel();

        $this->assertEmpty($this->model->getTranslations('title'));
    }

    /**
     * @test
     */
    public function testItWillReturnBoolWhenCheckTranslation()
    {
        $this->setUpModel();
        $this->model->setTranslation(Language::EN, 'title', 'title_en');

        $this->assertTrue($this->model->hasTranslation('title', Language::EN));
        $this->assertFalse($this->model->hasTranslation('title', Language::RU));
    }

    /**
     * @test
     */
    public function testItCanSaveTranslationsViaMagicProperty()
    {
        $this->setUpModel();

        $this->model->title = [
            Language::UK => 'title_uk',
        ];

        $this->assertDatabaseHas('dummy_translations', [
            'dummy_id' => 1,
            'lang' => Language::UK,
            'column' => 'title',
            'value' => 'title_uk'
        ]);
    }

    /**
     * @test
     */
    public function testItCanSaveAndWillReturnPlainFieldViaMagicProperty()
    {
        $this->setUpModel();
        $this->model->test_column = 'test';

        $this->assertEquals('test', $this->model->test_column);
    }

    /**
     * @test
     */
    public function testItWillReturnTranslationsViaMagicProperty()
    {
        $this->setUpModel();

        $translations = [
            Language::UK => 'title_uk',
            Language::RU => 'title_ru',
            Language::EN => 'title_en',
        ];

        $this->model->title = $translations;

        $this->assertEquals($translations, $this->model->title);
    }
}
