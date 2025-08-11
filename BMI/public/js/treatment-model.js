// Treatment Model API JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for API requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // API endpoints
    const apiBase = '/api/treatment-model';
    
    // Utility functions
    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    }
    
    function displayResult(containerId, contentId, data, isError = false) {
        const container = document.getElementById(containerId);
        const content = document.getElementById(contentId);
        
        if (!container || !content) return;
        
        container.classList.remove('hidden');
        
        if (isError) {
            content.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Error:</strong> ${data.message || data.error || 'An error occurred'}
            </div>`;
        } else {
            content.innerHTML = `<pre class="whitespace-pre-wrap text-sm">${JSON.stringify(data, null, 2)}</pre>`;
        }
    }
    
    async function makeApiRequest(endpoint, method = 'GET', data = null) {
        showLoading();
        
        try {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Authorization': `Bearer ${localStorage.getItem('api_token') || ''}`
                }
            };
            
            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }
            
            const response = await fetch(apiBase + endpoint, options);
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || result.error || 'Request failed');
            }
            
            return result;
        } catch (error) {
            throw error;
        } finally {
            hideLoading();
        }
    }
    
    // Health Check
    const checkHealthBtn = document.getElementById('checkHealthBtn');
    if (checkHealthBtn) {
        checkHealthBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/health');
                displayResult('healthStatus', 'healthStatus', result);
            } catch (error) {
                displayResult('healthStatus', 'healthStatus', error, true);
            }
        });
    }
    
    // Data Validation
    const validateDataBtn = document.getElementById('validateDataBtn');
    if (validateDataBtn) {
        validateDataBtn.addEventListener('click', async () => {
            const formData = new FormData(document.getElementById('assessmentForm'));
            const data = Object.fromEntries(formData);
            
            try {
                const result = await makeApiRequest('/data/validate', 'POST', data);
                displayResult('validationContainer', 'validationContent', result);
            } catch (error) {
                displayResult('validationContainer', 'validationContent', error, true);
            }
        });
    }
    
    // Patient Assessment
    const assessmentForm = document.getElementById('assessmentForm');
    if (assessmentForm) {
        assessmentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            // Copy data to recommendations form
            const recAgeMonths = document.getElementById('rec_age_months');
            const recWeight = document.getElementById('rec_weight');
            const recHeight = document.getElementById('rec_height');
            const recSex = document.getElementById('rec_sex');
            
            if (recAgeMonths) recAgeMonths.value = data.age_months;
            if (recWeight) recWeight.value = data.weight;
            if (recHeight) recHeight.value = data.height;
            if (recSex) recSex.value = data.sex;
            
            try {
                const result = await makeApiRequest('/assess/single', 'POST', data);
                displayResult('resultsContainer', 'resultContent', result);
            } catch (error) {
                displayResult('resultsContainer', 'resultContent', error, true);
            }
        });
    }
    
    // Risk Stratification
    const riskStratifyBtn = document.getElementById('riskStratifyBtn');
    if (riskStratifyBtn) {
        riskStratifyBtn.addEventListener('click', async () => {
            const formData = new FormData(document.getElementById('assessmentForm'));
            const data = Object.fromEntries(formData);
            
            try {
                const result = await makeApiRequest('/risk/stratify', 'POST', data);
                displayResult('resultsContainer', 'resultContent', result);
            } catch (error) {
                displayResult('resultsContainer', 'resultContent', error, true);
            }
        });
    }
    
    // Uncertainty Analysis
    const uncertaintyBtn = document.getElementById('uncertaintyBtn');
    if (uncertaintyBtn) {
        uncertaintyBtn.addEventListener('click', async () => {
            const formData = new FormData(document.getElementById('assessmentForm'));
            const data = Object.fromEntries(formData);
            
            try {
                const result = await makeApiRequest('/predict/uncertainty', 'POST', data);
                displayResult('resultsContainer', 'resultContent', result);
            } catch (error) {
                displayResult('resultsContainer', 'resultContent', error, true);
            }
        });
    }
    
    // Load Protocols
    const loadProtocolsBtn = document.getElementById('loadProtocolsBtn');
    if (loadProtocolsBtn) {
        loadProtocolsBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/protocols');
                displayResult('protocolsContainer', 'protocolsContainer', result);
            } catch (error) {
                displayResult('protocolsContainer', 'protocolsContainer', error, true);
            }
        });
    }
    
    // Personalized Recommendations
    const recommendationsForm = document.getElementById('recommendationsForm');
    if (recommendationsForm) {
        recommendationsForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            // Convert checkbox arrays to actual arrays
            const medicalHistory = formData.getAll('medical_history[]');
            const preferences = formData.getAll('preferences[]');
            
            data.medical_history = medicalHistory;
            data.preferences = preferences;
            
            try {
                const result = await makeApiRequest('/recommendations/personalized', 'POST', data);
                displayResult('recommendationsContainer', 'recommendationsContent', result);
            } catch (error) {
                displayResult('recommendationsContainer', 'recommendationsContent', error, true);
            }
        });
    }
    
    // Get Template
    const getTemplateBtn = document.getElementById('getTemplateBtn');
    if (getTemplateBtn) {
        getTemplateBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/data/template');
                displayResult('templateContainer', 'templateContainer', result);
            } catch (error) {
                displayResult('templateContainer', 'templateContainer', error, true);
            }
        });
    }
    
    // Admin Controls (check if elements exist)
    const modelInfoBtn = document.getElementById('modelInfoBtn');
    if (modelInfoBtn) {
        modelInfoBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/model/info');
                displayResult('adminResultsContainer', 'adminResultContent', result);
            } catch (error) {
                displayResult('adminResultsContainer', 'adminResultContent', error, true);
            }
        });
    }
    
    const analyticsBtn = document.getElementById('analyticsBtn');
    if (analyticsBtn) {
        analyticsBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/analytics/summary');
                displayResult('adminResultsContainer', 'adminResultContent', result);
            } catch (error) {
                displayResult('adminResultsContainer', 'adminResultContent', error, true);
            }
        });
    }
    
    const retrainModelBtn = document.getElementById('retrainModelBtn');
    if (retrainModelBtn) {
        retrainModelBtn.addEventListener('click', async () => {
            try {
                const result = await makeApiRequest('/model/train', 'POST', {
                    retrain_percentage: 80,
                    validation_split: 0.2
                });
                displayResult('adminResultsContainer', 'adminResultContent', result);
            } catch (error) {
                displayResult('adminResultsContainer', 'adminResultContent', error, true);
            }
        });
    }
});
