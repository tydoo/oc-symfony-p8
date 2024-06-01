<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase {
    public function testTask(): void {
        $task = new Task();
        $task->setTitle('Test title');
        $task->setContent('Test content');
        $task->setDone(false);
        $task->setCreatedAt(new \DateTimeImmutable('2021-01-01 00:00:00'));

        $user = new User();
        $task->setUser($user);

        $this->assertSame('Test title', $task->getTitle());
        $this->assertSame('Test content', $task->getContent());
        $this->assertFalse($task->isDone());
        $this->assertEquals(new \DateTimeImmutable('2021-01-01 00:00:00'), $task->getCreatedAt());
        $this->assertNull($task->getId());
        $this->assertSame($user, $task->getUser());

        //test onPrePersist
        $task->onPrePersist();
        $this->assertInstanceOf(\DateTimeImmutable::class, $task->getCreatedAt());
    }
}
