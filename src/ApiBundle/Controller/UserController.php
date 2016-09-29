<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\RegisterType;
use ApiBundle\Model\Register;
use OAuthBundle\Model\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 */
class UserController extends BaseController
{

    public function profileAction()
    {
        $current_user = $this->getUser();

        return $this->success($current_user, 'user', Response::HTTP_OK, array('USER_PROFILE'));
    }

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
