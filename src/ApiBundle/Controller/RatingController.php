<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Notification;
use AccessibilityBarriersBundle\Entity\Rating;
use ApiBundle\Form\Type\RatingType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RatingController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Rating notification",
     *  parameters={
     *      {"name"="content", "dataType"="string", "required"=true, "description"="Content of comment"}
     *  })
     * )
     * @param Request $request
     * @param Notification $notification
     * @return Response
     * @ParamConverter("notification", class="AccessibilityBarriersBundle\Entity\Notification", options={"id" = "notification_id"})
     */
    public function ratingAction(Request $request, Notification $notification)
    {
        /** @var Rating $rating */
        foreach ($notification->getRatings() as $rating) {
            if ($rating->getUser() == $this->getUser()) {
                return JsonResponse::create(['status' => 'ERROR'], Response::HTTP_CREATED);
            }
        }

        $form = $this->createForm(RatingType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var Rating $rating */
        $rating = $form->getData();
        $rating->setNotification($notification);
        $rating->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($rating);
        $em->flush();

        return JsonResponse::create(['status' => 'SAVED'], Response::HTTP_CREATED);
    }
}
