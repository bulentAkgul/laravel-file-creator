<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class NotificationAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Notifications;',
                9 => 'class {{ name }}Notification extends Notification',
                23 => 'public function toMail(mixed $notifiable): MailMessage'
            ],
            [
                'name' => $this->setName($path, 'Notification.php')
            ],
            $path
        );
    }

    public function markdown(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Notifications;',
                9 => 'class {{ name }}Notification extends Notification',
                25 => 'return (new MailMessage)->markdown('. "'DummyView'" . ');'
            ],
            [
                'name' => $this->setName($path, 'Notification.php')
            ],
            $path
        );
    }
}
