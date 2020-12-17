<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur ce blog',
        ]);
    }

    /**
     * @Route("/blog/new", name="article_create")
     * @Route("/blog/{id}/edit", name="article_edit")
     */
    public function form(Article $article = null,Request $request, EntityManagerInterface $manager): Response
    {
        if(!$article) {
            $article = new Article();
        }

        // On crée un formulaire avec les différents champs d'un article
        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig', [
            'articleForm' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article): Response
    {
        return $this->render('blog/show.html.twig', [
            "article" => $article
        ]);
    }

}
