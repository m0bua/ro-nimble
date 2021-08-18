<?php
namespace App\Console\Commands;

use App\Console\Commands\Extend\ExtCommand;
use App\Helpers\Chunks\ChunkCursor;
use App\Models\Elastic\ProducersModel;
use Illuminate\Support\Facades\DB;

class IndexProducers extends ExtCommand
{
    /**
     * @var string
     */
    protected $signature = 'db:index-producers';

    /**
     * @var string
     */
    protected $description = 'Indexing producers from producers table';

    /**
     * @var ProducersModel
     */
    protected ProducersModel $elasticProducers;

    /**
     * IndexProducers constructor.
     * @param ProducersModel $elasticProducers
     */
    public function __construct(ProducersModel $elasticProducers)
    {
        $this->elasticProducers = $elasticProducers;

        parent::__construct();
    }

    /**
     *
     */
    protected function extHandle()
    {
        $this->elasticProducers->setIndexVars();

        $this->elasticProducers->createIndex(
            $this->elasticProducers->getCreatingIndex(),
            $this->elasticProducers->getIndexStructure()
        );

        $this->fillIndex();

        $this->elasticProducers->updateAliases([
            $this->elasticProducers->removeAliasAction($this->elasticProducers->getDeletingIndex(), 'producers'),
            $this->elasticProducers->addAliasAction($this->elasticProducers->getCreatingIndex(), 'producers')
        ]);

        $this->elasticProducers->deleteIndex($this->elasticProducers->getDeletingIndex());
    }

    /**
     * Наполнение индекса
     */
    public function fillIndex()
    {
        $producersQuery = DB::table('producers as p')
            ->select([
                'p.id',
                'p.name',
                'p.title',
                'p.status',
            ]);

        $producersIDs = [];

        ChunkCursor::iterate($producersQuery, function ($producers) use (&$producersIDs) {
            $producersData = [];

            array_map(function ($producer) use (&$producersData, &$producersIDs) {
                $title = trim($producer->title);
                $producersIDs[$producer->id] = $producer->id;
                $producersData[$producer->id] = [
                    'id' => $producer->id,
                    'name' => $producer->name,
                    'title' => $title,
                    'status' => $producer->status,
                    'first_symbol' => mb_strtoupper(mb_substr($title, 0, 1)),
                ];
            }, $producers);

            $updateData = ['body' => []];

            foreach ($producersData as $id => $producerData) {
                $updateData['body'][] = [
                    'update' => [
                        '_index' => $this->elasticProducers->getCreatingIndex(),
                        '_id' => $id
                    ],
                ];
                $updateData['body'][] = [
                    'doc' => $producerData,
                    'doc_as_upsert' => true,
                ];
            }

            $this->elasticProducers->bulk($updateData);
        });
    }
}
