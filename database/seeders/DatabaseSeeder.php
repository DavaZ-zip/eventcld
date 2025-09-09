<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Participant;
use App\Models\Sponsor;
use App\Models\EventSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@eventmanagement.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // Create Organizer User
        $organizer = User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@eventmanagement.com',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
            'email_verified_at' => now()
        ]);

        // Create regular user
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@eventmanagement.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now()
        ]);

        // Create Speakers
        $speakers = collect([
            [
                'name' => 'Dr. John Smith',
                'bio' => 'Expert in Software Engineering with 15+ years experience in building scalable applications.',
                'email' => 'john.smith@example.com',
                'phone' => '+628123456789',
                'expertise' => 'Software Engineering, AI, Machine Learning',
                'social_media' => ['linkedin' => 'johnsmith', 'twitter' => '@johnsmith']
            ],
            [
                'name' => 'Prof. Sarah Wilson',
                'bio' => 'Digital Marketing Strategist and Business Consultant helping companies grow their online presence.',
                'email' => 'sarah.wilson@example.com',
                'phone' => '+628987654321',
                'expertise' => 'Digital Marketing, Business Strategy',
                'social_media' => ['linkedin' => 'sarahwilson', 'instagram' => '@sarahwilson']
            ],
            [
                'name' => 'Michael Chen',
                'bio' => 'Startup Founder and Technology Evangelist passionate about innovation and entrepreneurship.',
                'email' => 'michael.chen@example.com',
                'phone' => '+628456789123',
                'expertise' => 'Entrepreneurship, Technology Innovation',
                'social_media' => ['linkedin' => 'michaelchen', 'twitter' => '@mikechen']
            ]
        ])->map(function ($speaker) {
            return Speaker::create($speaker);
        });

        // Create Participants
        $participants = collect([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'phone' => '+628111222333',
                'organization' => 'Tech Corp Indonesia',
                'position' => 'Senior Software Developer'
            ],
            [
                'name' => 'Bob Davis',
                'email' => 'bob.davis@example.com',
                'phone' => '+628222333444',
                'organization' => 'Startup Inc',
                'position' => 'Product Manager'
            ],
            [
                'name' => 'Carol Brown',
                'email' => 'carol.brown@example.com',
                'phone' => '+628333444555',
                'organization' => 'Digital Agency Pro',
                'position' => 'Marketing Director'
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+628444555666',
                'organization' => 'Universitas Surabaya',
                'position' => 'Mahasiswa S2 Informatika'
            ],
            [
                'name' => 'Eva Martinez',
                'email' => 'eva.martinez@example.com',
                'phone' => '+628555777888',
                'organization' => 'Creative Solutions',
                'position' => 'UI/UX Designer'
            ]
        ])->map(function ($participant) {
            return Participant::create($participant);
        });

        // Create Sponsors
        $sponsors = collect([
            [
                'name' => 'TechCorp Solutions',
                'description' => 'Leading technology solutions provider specializing in enterprise software and cloud infrastructure.',
                'website' => 'https://techcorp.id',
                'contact_person' => 'John Manager',
                'contact_email' => 'sponsor@techcorp.id',
                'contact_phone' => '+628555666777',
                'contribution_amount' => 75000000,
                'sponsorship_level' => 'platinum'
            ],
            [
                'name' => 'Digital Agency Pro',
                'description' => 'Full-service digital marketing agency helping businesses succeed online.',
                'website' => 'https://digitalagency.id',
                'contact_person' => 'Jane Director',
                'contact_email' => 'contact@digitalagency.id',
                'contact_phone' => '+628666777888',
                'contribution_amount' => 35000000,
                'sponsorship_level' => 'gold'
            ],
            [
                'name' => 'StartupHub Indonesia',
                'description' => 'Leading startup incubator and accelerator supporting Indonesian entrepreneurs.',
                'website' => 'https://startuphub.id',
                'contact_person' => 'Mike Founder',
                'contact_email' => 'hello@startuphub.id',
                'contact_phone' => '+628777888999',
                'contribution_amount' => 15000000,
                'sponsorship_level' => 'silver'
            ],
            [
                'name' => 'Innovation Labs',
                'description' => 'Research and development company focusing on emerging technologies.',
                'website' => 'https://innovationlabs.id',
                'contact_person' => 'Lisa Tech',
                'contact_email' => 'info@innovationlabs.id',
                'contact_phone' => '+628888999000',
                'contribution_amount' => 8000000,
                'sponsorship_level' => 'bronze'
            ]
        ])->map(function ($sponsor) {
            return Sponsor::create($sponsor);
        });

        // Create Events
        $events = collect([
            [
                'title' => 'Indonesia Tech Summit 2024',
                'description' => 'Konferensi teknologi terbesar di Indonesia featuring the latest trends in software development, AI, digital transformation, and startup ecosystem.',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(32),
                'location' => 'Jakarta Convention Center, Jakarta',
                'max_participants' => 500,
                'status' => 'published',
                'created_by' => $admin->id
            ],
            [
                'title' => 'Digital Marketing Masterclass',
                'description' => 'Workshop intensif tentang strategi digital marketing modern, social media marketing, dan growth hacking untuk bisnis.',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(15),
                'location' => 'Surabaya Business Center, Surabaya',
                'max_participants' => 100,
                'status' => 'published',
                'created_by' => $organizer->id
            ],
            [
                'title' => 'Startup Pitch Competition 2024',
                'description' => 'Kompetisi pitch untuk startup early-stage dengan total hadiah 1 miliar rupiah dari investor terkemuka.',
                'start_date' => now()->addDays(45),
                'end_date' => now()->addDays(46),
                'location' => 'Bandung Innovation Hub, Bandung',
                'max_participants' => 200,
                'status' => 'published',
                'created_by' => $admin->id
            ],
            [
                'title' => 'AI & Machine Learning Workshop',
                'description' => 'Workshop hands-on tentang implementasi AI dan Machine Learning untuk solving real-world problems.',
                'start_date' => now()->addDays(60),
                'end_date' => now()->addDays(61),
                'location' => 'Yogyakarta Tech Park, Yogyakarta',
                'max_participants' => 80,
                'status' => 'draft',
                'created_by' => $organizer->id
            ]
        ])->map(function ($event) {
            return Event::create($event);
        });

        // Create Sessions for Events
        foreach ($events as $index => $event) {
            $sessionsData = [
                // Tech Summit Sessions
                0 => [
                    [
                        'title' => 'The Future of Artificial Intelligence in Indonesia',
                        'description' => 'Exploring the latest developments in AI and machine learning with focus on Indonesian market opportunities.',
                        'start_time' => $event->start_date->copy()->addHours(9),
                        'end_time' => $event->start_date->copy()->addHours(10)->addMinutes(30),
                        'speaker_id' => $speakers->first()->id,
                        'location' => 'Main Hall A',
                        'max_participants' => 300
                    ],
                    [
                        'title' => 'Building Scalable Web Applications with Modern Tech Stack',
                        'description' => 'Best practices for developing high-performance web applications using React, Laravel, and cloud technologies.',
                        'start_time' => $event->start_date->copy()->addHours(11),
                        'end_time' => $event->start_date->copy()->addHours(12)->addMinutes(30),
                        'speaker_id' => $speakers->first()->id,
                        'location' => 'Technical Room A',
                        'max_participants' => 150
                    ],
                    [
                        'title' => 'Startup Ecosystem in Southeast Asia',
                        'description' => 'Panel discussion about the growing startup ecosystem and investment opportunities in the region.',
                        'start_time' => $event->start_date->copy()->addDays(1)->addHours(10),
                        'end_time' => $event->start_date->copy()->addDays(1)->addHours(11)->addMinutes(30),
                        'speaker_id' => $speakers->last()->id,
                        'location' => 'Panel Room B',
                        'max_participants' => 200
                    ]
                ],
                // Digital Marketing Workshop Sessions
                1 => [
                    [
                        'title' => 'Social Media Marketing Strategy 2024',
                        'description' => 'Effective strategies for building brand presence and engagement across major social media platforms.',
                        'start_time' => $event->start_date->copy()->addHours(9),
                        'end_time' => $event->start_date->copy()->addHours(12),
                        'speaker_id' => $speakers->get(1)->id,
                        'location' => 'Workshop Room 1',
                        'max_participants' => 50
                    ],
                    [
                        'title' => 'Growth Hacking for Indonesian Startups',
                        'description' => 'Learn proven growth hacking techniques specifically tailored for the Indonesian market.',
                        'start_time' => $event->start_date->copy()->addHours(13),
                        'end_time' => $event->start_date->copy()->addHours(16),
                        'speaker_id' => $speakers->get(1)->id,
                        'location' => 'Workshop Room 1',
                        'max_participants' => 50
                    ]
                ],
                // Startup Pitch Competition Sessions
                2 => [
                    [
                        'title' => 'Startup Pitching Bootcamp',
                        'description' => 'Intensive training session for selected startups to perfect their pitch presentations.',
                        'start_time' => $event->start_date->copy()->addHours(9),
                        'end_time' => $event->start_date->copy()->addHours(12),
                        'speaker_id' => $speakers->last()->id,
                        'location' => 'Training Room',
                        'max_participants' => 50
                    ],
                    [
                        'title' => 'Final Pitch Competition',
                        'description' => 'Final pitching session where startups present to panel of investors and judges.',
                        'start_time' => $event->start_date->copy()->addDays(1)->addHours(10),
                        'end_time' => $event->start_date->copy()->addDays(1)->addHours(16),
                        'speaker_id' => $speakers->last()->id,
                        'location' => 'Main Auditorium',
                        'max_participants' => 200
                    ]
                ],
                // AI Workshop Sessions
                3 => [
                    [
                        'title' => 'Introduction to Machine Learning',
                        'description' => 'Hands-on workshop covering fundamentals of machine learning with Python and scikit-learn.',
                        'start_time' => $event->start_date->copy()->addHours(9),
                        'end_time' => $event->start_date->copy()->addHours(12),
                        'speaker_id' => $speakers->first()->id,
                        'location' => 'Lab Room 1',
                        'max_participants' => 40
                    ],
                    [
                        'title' => 'Deep Learning Applications',
                        'description' => 'Advanced session on implementing deep learning solutions for real-world problems.',
                        'start_time' => $event->start_date->copy()->addHours(13),
                        'end_time' => $event->start_date->copy()->addHours(16),
                        'speaker_id' => $speakers->first()->id,
                        'location' => 'Lab Room 1',
                        'max_participants' => 40
                    ]
                ]
            ];

            if (isset($sessionsData[$index])) {
                foreach ($sessionsData[$index] as $sessionData) {
                    $sessionData['event_id'] = $event->id;
                    EventSession::create($sessionData);
                }
            }
        }

        // Register participants to events
        $events->each(function ($event) use ($participants) {
            $randomParticipants = $participants->random(rand(2, 4));
            foreach ($randomParticipants as $participant) {
                $event->participants()->attach($participant->id, [
                    'registration_date' => now()->subDays(rand(1, 10)),
                    'status' => collect(['registered', 'confirmed', 'attended'])->random()
                ]);
            }
        });

        // Attach sponsors to events
        $events->each(function ($event) use ($sponsors) {
            $randomSponsors = $sponsors->random(rand(1, 3));
            foreach ($randomSponsors as $sponsor) {
                $event->sponsors()->attach($sponsor->id, [
                    'sponsorship_level' => $sponsor->sponsorship_level,
                    'contribution_amount' => $sponsor->contribution_amount
                ]);
            }
        });

        $this->command->info('Database seeded successfully!');
        $this->command->line('');
        $this->command->line('Login credentials:');
        $this->command->line('Admin: admin@eventmanagement.com / password123');
        $this->command->line('Organizer: organizer@eventmanagement.com / password123');
        $this->command->line('User: user@eventmanagement.com / password123');
    }
}