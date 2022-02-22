<?php

namespace App\Cores\ConsumerCore;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use RuntimeException;

class Message implements MessageInterface
{
    /**
     * Origin message
     *
     * @var AMQPMessage
     */
    private AMQPMessage $message;

    /**
     * Message body
     *
     * @var object|array
     */
    private $body;

    /**
     * Decode message body as array
     *
     * @var bool
     */
    private bool $asArray;

    /**
     * Message constructor.
     * @param AMQPMessage $message
     * @param bool $asArray
     * @throws Exception
     */
    public function __construct(AMQPMessage $message, bool $asArray = false)
    {
        $this->message = $message;
        $this->asArray = $asArray;
        $this->setBody($message->body);
    }

    /**
     * Get raw message body
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message->getBody();
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     * @noinspection PhpDeprecationInspection
     * @noinspection PhpInternalEntityUsedInspection
     */
    public function getRoutingKey(): string
    {
        return $this->message->delivery_info['routing_key'];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getField(string $fieldRoute)
    {
        $result = $this->body;

        $routes = explode('.', $fieldRoute);
        foreach ($routes as $route) {
            $result = $this->asArray
                ? $this->getFieldArray($result, $route)
                : $this->getFieldObject($result, $route);
        }

        return $result;
    }

    /**
     * Perform field as array
     *
     * @param array $data
     * @param string $route
     * @return mixed
     * @throws Exception
     */
    private function getFieldArray(array $data, string $route)
    {
        if (!array_key_exists($route, $data)) {
            throw new RuntimeException("Field \"$route\" does not exists.");
        }

        return $data[$route];
    }

    /**
     * Perform field as object
     *
     * @param object $data
     * @param string $route
     * @return mixed
     * @throws Exception
     */
    private function getFieldObject(object $data, string $route)
    {
        if (!property_exists($data, $route)) {
            throw new RuntimeException("Field \"$route\" does not exists.");
        }

        return $data->$route;
    }

    /**
     * Validate and set body
     *
     * @param string $json
     * @throws Exception
     * @noinspection JsonEncodingApiUsageInspection
     */
    private function setBody(string $json): void
    {
        $this->body = json_decode($json, $this->asArray);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                $error = 'The maximum stack depth has been exceeded.';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Invalid or malformed JSON.';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Control character error, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON.';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                break;
            case JSON_ERROR_RECURSION:
                $error = 'One or more recursive references in the value to be encoded.';
                break;
            case JSON_ERROR_INF_OR_NAN:
                $error = 'One or more NAN or INF values in the value to be encoded.';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE:
                $error = 'A value of a type that cannot be encoded was given.';
                break;
            default:
                $error = 'Unknown JSON error occurred.';
                break;
        }

        if (isset($error)) {
            throw new RuntimeException("$error; Json was given: $json");
        }
    }
}
