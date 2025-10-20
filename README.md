Laravel Blog Application

A clean and modern blog platform built with Laravel 11, featuring user authentication, image uploads, likes, and comments. This project demonstrates a full-featured CRUD system with responsive design and English-language seeded data.

🚀 Features
🧑‍💻 Public Area

View all published posts (paginated, 20 per page).

Read full posts with image, author, and comments.

Add comments (for authenticated users).

Like or unlike posts dynamically.

🔐 Authenticated Users

Create, edit, and delete your own posts.

Upload images for posts (stored locally or from URL).

Manage your posts easily with clean UI controls.

🧠 Admin / Developer Features

Database seeding with realistic English posts, comments, and users.

Uses Faker (en_US) locale for natural titles and body text.

Profile pictures and post images auto-generated from Picsum.photos.

🛠️ Tech Stack
Category	Tools
Framework	Laravel 11

Database	MySQL / MariaDB
Frontend	Blade + TailwindCSS
Authentication	Laravel Breeze
Seeder / Faker	English (en_US)
Storage	Laravel Storage (public/storage)
Development	Artisan CLI + XAMPP / Valet / Sail
⚙️ Installation
1. Clone the Repository
git clone https://github.com/yourusername/laravel_blog.git
cd laravel_blog

2. Install Dependencies
composer install
npm install && npm run dev

3. Configure Environment


4. Set Up Database
php artisan migrate --seed


This creates tables and fills them with English test data (users, posts, and comments).

5. Link Storage (for post images)
php artisan storage:link

6. Run the Application
php artisan serve


Visit http://127.0.0.1:8000

💾 Default Seed Data

10 Users (auto-generated)

30 Posts (English titles + bodies)

60 Comments (short, realistic phrases)

Each post includes an image and author name.

🧩 Project Structure
app/
 ├── Http/Controllers/        # Post, Comment, Like controllers
 ├── Models/                  # Eloquent models (Post, Comment, Like)
 └── Database/
     ├── factories/           # Faker factories (English)
     └── seeders/             # Seeder classes
resources/
 └── views/posts/             # Blade templates for blog pages
public/
 └── storage/                 # Uploaded images

❤️ Contributing

Pull requests are welcome!
If you’d like to suggest a feature (e.g., categories or user profiles), please open an issue first to discuss your idea.