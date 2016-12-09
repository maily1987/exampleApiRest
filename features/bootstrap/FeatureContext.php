<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Profiler\Profile;

class FeatureContext extends RawMinkContext implements Context
{
    use ContextTrait;

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
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $purger = new ORMPurger($this->manager);
        $purger->purge(ORMPurger::PURGE_MODE_TRUNCATE);
    }

    /**
     * @throws UnsupportedDriverActionException
     * @throws \RuntimeException
     *
     * @return Profile
     */
    public function getSymfonyProfile()
    {
        $profile = $this->getMinkClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException('The profiler is disabled.');
        }

        return $profile;
    }

    /**
     * @Then I should receive an email with subject :expectedSubject
     * @Then I should receive an email with subject :expectedSubject on position :mailPosition
     */
    public function iShouldReceiveAnEmailWithSubject($expectedSubject, $mailPosition = 0)
    {
        $profile = $this->featureContext->getSymfonyProfile();
        /** @var \Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector $collector */
        $collector = $profile->getCollector('swiftmailer');

        if (0 === $collector->getMessageCount()) {
            throw new ErrorException(sprintf('No message sent.'));
        }

        if (!isset($collector->getMessages()[$mailPosition])) {
            throw new ErrorException(sprintf('No message on position "%s".', $mailPosition));
        }

        $messageSubject = $collector->getMessages()[$mailPosition]->getSubject();
        \PHPUnit_Framework_Assert::assertEquals($expectedSubject, $messageSubject);
    }
}
