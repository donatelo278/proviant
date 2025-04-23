<?php
// src/Controller/TaskController.php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskController extends AbstractController
{
    // Создание задачи с валидацией
    #[Route('/tasks', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Создание новой задачи
        $task = new Task();
        $task->setTitle($data['title'] ?? '');
        $task->setDescription($data['description'] ?? null);
        $task->setStatus($data['status'] ?? Task::STATUS_TODO);

        // Валидация
        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        // Сохранение в базе данных
        $em->persist($task);
        $em->flush();

        // Ответ с созданной задачей
        return new JsonResponse($task->toArray(), Response::HTTP_CREATED);
    }

    // Получение списка задач (без изменений)
    #[Route('/tasks', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $tasks = $em->getRepository(Task::class)->findAll();

        $data = array_map(fn(Task $task) => $task->toArray(), $tasks);
        return new JsonResponse($data);
    }

    // Обновление задачи с валидацией
    #[Route('/tasks/{id}', methods: ['PUT'])]
    public function update(
        $id,
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $task = $em->getRepository(Task::class)->find($id);
        if (!$task) {
            return new JsonResponse(
                ['error' => 'Task not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $data = json_decode($request->getContent(), true);

        // Обновление полей задачи
        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }
        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        // Валидация
        $errors = $validator->validate($task);
        if (count($errors) > 0) {
            return $this->validationErrorResponse($errors);
        }

        // Сохранение изменений
        $em->flush();

        return new JsonResponse($task->toArray());
    }

    // Удаление задачи (без изменений)
    #[Route('/tasks/{id}', methods: ['DELETE'])]
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        $task = $em->getRepository(Task::class)->find($id);
        if (!$task) {
            return new JsonResponse(
                ['error' => 'Task not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $em->remove($task);
        $em->flush();

        return new JsonResponse(['message' => 'Task deleted successfully']);
    }

    /**
     * Формирует JSON-ответ с ошибками валидации
     */
    private function validationErrorResponse($errors): JsonResponse
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return new JsonResponse(
            ['errors' => $errorMessages],
            Response::HTTP_BAD_REQUEST
        );
    }
}