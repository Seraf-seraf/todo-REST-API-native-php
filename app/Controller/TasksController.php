<?php

namespace ToDo\Controller;

use OpenApi\Annotations as OA;
use ToDo\Model\Tasks;
use ToDo\Validator\Validator;

/**
 * @OA\Info(title="ToDo REST API", version="1.0")
 */
class TasksController extends AbstractController
{
    /**
     * @OA\Get(
     *   path="/tasks",
     *   summary="Retrieve a list of tasks",
     *   @OA\Response(
     *     response=200,
     *     description="A list of tasks",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="body", type="array", @OA\Items(ref="#/components/schemas/Task")),
     *       @OA\Property(property="statusCode", type="integer", example=200)
     *     )
     *   )
     * )
     */
    public function index(): bool|string
    {
        $tasks = Tasks::findAll();
        return $this->responseJson($tasks, 200);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="body", ref="#/components/schemas/Task"),
     *             @OA\Property(property="statusCode", type="integer", example=201)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="body", type="object",
     *                 @OA\Property(property="errors", type="object",
     *                     additionalProperties=@OA\Schema(type="array", @OA\Items(type="string"))
     *                 )
     *             ),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     )
     * )
     */
    public function store(): bool|string
    {
        $data = $this->getValues(Tasks::$fields);
        $errors = Validator::validate($data, Tasks::rules());

        if (!empty($errors)) {
            return $this->responseJson(['errors' => $errors], 422);
        } else {
            $task = Tasks::create($data);
            return $this->responseJson($task, 201);
        }
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Retrieve a task by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="body", ref="#/components/schemas/Task"),
     *             @OA\Property(property="statusCode", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Task not found"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function show(string $id): bool|string
    {
        $task = Tasks::find($id);
        if (!$task) {
            return $this->responseJson(['error' => 'Task not found'], 404);
        }
        return $this->responseJson($task, 200);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     summary="Update an existing task",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="body", ref="#/components/schemas/Task"),
     *             @OA\Property(property="statusCode", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="body", type="object",
     *                 @OA\Property(property="errors", type="object",
     *                     additionalProperties=@OA\Schema(type="array", @OA\Items(type="string"))
     *                 )
     *             ),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Task not found"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function update(string $id): bool|string
    {
        $data = $this->getValues(Tasks::$fields);
        $errors = Validator::validate($data, Tasks::rules());

        if (!empty($errors)) {
            return $this->responseJson(['errors' => $errors], 422);
        } else {
            $task = Tasks::update($data, $id);
            return $this->responseJson($task, 200);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     summary="Delete a task",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Task not found"),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        Tasks::delete($id);
        return $this->responseJson([], 204);
    }
}

/**
 * @OA\Schema(
 *     schema="Task",
 *     required={"title"},
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="due_date", type="string", format="date-time"),
 *     @OA\Property(property="status", type="integer", enum={0, 1}, description="0 for incomplete, 1 for complete")
 * )
 */
