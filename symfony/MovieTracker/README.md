# MovieTracker

This is a Symfony learning project built to practice Doctrine, Symfony Forms, validation, filtering, flash messages, and a small polished UI.

The application allows managing a movie watchlist with watched/unwatched status.

## Main Features

- Movie list
- Add movie
- Edit movie
- Delete movie
- Toggle watched/unwatched status
- Filter movies by all, watched, and not watched
- Flash messages after successful actions
- Symfony FormType with explicit field types
- Entity validation
- Cartoon-style page-specific CSS

## Technologies

- PHP
- Symfony
- Twig
- Doctrine ORM
- PostgreSQL
- Symfony Forms
- Symfony Validator
- HTML
- CSS

## What I Practiced

- Doctrine entities and repositories
- Database migrations
- CRUD with Doctrine
- Symfony FormType field types
- `TextType`, `ChoiceType`, `IntegerType`, and `CheckboxType`
- Entity validation with constraints
- Flash messages
- Filtering with query parameters
- Toggle actions with POST forms
- Page-specific CSS with shared global styles

## Project Structure

Important files:

- `src/Entity/Movie.php` - movie entity and validation rules
- `src/Controller/MovieController.php` - CRUD, filtering, toggle, and flash message flow
- `src/Form/MovieType.php` - Symfony form definition
- `templates/movie/` - Twig templates
- `assets/styles/` - global and page-specific CSS files
- `migrations/` - Doctrine migrations

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
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/movietracker?serverVersion=16&charset=utf8"
```

## Future Improvements

- CSRF protection for delete and toggle forms
- Search by movie title
- Sorting by rating or release year
- Moving some logic into services

## Note

This project is a learning exercise and is not intended as a polished portfolio project yet.
