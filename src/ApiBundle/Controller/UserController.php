<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\Type\RegisterType;
use ApiBundle\Form\Type\UserType;
use ApiBundle\Model\Register;
use ApiBundle\Model\User as UserData;
use OAuthBundle\Entity\User;
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

    /**
     * @ApiDoc(
     *  description="This method register new user",
     *  parameters={
     *      {"name"="first_name", "dataType"="string", "required"=true, "description"="User first name"},
     *      {"name"="last_name", "dataType"="string", "required"=true, "description"="User last name"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="User email"},
     *      {"name"="email_notification", "dataType"="boolean", "required"=true, "description"="Allow email notification"},
     *  })
     * )
     * @param Request $request
     * @return mixed
     */
    public function updateAction(Request $request)
    {
        $form = $this->get('form.factory')->create(UserType::class);
        $formData = json_decode($request->getContent(), true);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->error($this->getFormErrorsAsArray($form));
        }

        /** @var UserData $userData */
        $userData = $form->getData();

        /** @var User $user */
        $user = $this->getUser();
        $user->setFirstName($userData->firstName);
        $user->setLastName($userData->lastName);
        $user->setEmail($userData->email);
        $user->setEmailNotification($userData->emailNotification);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->success($user, 'user', Response::HTTP_OK, array('USER_PROFILE'));
    }
}
