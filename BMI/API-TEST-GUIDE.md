# FastAPI Model Test Script for BMI Laravel Project

This script allows you to test your FastAPI model integration with your Laravel application.

## Prerequisites

1. **FastAPI Server Running**: Make sure your FastAPI server is running on `http://127.0.0.1:8000`
2. **Laravel Server Running**: Make sure your Laravel server is running on `http://127.0.0.1:8080` (or your configured port)

## Testing Methods

### Method 1: Web Interface (Recommended)

1. **Start your Laravel application**:
   ```bash
   composer dev
   ```
   
2. **Access the API Test Interface**:
   - Login to your Laravel admin panel
   - Navigate to: `http://your-laravel-url/admin/api-test`
   - Or click "API Test" in the admin navigation menu

3. **Test Features Available**:
   - ✅ API Health Check
   - ✅ Model Information
   - ✅ Treatment Protocols
   - ✅ Single Patient Assessment
   - ✅ Form-based Testing with Real Data

### Method 2: Direct API Testing

#### Test API Health:
```bash
curl -X GET "http://127.0.0.1:8080/api/health" -H "Accept: application/json"
```

#### Test Patient Assessment:
```bash
curl -X POST "http://127.0.0.1:8080/api/assess" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Patient",
    "age_months": 24,
    "weight": 10.5,
    "height": 85,
    "sex": "Male",
    "municipality": "Test Municipality",
    "patient_id": "TEST001",
    "total_household": 5,
    "adults": 2,
    "children": 3,
    "is_twin": false,
    "fourps_beneficiary": "No",
    "breastfeeding": "Yes",
    "tuberculosis": "No",
    "malaria": "No",
    "congenital_anomalies": "No",
    "other_medical_problems": "No",
    "edema": false
  }'
```

#### Get Model Information:
```bash
curl -X GET "http://127.0.0.1:8080/api/model-info" -H "Accept: application/json"
```

### Method 3: Test Specific FastAPI Endpoints

#### Test FastAPI Health directly:
```bash
curl -X GET "http://127.0.0.1:8000/health"
```

#### Test FastAPI Assessment directly:
```bash
curl -X POST "http://127.0.0.1:8000/assess" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Patient",
    "age_months": 24,
    "weight": 10.5,
    "height": 85,
    "sex": "Male",
    "municipality": "Test Municipality"
  }'
```

## Expected Results

### Healthy API Response:
```json
{
  "success": true,
  "message": "API is healthy",
  "data": {
    "status": "healthy",
    "timestamp": "2025-07-20T13:04:39.608818",
    "model_loaded": true,
    "protocols_loaded": true,
    "version": "1.0.0",
    "service": "Malnutrition Assessment API"
  }
}
```

### Assessment Response:
```json
{
  "success": true,
  "message": "Assessment completed successfully",
  "data": {
    "classification": {
      "status": "Normal/Malnourished",
      "severity": "Mild/Moderate/Severe",
      "category": "SAM/MAM/Normal"
    },
    "measurements": {
      "bmi": 14.5,
      "zscore": -1.2,
      "weight_category": "Normal",
      "height_category": "Normal"
    },
    "treatment": {
      "protocol": "Treatment Protocol Name",
      "action": "Required Action",
      "recommendations": "Treatment recommendations"
    },
    "follow_up": {
      "next_visit": "Date",
      "frequency": "Weekly/Monthly",
      "priority": "High/Medium/Low"
    }
  }
}
```

## Troubleshooting

### Common Issues:

1. **API Connection Failed**:
   - Check if FastAPI server is running on port 8000
   - Verify the URL configuration in Laravel config/services.php

2. **Model Not Loaded**:
   - Check FastAPI logs for model loading errors
   - Ensure model files are in the correct directory

3. **Protocols Not Loaded**:
   - Check if protocol files are accessible
   - Verify protocol file format and location

4. **CSRF Token Issues**:
   - The web interface handles CSRF automatically
   - For direct API calls, ensure proper authentication

### Configuration Check:

Verify your Laravel configuration in `config/services.php`:
```php
'malnutrition' => [
    'api_url' => env('MALNUTRITION_API_URL', 'http://127.0.0.1:8000'),
    'timeout' => env('MALNUTRITION_API_TIMEOUT', 30),
],
```

Add to your `.env` file if needed:
```
MALNUTRITION_API_URL=http://127.0.0.1:8000
MALNUTRITION_API_TIMEOUT=30
```

## Quick Start Test

1. Start FastAPI: `uvicorn main:app --host 127.0.0.1 --port 8000`
2. Start Laravel: `composer dev`
3. Open: `http://your-laravel-url/admin/api-test`
4. Click "Check API Health"
5. Fill the assessment form and click "Test Assessment"

## Integration Points

Your Laravel application integrates with FastAPI through:

- **MalnutritionService**: Handles API communication
- **AssessmentController**: Processes assessment requests
- **API Routes**: `/api/health`, `/api/assess`, `/api/model-info`
- **Web Interface**: Admin panel test interface

The integration is complete and ready for testing!
