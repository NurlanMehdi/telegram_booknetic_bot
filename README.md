README for Telegram bot for Booknetic Project
This project is a simple web application developed in PHP language. The application utilizes a migration file to create a database table and perform operations on this table.

Installation
Clone the Project

bash
Copy code
git clone https://github.com/NurlanMehdi/TelegramBookNeticBot.git
Database Connection

Update your database connection details in the config.php file.
Running the Migration File

To run the migration file, navigate to the project directory in your terminal or command prompt and execute the following command:

bash
Copy code
php database/migration/create_services_table.php
This command will execute create_services_table.php to create a table named services in the database.

Project Structure
config.php: File containing database connection settings.
database/migration/create_services_table.php: Migration file to create the database table.
models/Service.php: Model for Service.
Helper/TelegramHelper.php: Helper class for communication with Telegram.
Usage
You can run the application using a web server (e.g., Apache or Nginx) or locally by starting PHP's built-in server:

bash
Copy code
php -S localhost:8000
Then, access the application in your browser at http://localhost:8000.

Contributing
Feel free to submit pull requests for adding new features or fixing bugs.
