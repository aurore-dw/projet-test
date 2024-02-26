<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private string $path = '/users/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(User::class);
        $this->manager = static::getContainer()->get('doctrine')->getManager();

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('To Do List app');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        //$this->markTestIncomplete();
        $this->client->request('GET', sprintf('%screate', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[username]' => 'Testing', 
            'user[password][first]' => 'Testing', 
            'user[password][second]' => 'Testing', 
            'user[email]' => 'test@mail.fr', 
            'user[roles]' => ['ROLE_USER'],
        ]);

        self::assertResponseRedirects('/users/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /*public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }*/

    public function testEdit(): void
    {
        //$this->markTestIncomplete();
        $fixture = new User();
        $fixture->setUsername('User');
        $fixture->setPassword('user');
        $fixture->setEmail('email@email.fr');
        $fixture->setRoles(['ROLE_USER']); 

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Modifier', [
            'user[username]' => 'Something New',
            'user[password][first]' => 'Something New', 
            'user[password][second]' => 'Something New', 
            'user[email]' => 'newmail@email.fr',
            'user[roles]' => ['ROLE_USER'], 
        ]);

        self::assertResponseRedirects('/users/');

        $updatedUser = $this->repository->find($fixture->getId());

        self::assertSame('Something New', $updatedUser->getUsername());
        self::assertSame('Something New', $updatedUser->getPassword());
        self::assertSame('newmail@email.fr', $updatedUser->getEmail());
        self::assertSame(['ROLE_USER'], $updatedUser->getRoles()); 
    }

    /*public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new User();
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/users/');
    }*/
}
