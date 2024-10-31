<?php
/**
 * This file contains a CertificationHelper class. The purpose of this class is to help with the certification
 * scenarios, when moving from development to production.
 */

declare(strict_types=1);

namespace Amadeus;

use Amadeus\Client\Response;
use Amadeus\Exceptions\ResponseException;
use stdClass;

/**
 * The class CertificationHelper contains 3 public functions and a single private function.
 *
 * @saveRequest - This is intended to save the Requests sent to the Amadeus API.
 * @saveResponse - This is intended to save the valid Responses from the Amadeus API.
 * @saveErrorResponse - This is intended to save the Error responses from the Amadeus API.
 *
 * The CertificationHelper is enabled if the log level is equal to 'certification'.
 *
 */
class CertificationHelper
{
    /**
     * @var string
     */
    private string $logLevel;

    /**
     * The constructor pulls in the running Amadeus instance.
     *
     * @param string $logLevel
     */
    public function __construct(string $logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * @param Response $response
     * @param string $title
     * @param string $params
     *
     * @return void
     */
    public function saveSuccess(Response $response, string $title, string $params): void
    {

        // Save request file for certification purposes
        $this->saveRequest(
            $title,
            $response,
	        json_decode($params, false)
        );
        // Save response file for certification purposes
        $this->saveResponse(
            $title,
            $response,
            json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
        );

    }

    /**
     * @param ResponseException $responseException
     * @param string $title
     * @param array $params
     *
     * @return void
     */
    public function saveError(ResponseException $responseException, string $title, array $params): void
    {
        $this->saveErrorResponse($title, $responseException, json_encode($params, JSON_PRETTY_PRINT));
    }

    /**
     * This function is intended to be used to save request messages.
     *
     * @param string $fileTitle
     * @param Response $response
     * @param string $content
     *
     * @return void
     */
    public function saveRequest(string $fileTitle, Response $response, string $content): void
    {
        $this->saveMessage(
            $fileTitle . ' RQ.json',
            $response->getRequest()->getVerb() . ' ' . $response->getRequest()->getUri() .
            PHP_EOL .
            PHP_EOL .
            implode(PHP_EOL, $response->getRequest()->getHeaders()) .
            PHP_EOL .
            PHP_EOL .
            $content
        );
    }

    /**
     * This function is intended to be used to save response messages from the Amadeus API.
     *
     * @param string $fileTitle
     * @param Response $response
     * @param string $content
     *
     * @return void
     */
    public function saveResponse(string $fileTitle, Response $response, string $content): void
    {
        $this->saveMessage(
            $fileTitle . ' RS.json',
            $response->getRequest()->getVerb() . ' ' . $response->getRequest()->getUri() .
            PHP_EOL .
            PHP_EOL .
            $response->getHeaders() .
            $content
        );

    }

    /**
     * This function is intended to be used to save error messages sent to the Amadeus API.
     *
     * @param string $fileTitle
     * @param string $content
     *
     * @return void
     */
    public function saveErrorRequest(string $fileTitle, string $content): void
    {
        $this->saveMessage($fileTitle . ' RQ.json', $content);
    }

    /**
     * This function is intended to be used to save error messages from the Amadeus API.
     *
     * @param string $fileTitle
     * @param ResponseException $content
     * @param string $body
     *
     * @return void
     */
    public function saveErrorResponse(string $fileTitle, ResponseException $content, string $body): void
    {
        $this->saveMessage(
            $fileTitle . ' RS.json',
            $content->getUrl()
            . PHP_EOL
            . PHP_EOL
            . $content->getMessage()
            . PHP_EOL
            . PHP_EOL
            . $body
        );
    }

    /**
     * This function is the saves the files if the log level is equal to 'certification'.
     *
     * @param string $fileTitle
     * @param $content
     *
     * @return void
     */
    private function saveMessage(string $fileTitle, $content): void
    {
        if (strcasecmp('certification', $this->logLevel) === 0) {
            $counter = 0;
            while ($counter >= 0) {
                if (! file_exists($counter . ' - ' . $fileTitle)) {
                    file_put_contents($counter . ' - ' . $fileTitle, $content);
                    $counter = - 1;
                } else {
                    $counter++;
                }
            }
        }
    }
}
