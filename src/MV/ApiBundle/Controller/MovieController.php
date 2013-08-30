<?php

namespace MV\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends Controller
{
    /**
     * Get a list of all the movies
     *
     * @ApiDoc()
     *
     * @Rest\View
     */
    public function allAction()
    {
        $movieService = $this->get('mv_api.movieService');
        $movies       = $movieService->findAll();

        return array(
            'movies' => $movies
        );
    }

    /**
     * Get a single movie by unique id
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to get this movie",
     *         404={
     *           "Returned when the movie is not found"
     *         }
     *     },
     *  output="MV\ApiBundle\Entity\Movie"
     * )
     *
     * @param integer $id The unique movie id
     *
     * @Rest\View
     */
    public function getAction($id)
    {
        $movieService = $this->get('mv_api.movieService');
        $movie        = $movieService->find($id);

        if (!$movie) {
            throw new NotFoundHttpException('movie not found');
        }

        return array(
            'movie' => $movie
        );
    }

    /**
     * Get an array of authors of a movie
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to get this movie",
     *         404={
     *           "Returned when the movie is not found"
     *         }
     *     }
     * )
     *
     * @param integer $id The unique movie id
     *
     * @Rest\View
     */
    public function getAuthorsAction($id)
    {
        $movieService = $this->get('mv_api.movieService');
        $movie        = $movieService->find($id);

        if (!$movie) {
            throw new NotFoundHttpException('movie not found');
        }

        return array(
            'authors' => $movie->getAuthors()
        );
    }

    /**
     * Get an array of users that watched the movie
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the user is not authorized to get this movie",
     *         404={
     *           "Returned when the movie is not found"
     *         }
     *     }
     * )
     *
     * @param integer $id The unique movie id
     *
     * @Rest\View
     */
    public function getWatchersAction($id)
    {
        $movieService = $this->get('mv_api.movieService');
        $movie        = $movieService->find($id);

        if (!$movie) {
            throw new NotFoundHttpException('movie not found');
        }

        return array(
            'watchers' => $movie->getWatchedBy()
        );
    }
}
