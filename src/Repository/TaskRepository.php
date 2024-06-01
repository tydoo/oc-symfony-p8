<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Task::class);
    }

    public function toggle(Task $task): void {
        $task->setDone(!$task->isDone());
        $this->getEntityManager()->flush();
    }

    public function delete(Task $task): void {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }

    public function save(Task $task): void {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }
}
