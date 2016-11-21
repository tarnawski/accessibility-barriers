<?php

namespace ApiBundle\Behat;

use AccessibilityBarriersBundle\Entity\Alert;
use AccessibilityBarriersBundle\Entity\Area;
use AccessibilityBarriersBundle\Entity\Category;
use AccessibilityBarriersBundle\Entity\Comment;
use AccessibilityBarriersBundle\Entity\Notification;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Behat\WebApiExtension\Context\WebApiContext;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Coduo\PHPMatcher\Matcher;
use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Model\TokenInterface;
use OAuthBundle\Entity\AccessToken;
use OAuthBundle\Entity\Client;
use OAuthBundle\Entity\RefreshToken;
use OAuthBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

/**
 * Defines application features from the specific context.
 */
class ApiContext extends WebApiContext implements Context, SnippetAcceptingContext, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @BeforeScenario @cleanDB
     * @AfterScenario @cleanDB
     */
    public function cleanDB()
    {
        $application = new Application($this->getKernel());
        $application->setAutoExit(false);
        $application->run(new StringInput("doctrine:schema:drop --force -n -q"));
        $application->run(new StringInput("doctrine:schema:create -n -q"));
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @param PyStringNode $string
     * @Then the JSON response should match:
     */
    public function theJsonResponseShouldMatch(PyStringNode $string)
    {
        $factory = new SimpleFactory();
        $matcher = $factory->createMatcher();
        $match = $matcher->match($this->response->getBody()->getContents(), $string->getRaw());
        \PHPUnit_Framework_Assert::assertTrue($match, $matcher->getError());
    }

    private function createToken(TokenInterface $token, $row)
    {
        $reflectionClass = new \ReflectionClass(get_class($token));
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($token, $row['ID']);
        $token->setToken($row['TOKEN']);
        $expiresAt = new \DateTime($row['EXPIRES_AT']);
        $token->setExpiresAt($expiresAt->getTimestamp());
        /** @var Client $client */
        $client = $this->getManager()->getReference(Client::class, $row['CLIENT']);
        $token->setClient($client);
        /** @var User $user */
        $user = $this->getManager()->getReference(User::class, $row['USER']);
        $token->setUser($user);
        $this->getManager()->persist($token);
        $metadata = $this->getManager()->getClassMetaData(get_class($token));
        $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_CUSTOM);
    }

    /**
     * @param TableNode $table
     * @Given There are the following clients:
     */
    public function thereAreTheFollowingClients(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $client = new Client();
            $reflectionClass = new \ReflectionClass(Client::class);
            $id = $reflectionClass->getProperty('id');
            $id->setAccessible(true);
            $id->setValue($client, $row['ID']);
            $client->setRandomId($row['RANDOM_ID']);
            $client->setSecret($row['SECRET']);
            $client->setRedirectUris(explode(',', $row['URL']));
            $client->setAllowedGrantTypes(explode(',', $row['GRANT_TYPES']));

            $this->getManager()->persist($client);
        }

        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given there are the following users:
     */
    public function thereAreTheFollowingUsers(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $user = new User();
            $user->setFirstName($row['FIRST_NAME']);
            $user->setLastName($row['LAST_NAME']);
            $user->setEmailNotification(false);
            $user->setUsername($row['USERNAME']);
            $user->setEmail($row['EMAIL']);
            $user->setPlainPassword($row['PASSWORD']);
            $user->setSuperAdmin(boolval($row['SUPERADMIN']));
            $user->setEnabled($row['ENABLED']);
            $user->setRoles(explode(',', $row['ROLE']));
            $this->getManager()->persist($user);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following categories:
     */
    public function thereAreTheFollowingCategories(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $category = new Category();
            $category->setName($row['NAME']);
            $this->getManager()->persist($category);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following refresh tokens:
     */
    public function thereAreTheFollowingRefreshTokens(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $accessToken = new RefreshToken();
            $this->createToken($accessToken, $row);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following access tokens:
     */
    public function thereAreTheFollowingAccessTokens(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $accessToken = new AccessToken();
            $this->createToken($accessToken, $row);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following notifications:
     */
    public function thereAreTheFollowingNotifications(TableNode $table)
    {
        $categoryRepository = $this->getManager()->getRepository(Category::class);
        $userRepository = $this->getManager()->getRepository(User::class);
        foreach ($table->getColumnsHash() as $row) {
            $notification = new Notification();
            $notification->setName($row['NAME']);
            $notification->setDescription($row['DESCRIPTION']);
            $notification->setLatitude($row['LATITUDE']);
            $notification->setLongitude($row['LONGITUDE']);
            $notification->setAddress($row['ADDRESS']);
            $now = new \DateTime();
            $datetime = $now->modify($row['CREATED_AT']);
            $notification->setCreatedAt($datetime);
            /** @var Category $category */
            $category = $categoryRepository->find($row['CATEGORY_ID']);
            $notification->setCategory($category);
            /** @var User $user */
            $user = $userRepository->find($row['USER_ID']);
            if ($user) {
                $notification->setUser($user);
            }
            $this->getManager()->persist($notification);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following comments:
     */
    public function thereAreTheFollowingComments(TableNode $table)
    {
        $notificationRepository = $this->getManager()->getRepository(Notification::class);
        $userRepository = $this->getManager()->getRepository(User::class);
        foreach ($table->getColumnsHash() as $row) {
            $comment = new Comment();
            $comment->setContent($row['CONTENT']);
            $now = new \DateTime();
            $datetime = $now->modify($row['CREATED_AT']);
            $comment->setCreatedAt($datetime);
            /** @var Notification $notification */
            $notification = $notificationRepository->find($row['NOTIFICATION_ID']);
            $comment->setNotification($notification);
            /** @var User $user */
            $user = $userRepository->find($row['USER_ID']);
            $comment->setUser($user);
            $this->getManager()->persist($comment);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @param TableNode $table
     * @Given There are the following alerts:
     */
    public function thereAreTheFollowingAlerts(TableNode $table)
    {
        $notificationRepository = $this->getManager()->getRepository(Notification::class);
        $userRepository = $this->getManager()->getRepository(User::class);
        $commentRepository = $this->getManager()->getRepository(Comment::class);

        foreach ($table->getColumnsHash() as $row) {
            $alert = new Alert();
            $active = $row['ACTIVE'] == 'TRUE' ? true : false;
            $alert->setActive($active);
            $now = new \DateTime();
            $datetime = $now->modify($row['CREATED_AT']);
            $alert->setCreatedAt($datetime);
            /** @var Notification $notification */
            $notification = $notificationRepository->find($row['NOTIFICATION_ID']);
            $alert->setNotification($notification);
            /** @var User $user */
            $user = $userRepository->find($row['USER_ID']);
            $alert->setUser($user);
            $comment = $commentRepository->find($row['COMMENT_ID']);
            $alert->setComment($comment);
            $this->getManager()->persist($alert);
        }
        $this->getManager()->flush();
        $this->getManager()->clear();
    }
}
