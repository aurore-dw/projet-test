<?php

namespace App\Test\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();

        // Accéder à la page de connexion
        $crawler = $client->request('GET', '/login');

        // Vérifier que la page est accessible
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');

        // Remplir et soumettre le formulaire de connexion
        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'your_username';
        $form['password'] = 'your_password';
        $client->submit($form);

        // Vérifier que l'utilisateur est redirigé après une connexion réussie
        $this->assertResponseRedirects('/login'); // À modifier selon votre configuration de redirection

        // Suivre la redirection après la connexion
        $client->followRedirect();

        // Vérifier que l'utilisateur est bien connecté (par exemple, en vérifiant un élément de la page après connexion)
        $this->assertSelectorTextContains('h1', 'Connexion'); // À modifier selon votre page d'accueil
    }
}
