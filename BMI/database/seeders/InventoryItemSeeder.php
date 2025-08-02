<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use Carbon\Carbon;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Vitamin C Tablets',
                'sku' => 'SUPP-001',
                'category' => 'supplements',
                'unit' => 'bottles',
                'current_stock' => 50,
                'minimum_stock' => 10,
                'expiry_date' => Carbon::now()->addMonths(6),
                'description' => '100mg Vitamin C tablets',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Rice',
                'sku' => 'FOOD-001',
                'category' => 'therapeutic_food',
                'unit' => 'kg',
                'current_stock' => 200,
                'minimum_stock' => 50,
                'expiry_date' => Carbon::now()->addMonths(12),
                'description' => 'Sack of white rice',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Paracetamol Syrup',
                'sku' => 'MED-001',
                'category' => 'medical_supplies',
                'unit' => 'bottles',
                'current_stock' => 30,
                'minimum_stock' => 5,
                'expiry_date' => Carbon::now()->addMonths(3),
                'description' => '120ml Paracetamol syrup for children',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Weighing Scale',
                'sku' => 'EQP-001',
                'category' => 'equipment',
                'unit' => 'pieces',
                'current_stock' => 5,
                'minimum_stock' => 2,
                'expiry_date' => null,
                'description' => 'Digital weighing scale',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Peanut Butter',
                'sku' => 'FOOD-002',
                'category' => 'therapeutic_food',
                'unit' => 'bottles',
                'current_stock' => 40,
                'minimum_stock' => 10,
                'expiry_date' => Carbon::now()->addMonths(8),
                'description' => 'High-protein peanut butter',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Multivitamin Drops',
                'sku' => 'SUPP-002',
                'category' => 'supplements',
                'unit' => 'bottles',
                'current_stock' => 20,
                'minimum_stock' => 5,
                'expiry_date' => Carbon::now()->addMonths(4),
                'description' => 'Multivitamin drops for infants',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Stethoscope',
                'sku' => 'EQP-002',
                'category' => 'equipment',
                'unit' => 'pieces',
                'current_stock' => 3,
                'minimum_stock' => 1,
                'expiry_date' => null,
                'description' => 'Standard stethoscope',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Antibiotic Ointment',
                'sku' => 'MED-002',
                'category' => 'medical_supplies',
                'unit' => 'boxes',
                'current_stock' => 15,
                'minimum_stock' => 5,
                'expiry_date' => Carbon::now()->addMonths(10),
                'description' => 'Topical antibiotic ointment',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Measuring Tape',
                'sku' => 'EQP-003',
                'category' => 'equipment',
                'unit' => 'pieces',
                'current_stock' => 10,
                'minimum_stock' => 3,
                'expiry_date' => null,
                'description' => 'Flexible measuring tape',
                'barangay' => 'Barangay 3',
            ],
            [
                'name' => 'Other Sample Item',
                'sku' => 'OTH-001',
                'category' => 'other',
                'unit' => 'boxes',
                'current_stock' => 8,
                'minimum_stock' => 2,
                'expiry_date' => Carbon::now()->addMonths(2),
                'description' => 'Miscellaneous item',
                'barangay' => 'Barangay 3',
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::firstOrCreate([
                'sku' => $item['sku'],
            ], $item);
        }
    }
} 