<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Comment;
use AccessibilityBarriersBundle\Entity\Notification;
use ApiBundle\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class CommentController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Return all comments belongs to notification"
     * )
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     */
    public function indexAction(Notification $notification)
    {
        return $this->success($notification->getComments(), 'Comment', Response::HTTP_OK, array(
            'COMMENT_LIST',
            'NOTIFICATION_COMMENTS',
            'USER_BASIC'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Return single comment belong to notification"
     * )
     * @param Notification $notification
     * @param Comment $comment
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     * @ParamConverter("comment", class="AccessibilityBarriersBundle\Entity\Comment", options={"id" = "comment_id"})
     */
    public function showAction(Notification $notification, Comment $comment)
    {
        return $this->success($comment, 'Comment', Response::HTTP_OK, array(
            'COMMENT_LIST',
            'COMMENT_DETAILS',
            'USER_BASIC',
            'NOTIFICATION_LIST'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Create new comment",
     *  parameters={
     *      {"name"="content", "dataType"="string", "required"=true, "description"="Content of comment"}
     *  })
     * )
     * @param Request $request
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     */
    public function createAction(Request $request, Notification $notification)
    {
        $form = $this->createForm(CommentType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var Comment $comment */
        $comment = $form->getData();
        $comment->setUser($this->getUser());
        $comment->setNotification($notification);
        $comment->setCreatedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->success($comment, 'Comment', Response::HTTP_CREATED, array('COMMENT_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Update comment",
     *  parameters={
     *      {"name"="content", "dataType"="string", "required"=true, "description"="Content of comment"}
     *  })
     * )
     * @param Request $request
     * @param Notification $notification
     * @param Comment $comment
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     * @ParamConverter("comment", class="AccessibilityBarriersBundle\Entity\Comment", options={"id" = "comment_id"})
     */
    public function updateAction(Request $request, Notification $notification, Comment $comment)
    {
        $this->denyAccessUnlessGranted('access', $comment);

        $formData = json_decode($request->getContent(), true);
        $form = $this->createForm(CommentType::class, $comment);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        $comment = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->success($comment, 'Comment', Response::HTTP_OK, array('COMMENT_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Delete comment"
     *)
     * @param Notification $notification
     * @param Comment $comment
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     * @ParamConverter("comment", class="AccessibilityBarriersBundle\Entity\Comment", options={"id" = "comment_id"})
     */
    public function deleteAction(Notification $notification, Comment $comment)
    {
        $this->denyAccessUnlessGranted('access', $comment);

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Comment properly removed'), 'Comment');
    }
}
