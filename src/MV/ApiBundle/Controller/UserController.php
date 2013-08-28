<?php

namespace MV\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\JsonResponse;

use MV\ApiBundle\Form\Type;
use MV\ApiBundle\Entity\User;

class UserController extends Controller
{
    /**
     * Get a list of all the users
     *
     * @ApiDoc()
     *
     * @Rest\View
     */
    public function allAction()
    {
        $userService = $this->get('mv_api.userService');
        $users       = $userService->findAll();

        return array(
            'users' => $users
        );
    }

    /**
     * Get a single user by unique id
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to get this user",
     *         404={
     *           "Returned when the user is not found"
     *         }
     *     }
     * )
     *
     * @param integer $id The unique user id
     *
     * @Rest\View
     */
    public function getAction($id)
    {
        $userService = $this->get('mv_api.userService');
        $user        = $userService->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return array(
            'user' => $user
        );
    }

    /**
     * Create a new user
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to create a new user",
     *         404={
     *           "Returned when there is a error"
     *         }
     *     },
     *  input="MV\ApiBundle\Form\Type\UserType",
     *  output="MV\ApiBundle\Entity\User"
     * )
     *
     * @Rest\View
     */
    public function newAction()
    {
        $userService = $this->get('mv_api.userService');
        $user        = $userService->newInstance();

        return $this->processForm($user);
    }

    protected function processForm(User $user)
    {
        $statusCode = $user->getId() ? 201 : 204;

        $form = $this->createForm(new Type\UserType(), $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush($user);
            $em->persist($user);

            $response = new Response();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (201 === $statusCode) {
                $response->headers->set('Location', $this->generateUrl(
                    'api_user_get',
                    array('id' => $user->getId()),
                    true // absolute
                ));
            }

            return $response;
        }

        return View::create($form, 400);
    }
}
