<?php
/**
 * This file contains a CertificationHelper class. The purpose of this class is to help with the certification
 * scenarios, when moving from development to production.
 *
 *
 */

namespace Amadeus;

use Amadeus\Client\Response;
use Amadeus\Exceptions\ResponseException;

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
     * A reference to the main Amadeus client instance.
     *
     * @var Amadeus
     */
    private Amadeus $amadeus;

    /**
     * The constructor pulls in the running Amadeus instance.
     *
     * @param Amadeus $amadeus
     */
    public function __construct(Amadeus $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    /**
     * This function is intended to be used to save request messages.
     *
     * @param string $fileTitle
     * @param Response $response
     * @param string $content
     * @return void
     */
    public function saveRequest( string $fileTitle, Response $response, string $content ): void
    {
        $this->saveMessage($fileTitle . ' RQ.json',
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
     * @return void
     */
    public function saveResponse( string $fileTitle, Response  $response, string $content ): void
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
     * @return void
     */
    public function saveErrorRequest( string $fileTitle, string $content): void
    {
        $this->saveMessage($fileTitle . ' RQ.json', $content);
    }

    /**
     * This function is intended to be used to save error messages from the Amadeus API.
     *
     * @param string $fileTitle
     * @param ResponseException $content
     * @return void
     */
    public function saveErrorResponse( string $fileTitle, ResponseException $content ): void
    {
        $this->saveMessage($fileTitle . ' RS.json',
            $content->getUrl()
            . PHP_EOL
            . PHP_EOL
            . $content->getMessage()
        );
    }

    /**
     * This function is the saves the files if the log level is equal to 'certification'.
     *
     * @param string $fileTitle
     * @param $content
     * @return void
     */
    private function saveMessage( string $fileTitle, $content): void
    {
        if (strcasecmp('certification', $this->amadeus->getClient()->getConfiguration()->getLogLevel()) === 0) {
            $counter = 0;
            while ($counter >= 0) {
                if (!file_exists($counter . ' - ' . $fileTitle)) {
                    file_put_contents($counter . ' - ' . $fileTitle, $content);
                    $counter = -1;
                } else {
                    $counter++;
                }
            }
        }
    }
}
