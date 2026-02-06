<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Categories
        $wellness = \App\Models\ServiceCategory::create([
            'name' => 'Wellness & Spa',
            'slug' => 'wellness-spa',
            'description' => 'Relaxing treatments for body and mind.',
            'is_active' => true,
        ]);

        $medical = \App\Models\ServiceCategory::create([
            'name' => 'Medical Consultation',
            'slug' => 'medical-consultation',
            'description' => 'Professional health advice and checkups.',
            'is_active' => true,
        ]);

        // 2. Create Services
        $services = [
            [
                'category_id' => $wellness->id,
                'name' => 'Swedish Massage',
                'slug' => 'swedish-massage',
                'short_description' => 'A classic relaxation massage using long strokes.',
                'description' => 'Swedish massage is the most common and best-known type of massage in the West. If it\'s your first time at the spa, Swedish massage is the perfect place to start.',
                'currency' => 'PHP',
                'base_price' => 800.00,
                'duration_minutes' => 60,
                'benefits' => ['Relaxes muscles', 'Improves circulation', 'Reduces stress'],
                'contraindications' => ['Recent surgery', 'High fever', 'Skin infections'],
                'sort_order' => 1,
            ],
            [
                'category_id' => $wellness->id,
                'name' => 'Deep Tissue Massage',
                'slug' => 'deep-tissue-massage',
                'short_description' => 'Targets deep layers of muscle and connective tissue.',
                'description' => 'Excellent for rehabilitating injuries and reducing chronic muscle pain throughout the body.',
                'currency' => 'PHP',
                'base_price' => 1200.00,
                'duration_minutes' => 90,
                'benefits' => ['Relieves chronic pain', 'Breaks down scar tissue', 'Improves range of motion'],
                'contraindications' => ['Blood clots', 'Osteoporosis', 'Pregnancy'],
                'sort_order' => 2,
            ],
            [
                'category_id' => $wellness->id,
                'name' => 'Organic Facial',
                'slug' => 'organic-facial',
                'short_description' => 'Rejuvenating skin treatment using 100% natural products.',
                'description' => 'Cleanses, exfoliates, and nourishes the skin, promoting a clear, well-hydrated complexion and can help your skin look younger.',
                'currency' => 'PHP',
                'base_price' => 1500.00,
                'duration_minutes' => 60,
                'benefits' => ['Closer pores', 'Glowing skin', 'Detoxifies skin'],
                'contraindications' => ['Cystic acne', 'Open wounds', 'Severe sunburn'],
                'sort_order' => 3,
            ],
            [
                'category_id' => $medical->id,
                'name' => 'General Consultation',
                'slug' => 'general-consultation',
                'short_description' => 'Standard health checkup with a general practitioner.',
                'description' => 'A comprehensive assessment of your overall health status, including history taking and physical examination.',
                'currency' => 'PHP',
                'base_price' => 500.00,
                'duration_minutes' => 30,
                'benefits' => ['Early detection of conditions', 'Personalized health advice', 'Prescription updates'],
                'contraindications' => ['None'],
                'sort_order' => 4,
            ],
            [
                'category_id' => $medical->id,
                'name' => 'Tele-Consultation',
                'slug' => 'tele-consultation',
                'short_description' => 'Checkup from the comfort of your home via video call.',
                'description' => 'No need to travel. Speak with our doctors online for non-emergency medical concerns and advice.',
                'currency' => 'PHP',
                'base_price' => 400.00,
                'duration_minutes' => 20,
                'benefits' => ['Convenient', 'No travel time', 'Safe during pandemics'],
                'contraindications' => ['Emergency cases requiring physical intervention'],
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::updateOrCreate(['slug' => $service['slug']], $service);
        }
    }
}
