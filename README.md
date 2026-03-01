# Smart Ticket Triage

This repository contains a **Vue.js frontend** and a **Laravel backend** for a ticket triage application.

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL or other supported database

## Setup Instructions

### 1. Clone the repository
git clone https://github.com/your-username/smart_ticket_triage.git
cd smart_ticket_triage
2. Setup Backend (Laravel)
bash
Copy code
cd backend
composer install                   # Install PHP dependencies
cp .env.example .env               # Create environment file
php artisan key:generate           # Generate app key
3. Configure Database
Edit .env and set your database credentials:

env
Copy code
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
4. Run Migrations
bash
Copy code
php artisan migrate
5. Seed Database
bash
Copy code
php artisan db:seed --class=TicketSeeder
6. Serve Backend
bash
Copy code
php artisan serve
By default, backend runs at http://127.0.0.1:8000 .
Also run this command to get the job running : php artisan queue:work

7. Setup Frontend (Vue.js)
Open a new terminal:

bash
Copy code
cd frontend
npm install                       # Install frontend dependencies
8. Run Frontend
bash
Copy code
npm run dev
By default, frontend runs at http://localhost:5173

9. Access Application
Frontend: http://localhost:5173

Backend API: http://127.0.0.1:8000/api

## What I'd do with more time
- Implement a more robust error handling and logging system.
- Add middleware to the frontend with role-based access logics.
- Improve the user interface with more interactive elements.
- Add more unit and end-to-end tests for the front end. 
