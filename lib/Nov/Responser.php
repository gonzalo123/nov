<?php

namespace Nov;

use Symfony\Component\HttpFoundation\Response;
use Nov\Instance;

class Responser
{
    private $instance;

    public function __construct(Instance $instance)
    {
        $this->instance = $instance;
    }

    public function getRequest()
    {
        return $this->instance->getRequest();
    }

    public function getResponse()
    {
        $out = $this->instance->invoke();

        $response = new Response();
        if (is_array($out)) {
            $response->headers->set('Content-Type', 'application/json');
            $out = json_encode($out, true);
        } else {
            $response->headers->set('Content-Type', 'text/html');
        }

        $response->setContent($out);
        $response->setStatusCode(200);

        return $response;
    }
}