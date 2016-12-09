<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

class APIContext implements Context
{
    use ContextTrait;

    /**
     * @param string $method
     * @param string $uri
     * @param string $content
     * @param array  $headers
     */
    public function iSendAJsonLdRequestTo($method, $uri, $content = null, array $headers = [])
    {
        $headers += [
            'HTTP_ACCEPT' => 'application/ld+json',
        ] + ($content ? ['CONTENT_TYPE' => 'application/ld+json'] : []);

        $this->getMinkClient()->request($method, $uri, [], [], $headers, $content);
    }

    /**
     * @param int $codeStatus
     */
    public function theJsonLdResponseShouldHaveStatusCode(int $codeStatus)
    {
        $this->minkContext->assertResponseStatus($codeStatus);
        $this->jsonContext->theResponseShouldBeInJson();
        $this->restContext->theHeaderShouldBeContains('Content-Type', 'application/ld+json');
    }

    /**
     * @param TableNode $expectedErrors
     *
     * @throws \RuntimeException
     */
    public function theJsonLdResponseHasExpectedFormErrors(TableNode $expectedErrors)
    {
        $this->theJsonLdResponseShouldHaveStatusCode(400);

        $errors = array_map(function ($property) {
            return $property['message'];
        }, $this->getJsonResponse()['violations']);
        $expectedErrors = $expectedErrors->getColumn(0);

        $missingErrors = array_diff($errors, $expectedErrors);
        if ($missingErrors) {
            throw new \RuntimeException(sprintf("Missing errors:\n %s", implode("\n", $missingErrors)));
        }

        $unwantedErrors = array_diff($expectedErrors, $errors);
        if ($unwantedErrors) {
            throw new \RuntimeException(sprintf("Unwanted errors:\n %s", implode("\n", $unwantedErrors)));
        }
    }

    /**
     * @param TableNode $expectedErrors
     *
     * @throws \RuntimeException
     */
    public function theJsonResponseHasExpectedMessageError(TableNode $expectedErrors)
    {
        $this->minkContext->assertResponseStatus(400);

        $message = $this->getJsonResponse()['message'];
        $expectedMessage = $expectedErrors->getColumn(0)[0];

        \PHPUnit_Framework_Assert::assertEquals($expectedMessage, $message);
    }
}
