# Task Management Application

A simple task management application built with Laravel. This app allows users to create, view, edit, and delete tasks, as well as register and log in to manage their tasks.

## Features
- User registration and authentication
- CRUD operations for tasks
- Each user has their own task list
- Task completion status tracking
- **New:** Notification to remind users of their tasks and due dates

## Requirements
- PHP >= 8.1
- Composer
- MySQL
- Laravel => 11

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Grizi7/Task-Management-Application.git
```

### 2. Install Dependencies

Run the following command to install PHP and Composer dependencies:

```bash
composer install
```

### 3. Set Up Environment

- Duplicate the `.env.example` file and rename it to `.env`.

```bash
cp .env.example .env
```

- Open `.env` and set up your environment variables, particularly for the database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Generate Application Key

Generate an application encryption key:

```bash
php artisan key:generate
```

### 5. Set Up Database

- Create a new database named `task_manager` in MySQL or the database of your choice.
- Run the migrations to set up the necessary tables:

```bash
php artisan migrate --seed
```

### 6. Run the Application

Start the local development server:

```bash
php artisan serve
```

By default, the application will be available at `http://127.0.0.1:8000`.

## Usage

### Test User Credentials

Use the following credentials to log in as a test user:

- **Email**: `test@example.com`
- **Password**: `password`

These credentials will help you quickly test the application's authentication and task management features.

### Authentication

- **Register**: Go to `http://127.0.0.1:8000/register` to create a new account.
- **Login**: Go to `http://127.0.0.1:8000/login` to log in.

### Task Management

Once logged in, you can:
- **Create a New Task**: Click on "Add New Task" to create a new task with a title and optional description.
- **View Tasks**: The dashboard displays all tasks you have created.
- **Finish a Task**: Click "Finish" to finish an existing task.
- **Edit a Task**: Click "Edit" to modify an existing task.
- **Delete a Task**: Click "Delete" to remove a task from your list.

## Code Structure

### Models
- **User**: The `User` model is used for user authentication.
- **Task**: The `Task` model is used to represent each task, associated with a user.

### Controllers
- **TaskController**: Manages CRUD operations for tasks.
- **Auth Controllers**: `LoginController` and `RegisterController` handle user login and registration.

### Validation
Validation rules for users registration and login are handled by `RegisterRequest` and `LoginRequest`, ensuring each user has valid credentials such as email and password.

Validation rules for tasks are handled by `TaskRequest`, ensuring each task has a title and description.

## Deployment

The application is deployed and available at [https://grizi7.space/](https://grizi7.space/).

## Additional Information

- **Database Structure**:
  - **Users Table**: Contains `id`, `name`, `email`, `password`, and timestamps.
  - **Tasks Table**: Contains `id`, `title`, `description`, `completed`, `user_id`, and timestamps.
  
- **Routes**:
  - Auth routes: `/register`, `/login`, `/logout`
  - Task routes: `/tasks`, `/tasks/create`, `/tasks/finish/{id}`, `/tasks/edit/{id}`, `/tasks/update/{id}`, `/tasks/delete/{id}`
````
