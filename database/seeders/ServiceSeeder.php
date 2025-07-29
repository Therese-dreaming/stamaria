<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Baptism',
                'description' => 'Welcome your child into the Christian faith through the sacred sacrament of Baptism.',
                'price' => 500.00,
                'icon' => 'fas fa-water',
                'duration_minutes' => 60,
                'types' => [
                    'GROUP BAPTISM - Sundays Only (4 slots) 10AM ONLY',
                    'SOLO BAPTISM - Tuesday to Saturday (1 slot)'
                ],
                'schedules' => 'Group: Sundays 10AM; Solo: Tue-Sat by appointment',
                'requirements' => [
                    'Certified true copy of Birth Certificate',
                    'Marriage Contract (if married)',
                    'Baptismal Permit (if coming from other parish)'
                ],
                'additional_notes' => 'Please arrive 30 minutes before the scheduled time. Godparents must be practicing Catholics.'
            ],
            [
                'name' => 'Wedding',
                'description' => 'Celebrate your love and commitment through the sacred bond of marriage.',
                'price' => 3000.00,
                'icon' => 'fas fa-rings-wedding',
                'duration_minutes' => 120,
                'types' => null,
                'schedules' => 'By appointment',
                'requirements' => [
                    'Marriage License',
                    'Baptismal Certificate',
                    'Confirmation Certificate',
                    'Canonical Interview'
                ],
                'additional_notes' => 'Pre-Cana seminar required. Book at least 3 months in advance.'
            ],
            [
                'name' => 'Mass Intention',
                'description' => 'Request a Mass to be celebrated for your special intentions or loved ones.',
                'price' => 100.00,
                'icon' => 'fas fa-church',
                'duration_minutes' => 30,
                'types' => null,
                'schedules' => 'Daily Masses',
                'requirements' => [
                    'Name of person/intention',
                    'Preferred date and time'
                ],
                'additional_notes' => 'Submit requests at least 1 day before the intended date.'
            ],
            [
                'name' => 'House Blessing',
                'description' => 'Receive blessings for your home, vehicle, or other possessions.',
                'price' => 800.00,
                'icon' => 'fas fa-pray',
                'duration_minutes' => 90,
                'types' => null,
                'schedules' => 'By appointment',
                'requirements' => [
                    'Complete address',
                    'Contact number',
                    'Preferred date and time'
                ],
                'additional_notes' => 'Prepare a crucifix and holy water if available.'
            ],
            [
                'name' => 'Confirmation',
                'description' => 'Complete your Christian initiation through the sacrament of Confirmation.',
                'price' => 1500.00,
                'icon' => 'fas fa-dove',
                'duration_minutes' => 90,
                'types' => null,
                'schedules' => 'Scheduled annually',
                'requirements' => [
                    'Baptismal Certificate',
                    'Birth Certificate',
                    'Sponsor (must be confirmed Catholic)'
                ],
                'additional_notes' => 'Attend preparatory seminar before the sacrament.'
            ],
            [
                'name' => 'Sick Call',
                'description' => 'Request pastoral care and anointing for the sick and elderly.',
                'price' => 0.00,
                'icon' => 'fas fa-hospital-user',
                'duration_minutes' => null,
                'types' => null,
                'schedules' => 'On call',
                'requirements' => [
                    'Patient name',
                    'Address',
                    'Contact person'
                ],
                'additional_notes' => 'For emergencies, call the parish office directly.'
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
