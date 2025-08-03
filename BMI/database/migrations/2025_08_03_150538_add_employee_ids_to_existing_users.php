<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all users without employee IDs
        $users = User::whereNull('employee_id')->orWhere('employee_id', '')->get();
        
        foreach ($users as $user) {
            $user->employee_id = User::generateEmployeeId($user->role);
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need to be reversed
        // Employee IDs are auto-generated and should remain
    }
};
