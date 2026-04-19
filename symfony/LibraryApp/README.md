# LibraryApp

This is a Symfony learning project focused on the first Doctrine and Symfony Forms CRUD application.

The application allows creating, listing, editing, and deleting books stored in a PostgreSQL database.

## Main Features

- Book list
- Add book
- Edit book
- Delete book
- Doctrine entity and repository
- Symfony FormType
- PostgreSQL database
- Page-specific CSS files

## Technologies

- PHP
- Symfony
- Twig
- Doctrine ORM
- PostgreSQL
- Symfony Forms
- HTML
- CSS

## What I Practiced

- Creating Doctrine entities
- Running migrations
- Using repositories for database queries
- Using `EntityManagerInterface`
- Creating records with `persist()` and `flush()`
- Updating existing records with `flush()`
- Deleting records with `remove()` and `flush()`
- Building forms with `FormType`
- Rendering Symfony forms in Twig

## Setup

1. Install dependencies.
2. Configure database credentials in `.env` or `.env.local`.
3. Create the database.
4. Run migrations.
5. Start the development server.

```bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony serve
```

## Environment Variables

Example PostgreSQL connection:

```env
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/libraryapp?serverVersion=16&charset=utf8"
```

## Note

This is a practice project for learning Doctrine and Symfony Forms.
