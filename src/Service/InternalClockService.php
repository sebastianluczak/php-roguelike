<?php

namespace App\Service;

use App\Model\RandomEvent\RandomEventInterface;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\Messenger\MessageBusInterface;

class InternalClockService
{
    protected CarbonImmutable $gameStartTime;
    protected MessageBusInterface $messageBus;
    /**
     * @var RandomEventInterface[]
     */
    protected array $gameEvents;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->gameStartTime = CarbonImmutable::now();
        $this->messageBus = $messageBus;
        $this->gameEvents = [];
    }

    public function getGameStartTime(): CarbonImmutable
    {
        return $this->gameStartTime;
    }

    public function tick(): void
    {
        $eventsToFire = [];
        foreach ($this->gameEvents as $gameEvent) {
            if ($this->isGameEventStillInEffect($gameEvent)) {
                $eventsToFire[] = $gameEvent;
            }
        }
        $this->gameEvents = $eventsToFire;

        foreach ($this->gameEvents as $gameEvent) {
            $gameEvent->applyEffect();
        }
    }

    public function addGameEventToEventLoop(RandomEventInterface $event): void
    {
        $this->gameEvents[] = $event;
    }

    private function isGameEventStillInEffect(RandomEventInterface $gameEvent): bool
    {
        if (Carbon::now()->diffInSeconds($gameEvent->getLastsUntil(), false) >= 0) {
            return true;
        }

        return false;
    }

    public function getActiveGameEventsCount(): int
    {
        return count($this->gameEvents);
    }
}
