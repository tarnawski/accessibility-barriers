<?php

namespace AccessibilityBarriersBundle\Command;

use AccessibilityBarriersBundle\Notification\SenderEngine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notification:distribute');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SenderEngine $senderEngine */
        $senderEngine = $this->getContainer()->get('accessibility_barriers.sender_engine');
        $senderEngine->distribute();

        $output->writeln("Finished with success");
    }
}
