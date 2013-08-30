<?php

namespace MV\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Response;

use MV\ApiBundle\Form\Type;
use MV\ApiBundle\Entity\User;
use MV\ApiBundle\Entity\Movie;

class UserController extends FOSRestController
{
    /**
     * Get a list of all the users
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to get the user list",
     *     }
     * )
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
     *     },
     *  output="MV\ApiBundle\Entity\User"
     * )
     *
     * @param integer $id The unique user id
     *
     * @Rest\View
     */
    public function getAction(User $user)
    {
        return array(
            'user' => $user
        );
    }

    /**
     * Get an array of wachted movies of the user
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
    public function getMoviesAction(User $user)
    {
        return array(
            'movies' => $user->getWatchedMovies()
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

    /**
     * Delete a user by unique id
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when user successful deleted",
     *         403="Returned when the user is not authorized to delete a new user",
     *         404={
     *           "Returned when there is a error"
     *         }
     *     }
     *  )
     * )
     *
     * @Rest\View
     */
    public function removeAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return View::create('User successfully deleted', 200);
    }

    /**
     * Link Action?
     *
     * @ApiDoc()
     *
     * @Rest\View(statusCode=204)
     */
    public function linkAction(User $user, Request $request)
    {
        if (!$request->attributes->has('links')) {
            throw new HttpException(400);
        }

        foreach ($request->attributes->get('links') as $movie) {
            if (!$movie instanceof Movie) {
                throw new NotFoundHttpException('Invalid resource');
            }

            if ($user->hasWachtedMovie($movie)) {
                throw new HttpException(409, 'User are already has this movie');
            }

            $user->addWachtedMovie($movie);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

    protected function processForm(User $user)
    {
        $statusCode = $user->getId() ? 201 : 204;

        $form = $this->createForm(new Type\UserType(), $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $response = new Response();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            if (204 === $statusCode) {
                $response->headers->set('Location', $this->generateUrl('api_user_get', array('id' => $user->getId()), true));
            }

            return $response;
        }

        return View::create($form, 400);
    }
}
