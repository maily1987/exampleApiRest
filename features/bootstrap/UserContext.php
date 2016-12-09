<?php

use AppBundle\Entity\PasswordToken;
use AppBundle\Entity\Article;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Gorghoa\ScenarioStateBehatExtension\Annotation\ScenarioStateArgument;
use Gorghoa\ScenarioStateBehatExtension\Context\ScenarioStateAwareContext;
use Gorghoa\ScenarioStateBehatExtension\Context\ScenarioStateAwareTrait;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArticleContext implements Context, ScenarioStateAwareContext
{
    use ScenarioStateAwareTrait,
        ContextTrait;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->manager = $doctrine->getManager();
    }

    /**
     * @When I list articles
     */
    public function iListArticles()
    {
        $this->apiContext->iSendAJsonLdRequestTo('GET', '/articles');
    }

    /**
     * @When I get article :id
     */
    public function iGetArticle($id)
    {
        $this->apiContext->iSendAJsonLdRequestTo('GET', '/articles/'.$id);
    }

    /**
     * @Then I should get a valid list of :nbArticles articles
     */
    public function iShouldGetAValidListOfArticles($expectedNbArticles)
    {
        $this->apiContext->theJsonLdResponseShouldHaveStatusCode(200);

        $this->jsonContext->theJsonNodeShouldContain('@id', '/articles');
        $this->jsonContext->theJsonNodeShouldBeEqualToTheString('@type', 'hydra:Collection');
        $this->jsonContext->theJsonNodeShouldBeEqualToTheNumber('hydra:totalItems', $expectedNbArticles);
    }

    /**
     * @Then I should get valid article :id data
     */
    public function iShouldGetValidArticlData($id)
    {
        $this->apiContext->theJsonLdResponseShouldHaveStatusCode(200);

        $this->jsonContext->theJsonNodeShouldContain('@id', '/articles/'.$id);
        $this->jsonContext->theJsonNodeShouldBeEqualToTheString('@type', 'Article');
    }

}
