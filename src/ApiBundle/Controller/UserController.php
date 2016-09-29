<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ApiBundle\Form\Type\LoginType;
use ApiBundle\Form\Type\RegisterType;
use PizzaBundle\Model\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package ApiBundle\Controller
 * @ApiDoc()
 */
class UserController extends BaseController
{

    public function profileAction()
    {
        $current_user = $this->getUser();

        return $this->success($current_user, 'user', Response::HTTP_OK, array('Default', 'Profile'));
    }

    public function registerAction(Request $request)
    {
        $form = $this->get('form.factory')->create(new RegisterType());
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getErrorMessages($form));
        }
        /** @var Register $data */
        $data = $form->getData();

        $userFactory = $this->get('pizza_user_factory');
        $user = $userFactory->buildAfterRegistration($data->username, $data->email, $data->password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $accessToken = $this->get('pizza_token_builder');
        $token = $accessToken->build($user, $data);

        return $token;
    }
}
