<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 08/06/2018
 * Time: 14:00
 */

namespace Tests\OC\LouvreBundle\Service;

use OC\LouvreBundle\Service\CommandeService;
use PHPUnit\Framework\TestCase;

class EmailCommandeTest extends TestCase
{
    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // enables the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/payement');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Vos Billets - Musee du LOUVRE', $message->getSubject());
        $this->assertSame('send@example.com', key($message->getFrom()));
//        $this->assertSame('recipient@example.com', key($message->getTo()));
        $this->assertSame(
            'Merci d\'avoir commandé vos billet sur notre site.',
            $message->getBody()
        );
    }
}