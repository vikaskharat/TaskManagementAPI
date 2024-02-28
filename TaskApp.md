# _Task Manager_

## Tech
- [Laravel](https://laravel.com/)
- [Composer](https://getcomposer.org/)
- [Xampp](https://www.apachefriends.org/index.html)
- [Visual studio](https://code.visualstudio.com/)

## Installation

Install the dependencies and devDependencies and start the server.

```
Install Composer using installer at [https://getcomposer.org/]
composer global require laravel/installer
laravel new example-app
cd example-app
php artisan serve
```
Verify the deployment by navigating to your server address in
your preferred browser.

```sh
127.0.0.1:8000
```
## Development
> Note:- Navigate to your Laravel project directory in your terminal or command prompt and run the following commands:

##### Install Laravel Sanctum for API authentication:
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

##### Database Configuration:- 
Create Database and  configure your database connection settings in the .env file

```sh
php artisan migrate
php artisan db:seed Seeder_name
```

# API Endpoints

### User Authentication

- **Authenticate a user.**
  - `POST /api/login`

### Tasks
- **Get tasks**
  - `GET /api/tasks`
  
- **Create a task**
  - `POST /api/tasks`
  
- **Update a task**
  - `PUT /api/tasks/{taskId}`
  
- **Delete a task**
  - `DELETE /api/tasks/{taskId}`
  
- **Assign users to a task**
  - `POST /api/tasks/{taskId}/assign`
  
- **Unassign a user from a task**
  - `DELETE /api/tasks/{taskId}/unassign/{userId}`
  
- **Update task status as a logged-in user**
  - `PUT /api/utask_update/{taskID}/status`
  
- **Update task status**
  - `PUT /api/task_update/{taskId}/status`
  
- **Get tasks assigned to a specific user**
  - `GET /api/users/{userId}/tasks`
  
- **Filter tasks by status**
  - `GET /api/get_tasks/status/{status}`
  
- **Filter tasks by date**
  - `GET /api/get_tasks/date/{date}`
  
- **Filter tasks by username**
  - `GET /api/get_tasks/user/{username}`
  
- **Get tasks assigned to the currently logged-in user**
  - `GET /api/tasksu/tasksofuser`

