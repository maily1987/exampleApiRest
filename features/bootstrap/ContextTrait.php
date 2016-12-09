<?php

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Sanpi\Behatch\Context\JsonContext;
use Sanpi\Behatch\Context\RestContext;
use Symfony\Bundle\FrameworkBundle\Client;

trait ContextTrait
{
    /**
     * @var MinkContext
     */
    public $minkContext;

    /**
     * @var JsonContext
     */
    public $jsonContext;

    /**
     * @var RestContext
     */
    public $restContext;

    /**
     * @var \FeatureContext
     */
    public $featureContext;

    /**
     * @var \OAuthContext
     */
    public $oauthContext;

    /**
     * @var \APIContext
     */
    public $apiContext;

    /**
     * @param BeforeScenarioScope $scope
     *
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();

        $this->minkContext = $environment->getContext('Behat\MinkExtension\Context\MinkContext');
        $this->jsonContext = $environment->getContext('Sanpi\Behatch\Context\JsonContext');
        $this->restContext = $environment->getContext('Sanpi\Behatch\Context\RestContext');
        $this->featureContext = $environment->getContext('FeatureContext');
        $this->oauthContext = $environment->getContext('OAuthContext');
        $this->apiContext = $environment->getContext('APIContext');
    }

    /**
     * @return Client
     */
    public function getMinkClient()
    {
        return $this->minkContext->getSession()->getDriver()->getClient();
    }

    /**
     * @return array
     */
    public function getJsonResponse($assoc = true)
    {
        return json_decode($this->getMinkClient()->getResponse()->getContent(), $assoc);
    }
}
