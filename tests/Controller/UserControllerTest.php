<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {

    private KernelBrowser $client;

    protected function setUp(): void {
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'tydoo',
            'password' => '112233',
        ]);

        $this->client->submit($form);

        $this->client->followRedirect();
    }

    public function testUserRouteIndex(): void {
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }

    public function testUserRouteEdit(): void {
        $crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton("Modifier l'utilisateur")->form([
            'user[username]' => 'tydoo',
            'user[email]' => 'blob@gg.fr',
            'user[password][first]' => '112233',
            'user[password][second]' => '112233',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/users/1/edit');

        $this->client->followRedirect();
    }

    public function testUserRouteEditWithoutAdmin(): void {
        $this->client->request('GET', '/logout');
        $this->client->request('GET', '/users/1/edit');
        $this->assertResponseRedirects('/login');
    }

    public function testUserRouteAdd(): void {
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $userUnik = 'usertest-' . rand(0, 100000000) - time() - rand(0, 100000000);

        $form = $crawler->selectButton("Ajouter l'utilisateur")->form([
            'user[username]' => $userUnik,
            'user[email]' => $userUnik . '@test.fr',
            'user[password][first]' => '112233',
            'user[password][second]' => '112233'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->client->followRedirect();

        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testUserRouteAddWithoutAdmin(): void {
        $this->client->request('GET', '/logout');
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton("Ajouter l'utilisateur")->form([
            'user[username]' => 'usertest-' . rand(0, 1000),
            'user[email]' => 'usertest-' . rand(0, 1000) . '@test.fr',
            'user[password][first]' => '112233',
            'user[password][second]' => '112233'
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->client->followRedirect();

        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}
