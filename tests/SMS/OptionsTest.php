<?php

namespace Craftsys\Tests\Msg91\SMS;

use Craftsys\Msg91\SMS\Options;
use Craftsys\Tests\Msg91\TestCase;

class OptionsTest extends TestCase
{
    /**
     * Options
     * @var \Craftsys\Msg91\SMS\Options
     */
    protected $options;

    protected function setUp(): void
    {
        parent::setUp();
        $this->options = new Options();
    }

    public function test_default_receiver_key()
    {
        $this->options = $this->options->to(919999999999);
        $this->assertEquals('mobiles', $this->options->receiver_key);
        $recipients = $this->options->getPayloadForKey('recipients');
        $this->assertCount(1, $recipients);
        $recipient = $recipients[0];
        $this->assertArrayHasKey('mobiles', $recipient);
    }

    public function test_custom_receiver_key()
    {
        $this->options = $this->options->receiverKey('contact')->to(919999999999);
        $this->assertEquals('contact', $this->options->receiver_key);
        $recipients = $this->options->getPayloadForKey('recipients');
        $this->assertCount(1, $recipients);
        $recipient = $recipients[0];
        $this->assertArrayHasKey('contact', $recipient);
    }

    public function test_custom_receiver_key_after_recipients()
    {
        $this->options = $this->options->to(919999999999)->receiverKey('contact');
        $this->assertEquals('contact', $this->options->receiver_key);
        $recipients = $this->options->getPayloadForKey('recipients');
        $this->assertCount(1, $recipients);
        $recipient = $recipients[0];
        $this->assertArrayHasKey('contact', $recipient);
    }

    public function test_variable_for_single_recipient()
    {
        $this->options = $this->options->to(919999999999)->variable('name', 'Craft Sys');
        $recipients = $this->options->getPayloadForKey('recipients');
        $recipient = $recipients[0];
        $this->assertArrayHasKey('name', $recipient);
        $this->assertEquals('Craft Sys', $recipient['name']);
    }

    public function test_variable_for_multiple_recipients()
    {
        $this->options = $this->options->recipients([[
            'mobiles' => '91123123123'
        ], [
            'mobiles' => '91123123124'
        ]])->variable('name', 'Craft Sys');
        $recipients = $this->options->getPayloadForKey('recipients');
        foreach ($recipients as $recipient) {
            $this->assertArrayHasKey('name', $recipient);
            $this->assertEquals('Craft Sys', $recipient['name']);
        }
    }
}
