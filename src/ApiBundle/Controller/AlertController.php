<?php

namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Repository\AlertRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AccessibilityBarriersBundle\Entity\Alert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class AlertController
 */
class AlertController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Return all active alerts",
     * )
     * @return Response
     */
    public function indexAction()
    {
        $current_user = $this->getUser();
        /** @var AlertRepository $alertRepository */
        $alertRepository = $this->getRepository(Alert::class);
        $alerts = $alertRepository->getActiveAlertByUser($current_user);

        return $this->success($alerts, 'Alert', Response::HTTP_OK, array(
            'ALERT_LIST',
            'NOTIFICATION_BASIC'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Deactivate alert",
     * )
     * @param Alert $alert
     * @return Response
     * @ParamConverter("alert", class="AccessibilityBarriersBundle\Entity\Alert", options={"id" = "id"})
     */
    public function deactivateAction(Alert $alert)
    {
        $this->denyAccessUnlessGranted('access', $alert);

        $alert->setActive(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($alert);
        $em->flush();

        return $this->success(array('status' => 'Success', 'message' => 'Alert properly deactivate'), 'Alert');
    }
}
