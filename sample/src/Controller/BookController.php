<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Command\CreateBook;
use App\Application\Dto\BookCreationDto;
use App\Application\Dto\BookDto;
use App\Application\Exception\BookNotFound;
use App\Application\Service\BookService;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Deserialize;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Nelmio\ApiDocBundle\Annotation\Model;
use Pagerfanta\Pagerfanta;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books")
 */
final class BookController
{
    private $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Get(
     *     tags={"book"},
     *     summary="List books.",
     *     @OpenApi\Parameter(
     *         name="authorId",
     *         type="string",
     *         in="query",
     *         description="Author ID."
     *     ),
     *     @OpenApi\Parameter(
     *         name="page",
     *         type="integer",
     *         in="query",
     *         description="Page number.",
     *         default=1
     *     ),
     *     @OpenApi\Response(
     *         response=200,
     *         description="Books list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=BookDto::class)))
     *     )
     * )
     *
     * @Route("", methods={"GET"})
     * @Serialize()
     */
    public function listAction(Request $request): Pagerfanta
    {
        return $this->service
            ->fetchRange($request->query->get('authorId'))
            ->setAllowOutOfRangePages(true)
            ->setMaxPerPage(10)
            ->setCurrentPage($request->query->getInt('page', 1))
        ;
    }

    /**
     * @OpenApi\Get(
     *     tags={"book"},
     *     summary="Get book.",
     *     @OpenApi\Response(
     *         response=200,
     *         description="Book info.",
     *         @OpenApi\Schema(ref=@Model(type=BookDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Book not found."
     *     )
     * )
     *
     * @Route("/{id}", methods={"GET"})
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function viewAction(string $id): BookDto
    {
        try {
            return $this->service->fetch($id);
        } catch (BookNotFound $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @OpenApi\Post(
     *     tags={"book"},
     *     summary="Create book.",
     *     @OpenApi\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @OpenApi\Schema(ref=@Model(type=BookCreationDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=201,
     *         description="Book info.",
     *         @OpenApi\Schema(ref=@Model(type=BookDto::class))
     *     )
     * )
     *
     * @Route("", methods={"POST"})
     * @Deserialize(BookCreationDto::class, validate=true, param="book")
     * @Serialize()
     */
    public function createAction(BookCreationDto $book): BookDto
    {
        return $this->service->create(new CreateBook($book));
    }
}
