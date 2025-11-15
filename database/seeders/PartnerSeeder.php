<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\PartnerOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo partner accounts
        $partners = [
            [
                'name' => 'John Doe',
                'email' => 'partner@diantara.com',
                'password' => Hash::make('password123'),
                'phone' => '+62812345678',
                'organization_name' => 'EventPro Indonesia',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => 1, // Assuming admin user ID is 1
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'partner2@diantara.com',
                'password' => Hash::make('password123'),
                'phone' => '+62823456789',
                'organization_name' => 'Creative Events Co.',
                'address' => 'Jl. Gatot Subroto No. 456, Bandung, Jawa Barat',
                'status' => 'pending',
                'verified_at' => null,
                'verified_by' => null,
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'partner3@diantara.com',
                'password' => Hash::make('password123'),
                'phone' => '+62834567890',
                'organization_name' => 'Venue Masters',
                'address' => 'Jl. Malioboro No. 789, Yogyakarta, DIY',
                'status' => 'verified',
                'verified_at' => now()->subDays(7),
                'verified_by' => 1,
            ],
        ];

        foreach ($partners as $partnerData) {
            $partner = Partner::create($partnerData);

            // Create organization for verified partners
            if ($partner->status === 'verified') {
                PartnerOrganization::create([
                    'partner_id' => $partner->id,
                    'name' => $partner->organization_name,
                    'type' => collect(['Event Organizer', 'Promotor', 'Venue Owner'])->random(),
                    'description' => 'Professional event management company with years of experience in organizing memorable experiences.',
                    'website' => 'https://www.' . strtolower(str_replace(' ', '', $partner->organization_name)) . '.com',
                    'logo' => null,
                    'contact_info' => json_encode([
                        'phone' => $partner->phone,
                        'email' => $partner->email,
                        'address' => $partner->address,
                    ]),
                    'business_info' => json_encode([
                        'registration_number' => 'REG-' . rand(100000, 999999),
                        'tax_number' => 'TAX-' . rand(100000, 999999),
                        'bank_account' => 'BCA-' . rand(1000000000, 9999999999),
                    ]),
                    'status' => 'active',
                ]);
            }
        }

        $this->command->info('Partner seeder completed successfully!');
        $this->command->info('Demo accounts created:');
        $this->command->info('- partner@diantara.com (verified)');
        $this->command->info('- partner2@diantara.com (pending)');
        $this->command->info('- partner3@diantara.com (verified)');
        $this->command->info('Password for all accounts: password123');
    }
}
