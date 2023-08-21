<?php

namespace Amadeus;


class CertificationHelper
{
    private $amadeus;

    public function __construct(Amadeus $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    public function saveRequest( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RQ.json', $content);
    }

    public function saveResponse( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RS.json', $content);
    }

    public function saveErrorResponse( string $fileTitle, string $content )
    {
        $this->saveMessage($fileTitle . ' RS.json', $content);
    }

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