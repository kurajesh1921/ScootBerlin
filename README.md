<div align="center">

🛴 ScootBerlin
Scalable Shared Electric Scooter Backend

A production-inspired backend platform built with Laravel, PostgreSQL, PostGIS, Redis, Docker, and Stripe.

🚧 Status: In Development   •   📍 Current Milestone: Infrastructure & System Architecture

</div>

## 🚀 About The Project

ScootBerlin is a backend-focused portfolio project that simulates the core infrastructure of a modern shared electric scooter platform.

The goal is not simply to build CRUD APIs, but to demonstrate production-ready backend engineering practices including scalable system design, geospatial data processing, background job processing, secure payment integration, and clean software architecture.

The project is being developed incrementally using a feature-branch workflow, with each milestone documented and implemented following professional software development practices.

---

## ✨ Features

### 🔐 Authentication & Authorization

* User Registration & Login
* Laravel Sanctum Authentication
* Role-Based Access Control (RBAC)
* Protected REST APIs

### 🛴 Scooter Management

* Scooter Registration
* Scooter Availability Management
* Battery Monitoring
* Scooter Status Tracking
* Maintenance Management

### 📍 GPS Location Service

* High-Frequency GPS Ingestion
* Real-Time Scooter Location Updates
* Nearby Scooter Search
* Geospatial Queries with PostGIS

### 🚴 Ride Management

* Start Ride
* End Ride
* Ride Duration Calculation
* Distance Tracking
* Ride History

### 💳 Billing & Payments

* Stripe Integration
* Secure Payment Processing
* Webhook Handling
* Payment History

### ⚡ Performance

* Redis Caching
* Background Job Processing
* Queue Workers
* Optimized Database Queries

---

## 🏗️ Technology Stack



| Category         | Technology              |
| ---------------- | ----------------------- |
| Backend          | Laravel 13, PHP 8.4     |
| Database         | PostgreSQL 17 + PostGIS |
| Cache & Queue    | Redis                   |
| Web Server       | Nginx                   |
| Containerization | Docker                  |
| Payments         | Stripe                  |
| Authentication   | Laravel Sanctum         |
| Testing          | Pest, PHPUnit           |
| Code Quality     | Laravel Pint, PHPStan   |
| Version Control  | Git & GitHub            |

---

## 📂 Project Modules

* Authentication
* User Management
* Scooter Management
* GPS Tracking
* Ride Management
* Billing & Payments
* Maintenance
* Administration
* Reporting

---

## 📈 Project Roadmap

* [x] Docker Development Environment
* [x] PostgreSQL + PostGIS Setup
* [x] Redis Integration
* [x] Infrastructure Architecture
* [ ] Authentication Module
* [ ] Scooter Management
* [ ] GPS Tracking
* [ ] Nearby Scooter Search
* [ ] Ride Lifecycle
* [ ] Stripe Billing
* [ ] WebSockets
* [ ] Automated Testing
* [ ] GitHub Actions CI
* [ ] Production Deployment

---

## 🏛️ Architecture Overview

The application follows a layered architecture to separate concerns and improve maintainability.

```text
                Client Applications
                       │
                  REST API (Laravel)
                       │
      ┌────────────────┼────────────────┐
      │                │                │
Authentication   Business Logic   Background Jobs
      │                │                │
      └────────────────┼────────────────┘
                       │
              PostgreSQL + PostGIS
                       │
                    Redis Cache
                       │
               Stripe / External APIs
```

---

## 📁 Project Structure

```text
ScootBerlin/
├── app/
├── bootstrap/
├── config/
├── database/
├── docker/
│   ├── nginx/
│   └── php/
├── docs/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── docker-compose.yml
├── README.md
└── .env.example
```

---

## 📚 Documentation

Detailed project documentation will be maintained inside the **docs/** directory.

* System Architecture
* Database Design (ERD)
* Authentication Flow
* API Standards
* Deployment Guide

---

## 🛠️ Development Workflow

This project follows a feature-branch Git workflow.

```text
main
│
develop
│
├── feature/docker
├── feature/auth
├── feature/scooters
├── feature/gps
├── feature/rides
├── feature/billing
└── feature/websocket
```

Each feature is developed independently, reviewed, and merged into the **develop** branch before being promoted to **main**.

---

## 🎯 Project Goals

* Build a production-inspired backend platform.
* Demonstrate scalable API design.
* Implement geospatial search using PostGIS.
* Process asynchronous jobs with Redis.
* Integrate secure payments using Stripe.
* Apply clean architecture principles.
* Follow modern Git workflows and engineering best practices.

---

## 🚀 Future Enhancements

* Live Scooter Tracking
* Fleet Balancing
* Dynamic Pricing
* Push Notifications
* Admin Dashboard
* Kubernetes Deployment
* Terraform Infrastructure
* Monitoring & Observability

---

## 🤝 Contributing

This is currently a personal portfolio project developed for learning and demonstrating backend software engineering practices.

Contributions and suggestions will be welcome in future releases.

---

## 📄 License

This project is licensed under the MIT License.
