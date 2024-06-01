<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TasksType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tasks', name: 'task', methods: ['GET', 'POST'])]
class TaskController extends AbstractController {

    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {
    }

    #[Route('', name: '_list', methods: ['GET'])]
    public function list(): Response {
        return $this->render('task/index.html.twig', [
            'title' => 'Tâches à faire',
            'tasks' => $this->taskRepository->findBy(['done' => false], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/done', name: '_list_done', methods: ['GET'])]
    public function listDone(): Response {
        return $this->render('task/index.html.twig', [
            'title' => 'Tâches terminées',
            'tasks' => $this->taskRepository->findBy(['done' => true], ['createdAt' => 'DESC']),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/create', name: '_create')]
    public function create(Request $request): Response {
        $task = new Task();
        $task->setUser($this->getUser());
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskRepository->save($task);

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form_task' => $form,
            'title' => 'Créer une tâche',
            'task' => $task,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: '_edit')]
    public function edit(Task $task, Request $request): Response {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskRepository->save($task);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute($task->isDone() ? 'task_list_done' : 'task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form_task' => $form,
            'title' => 'Modifier une tâche',
            'task' => $task,
        ]);
    }

    #[Route('/{id}/toggle', name: '_toggle', methods: ['GET'])]
    public function toogle(Task $task, Request $request): RedirectResponse {
        $this->taskRepository->toggle($task);

        $this->addFlash('success', sprintf('La tâche "%s" a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('task_list'));
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/delete', name: '_delete', methods: ['GET'])]
    public function delete(Task $task, Request $request): RedirectResponse {
        if (
            ($task->getUser() === $this->getUser()) ||
            ($this->isGranted('ROLE_ADMIN') && $task->getUser()->getUsername() === 'anonymous')
        ) {
            $this->taskRepository->delete($task);

            $this->addFlash('success', sprintf('La tâche "%s" a bien été supprimée.', $task->getTitle()));

            return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('task_list'));
        } else {
            throw $this->createAccessDeniedException();
        }
    }
}
