<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends FOSRestController
{
    /**
     * @Rest\Get("/articles")
     */
    public function getListArticleAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();
        if (!$articles) {
            return new View('There are no article exist', Response::HTTP_NOT_FOUND);
        }

        return $articles;
    }

    /**
     * @Rest\Get("/articles/{id}", requirements= {"id": "\d+"})
     */
    public function getArticleAction($id)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);
        if (!$article) {
            return new View('Artcile not found', Response::HTTP_NOT_FOUND);
        }

        return $article;
    }
	
	/**
     * @Rest\Get("/articles/{slug}")
     */
    public function getArticleBySlugAction($slug)
    {
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->findOneBy(array('slug' => $slug));
        if (!$article) {
            return new View('Artcile not found', Response::HTTP_NOT_FOUND);
        }

        return $article;
    }

    /**
     * @Rest\Post("/articles/")
     */
    public function postArticleAction(Request $request)
    {
        $article = new Article();
		
		$title = $request->get('title');
        $slug = $request->get('slug');
        $createdBy = $request->get('createdBy');
		
		if (!$title || !$slug || !$createdBy) {
			return new View('Null values are not allowed', Response::HTTP_NOT_ACCEPTABLE);
		}

        $article->setTitle($request->get('title'));
        $article->setLeadingArticle($request->get('leadingArticle'));
        $article->setBody($request->get('body'));
        $article->setSlug($request->get('slug'));
        $article->setCreatedBy($request->get('createdBy'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
		
		if (!$article->getId()) {
			return new View('Article has not been added', 400);
		}

        return new View('Article added Successfully', Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteArticleAction($id)
    {
        $data = new Article();
        $em = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find($id);
        if (empty($article)) {
            return new View('Article not found', Response::HTTP_NOT_FOUND);
        }
        $em->remove($article);
        $em->flush();

        return new View('Article deleted successfully', Response::HTTP_OK);
    }
}
