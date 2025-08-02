# Malnutrition Assessment and Treatment System

A comprehensive capstone project that combines a Laravel-based BMI assessment system with a Python-based machine learning treatment recommendation system.

## Project Overview

This project consists of two main components:

1. **BMI Assessment System** (Laravel/PHP) - A web-based application for patient management, nutrition assessment, and inventory tracking
2. **Treatment Model** (Python/Streamlit) - A machine learning system that provides treatment recommendations based on patient data

## Project Structure

```
capstone/
├── BMI/                          # Laravel application
│   ├── app/                      # Application logic
│   ├── database/                 # Database migrations and seeders
│   ├── resources/                # Views and assets
│   ├── routes/                   # Application routes
│   └── public/                   # Public assets
└── Treatment_Model_Random_Forest/ # Python ML application
    ├── app.py                    # Main Streamlit application
    ├── malnutrition_model.py     # ML model implementation
    ├── treatment_protocols/      # Treatment protocol definitions
    └── requirements.txt          # Python dependencies
```

## Features

### BMI Assessment System (Laravel)
- **User Management**: Multi-role system (Admin, Nutritionist, Patient)
- **Patient Management**: Comprehensive patient records and assessments
- **Nutrition Assessment**: BMI calculations and malnutrition screening
- **Inventory Management**: Food item tracking and transaction logging
- **API Integration**: RESTful API for external system integration
- **Reporting**: Comprehensive reports and analytics

### Treatment Model (Python)
- **Machine Learning**: Random Forest model for treatment recommendations
- **Treatment Protocols**: WHO-standard, hospital-intensive, and community-based protocols
- **Streamlit Interface**: User-friendly web interface for model interaction
- **Database Integration**: SQLite database for data persistence

## Installation and Setup

### Prerequisites
- PHP 8.1+ with Laravel requirements
- Python 3.8+
- MySQL/MariaDB
- Composer
- Node.js and npm

### BMI System Setup

1. **Navigate to the BMI directory:**
   ```bash
   cd BMI
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server:**
   ```bash
   php artisan serve
   ```

### Treatment Model Setup

1. **Navigate to the Treatment Model directory:**
   ```bash
   cd Treatment_Model_Random_Forest
   ```

2. **Create a virtual environment:**
   ```bash
   python -m venv venv
   source venv/bin/activate  # On Windows: venv\Scripts\activate
   ```

3. **Install Python dependencies:**
   ```bash
   pip install -r requirements.txt
   ```

4. **Run the Streamlit application:**
   ```bash
   streamlit run app.py
   ```

## Usage

### BMI System
- Access the web application at `http://localhost:8000`
- Default admin credentials are seeded in the database
- API endpoints are available at `/api/` for external integrations

### Treatment Model
- Access the Streamlit interface at `http://localhost:8501`
- Input patient data to receive treatment recommendations
- View different treatment protocols and their effectiveness

## API Documentation

The BMI system provides RESTful APIs for:
- Patient management
- Nutrition assessments
- Inventory tracking
- Treatment recommendations

API documentation is available in the `BMI/API-TEST-GUIDE.md` file.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is part of a capstone project for academic purposes.

## Support

For questions or issues, please refer to the documentation in each component's directory or contact the development team. 