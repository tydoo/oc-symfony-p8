<?php

namespace App\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormAuthenticatorTest extends WebTestCase {
    public function testLogin(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'username',
            'password' => 'password',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login');

        $client->followRedirect();

        $this->assertSelectorTextContains('div.alert', 'Identifiants invalides.');

        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'tydoo',
            'password' => '112233',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/');

        $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');

        $crawler = $client->request('GET', '/login');

        $this->assertResponseRedirects('/');
    }
}
