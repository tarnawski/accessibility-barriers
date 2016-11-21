<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Notification\SenderEngine;
use AccessibilityBarriersBundle\Repository\NotificationRepository;
use AccessibilityBarriersBundle\Service\GooglePlacesService;
use ApiBundle\Form\Type\NearMeCriteriaType;
use ApiBundle\Form\Type\NotificationCriteriaType;
use ApiBundle\Form\Type\NotificationType;
use JMS\JobQueueBundle\Entity\Job;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class NotificationController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Return all notifications"
     * )
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(NotificationCriteriaType::class);
        $form->submit($request->query->all());
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getRepository(Notification::class);
        $notifications = $notificationRepository->findByCriteria($form->getData());

        return $this->success($notifications, 'Notification', Response::HTTP_OK, array('NOTIFICATION_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Return notifications near specified point"
     * )
     * @param Request $request
     * @return Response
     */
    public function nearMeAction(Request $request)
    {
        $form = $this->createForm(NearMeCriteriaType::class);
        $form->submit($request->query->all());
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getRepository(Notification::class);
        $notifications = $notificationRepository->findNearNotifications($form->getData());

        return $this->success($notifications, 'Notification', Response::HTTP_OK, array('NOTIFICATION_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Return single notification"
     * )
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "id"})
     */
    public function showAction(Notification $notification)
    {
        return $this->success($notification, 'Notification', Response::HTTP_OK, array(
            'NOTIFICATION_DETAILS',
            'COMMENT_LIST',
            'USER_BASIC',
            'CATEGORY_LIST'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Create new notification",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of notification"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Short description of notification"},
     *      {"name"="latitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="longitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="category", "dataType"="integer", "required"=true, "description"="Category ID"}
     *  })
     * )
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(NotificationType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }
        /** @var Notification $notification */
        $notification = $form->getData();
        $notification->setUser($this->getUser());
        $notification->setCreatedAt(new \DateTime());

        /** Set address via external api */
        /** @var GooglePlacesService $placesService */
        $placesService = $this->get('accessibility_barriers.services.google_places');
        $address = $placesService->getPlaceName($notification->getLatitude(), $notification->getLongitude());
        $notification->setAddress($address);

        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();

        /** Add jobs to queue */
        $job = new Job('notification:email', [$notification->getId()]);
        $em->persist($job);
        $job = new Job('notification:alert', [$notification->getId()]);
        $em->persist($job);

        $em->flush();

        return $this->success($notification, 'Notification', Response::HTTP_CREATED, array(
            'NOTIFICATION_DETAILS',
            'COMMENT_LIST',
            'USER_BASIC',
            'CATEGORY_LIST'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Update notification",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of notification"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="Short description of notification"},
     *      {"name"="latitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="longitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="category", "dataType"="integer", "required"=true, "description"="Category ID"}
     *  })
     * )
     * @param Request $request
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "id"})
     */
    public function updateAction(Request $request, Notification $notification)
    {
        $this->denyAccessUnlessGranted('access', $notification);

        $formData = json_decode($request->getContent(), true);
        $form = $this->createForm(NotificationType::class, $notification);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        $notification = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();

        return $this->success($notification, 'Notification', Response::HTTP_OK, array(
            'NOTIFICATION_DETAILS',
            'COMMENT_LIST',
            'USER_BASIC',
            'CATEGORY_LIST'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Delete notification"
     *)
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "id"})
     */
    public function deleteAction(Notification $notification)
    {
        $this->denyAccessUnlessGranted('access', $notification);

        $em = $this->getDoctrine()->getManager();
        $em->remove($notification);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Notification properly removed'), 'Notification');
    }
}
