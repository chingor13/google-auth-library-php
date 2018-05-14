<?php

namespace Google\Auth\HttpHandler;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use OpenCensus\Trace\Integrations\Guzzle\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Guzzle6HttpHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client ?: $this->defaultClient();
    }

    /**
     * Accepts a PSR-7 request and an array of options and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, array $options = [])
    {
        return $this->client->send($request, $options);
    }

    /**
     * Accepts a PSR-7 request and an array of options and returns a PromiseInterface
     *
     * @param RequestInterface $request
     * @param array $options
     *
     * @return \GuzzleHttp\Promise\Promise
     */
    public function async(RequestInterface $request, array $options = [])
    {
        return $this->client->sendAsync($request, $options);
    }

    private function defaultClient()
    {
        $stack = new HandlerStack();
        $stack->setHandler(\GuzzleHttp\choose_handler());
        $stack->push(new Middleware());
        return new Client(['handler' => $stack]);
    }
}
