<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Can View Dashboard Count',
            'Can View Dashboard Analytics',
            'Can View All User Dashboard',
            'Can View Transave Staff',
            'Can Create Staff',
            'Can View Accounting Dashboard',
            'Can Manage Roles',
            'Can Add New Roles',
            'Can Update Roles',
            'Can View Operation’s Dashboard',
            'Can View User Analytics',
            'Can Suspend/Verify User',
            'Can View Users (Operations)',
            'Can View Account Upgrade',
            'Can Accept/Decline Document',
            'Can View Transave Staff',
            'Can View ID Card, Address & Passport',
            'Can View Transave Staff',
            'Can Create Users',
            'Can Hide Account',
            'Can Suspend Users',
            'See Users Transaction',
            'Can Edit/Update Users Kyc',
            'Can See Users Balance',
            'Can Delete Users',
            'See Users Kyc Level',
            'View User Full Details',
            'Can Export Users Table',
            'Can View Transactions',
            'View Transaction Amount',
            'Can Suspend Users',
            'Can Export Table',
            'Can View All Bills',
            'Can View Bill Amount',
            'Can Shutdown Requests',
            'Can Reactivate Requests',

            'View Savings Account Dashboard',
            'View Savings Account',
            'Can Edit User Savings',
            'View Savings Details',
            'View Savings Balance',
            'View Savings User',
            'View Agent Savings',
            'View Agent Details',
            'Set % Commission',
            'Suspend Account',

            'Can View Pos Dashboard Count',
            'View Pos Request',
            'Can View Pos Request Details',
            'Can View Agent Transaction',
            'View Pos Agents',
            'View Pos Agents Balance',
            'View Pos Agents Details',
            'Can Create Agent',
            'Can View Pos Terminals',
            'Assign Terminal To Agents',
            'View Agent\'s Balance',
            'Do Agent Settings',
            'Can View Agent’s Terminals',
            'Can View Terminal Details',
            'Can View Terminal Transactions',
            'View All Pos Terminals',
            'Can Create Terminals',
            'Can Fund Terminal',
            'Can Delete Terminal',
            'Can Export Data',
            'Can See Loan Requests',
            'View Loan Amount',
            'View Loan Details',
            'Accept Loan Application',
            'Process Loan',
            'Reject Loan',
            'Export Loan',
            'Can View Businesses',
            'Can View Business Details',
            'Can View Business Transactions',
            'Can Delete Business',
            'Can Accept/Reject Kyc Doc',
            'Can Edit Business Info',
            'Can View Business Staff',
            'Can View Business Card/Details',
            'Can View Business Transaction',
            'Can View Card Requests',
            'Can View Active Cards',
            'Can View Card Detail-Can View Card Transactions',
            'Can Accept/Reject Card Requests',
            'Can Authorise Card Process'
        ];

        foreach ($permissions as $permission){
            Permission::updateOrCreate([
                'name' => $permission
            ],[
                'id' => Str::uuid(),
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }
    }
}
