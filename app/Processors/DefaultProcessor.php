<?php


namespace App\Processors;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\Log;

class DefaultProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        Log::channel('consumer_message')->notice($message->getRoutingKey(), [
            'context' => 'default_processor',
            'body' => $message->getBody()
        ]);

        return Codes::SKIP;
    }
}
