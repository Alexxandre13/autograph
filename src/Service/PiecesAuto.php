<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PiecesAuto
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    private function getCarID(string $license)
    {
        $res = $this->client->request('GET', "https://www.piecesauto.com/homepage/numberplate?value=$license");
        return json_decode($res->getContent())->carId;
    }

    private function getLink(string $cardId): string
    {
        $res = $this->client->request('GET', "https://www.piecesauto.com//common/seekCar?carid=$cardId");
        return $res->getContent();
    }

    public function generateLink(string $license): string
    {
        $carId = $this->getCarID($license);
        $link = $this->getLink($carId);
        return "https://www.piecesauto.com$link";
    }
}
