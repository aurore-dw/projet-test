<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        // Créer plusieurs utilisateurs avec le rôle ROLE_USER
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@email.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash('1234', PASSWORD_DEFAULT)); // Mot de passe par défaut
            $manager->persist($user);
        }

         // Créer plusieurs utilisateurs avec le rôle ROLE_ADMIN
        for ($i = 0; $i < 3; $i++) {
            $admin = new User();
            $admin->setUsername('admin' . $i);
            $admin->setEmail('admin' . $i . '@email.com');
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword(password_hash('1234', PASSWORD_DEFAULT)); // Mot de passe par défaut
            $manager->persist($admin);
        }

        // Création de tâches avec des dates aléatoires
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence(3)); // Génère un titre de 3 mots
            $task->setContent($faker->paragraph(3)); // Génère un contenu de 3 paragraphes
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->toggle($faker->boolean); // Génère une valeur booléenne aléatoire (true/false)
            $manager->persist($task);
        }

        // Enregistrez les utilisateurs dans la base de données
        $manager->flush();
    }
}
