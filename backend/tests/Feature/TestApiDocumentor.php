<?php

namespace Tests\Feature;

use Illuminate\Testing\TestResponse;

class TestApiDocumentor
{

    private $testResults;

    public function __destruct()
    {
        $this->writeMdFile();
    }

    /**
     * @param string $apiBlockDescription
     * @param string $apiMethodDescription
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @param TestResponse $response
     * @return $this
     */
    public function addTestResult(
        string $apiBlockDescription,
        string $apiMethodDescription,
        $method,
        $uri,
        array $data,
        array $headers,
        TestResponse $response
    )
    {
        $this->testResults[$apiBlockDescription][$method][$uri][$response->getStatusCode()] = [
            $apiMethodDescription, $data, $headers, $response->getContent()
        ];
        return $this;
    }

    private function writeMdFile()
    {
        //$this->createMdFile();
        //$this->createContentsParagraph();
        //$this->create
    }
}
