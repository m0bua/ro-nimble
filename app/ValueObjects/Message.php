<?php


namespace App\ValueObjects;


use App\Interfaces\MessageInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Message\AMQPMessage;

class Message implements MessageInterface
{
    /**
     * @var AMQPMessage
     */
    private $message;

    /**
     * @var object
     */
    private $body;

    /**
     * Message constructor.
     * @param AMQPMessage $message
     * @throws Exception
     */
    public function __construct(AMQPMessage $message)
    {
        $this->message = $message;
        $this->body = $this->jsonValidate($this->message->body);
    }

    /**
     * @return object
     */
    public function getBody(): object
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getRoutingKey(): string
    {
        return $this->message->delivery_info['routing_key'];
    }

    /**
     * @param string $fieldRoute
     * @return mixed
     * @throws Exception
     */
    public function getField(string $fieldRoute)
    {
        $result = $this->body;

        if (!empty((array)$result)) {
            $routes = explode('.', $fieldRoute);
            foreach ($routes as $route) {
                if (!property_exists($result, $route)) {
//                throw new Exception("Field \"$route\" does not exists.");
                    Log::channel('consumer')->warning("Field \"$route\" does not exists.");
                }
                $result = $result->$route;
            }
        }

        return $result;
    }

    /**
     * @param string $json
     * @return object
     * @throws Exception
     */
    private function jsonValidate(string $json): object
    {
        $result = json_decode($json);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $error = ''; // JSON is valid
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
                $error = 'Unknown JSON error occured.';
                break;
        }

        if ($error !== '') {
//            throw new Exception("$error Json was given: $json");
            Log::channel('consumer')->warning("$error Json was given: $json");

            return (object)[];
        }

        return $result;
    }
}
