<?php

namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Subscribe;
use ApiBundle\Form\Type\SubscribeType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubscribeController
 */
class SubscribeController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Add email to subscribe list",
     *  parameters={
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Address email"}
     *  })
     * )
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(SubscribeType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }
        /** @var Subscribe $subscribe */
        $subscribe = $form->getData();
        $subscribe->setSecret(Uuid::uuid4()->toString());
        $em = $this->getDoctrine()->getManager();
        $em->persist($subscribe);
        $em->flush();

        return $this->success($subscribe, 'Subscribe', Response::HTTP_CREATED, array('SUBSCRIBE_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Remove email to subscribe list",
     * )
     * @param Subscribe $subscribe
     * @return Response
     * @ParamConverter("subscribe", class="AccessibilityBarriersBundle\Entity\Subscribe", options={"secret" = "secret"})
     */
    public function deleteAction(Subscribe $subscribe)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subscribe);
        $em->flush();

        return $this->success(array('status' => 'Success', 'message' => 'Email properly removed'), 'Subscribe');
    }
}
