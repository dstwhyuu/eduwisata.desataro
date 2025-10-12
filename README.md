# Taro Edu-tourism QR Code Information System

A web-based information system designed to manage plant data and generate QR codes for the Taro Edu-tourism site in Bali. This application allows administrators to manage botanical information, which can then be easily accessed by visitors via QR codes.

![sc1](https://github.com/dstwhyuu/Sistem_Informasi_Eduwisata_Taro/assets/107770857/d49b251a-d04b-466d-ad02-d96a603ac539)


## 📋 Key Features

* **Admin Dashboard**: Centralized dashboard for managing all application data.
* **Full CRUD Functionality**: Administrators can Create, Read, Update, and Delete plant data.
* **Rich Text Editor**: Uses CKEditor for easy and formatted content entry for plant descriptions.
* **QR Code Generation**: Automatically generates a unique QR code for each plant entry.
* **User Management**: Secure login system for administrators.
* **Email Functionality**: Integrated with PHPMailer for sending emails (e.g., password resets or notifications).

## 🚀 Technology Stack

This project is built with a classic and robust web technology stack.

<p align="left">
  <a href="https://www.php.net" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="50" height="50"/> 
  </a>
  <a href="https://www.mysql.com/" target="_blank" rel="noreferrer"> 
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg" alt="mysql" width="50" height="50"/> 
  </a>
  <a href="https://developer.mozilla.org/en-US/docs/Web/HTML" target="_blank" rel="noreferrer">
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original-wordmark.svg" alt="html5" width="50" height="50"/>
  </a>
  <a href="https://developer.mozilla.org/en-US/docs/Web/CSS" target="_blank" rel="noreferrer">
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original-wordmark.svg" alt="css3" width="50" height="50"/>
  </a>
    <a href="https://getcomposer.org/" target="_blank" rel="noreferrer">
    <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/composer/composer-original.svg" alt="composer" width="50" height="50"/>
  </a>
</p>

* **Backend**: PHP
* **Database**: MySQL
* **Frontend**: HTML, CSS, JavaScript
* **Dependencies**: 
    * [PHPMailer](https://github.com/PHPMailer/PHPMailer) for handling email transmission.
    * [CKEditor](https://ckeditor.com/) for a rich text editing experience.

## ⚙️ Setup and Installation

To run this project on your local machine, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/dstwhyuu/Sistem_Informasi_Eduwisata_Taro.git](https://github.com/dstwhyuu/Sistem_Informasi_Eduwisata_Taro.git)
    ```

2.  **Navigate to the project directory:**
    ```bash
    cd Sistem_Informasi_Eduwisata_Taro
    ```

3.  **Setup the Database:**
    * Create a new database in your MySQL server (e.g., using phpMyAdmin).
    * Import the `db_tanaman.sql` file into your newly created database.

4.  **Configure Database Connection:**
    * Open the `config.php` file.
    * Update the database credentials (`$host`, `$user`, `$password`, `$database`) to match your local setup.

5.  **Install PHP Dependencies:**
    * Make sure you have [Composer](https://getcomposer.org/) installed.
    * Run the following command to install the required libraries (like PHPMailer):
    ```bash
    composer install
    ```

6.  **Run the Application:**
    * Place the project folder in your local server's root directory (e.g., `htdocs` for XAMPP or `www` for Laragon).
    * Open your web browser and navigate to `http://localhost/Sistem_Informasi_Eduwisata_Taro`.

## 📸 Screenshots

*ADD YOUR SCREENSHOTS HERE*

**Example:**
*A screenshot of the Admin Login Page*
![Login Page](link-to-your-screenshot.png)

*A screenshot of the Main Dashboard*
![Admin Dashboard](link-to-your-screenshot.png)

---

This project was developed as part of my coursework to apply fundamental web development concepts.
