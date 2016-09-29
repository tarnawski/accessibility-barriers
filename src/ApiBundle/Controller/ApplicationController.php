<?php

namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Repository\CategoryRepository;
use AccessibilityBarriersBundle\Repository\NotificationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AccessibilityBarriersBundle\Entity\Category;
use AccessibilityBarriersBundle\Entity\Notification;

/**
 * Class ApplicationController
 */
class ApplicationController extends BaseController
{

    /**
     * @ApiDoc(
     *  description="Return application status",
     * )
     * @return Response
     */
    public function statusAction()
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getRepository(Category::class);
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getRepository(Notification::class);

        return JsonResponse::create([
            'category' => $categoryRepository->getCategoriesCount(),
            'notifications' => $notificationRepository->getNotificationsCount()
        ]);
    }
}
