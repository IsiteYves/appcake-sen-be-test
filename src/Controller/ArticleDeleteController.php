<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleDeleteController extends AbstractController
{
    /**
     * @Route("/news/delete/{id}", name="news_delete")
     */
    public function index($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(News::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id ' . $id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('news');
    }
}
