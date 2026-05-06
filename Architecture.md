# Dynamic Quiz System Architecture

## Overview

This project is a flexible and extensible Quiz System built using Laravel 12.

The system supports multiple question types:
- Binary
- Single Choice
- Multiple Choice
- Number Input
- Text Input

The application follows a modular architecture to avoid hardcoded evaluation logic and to allow future question types to be added easily.

---

# Tech Stack

- Laravel 12
- Blade Templates
- SQLite / PostgreSQL
- Docker
- Render Deployment

---

# Project Structure

## Models

### Quiz
Represents a quiz containing multiple questions.

### Question
Stores question data including:
- type
- marks
- media
- video URL

### Option
Stores answer options for objective questions.

### Attempt
Represents a user quiz attempt.

### Answer
Stores submitted answers for each question.

---

# Question Type System

The application uses a Strategy Pattern-like architecture.

## Components

### QuestionTypeInterface
Defines common methods for all question types.

### QuestionTypeRegistry
Registers and resolves question type handlers dynamically.

### Type Classes
Located in:

app/QuestionTypes/Types/

Each class handles:
- validation
- evaluation
- answer processing

Examples:
- BinaryType
- SingleChoiceType
- MultipleChoiceType
- NumberType
- TextType

---

# Evaluation Flow

1. User submits quiz
2. Answers are sent to QuizEvaluatorService
3. Service resolves question type
4. Appropriate handler evaluates answer
5. Marks are calculated
6. Final score is generated

This avoids hardcoded if-else logic throughout the application.

---

# Database Design

## Tables

### quizzes
Stores quiz metadata.

### questions
Stores questions linked to quizzes.

### options
Stores options for choice-based questions.

### attempts
Stores quiz attempt records.

### answers
Stores submitted answers.

---

# Extensibility

To add a new question type:

1. Create new handler class
2. Implement QuestionTypeInterface
3. Register inside QuestionTypeRegistry

No changes are required in evaluation service logic.

This makes the system scalable and maintainable.

---

# Frontend

The frontend uses Blade templates.

Views:
- quiz creation
- quiz listing
- quiz attempt
- result display

---

# Deployment

The project is containerized using Docker and deployed on Render.

---

# Design Goals

- Extensible architecture
- Separation of concerns
- Maintainable evaluation logic
- Simple deployment
- Clean database structure