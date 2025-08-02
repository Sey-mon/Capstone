# Changelog

## 2025-07-21

- Added `religion` field to patients table, forms, and registration.
- Added `barangay` field to users and inventory_items tables, forms, and registration.
- Made `barangay` and `religion` required in all relevant places.
- Admin can now filter inventory by barangay and assign barangay when adding items.
- Nutritionist inventory creation now auto-assigns barangay and hides the field.
- Admin can see and manage all barangays' inventory, nutritionists see only their own.
- Updated seeders to include barangay for users and inventory items.
- Improved inventory CRUD and UI for barangay-specific management. 
- Added missing `admin.transactions.store` and `nutritionist.transactions.store` routes for transaction creation.
- Added inventory transaction log feature for nutritionist (shows only inventory-related logs for their barangay). 