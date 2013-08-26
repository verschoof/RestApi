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
     *  filters={
     *      {"name"="test"}
     *  },
     *  input={
     *      {"parameters"="array"}
     *  }
     * )
     *
     * @param array $parameters An array with parameters to create the user
     *
     * @Rest\View
     */
    public function newAction($parameters)
    {
        $userService = $this->get('mv_api.userService');
        $user        = $userService->newInstance();

        $result = $this->processForm($user);

        return $result;
    }

    public function editAction(User $user)
    {
        return $this->processForm($user);
    }

    /**
     * @Rest\View(statusCode=204)
     */
    public function removeAction(User $user)
    {
        $user->delete();
    }

    public function getFriendsAction(User $user)
    {
        return array('friends' => $user->getFriends());
    }

    /**
     * @Rest\View(statusCode=204)
     */
    public function linkAction(User $user, Request $request)
    {
        if (!$request->attributes->has('links')) {
            throw new HttpException(400);
        }

        foreach ($request->attributes->get('links') as $u) {
            if (!$u instanceof User) {
                throw new NotFoundHttpException('Invalid resource');
            }

            if ($user->hasFriend($u)) {
                throw new HttpException(409, 'Users are already friends');
            }

            $user->addFriend($u);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

    public function patchAction(User $user, Request $request)
    {
        $parameters = array();
        foreach ($request->request->all() as $k => $v) {
            // whitelist
            if (in_array($k, array('email'))) {
                $parameters[$k] = $v;
            }
        }

        if (0 === count($parameters)) {
            return View::create(
                array('errors' => array('Invalid parameters.')), 400
            );
        }

        $user->fromArray($parameters);
        $errors = $this->get('validator')->validate($user);

        if (0 < count($errors)) {
            return View::create(array('errors' => $errors), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $response = new Response();
        $response->setStatusCode(204);
        $response->headers->set('Location',
            $this->generateUrl(
                'acme_demo_user_get', array('id' => $user->getId()),
                true // absolute
            )
        );

        return $response;
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
                $response->headers->set('Location',
                    $this->generateUrl(
                        'api_user_get',
                        array('id' => $user->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        return View::create($form, 400);
    }
}
