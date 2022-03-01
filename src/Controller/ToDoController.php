<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Form\ToDoType;
use App\Repository\ToDoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToDoController extends AbstractController
{
    /**
     * @Route("/todos/{id}/edit", name="app_todos_edit")
     * @Route("/todos", name="app_todos")
     */
    public function index(Request         $request,
                          ManagerRegistry $managerRegistry,
                          ToDoRepository  $repository,
                          int             $id = null
    ): Response
    {
        if (null !== $id) {
            $todo = $repository->find($id);
            if (!$todo) {
                throw $this->createNotFoundException();
            }
        } else {
            $todo = new ToDo();
        }

        $form = $this->createForm(ToDoType::class, $todo, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $managerRegistry->getManager()->persist($form->getData()->setUser($this->getUser()));
            $managerRegistry->getManager()->flush();

            $this->addFlash('success', 'ToDo added successfully.');

            return $this->redirect($this->generateUrl('app_todos'));
        }

        return $this->render('Pages/todo.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param ToDoRepository $repository
     * @return Response
     */
    public function list(ToDoRepository $repository): Response
    {
        $todos = $repository
            ->findBy(['user' => $this->getUser()], ['status' => 'DESC', 'createdAt' => 'DESC']);

        return $this->render('Pages/todo-item.html.twig', ['todos' => $todos]);
    }

    /**
     * @Route("/todos/{id}/status", methods={"PUT"})
     * @return void
     */
    public function changeStatus(ManagerRegistry $managerRegistry, ToDoRepository $repository, int $id): JsonResponse
    {
        $todo = $repository->find($id);
        if (!$todo || $todo->getUser() !== $this->getUser()) {
            return new JsonResponse(['success' => false, 'error' => 'Task not found']);
        }

        $todo->setStatus($todo->getStatus() === ToDo::STATUS_PENDING ? ToDo::STATUS_COMPLETE : ToDo::STATUS_PENDING);

        $managerRegistry->getManager()->persist($todo);
        $managerRegistry->getManager()->flush();

        return new JsonResponse(['status' => $todo->getStatus()]);
    }

    /**
     * @Route("/todos/{id}", methods={"DELETE"})
     * @param ManagerRegistry $managerRegistry
     * @param ToDoRepository $repository
     * @param int $id
     * @return void
     */
    public function delete(ManagerRegistry $managerRegistry, ToDoRepository $repository, int $id): JsonResponse
    {
        $todo = $repository->find($id);
        if (!$todo || $todo->getUser() !== $this->getUser()) {
            return new JsonResponse(['success' => false, 'error' => 'Task not found']);
        }

        $managerRegistry->getManager()->remove($todo);
        $managerRegistry->getManager()->flush();

        return new JsonResponse(['success' => true]);
    }
}
