<?php

namespace AccessibilityBarriersBundle\Command;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Notification\StrategiesFactory;
use AccessibilityBarriersBundle\Repository\NotificationRepository;
use AccessibilityBarriersBundle\Repository\UserRepository;
use OAuthBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AlertNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('notification:alert')
            ->addArgument('id', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notificationId = $input->getArgument('id');
        /** @var StrategiesFactory $senderStrategiesFactory */
        $senderStrategiesFactory = $this->getContainer()->get('accessibility_barriers.strategies_factory');
        $senderStrategy = $senderStrategiesFactory->get(StrategiesFactory::ALERT_STRATEGY);
        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get('accessibility_barriers.repository.user');
        $users = $userRepository->findAll();
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getContainer()->get('accessibility_barriers.repository.notification');
        /** @var Notification $notification */
        $notification = $notificationRepository->find($notificationId);

        if ($notification) {
            /** @var User $user */
            foreach ($users as $user) {
                $senderStrategy->send($user, $notification);
            }
        }

        $output->writeln("Alert notification finish with success");
    }
}
