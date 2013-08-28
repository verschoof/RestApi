<?php

namespace MV\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lsw\ApiCallerBundle\Call\HttpGetJson;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\JsonResponse;

class AuthorController extends Controller
{
    /**
     * Get a list of all the authors
     *
     * @ApiDoc()
     *
     * @Rest\View
     */
    public function allAction()
    {
        $authorService = $this->get('mv_api.authorService');
        $authors       = $authorService->findAll();

        return array(
            'authors' => $authors
        );
    }

    /**
     * Get a single author by unique id
     *
     * @ApiDoc(
     *  statusCodes={
     *         200="Returned when successful",
     *         403="Returned when the author is not authorized to get this author",
     *         404={
     *           "Returned when the author is not found"
     *         }
     *     }
     * )
     *
     * @param integer $id The unique author id
     *
     * @Rest\View
     */
    public function getAction($id)
    {
        $authorService = $this->get('mv_api.authorService');
        $author        = $authorService->find($id);

        if (!$author) {
            throw new NotFoundHttpException('author not found');
        }

        return array(
            'author' => $author
        );
    }
}
