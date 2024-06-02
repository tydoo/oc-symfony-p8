<?php

namespace App\Tests\Controller;

use DOMElement;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase {
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

    public function testTaskList(): void {
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tâches à faire');
    }

    public function testTaskListDone(): void {
        $crawler = $this->client->request('GET', '/tasks/done');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tâches terminées');
    }

    public function testTaskToogle(): void {
        $crawler = $this->client->request('GET', '/tasks');

        $btn = $crawler->selectButton('Marquer comme faite')->first();

        $this->client->submit($btn->form());

        $this->assertResponseRedirects('/tasks');
    }

    public function testTaskDelete(): void {
        $crawler = $this->client->request('GET', '/tasks');

        $link = $crawler->selectLink('Supprimer')->first();

        $this->client->click($link->link());

        $this->assertResponseRedirects('/tasks');
    }

    public function testTaskDeleteWithoutAccess(): void {
        $crawler = $this->client->request('GET', '/tasks');
        $crawler->filter('div.card-footer')->each(function ($node) {
            if ($node->filter('a')->count() === 0) {
                preg_match('/\d+/', $node->filter('form')->attr('action'), $matches);
                if (count($matches) > 0) {
                    $this->client->request('GET', "/tasks/" . $matches[0] . '/delete');

                    $this->assertResponseStatusCodeSame(403);
                }
            }
        });
    }

    public function testTaskCreate(): void {
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Enregistrer')->form([
            'tasks[title]' => 'Test',
            'tasks[content]' => 'Test content',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
    }

    public function testTaskEdit(): void {
        $crawler = $this->client->request('GET', '/tasks');

        //select link on h4>a
        $link = $crawler->filter('h4 a')->first()->link();

        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('Enregistrer')->form([
            'tasks[title]' => 'Test',
            'tasks[content]' => 'Test content',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
    }
}
