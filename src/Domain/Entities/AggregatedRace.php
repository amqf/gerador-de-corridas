<?php

namespace App\Domain\Entities;

use App\Domain\Entities\ValueObjects\Id;
use App\Domain\Entities\ValueObjects\GeoCoordinate;
use App\Domain\Entities\ValueObjects\Transaction;
use App\Domain\Entities\ValueObjects\RaceCancellation;
use DateTimeImmutable;
use DateTimeZone;

final class AggregatedRace
{
    private DateTimeImmutable $createdAt;
    private ?RaceCancellation $cancellation;
    private ?Transaction $transaction;

    private function __construct(
        private Id $id,
        private GeoCoordinate $origin,
        private GeoCoordinate $destiny,
        ?Transaction $transaction = null
    )
    {
        $this->transaction = $transaction;
        $this->cancellation = null;
        $this->createdAt = new DateTimeImmutable(null, new DateTimeZone('America/Sao_Paulo'));
    }

    /**
     * @param array $data [
     *  'id' => \App\Domain\Entities\ValueObjects\Id | null,
     *  'origin' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ],
     *  'destiny' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ]
     * ]
     */
    public static function create(array $data) : self
    {
        /** @var AggregatedRace */
        $aggregatedRace = new self(
            isset($data['id']) ? Id::create($data['id']) : Id::generate(),
            GeoCoordinate::create($data['origin']),
            GeoCoordinate::create($data['destiny'])
        );

        if(isset($data['cancellation']))
        {
            $aggregatedRace->cancel($data['cancellation']);
        }

        return $aggregatedRace;
    }

    public function notPersisted() : bool
    {
        return $this->getId()->notPersisted();
    }

    /**
     * With this function is possible do pix or cash payment after create the race
     * 
     * @param $transaction [
     *      'amount' => float,
     *      'timestamp' => DateTimeImmutable
     * ]
     */
    public function setTransaction(array $transaction) : void
    {
        $this->transaction = Transaction::create($data['transaction']);
    }

    public function getId() : Id|null
    {
        return $this->id;
    }

    public function getOrigin() : GeoCoordinate
    {
        return $this->origin;
    }

    public function getDestiny() : GeoCoordinate
    {
        return $this->destiny;
    }

    public function getTransaction() : ?Transaction
    {
        return $this->transaction;
    }

    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCanceledAt() : DateTimeImmutable|null
    {
        return $this->canceledAt;
    }

    public function isPaid() : bool
    {
        return $this->transaction instanceof Transaction;
    }

    public function cancel(array $data) : void
    {
        $this->cancellation = RaceCancellation::create($data);
    }

    public function getCancellation() : RaceCancellation
    {
        return $this->cancellation;
    }

    public function toArray() : array
    {
        $data = [
            'id' => $this->getId()->__toString(),
            'origin' => $this->getOrigin()->toArray(),
            'destiny' => $this->getDestiny()->toArray(),
            'transaction' => $this->getTransaction()?->toArray(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];

        if($this->cancellation instanceof RaceCancellation)
        {
            $data['cancellation'] = $this->cancellation->toArray();
        }

        return $data;
    }
}