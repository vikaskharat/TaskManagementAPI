# Laravel Task Management API

This is a simple Task Management API built using Laravel. It provides endpoints to manage tasks, assign tasks to users, and filter tasks based on various criteria.

## Features

- User authentication with Laravel Sanctum
- CRUD operations for tasks
- Task assignment to multiple users
- Task status update
- Filtering tasks by status, date, and user

## Installation

# Clone the repository:
git clone <repository-url>

# Install composer
composer install

php artisan key:generate

# Run seeders to create user records from database/seeders/user_seeder.php:
php artisan db:seed --class='user_seeder'

# Run migrations to create the necessary tables:
php artisan migrate

# Install Laravel Sanctum for API authentication:
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# Endpoints

    POST /api/login: User authentication
    GET /api/tasks: List all tasks
    POST /api/tasks: Create a new task
    PUT /api/tasks/{taskId}: Update an existing task
    DELETE /api/tasks/{taskId}: Delete a task
    POST /api/tasks/{taskId}/assign: Assign users to a task
    DELETE /api/tasks/{taskId}/unassign/{userId}: Unassign a user from a task
    PUT /api/utask_update/{taskID}/status: Update task status as a logged-in user
    PUT /api/task_update/{taskId}/status: Update task status
    GET /api/users/{userId}/tasks: Get tasks assigned to a specific user
    GET /api/get_tasks/status/{status}: Filter tasks by status
    GET /api/get_tasks/date/{date}: Filter tasks by date
    GET /api/get_tasks/user/{username}: Filter tasks by username
    GET /api/tasksu/tasksofuser: Get tasks assigned to the currently logged-in user

Issues
    Authentication middleware may cause issues with route access. Ensure proper usage of authentication middleware or guard in route definitions.
    Laravel Sanctum may require additional configuration for API authentication. Refer to the Laravel Sanctum documentation for more details.

Feel free to contribute by submitting bug reports or feature requests.
