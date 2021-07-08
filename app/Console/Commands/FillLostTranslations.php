<?php

namespace App\Console\Commands;

use App\Models\Eloquent\Translation;
use DomainException;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FillLostTranslations extends Command
{
    /**
     * Collection of translation classes
     *
     * @var Collection
     */
    protected Collection $classes;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lost-translations:fill {class? : The direct classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for fill in the lost entity translations';

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->classes = new Collection();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $class = $this->argument('class');
        if (!$class) {
            $this->setClasses();
        } else {
            $this->classes->push($class);
        }

        foreach ($this->classes as $classname) {
            $this->processClass($classname);
        }

        return 0;
    }

    /**
     * Process all lost class translations
     *
     * @param string $class
     * @throws Exception
     * @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    protected function processClass(string $class): void
    {
        $translationClassname = self::resolveFullClassname($class);

        /** @var Translation $translation */
        $translation = new $translationClassname();
        $relatedModel = $translation->entity()->getRelated();
        $foreignKey = $relatedModel->translations()->getForeignKeyName();
        $query = $translation->whereNull($foreignKey)->latest();

        $now = now();
        $filled = 0;
        $skipped = 0;
        foreach ($query->cursor() as $item) {
            // If compound key set, we are searching for entity

            /** @var Translation $item */
            if (isset($item->compound_key)) {
                /** @var Model $entity */
                $entity = $relatedModel->where($item->compound_key)->first();
            }

            // If entity doesn't exist
            if (!isset($entity)) {
                // If it is unfilled more than one day, just delete it
                if ($item->created_at->diffInDays($now) >= 1) {
                    $item->delete();
                }

                $skipped++;
                continue;
            }

            $entity->setTranslation($item->lang, $item->column, $item->value);
            $item->delete();
            $filled++;
        }

        $this->line("Class [$translationClassname] processed. Filled: $filled. Skipped: $skipped");
    }

    /**
     * Check if class exists and resolve FQN
     *
     * @param string $class
     * @return string
     */
    private static function resolveFullClassname(string $class): string
    {
        if (Str::startsWith($class, 'App\\')) {
            $fqn = $class;
        } else {
            $fqn = "App\Models\Eloquent\\$class";
        }

        if (!class_exists($fqn)) {
            throw new DomainException("Class [$fqn] doesn't exist");
        }

        return $fqn;
    }

    /**
     * Scan directory of classes and filter for translations only
     *
     * @return void
     */
    private function setClasses(): void
    {
        $dir = app_path('Models/Eloquent');
        $files = collect(scandir($dir));

        $classes = $files
            ->filter(fn(string $fileName) => $fileName !== '.' && $fileName !== '..' && Str::contains($fileName, 'Translation') && $fileName !== 'Translation.php')
            ->map(fn(string $fileName) => Str::beforeLast($fileName, '.'));

        $this->classes
            ->push(...$classes)
            ->unique();
    }
}
