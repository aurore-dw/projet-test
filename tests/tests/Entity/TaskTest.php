<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

	public function testGettersAndSetters()
    {
    	//Créer une instance de la classe User
    	$task = new Task();

    	// Définit des valeurs pour les propriétés
    	$task->setTitle('title');
    	$task->setContent('content');
    	$date = new \DateTimeImmutable();
    	$task->setCreatedAt($date);

    	// Vérifie que les valeurs sont correctement récupérées
        $this->assertEquals('title', $task->getTitle());
        $this->assertEquals('content', $task->getContent());
        $this->assertEquals($date, $task->getCreatedAt());
    }





}