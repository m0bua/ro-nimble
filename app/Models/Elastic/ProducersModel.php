<?php

namespace App\Models\Elastic;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class ProducersModel
 * @package App\Models\Elastic
 *
 * @property integer $id
 * @property integer $name
 * @property integer $title
 * @property integer $first_symbol
 * @property integer $status
 */
class ProducersModel extends Elastic
{
    public const PRODUCER_INDEX_FIRST = 'producer_index_1';
    public const PRODUCER_INDEX_SECOND = 'producer_index_2';

    protected ?int $id              = null;
    protected ?string $name         = null;
    protected ?string $title        = null;
    protected ?string $first_symbol = null;
    protected ?string $status       = null;

    /**
     * Свойство указывающее какой индекс будет создан
     * @var string
     */
    protected $creatingIndex;

    /**
     * Свойство указывающее какой индекс будет удален
     * @var string
     */
    protected $deletingIndex;

    /**
     * @return string
     */
    public function indexName(): string
    {
        return 'producers';
    }

    /**
     * @inheritDoc
     */
    public function requiredFields(): array
    {
        return ['id', 'name', 'title', 'first_symbol', 'status'];
    }

    /**
     * @param array $params
     * @return array|callable
     * @throws Exception
     */
    public function index(array $params = [])
    {
        return parent::index(
            array_merge(['id' => $this->getAttribute('id')], $params)
        );
    }

    /**
     * Структура индекса
     * @return array
     */
    public function getIndexStructure(): array
    {
        return [
            'body' => [
                'settings' => [
                    'max_ngram_diff' => 15,
                    'index' => [
                        'analysis' =>  [
                            'analyzer' =>  [
                                'edge_title_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'edge_title_tokenizer',
                                    'filter' => ['lowercase']
                                ],
                                'title_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'title_tokenizer',
                                    'filter' => ['lowercase']
                                ],
                                'search_title_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'whitespace',
                                    'filter' =>  ['lowercase']
                                ]
                            ],
                            'tokenizer' =>  [
                                'edge_title_tokenizer' => [
                                    'type' => 'edge_ngram',
                                    'min_gram' => 1,
                                    'max_gram' => 15
                                ],
                                'title_tokenizer' => [
                                    'type' => 'ngram',
                                    'min_gram' => 1,
                                    'max_gram' => 15
                                ]
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' =>  [
                        'id' =>  [
                            'type' => 'integer'
                        ],
                        'name' =>  [
                            'type' => 'keyword'
                        ],
                        'title' =>  [
                            'type' => 'text',
                            'analyzer' => 'title_analyzer',
                            'search_analyzer' => 'search_title_analyzer',
                            'fields' =>  [
                                'edge' =>  [
                                    'type' => 'text',
                                    'analyzer' => 'edge_title_analyzer',
                                    'search_analyzer' => 'search_title_analyzer'
                                ],
                                'keyword' =>  [
                                    'type' => 'keyword',
                                    'ignore_above' => 256
                                ]
                            ]
                        ],
                        'first_symbol' =>  [
                            'type' => 'keyword'
                        ],
                        'status' =>  [
                            'type' => 'keyword'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Устанавливает переменные для создания и удаления индексов
     */
    public function setIndexVars(): void
    {
        $isFirstIndexExist = $this->existIndex(self::PRODUCER_INDEX_FIRST);
        $isSecondIndexExist = $this->existIndex(self::PRODUCER_INDEX_SECOND);

        switch (true) {
            case $isFirstIndexExist && !$isSecondIndexExist:
                $this->creatingIndex = self::PRODUCER_INDEX_SECOND;
                $this->deletingIndex = self::PRODUCER_INDEX_FIRST;
                break;
            case !$isFirstIndexExist && $isSecondIndexExist:
                $this->creatingIndex = self::PRODUCER_INDEX_FIRST;
                $this->deletingIndex = self::PRODUCER_INDEX_SECOND;
                break;
            default:
                $this->creatingIndex = self::PRODUCER_INDEX_FIRST;
                $this->deletingIndex = self::PRODUCER_INDEX_SECOND;
        }

        if ($isFirstIndexExist && $isSecondIndexExist) {
            Log::error('exist all producers indexes');
        }
    }

    /**
     * Возвращает имя индекса который будет создан
     * @return string
     */
    public function getCreatingIndex(): ?string
    {
        return $this->creatingIndex;
    }

    /**
     * Возвращает имя индекса который будет удален
     * @return string
     */
    public function getDeletingIndex(): ?string
    {
        return $this->deletingIndex;
    }
}
