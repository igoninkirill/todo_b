<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Task;

class TaskController extends Controller
{
    public function index(): void
    {
        $sortBy = $_GET['sort'] ?? 'id';
        $orderBy = $_GET['order'] ?? 'asc';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 3;
        $task = new Task();
        $tasks = $task->getPaginated($sortBy, $orderBy, $page, $perPage);
        $total = $task->getTotalCount();

        if (isset($_SESSION['success_message'])) {
            $successMessage = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }

        echo $this->twig->render('tasks/index.html.twig', [
            'tasks' => $tasks,
            'page' => $page,
            'totalPages' => ceil($total / $perPage),
            'order' => $orderBy,
            'successMessage' => $successMessage ?? null
        ]);
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => htmlspecialchars($_POST['username']),
                'email' => htmlspecialchars($_POST['email']),
                'description' => htmlspecialchars($_POST['description']),
            ];

            $task = new Task();

            if ($task->create($data)) {
                $_SESSION['success_message'] = 'Task created successfully!';
                header('Location: /tasks');
                exit;
            }

            echo $this->twig->render('tasks/create.html.twig', ['error' => 'Error saving task']);
        } else {
            echo $this->twig->render('tasks/create.html.twig');
        }
    }

    public function edit($id): void
    {
        if (empty($_SESSION['is_admin'])) {
            header('Location: /login');
            exit;
        }

        $model = new Task();

        $task = $model->find($id);

        if (is_null($task)) {
            http_response_code(404);
            echo $this->twig->render('errors/404.html.twig', [
                'message' => 'Task not found'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'description' => htmlspecialchars($_POST['description']),
                'status' => isset($_POST['status']),
            ];

            if ($data['description'] !== $task['description']) {
                $data['updated_by'] = $_SESSION['user_id'] ?? null;
            }

            if ($model->update($id, $data)) {
                $_SESSION['success_message'] = 'Task updated successfully!';
                header('Location: /');
                exit;
            }

            echo $this->twig->render('tasks/edit.html.twig', ['task' => $task, 'error' => 'Error updating task']);
        } else {
            echo $this->twig->render('tasks/edit.html.twig', ['task' => $task]);
        }
    }
}
