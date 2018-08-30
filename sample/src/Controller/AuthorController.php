<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Dto\AuthorDto;
use App\Application\Exception\AuthorNotFound;
use App\Application\Service\AuthorService;
use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as OpenApi;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/authors")
 */
final class AuthorController
{
    private $service;

    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Get(
     *     tags={"author"},
     *     summary="List authors.",
     *     @OpenApi\Response(
     *         response=200,
     *         description="Authors list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=AuthorDto::class)))
     *     )
     * )
     *
     * @Route("", methods={"GET"})
     * @Serialize()
     */
    public function listAction(): array
    {
        return $this->service->fetchAll();
    }

    /**
     * @OpenApi\Get(
     *     tags={"author"},
     *     summary="Get author.",
     *     @OpenApi\Response(
     *         response=200,
     *         description="Author info.",
     *         @OpenApi\Schema(ref=@Model(type=AuthorDto::class))
     *     ),
     *     @OpenApi\Response(
     *         response=404,
     *         description="Author not found."
     *     )
     * )
     *
     * @Route("/{id}", methods={"GET"}, name="author_view")
     * @Serialize()
     *
     * @throws NotFoundHttpException
     */
    public function viewAction(string $id): AuthorDto
    {
        try {
            return $this->service->fetch($id);
        } catch (AuthorNotFound $e) {
            throw new NotFoundHttpException();
        }
    }
}
