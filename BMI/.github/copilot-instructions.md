# Copilot Instructions

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

This is a Laravel project with authentication for both admin and regular users, featuring a comprehensive malnutrition monitoring and inventory management system.

## Project Context
- Laravel 12.x framework
- Multi-role authentication system (Admin and User roles)
- MySQL database (configurable to SQLite for development)
- Laravel Breeze for authentication scaffolding
- Tailwind CSS for styling
- Role-based access control with middleware
- **Malnutrition Monitoring System** - Complete patient management and nutrition assessments
- **Inventory Management System** - Full inventory tracking with stock management and transactions

## Coding Guidelines
- Follow Laravel conventions and best practices
- Use Eloquent models for database interactions
- Implement proper middleware for role-based access control
- Use Laravel's built-in authentication features
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add proper documentation for complex functions
- Use PHPDoc annotations for better IDE support

## Authentication Structure
- User model with role field (`admin` or `user`)
- AdminMiddleware for protecting admin routes
- Role-based navigation and redirects
- Seeded test users (admin@example.com and user@example.com)
- Admin dashboard with comprehensive management tools
- Regular user dashboard

## File Structure
### Models
- `app/Models/User.php` - Enhanced with role methods (isAdmin(), isUser())
- `app/Models/Patient.php` - Patient management with relationships
- `app/Models/NutritionAssessment.php` - Nutrition assessments with BMI calculations
- `app/Models/InventoryItem.php` - Inventory items with stock tracking
- `app/Models/InventoryTransaction.php` - Stock movement transactions

### Middleware
- `app/Http/Middleware/AdminMiddleware.php` - Protects admin routes

### Controllers
- `app/Http/Controllers/AdminController.php` - Complete admin functionality including:
  - Dashboard with statistics and quick actions
  - Patient management (CRUD operations)
  - Nutrition assessments with BMI calculations
  - Inventory management with stock tracking
  - Transaction recording and history
  - Comprehensive reporting and analytics

### Views - Admin Module
- `resources/views/admin/dashboard.blade.php` - Enhanced admin dashboard with stats and quick actions
- `resources/views/admin/users.blade.php` - User management interface
- `resources/views/admin/patients.blade.php` - **NEW** Patient management with search/filter capabilities
- `resources/views/admin/nutrition.blade.php` - **NEW** Nutrition assessment interface with form validation
- `resources/views/admin/inventory.blade.php` - **NEW** Inventory management with stock level monitoring
- `resources/views/admin/transactions.blade.php` - **NEW** Transaction history and recording
- `resources/views/admin/reports.blade.php` - **NEW** Analytics dashboard with multiple report types
- `resources/views/test-page.blade.php` - Project status page
- `resources/views/setup-database.blade.php` - Database setup guide

### External Assets (CSS & JavaScript Separation)
- `public/css/auth.css` - Authentication pages styling (login/register)
- `public/css/pages.css` - Test and setup pages styling
- `public/js/auth.js` - Authentication pages JavaScript functionality
- `public/js/pages.js` - Test and setup pages JavaScript functionality

**CSS/JS Separation Completed:**
- Removed all inline `<style>` blocks from view files
- Extracted authentication styles to `auth.css` with modern green theme
- Extracted test/setup page styles to `pages.css`
- Created interactive JavaScript files with features:
  - Form validation and submission feedback
  - Copy-to-clipboard functionality for code blocks
  - Smooth transitions and hover effects
  - Auto-focus on form inputs
  - Notification system for user feedback
- Updated all view files to use external asset links
- Maintained responsive design and accessibility features

### Routes
- Admin routes: Protected by admin middleware including:
  - `/admin/dashboard` - Main admin dashboard
  - `/admin/users` - User management
  - `/admin/patients` - Patient management (with CRUD operations)
  - `/admin/nutrition` - Nutrition assessments (with CRUD operations)
  - `/admin/inventory` - Inventory management (with CRUD operations)
  - `/admin/transactions` - Transaction history and recording
  - `/admin/reports` - Analytics and reporting
- Public routes: `/test`, `/setup` for debugging and setup

### Database
- Migration: `add_role_to_users_table.php` - Adds role enum field
- **NEW Migrations:**
  - `create_patients_table.php` - Patient demographics and information
  - `create_nutrition_assessments_table.php` - Nutrition assessments with BMI tracking
  - `create_inventory_items_table.php` - Inventory items with stock management
  - `create_inventory_transactions_table.php` - Stock movement tracking
- Seeder: `AdminUserSeeder.php` - Creates test admin and user accounts

## Feature Set
### Patient Management
- Complete patient registration and demographics
- Patient search and filtering capabilities
- Age-based categorization and statistics
- Patient profile management with assessment history

### Nutrition Assessment System
- BMI calculation and categorization
- Nutritional status determination (normal, at risk, malnourished)
- MUAC (Mid-Upper Arm Circumference) measurements
- Clinical signs tracking
- Follow-up scheduling and monitoring
- Assessment history and trends

### Inventory Management
- Multi-category inventory tracking (supplements, food, medicine, equipment)
- Stock level monitoring with automatic alerts
- Expiry date tracking with notifications
- Minimum stock threshold management
- Stock movement recording (in/out transactions)
- Comprehensive transaction history

### Reporting & Analytics
- Nutrition status distribution and trends
- Patient demographics and statistics
- Inventory valuation and movement reports
- Critical case identification and alerts
- Seasonal pattern analysis
- Intervention effectiveness tracking

## Development Notes
- Database connection configurable via .env (MySQL/SQLite)
- Test pages available for troubleshooting
- Role-based UI components in navigation
- Proper type hinting and PHPDoc for IDE support
- **CSS/JS Separation:** All inline styles and scripts moved to external files
- **Modern UI:** Custom green-themed authentication with glassmorphism effects
- **Interactive Features:** Form enhancements, copy functionality, smooth animations
- **Asset Organization:** Structured external CSS/JS files for maintainability
- **Complete Admin Module:** Fully functional malnutrition monitoring and inventory system
- **Responsive Design:** All views optimized for mobile and desktop
- **Data Validation:** Comprehensive form validation for all CRUD operations
- **Search & Filter:** Advanced filtering capabilities across all data views

## Recent Major Updates
1. **Complete Admin Module Expansion**: Added full malnutrition monitoring and inventory management
2. **New Models & Migrations**: Patient, NutritionAssessment, InventoryItem, InventoryTransaction
3. **Enhanced AdminController**: Full CRUD operations for all modules with data calculations
4. **Comprehensive Views**: Professional admin interface with statistics, forms, and data tables
5. **Advanced Routing**: Complete route structure for all admin operations
6. **Professional UI**: Modern dashboard with cards, statistics, and interactive elements
7. **Form Handling**: Complete form validation and data processing
8. **Relationship Management**: Proper Eloquent relationships between all models

## Recent Code Fixes (June-July 2025)
- Fixed "Undefined property: User::$id" by replacing all `$user->id` with `$user->getKey()` in controllers for safer Eloquent access.
- Updated `AdminControllerNew::reports()` to pass `criticalCases`, `nutritionStats`, `totalAssessments`, and `inventoryStats` to the reports view for analytics.
- Improved `resources/views/admin/reports.blade.php` to:
  - Safely check for missing patient relationships (`$case->patient`) and display fallback text if missing.
  - Add null checks for BMI and patient data to prevent errors.
  - Use `assessment_date` and `next_assessment_date` with null checks for robust date display.
- Confirmed all model relationships and accessors (e.g., `Patient::getAgeAttribute()`, `NutritionAssessment::patient()`) are correct and documented.
- All changes are saved and reflected in the workspace files.

## Bug Fixes (July 2025)
- Fixed column name mismatch error: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'nutritional_status'`
  - Updated all references from `nutritional_status` to `nutrition_status` in AdminController.php to match the database schema
  - Updated status values from `'normal', 'at_risk', 'malnourished'` to `'normal', 'mild_malnutrition', 'moderate_malnutrition', 'severe_malnutrition'` to match enum values in migration
  - Updated blade templates to use correct column names
  - Added `lastAssessment()` relationship method to Patient model to maintain compatibility with existing code

- Fixed column name mismatch error: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'follow_up_date'`
  - Updated all references from `follow_up_date` to `next_assessment_date` in AdminController.php and blade files to match the database schema
  - Fixed form field names to ensure proper data submission

- Enhanced navigation with comprehensive menu tabs
  - Added dedicated tabs for all key features: Dashboard, Patients, Nutrition, Inventory, Transactions, Reports, and Users
  - Implemented both desktop and mobile responsive navigation
  - Fixed route active state indicators for better navigation awareness
  - Applied green and white theme throughout navigation components
  - Updated all nav-link, responsive-nav-link, and dropdown components to support the green theme
  - Ensured consistent styling across desktop and mobile interfaces

## Role System Update (July 2025)
- **Added Nutritionist Role**: Expanded the role system to include three roles: admin, user (parent), and nutritionist
  - Created migration `2025_07_05_000000_update_role_enum_in_users_table.php` to update the role enum in users table
  - Updated User model with `isNutritionist()` method to check for nutritionist permissions
  - Created `NutritionistMiddleware` for protecting nutritionist-specific routes
  - Registered the nutritionist middleware in `Kernel.php`
  - Added dedicated routes for nutritionists with appropriate permissions
  - Created a `NutritionistController` that extends `AdminController` with specialized methods
  - Updated the registration form to allow users to select between Parent/Guardian and Nutritionist roles
  - Updated the dashboard redirect logic to handle nutritionist users
  - Added a seeded nutritionist test user (nutritionist@example.com)

## Nutritionist Interface Update (July 5, 2025)
- **Created Nutritionist Views**: Built complete interface for nutritionist users
  - Created `resources/views/nutritionist/dashboard.blade.php` with statistics overview, critical cases, and follow-ups
  - Created `resources/views/nutritionist/patients.blade.php` for patient management
  - Created `resources/views/nutritionist/patient-details.blade.php` for detailed patient information
  - Created `resources/views/nutritionist/nutrition.blade.php` for assessment creation and listing
  - Created `resources/views/nutritionist/nutrition-details.blade.php` for detailed assessment view
  - Created `resources/views/nutritionist/reports.blade.php` for nutrition status reporting
  - Added BMI calculation, color-coded status indicators, and responsive data tables
  - Implemented all necessary forms for creating patients and assessments
- **Fixed Controller Methods**: Enhanced NutritionistController with required functionality
  - Added `assessment_date` field handling for nutrition assessments
  - Implemented `showPatient` and `storePatient` methods
  - Configured proper redirects and success messages

## System Status
✅ **COMPLETED**: Laravel project with full authentication system
✅ **COMPLETED**: Multi-role user management (Admin/User)
✅ **COMPLETED**: Custom green-themed authentication UI
✅ **COMPLETED**: External CSS/JS separation and organization
✅ **COMPLETED**: Patient management system with full CRUD operations
✅ **COMPLETED**: Nutrition assessment system with BMI calculations
✅ **COMPLETED**: Inventory management with stock tracking
✅ **COMPLETED**: Transaction recording and history
✅ **COMPLETED**: Comprehensive reporting and analytics dashboard
✅ **COMPLETED**: Enhanced admin dashboard with statistics and quick actions
✅ **COMPLETED**: All database migrations and model relationships
✅ **COMPLETED**: Professional admin interface with responsive design

**PROJECT STATUS: FULLY FUNCTIONAL MALNUTRITION MONITORING & INVENTORY MANAGEMENT SYSTEM**
