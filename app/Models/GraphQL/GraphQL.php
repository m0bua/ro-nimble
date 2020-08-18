<?php

namespace App\Models\GraphQL;

use App\Interfaces\GraphQLInterface;
use GraphQL\Client;

/**
 * Class GraphQL
 * @package App\Library\Services
 */
abstract class GraphQL implements GraphQLInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $serviceName;

    /**
     * GraphQL constructor.
     */
    public function __construct()
    {
        $this->serviceName = $this->serviceName();
        $this->client = new Client(
            config("graphql.{$this->serviceName}.endpoint_url"),
            config("graphql.{$this->serviceName}.authorization_headers"),
            config("graphql.{$this->serviceName}.http_options")
        );
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
