<?php
/**
 * This file contains a CertificationHelper class. The purpose of this class is to help with the certification
 * scenarios, when development is moved from development to production.
 *
 */

namespace Amadeus;

/**
 * The class CertificationHelper contains 3 public functions and a single private function.
 *
 * @saveRequest - This is intended to save the Requests sent to the Amadeus API.
 * @saveResponse - This is intended to save the valid Responses from the Amadeus API.
 * @saveErrorResponse - This is intended to save the Error responses from the Amadeus API.
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
     *
     *
     * @param string $fileTitle
     * @param string $content
     * @return void
     */
    public function saveRequest( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RQ.json', $content);
    }

    /**
     *
     * @param string $fileTitle
     * @param string $content
     * @return void
     */
    public function saveResponse( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RS.json', $content);
    }

    /**
     *
     * @param string $fileTitle
     * @param string $content
     * @return void
     */
    public function saveErrorResponse( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RS.json', $content);
    }

    /**
     *
     * @param string $fileTitle
     * @param $content
     * @return void
     */
    private function saveMessage( string $fileTitle, $content)
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
