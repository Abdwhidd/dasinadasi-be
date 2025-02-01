# Blog Dasinadasi Laravel Backend Application

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>

## Introduction

This is the backend API documentation for the Personal Blogging Platform, built using Laravel. The API follows RESTful principles and provides endpoints for managing blog posts, categories, users, and authentication.

## Features
- User authentication (JWT-based)
- CRUD operations for blog posts
- Category management
- Commenting system
- Role-based access control
- API rate limiting

## Installation

Ensure you have the following installed:
- PHP >= 8.1
- Composer
- MySQL or PostgreSQL
- Laravel 11

## Setup

### 1.Clone the repository:
```bash
git clone https://github.com/Abdwhidd/dasinadasi-be.git
cd dasinadasi-be
```
### 2.Install dependencies:
```bash
composer install
```
### 3.Create the .env file:
```bash
cp .env.example .env
```
### 4.Set up the database:
- Configure .env with your database details
- Run migrations:
```bash
php artisan migrate
```
### 5.Generate application key:
```bash
php artisan key:generate
```
### 6.Run the server:
```bash
php artisan serve
```

## Testing
Run automated tests using PHPUnit:
```bash
php artisan test
```
