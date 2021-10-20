<?php

namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PiecesAuto
{
    private static $urlLicense = "https://www.piecesauto.com/homepage/numberplate?value=";
    private static $urlID = "https://www.piecesauto.com/common/seekCar?carid=";
    private static $urlLink = "https://www.piecesauto.com";

    private $client;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    private function getCarID(string $license): string
    {
        $res = $this->client->request('GET', self::$urlLicense . $license);

        return $res->getStatusCode() === 200 && isset(json_decode($res->getContent())->carId) ?
            json_decode($res->getContent())->carId : null;
    }

    private function getLink(string $cardId): string
    {
        $res = $this->client->request('GET', self::$urlID . $cardId);

        return $res->getStatusCode() === 200 ? $res->getContent() : null;
    }

    public function generateLink(string $license): string
    {
        return $this->cache->get('PiecesAuto-' . $license, function (ItemInterface $item) use ($license) {

            // Jours x Heures x minutes x secondes
            $item->expiresAfter(7 * 24 * 60 * 60);

            $carId = $this->getCarID($license);

            if ($carId === null) return self::$urlLink;

            $link = $this->getLink($carId);

            if ($link === null) return self::$urlLink;

            return self::$urlLink . $link;
        });
    }
}
