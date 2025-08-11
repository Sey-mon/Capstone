# Treatment Model API - Role-Based Integration

## Overview

This document describes the integration of the Python Treatment Model API into your Laravel application with proper role-based access control. The system provides secure endpoints for nutritionists and administrators to interact with the machine learning model for child malnutrition assessment.

## Roles and Permissions

### 👩‍⚕️ Nutritionist Role
**Core assessment and patient care functions**

- Patient Assessment
- Treatment & Recommendations  
- Data Templates & Validation
- System Status monitoring

### 🔧 Admin Role
**System management and configuration functions**

- Model Management (view info, retrain)
- Protocol Management (change active protocols)
- System Analytics (usage statistics)
- All Nutritionist permissions

## API Endpoints

### Nutritionist Endpoints (`/api/treatment-model`)

#### Patient Assessment
- `POST /assess/single` - Assess single patient (primary function)
- `POST /assess/batch` - Assess multiple patients
- `POST /assess/upload` - Upload patient data files for assessment

#### Treatment & Recommendations
- `GET /protocols` - View available treatment protocols
- `POST /risk/stratify` - Perform risk stratification analysis
- `POST /predict/uncertainty` - Get predictions with uncertainty measures
- `POST /recommendations/personalized` - Generate personalized treatment recommendations

#### Data Templates & Validation
- `GET /data/template` - Get patient data entry templates
- `POST /data/validate` - Validate patient data before assessment

#### System Status
- `GET /health` - Check system health
- `GET /` - View API information

### Admin Only Endpoints

#### Model Management
- `GET /model/info` - View model details and performance metrics
- `POST /model/train` - Retrain the ML model (critical system function)

#### Protocol Management
- `POST /protocols/set` - Change active treatment protocols (system configuration)

#### System Analytics
- `GET /analytics/summary` - View system usage statistics and performance

## Authentication & Security

### Authentication
- Uses Laravel Sanctum for API authentication
- Requires valid Bearer token in Authorization header
- Role-based middleware validates user permissions

### Role Verification
- `RequireRole` middleware checks user roles
- Nutritionist accounts must be approved by admin
- Comprehensive audit logging for all API requests

### Security Features
- Request/response logging with ApiLog model
- IP address and user agent tracking
- Rate limiting and input validation
- CSRF protection for web forms

## Usage Examples

### 1. Single Patient Assessment

```bash
curl -X POST /api/treatment-model/assess/single \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "age_months": 24,
    "weight": 10.5,
    "height": 85,
    "sex": "Male",
    "name": "Sample Patient"
  }'
```

### 2. Data Validation

```bash
curl -X POST /api/treatment-model/data/validate \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "age_months": 24,
    "weight": 10.5,
    "height": 85,
    "sex": "Male"
  }'
```

### 3. Personalized Recommendations

```bash
curl -X POST /api/treatment-model/recommendations/personalized \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "age_months": 24,
    "weight": 10.5,
    "height": 85,
    "sex": "Male",
    "medical_history": ["tuberculosis"],
    "preferences": ["breastfeeding"]
  }'
```

## Web Interface

### Nutritionist Dashboard
- Access via navigation: "🤖 Treatment Model API"
- Quick assessment form with real-time validation
- Multiple analysis options (assessment, risk stratification, uncertainty analysis)
- Personalized recommendations generator
- Protocol viewer and data template access

### Admin Controls
- Model information and performance metrics
- System analytics dashboard
- Model retraining functionality (with confirmation)
- Critical operation logging

## Configuration

### Environment Variables

```bash
# Treatment Model Python API Configuration
PYTHON_API_URL=http://localhost:8000
TREATMENT_MODEL_API_KEY=treatment_model_api_key_here
```

### Database Tables

#### api_logs
Stores all API request audit information:
- endpoint, method, user_id
- request_data, response_data, status
- ip_address, user_agent, execution_time
- timestamps for audit trail

## Error Handling

### Common Error Responses

```json
{
  "error": "Validation failed",
  "messages": {
    "age_months": ["The age months field is required."]
  }
}
```

```json
{
  "error": "Forbidden", 
  "message": "Insufficient permissions. Required roles: nutritionist, admin",
  "user_role": "parent_guardian"
}
```

```json
{
  "error": "Connection to treatment model failed",
  "message": "Connection timeout"
}
```

## Installation & Setup

### 1. Add Routes
Routes are automatically added to `routes/api.php` with proper middleware.

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Configure Environment
Update your `.env` file with Python API settings.

### 4. Set Permissions
Ensure users have appropriate roles:
- `nutritionist` - approved nutritionist accounts
- `admin` - system administrators

### 5. Start Python API
Ensure your Python Treatment Model API is running on the configured URL.

## Monitoring & Analytics

### API Usage Tracking
- All requests logged to `api_logs` table
- User activity monitoring
- Error rate tracking
- Performance metrics

### Admin Analytics Dashboard
- Total API requests (daily/monthly)
- Active users statistics
- Top endpoints usage
- Error rate analysis
- Python model health status

## Security Considerations

### Critical Operations
- Model retraining requires admin confirmation
- Protocol changes are logged with user details
- All admin actions have enhanced logging

### Data Protection
- Sensitive patient data is validated but not permanently stored in logs
- API responses are logged for audit purposes
- User permissions are strictly enforced

## Troubleshooting

### Common Issues

1. **"Connection to treatment model failed"**
   - Check if Python API is running
   - Verify PYTHON_API_URL in .env
   - Check network connectivity

2. **"Insufficient permissions"**
   - Verify user role in database
   - Ensure nutritionist account is approved
   - Check middleware configuration

3. **"Validation failed"**
   - Review required fields in data template
   - Check data types and ranges
   - Use validation endpoint before assessment

### Debug Mode
Enable Laravel debug mode to see detailed error messages during development.

## Support

For technical support or feature requests, contact the development team with:
- Error messages and logs
- User role and permissions
- Steps to reproduce issues
- API endpoint and request data used
