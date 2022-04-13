<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class MailAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Mails;',
                6 => 'use Illuminate\Mail\Mailable;',
                9 => 'class {{ name }}Mail extends Mailable',
                20 => 'return $this->view('. "'view.name'" . ');'
            ],
            [
                'name' => $this->setName($path, 'Mail.php')
            ],
            $path
        );
    }

    public function markdown(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Mails;',
                6 => 'use Illuminate\Mail\Mailable;',
                9 => 'class {{ name }}Mail extends Mailable',
                20 => 'return $this->markdown(' . "'DummyView'" . ');'
            ],
            [
                'name' => $this->setName($path, 'Mail.php')
            ],
            $path
        );
    }
}
