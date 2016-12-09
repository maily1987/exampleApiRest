<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class ArticleV2Controller extends FOSRestController
{
    /**
     * @Rest\Get("/articles_v2/{article_id}", requirements= {"id": "\d+"})
     * @ParamConverter("article", options={"id" = "article_id"})
     */
    public function getArticleAction(Article $article)
    {
        if (!$article) {
            return new View('Artcile not found', Response::HTTP_NOT_FOUND);
        }

        return $article;
    }

    /**
     * @Rest\Get("/articles_v2/{artilce_slug}")
     * @ParamConverter("artilce", options={"mapping": {"artilce_slug": "slug"}})
     */
    public function getArticleBySlugAction(Article $artilce)
    {
        if (!$artilce) {
            return new View('Artcile not found', Response::HTTP_NOT_FOUND);
        }

        return $artilce;
    }
}
