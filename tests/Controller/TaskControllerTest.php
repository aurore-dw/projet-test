<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Annotation\Route;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private TaskRepository $repository;
    private string $path = '/tasks/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Task::class);
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

        $this->client->submitForm('Ajouter', [
            'task[title]' => 'Testing',
            'task[content]' => 'Testing',
        ]);

        self::assertResponseRedirects('/tasks/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /*public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Task();
        $fixture->setCreatedAt('My Title');
        $fixture->setTitle('My Title');
        $fixture->setContent('My Title');
        $fixture->setIsDone('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Task');

        // Use assertions to check that the properties are properly displayed.
    }*/

    public function testEdit(): void
{
    //$this->markTestIncomplete();
    $fixture = new Task();
    $fixture->setCreatedAt(new \DateTimeImmutable()); // Utilisez un objet DateTimeImmutable pour la création
    $fixture->setTitle('My Title');
    $fixture->setContent('My Title');
    //$fixture->IsDone(true);

    $this->manager->persist($fixture);
    $this->manager->flush();

    $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

    $this->client->submitForm('Modifier', [
        'task[title]' => 'Something New',
        'task[content]' => 'Something New',
    ]);

    self::assertResponseRedirects('/tasks/');

    $fixture = $this->repository->findAll();

    self::assertInstanceOf(\DateTimeImmutable::class, $fixture[0]->getCreatedAt()); // Vérifiez que c'est une instance de DateTimeImmutable
    self::assertSame('Something New', $fixture[0]->getTitle());
    self::assertSame('Something New', $fixture[0]->getContent());
    //self::assertSame(true, $fixture[0]->IsDone()); // Utilisez la valeur attendue pour IsDone
}


    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Task();
        $fixture->setCreatedAt(new \DateTimeImmutable());
        $fixture->setTitle('My Title');
        $fixture->setContent('My Content');
        $fixture->IsDone(true);

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Supprimer');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/tasks/');
    }
}
