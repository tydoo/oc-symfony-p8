<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    public function testUser(): void {
        $user = new User();
        $user->setUsername('testU');
        $user->setAdmin(true);
        $user->setEmail('test@test.com');
        $user->setPassword('testP');

        $this->assertSame('testU', $user->getUsername());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertSame('testU', $user->getUserIdentifier());
        $this->assertNull($user->getId());
        $this->assertSame('testP', $user->getPassword());
        $this->assertSame('test@test.com', $user->getEmail());

        $user->setRoles([]);
        $this->assertNotContains('ROLE_ADMIN', $user->getRoles());
        $user->setRoles(['ROLE_ADMIN']);
        $user->setAdmin(false);
        $this->assertNotContains('ROLE_ADMIN', $user->getRoles());

        $this->assertNull($user->eraseCredentials());


        $task = (new Task())->setUser($user);

        $user->addTask($task);
        $this->assertCount(1, $user->getTasks());

        $user->removeTask($task);
        $this->assertCount(0, $user->getTasks());
    }
}
