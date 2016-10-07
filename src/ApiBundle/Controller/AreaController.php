<?php
namespace ApiBundle\Controller;

use AccessibilityBarriersBundle\Entity\Area;
use ApiBundle\Form\Type\AreaType;
use OAuthBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AreaController extends BaseController
{
    /**
     * @ApiDoc(
     *  description="Return all user area"
     * )
     * @param User $user
     * @return Response
     * @ParamConverter("user", class="OAuthBundle\Entity\User", options={"id" = "user_id"})
     */
    public function indexAction(User $user)
    {
        $areas = $user->getAreas();
        $this->denyAccessUnlessGranted('access', $areas->first());

        return $this->success($areas, 'Area', Response::HTTP_OK, array('AREA_LIST'));
    }

    /**
     * @ApiDoc(
     *  description="Create new user area",
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="Name of notification"},
     *      {"name"="latitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="longitude", "dataType"="string", "required"=true, "description"="Position od notify issue"},
     *      {"name"="distance", "dataType"="integer", "required"=true, "description"="Distance in KM"}
     *  })
     * )
     * @param Request $request
     * @param User $user
     * @return Response
     * @ParamConverter("user", class="OAuthBundle\Entity\User", options={"id" = "user_id"})
     */
    public function createAction(Request $request, User $user)
    {
        $form = $this->createForm(AreaType::class);
        $submittedData = json_decode($request->getContent(), true);
        $form->submit($submittedData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }
        /** @var Area $area */
        $area = $form->getData();
        $area->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $em->persist($area);
        $em->flush();

        return $this->success($area, 'Area', Response::HTTP_CREATED, array(
            'AREA_DETAILS',
            'USER_BASIC'
        ));
    }

    /**
     * @ApiDoc(
     *  description="Delete user area"
     *)
     * @param User $user
     * @param Area $area
     * @return Response
     * @ParamConverter("user", class="OAuthBundle\Entity\User", options={"id" = "user_id"})
     * @ParamConverter("area", class="AccessibilityBarriersBundle\Entity\Area", options={"id" = "area_id"})
     */
    public function deleteAction(User $user, Area $area)
    {
        $this->denyAccessUnlessGranted('access', $area);

        $em = $this->getDoctrine()->getManager();
        $em->remove($area);
        $em->flush();

        return $this->success(array('status' => 'Removed', 'message' => 'Area properly removed'), 'Area');
    }
}
