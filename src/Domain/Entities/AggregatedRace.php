<?php

namespace App\Domain\Entities;

use App\Domain\Entities\ValueObjects\Id;
use App\Domain\Entities\ValueObjects\GeoCoordinate;
use App\Domain\Entities\ValueObjects\Payment;
use App\Domain\Entities\ValueObjects\RaceCancellation;
use App\Domain\Entities\ValueObjects\Enums\RaceCancellationReason;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;

final class AggregatedRace
{
    private ?RaceCancellation $cancellation = null;
    private ?Payment $payment = null;

    private function __construct(
        private Id $id,
        private GeoCoordinate $origin,
        private GeoCoordinate $destiny,
        private DateTimeImmutable $requestedAt,
        private bool $isPersisted
    )
    {
    }

    public function pay(Payment $payment) : void
    {
        $this->payment = $payment;
    }

    /**
     * It's used for really create a new the race for persiste it.
     * 
     * @param array $data [
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
        /** @var DateTimeImmutable $currentDateTime */
        $currentDateTime = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));

        /** @var AggregatedRace */
        $aggregatedRace = new self(
            Id::generate(),
            GeoCoordinate::create($data['origin']),
            GeoCoordinate::create($data['destiny']),
            $currentDateTime,
            isPersisted: false
        );

        return $aggregatedRace;
    }

    /**
     * Make a aggregated race with data comming from database for example.
     * It's for case when the race was created before.
     * 
     * This method require the race id and optionally cancellation data.
     * 
     * @param array $data [
     *  'id' => uuid,
     *  'requested_at' => string,
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
    public static function make(array $data) : self
    {
        /** @var DateTimeImmutable $requestedAt */
        $requestedAt = new DateTimeImmutable($data['requested_at'], new DateTimeZone('America/Sao_Paulo'));

        /** @var AggregatedRace */
        $aggregatedRace = new self(
            Id::create($data['id']),
            GeoCoordinate::create($data['origin']),
            GeoCoordinate::create($data['destiny']),
            $requestedAt,
            isPersisted: true
        );

        if(isset($data['cancellation']))
        {
            $aggregatedRace->cancel($data['cancellation']);
        }

        return $aggregatedRace;
    }

    public function notPersisted() : bool
    {
        return !$this->isPersisted;
    }

    /**
     * With this function is possible do pix or cash payment after create the race
     * 
     * @param $payment [
     *      'amount' => float,
     *      'timestamp' => DateTimeImmutable
     * ]
     */
    public function setPayment(array $payment) : void
    {
        $this->payment = Payment::create($data['payment']);
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

    public function getPayment() : ?Payment
    {
        return $this->payment;
    }

    public function getRequestedAt() : DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function getCanceledAt() : DateTimeImmutable|null
    {
        return $this->canceledAt;
    }

    public function isPaid() : bool
    {
        return $this->payment instanceof Payment;
    }

    public function wasRequestedAfter3MinutesAgo() : bool
    {
        /** @var DateTimeImmutable $currentDateTime */
        $currentDateTime = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
        $diffInSeconds = $currentDateTime->getTimestamp() - $this->getRequestedAt()->getTimestamp();
        return $diffInSeconds > 180;
    }

    public function cancel(array $data) : void
    {
        /** @var RaceCancellation $cancellation */
        $cancellation = RaceCancellation::create($data);

        if($this->isCancelled() || ($this->wasRequestedAfter3MinutesAgo() && $cancellation->matchReason(RaceCancellationReason::Others))
        )
        {
            throw new DomainException('Cannot cancel the race now');
        }

        $this->cancellation = $cancellation;
    }

    public function isCancelled() : bool
    {
        return $this->cancellation instanceof RaceCancellation;
    }

    public function getCancellation() : ?RaceCancellation
    {
        return $this->cancellation;
    }

    public function toArray() : array
    {
        $data = [
            'id' => $this->getId()->__toString(),
            'origin' => $this->getOrigin()->toArray(),
            'destiny' => $this->getDestiny()->toArray(),
            'payment' => $this->getPayment()?->toArray(),
            'requested_at' => $this->getRequestedAt()->format('Y-m-d H:i:s'),
        ];

        if($this->isCancelled())
        {
            $data['cancellation'] = $this->getCancellation()->toArray();
        }

        return $data;
    }
}