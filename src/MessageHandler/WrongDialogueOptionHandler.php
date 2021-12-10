<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\WrongDialogueOptionMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class WrongDialogueOptionHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(WrongDialogueOptionMessage $message): void
    {
        $this->messageBus->dispatch(
            new AddAdventureLogMessage(
                'Wrong dialogue option chosen',
                MessageClassEnum::IMPORTANT()
            )
        );
    }
}
