<?php

namespace App\Service;

use App\Entity\Vehicule;
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

    /**
     * @param Vehicule[] $vehicules
     */
    public function handleVehicules(array $vehicules)
    {
        foreach ($vehicules as $v) {
            $v->piecesAutoLink = $this->generateLink($v);
        }
        return $vehicules;
    }

    private function getCarID(Vehicule $v)
    {
        $res = $this->client->request('GET', self::$urlLicense . $v->getLicense());

        return $res->getStatusCode() === 200 &&
            isset(json_decode($res->getContent())->carId)
            ?
            json_decode($res->getContent())->carId
            :
            null;
    }

    private function getUri(string $cardId)
    {
        $res = $this->client->request('GET', self::$urlID . $cardId);

        return $res->getStatusCode() === 200 ? $res->getContent() : null;
    }

    private function generateLink(Vehicule $v): string
    {
        return $this->cache->get('PiecesAuto-' . $v->getLicense() . '-' . $v->getId(), function (ItemInterface $item) use ($v) {

            // Jours x Heures x minutes x secondes
            $item->expiresAfter(7 * 24 * 60 * 60); // En cache pour 1 semaine !

            $paCarId = $this->getCarID($v);

            if ($paCarId === null) {
                return $this->getDefaultLink();
            };

            $paUri = $this->getUri($paCarId);

            if ($paUri === null) {
                return $this->getDefaultLink();
            }

            return $this->getDefaultLink() . $paUri;
        });
    }

    private function getDefaultLink(): string
    {
        return self::$urlLink;
    }
}
