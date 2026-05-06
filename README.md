# 🧠 Dynamic Quiz System (Laravel)

A flexible and extensible Quiz System built using Laravel.
Supports multiple question types, media, and dynamic evaluation logic without hardcoding.

---

## 🚀 Features

* Create quizzes with multiple questions
* Supports different question types:

  * Single Choice
  * Multiple Choice
  * Binary (Yes/No)
  * Number Input
  * Text Input
* Automatic scoring system
* Media support (image & video per question)
* Extensible architecture (add new question types easily)
* API + Web interface support

---

## 🏗️ Tech Stack

* Laravel (Latest)
* PHP 8+
* SQLite
* Blade (Frontend)

---

## ⚙️ Setup Instructions

```bash
# Clone repository
using command git clone 

# Go into project folder
cd Laravel-Quiz-system

# Install dependencies
composer install

# Setup environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start server
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

## 📡 API Endpoints

### Create Quiz

```
POST /api/quiz
```

### Submit Attempt

```
POST /api/quiz/attempt
```

### Get Result

```
GET /api/quiz/{id}
```

---

## 🖥️ Web Routes

| Route               | Description      |
| ------------------- | ---------------- |
| `/`                 | View all quizzes |
| `/quiz/create`      | Create quiz UI   |
| `/quiz/{id}`        | Attempt quiz     |
| `/quiz/result/{id}` | View result      |

---

## 🧠 How It Works

* Each question type follows a common interface
* A registry manages all question types
* Evaluation logic is delegated to each type class
* No hardcoded switch/case logic → fully extensible

---

## ➕ Adding New Question Type

1. Create a class implementing `QuestionTypeInterface`
2. Define:

   * key()
   * label()
   * saveOptions()
   * evaluate()
3. Register it in `AppServiceProvider`

---

## 📊 Example Question Types

| Type            | Input         |
| --------------- | ------------- |
| single_choice   | radio         |
| multiple_choice | checkbox      |
| binary          | radio         |
| number          | numeric input |
| text            | text input    |

---

## ✅ Testing

* Create quiz via UI or API
* Attempt quiz
* Verify score calculation

---

## 📌 Notes

* Evaluation is strict (case-insensitive for text)
* Multiple choice requires exact match
* Number type uses numeric comparison

---


