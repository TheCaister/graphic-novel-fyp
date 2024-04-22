![Comic Forger Main Banner](/public/assets/main%20banner.png)

# About Comic Forger

Welcome to Comic Forger, the web application designed to assist comic and manga authors in planning their stories! Set up the project easily using Docker containers, which include Laravel Sail, MySQL, and phpMyAdmin to interface with the MySQL database.

# Features of Comic Forger
Comic Forger offers a range of tools and features designed to assist comic and manga authors in planning and executing their stories. Below is a detailed list of the key functionalities that is available within the app.

## Content Upload
Allows authors to create and upload their comic series, chapters, and pages, organising content for easy management and access.

![Content Dashboard View](/public/assets/content%20dashboard.png)

![Chapter Edit](/public/assets/content%20edit.png)

## Element Creation
On top of content creation, Comic Forger provides authors with tools to create a diverse range of story elements that form the building blocks of their narratives.

![Create Element](/public/assets/element%20create.png)

### Simple Text
A text document feature where authors can jot down detailed notes about characters, special items, locations, and other critical story elements.

![Simple Text](/public/assets/simpletext.png)

### Mind Map
A panel that allows authors to visually map out relationships between characters, sequence of events, and other interconnected story aspects.

![Mind Map](/public/assets/mindmap.png)

### Panel Planner
A tool that helps authors draft comic panels efficiently. It allows for descriptions of panels and the addition of pre-created elements to panels, ensuring clarity throughout the story.

![Panel Planner](/public/assets/panel%20planner.png)

## Element Assignment
After creating your content and elements, you can attach them directly to each other to enhance your storytelling. This creates a unique referencing system that allows for precise organisation and tracking of story elements throughout your comic/manga.

![Element Assign](/public/assets/element%20assign.png)

![Search](/public/assets/search.png)

# Online Prototype
You can try out a live version of Comic Forger to see how it works in a real-world scenario by visiting the hosted prototype. Please note the following disclaimers before you proceed.

Visit the prototype at: http://167.71.131.102

## Disclaimers:

- Security: The prototype is served over HTTP, which is not secure. Please avoid using any sensitive or personal information when filling in forms such as registering a new account.
- Optimisation: This application is optimised for desktop use. It may not function or display properly on mobile devices.
- Potential Issues: You may encounter errors when uploading or processing images, typically due to CSRF validation failures. If this happens, refreshing the page will usually resolve the issue.

## Pre-requisites
- WSL 2
- Docker Desktop

## Docker Containers
- `sail/app`
- `phpmyadmin`
- `mysql`

## Basic Sail Commands

**Start Laravel Docker:**
```bash
./vendor/bin/sail up -d
```

**Stop Laravel Docker:**
```bash
./vendor/bin/sail stop
```

**Set up a Sail alias:**
```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

# Windows Project Setup

Follow these steps to set up the project on Windows using WSL:

1. Ensure WSL and Docker Desktop are installed:
   - [Install WSL](https://learn.microsoft.com/en-us/windows/wsl/install)
   - [Install Docker Desktop](https://www.docker.com/)
2. Clone the project repository into `/etc/home/[username]`.
3. Open the repository in Visual Studio Code by running `code .` in the WSL terminal.
4. Ensure Docker Desktop settings are enabled as shown below:

   ![Docker Desktop Settings](https://i.ibb.co/ckJpCtf/Docker-Desktop-hi-Dg-Tax0-WD.png)
   ![Additional Docker Desktop Settings](https://i.ibb.co/9GmZT73/Docker-Desktop-k-BB1ud-Hh-W6.png)

5. Install Composer dependencies:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php82-composer:latest \
       composer install --ignore-platform-reqs
   ```
6. Start the Sail application:
   ```bash
   ./vendor/bin/sail up
   ```
7. Migrate the database:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```
8. Optionally, seed the database with initial data:
   ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```
9. Install NPM modules and run the development build:
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ./vendor/bin/sail npm run build
   ```

## Working With The Database

To manage the database, you can migrate and seed via these commands:

**Migrate the database:**
```
./vendor/bin/sail artisan migrate
```

**Access phpMyAdmin:**
Go to [http://localhost:8080](http://localhost:8080)

## Project Structure

### Aesthetics
- **Frontend Files:** Located in the `resources` folder.
- **Vue Components:** Reusable and dynamic, accept props to render structured data differently.

### Routing
- **Routes:** Defined in `routes/web.php` and `routes/api.php`.
- **Controllers:** Located in `Http/Controllers/`.
- **Example Route Usage:**
  ```php
  Route::get('/elements/assign', [ElementController::class, 'assign'])->name('elements.assign');
  ```

1. **HTTP Method (GET):**
   - `Route::get` specifies that this route will respond to HTTP GET requests.

2. **URL (Endpoint):**
   - `'/elements/assign'` is the URL path that you can use to access this route. For instance, if the app is hosted at `http://localhost`, then navigating to `http://localhost/elements/assign` in a web browser would trigger this route.

3. **Controller and Method:**
   - `[ElementController::class, 'assign']` specifies which controller and method will handle the request for this route. Here, `ElementController` is the name of the controller class, and `assign` is the method within that controller.

4. **Route Name:**
   - `->name('elements.assign')` assigns a name to the route, which can be useful for generating URLs or redirects.

## Public Image Access
Enable public access to images:
```
sail artisan storage:link
```