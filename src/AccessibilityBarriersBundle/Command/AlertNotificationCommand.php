<?php

namespace AccessibilityBarriersBundle\Command;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Repository\NotificationRepository;
use AccessibilityBarriersBundle\Repository\UserRepository;
use AccessibilityBarriersBundle\Service\AlertService;
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
        /** @var AlertService $alerts */
        $alerts = $this->getContainer()->get('accessibility_barriers.services.alert_service');
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
                $alerts->create($user, $notification, 'Dodano nowe zgłoszenie');
            }
        }

        $output->writeln("Alert notification finish with success");
    }
}
