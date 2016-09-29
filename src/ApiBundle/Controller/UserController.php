<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\RegisterType;
use ApiBundle\Model\Register;
use OAuthBundle\Model\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class UserController
 */
class UserController extends BaseController
{

    /**
     * @ApiDoc(
     *  description="Return information about current user",
     * )
     * @return Response
     */
    public function profileAction()
    {
        $current_user = $this->getUser();

        return $this->success($current_user, 'user', Response::HTTP_OK, array('USER_PROFILE'));
    }

    /**
     * @ApiDoc(
     *  description="This method register new user",
     *  parameters={
     *      {"name"="username", "dataType"="string", "required"=true, "description"="User name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="User email"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="User password"},
     *      {"name"="client_id", "dataType"="string", "required"=true},
     *      {"name"="client_secret", "dataType"="string", "required"=true}
     *  })
     * )
     * @param Request $request
     * @return mixed
     */
    public function registerAction(Request $request)
    {
        $form = $this->get('form.factory')->create(RegisterType::class);
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var Register $data */
        $data = $form->getData();

        /** @var UserFactory $userFactory */
        $userFactory = $this->get('oauth.user_factory');
        $user = $userFactory->buildAfterRegistration(
            $data->firstName,
            $data->lastName,
            $data->username,
            $data->email,
            $data->password
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $accessToken = $this->get('oauth.token_factory');
        $token = $accessToken->build($user, $data);

        return $token;
    }
}
