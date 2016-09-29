<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Notification;
use ApiBundle\Form\Type\NotificationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class NotificationController extends BaseController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $notificationRepository = $this->getRepository(Notification::class);
        $notifications = $notificationRepository->findAll();

        return $this->success($notifications, 'Notification', Response::HTTP_OK, array('NOTIFICATION_LIST'));
    }

    /**
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
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();

        return $this->success($notification, 'Notification', Response::HTTP_CREATED, array(
            'NOTIFICATION_DETAILS',
            'COMMENT_LIST',
            'USER_BASIC',
            'CATEGORY_LIST'
        ));
    }

    /**
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

        return $this->success(array('status' => 'Success', 'message' => 'Notification properly removed'), 'Notification');
    }
}
