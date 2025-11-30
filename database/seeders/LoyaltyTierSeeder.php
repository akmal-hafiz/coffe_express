<?php

namespace Database\Seeders;

use App\Models\LoyaltyTier;
use Illuminate\Database\Seeder;

class LoyaltyTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'bronze',
                'min_points' => 0,
                'points_multiplier' => 100,
                'discount_percentage' => 0,
                'benefits' => json_encode([
                    'Earn 1 point per Rp1,000 spent',
                    'Birthday bonus points',
                    'Access to member-only promotions',
                ]),
                'badge_color' => '#CD7F32',
                'badge_icon' => 'award',
                'sort_order' => 1,
            ],
            [
                'name' => 'silver',
                'min_points' => 500,
                'points_multiplier' => 125,
                'discount_percentage' => 5,
                'benefits' => json_encode([
                    'Earn 1.25x points on all orders',
                    '5% discount on all orders',
                    'Early access to new menu items',
                    'Priority customer support',
                ]),
                'badge_color' => '#C0C0C0',
                'badge_icon' => 'award',
                'sort_order' => 2,
            ],
            [
                'name' => 'gold',
                'min_points' => 1500,
                'points_multiplier' => 150,
                'discount_percentage' => 10,
                'benefits' => json_encode([
                    'Earn 1.5x points on all orders',
                    '10% discount on all orders',
                    'Free delivery on orders over Rp50,000',
                    'Exclusive gold member rewards',
                    'Priority customer support',
                ]),
                'badge_color' => '#FFD700',
                'badge_icon' => 'star',
                'sort_order' => 3,
            ],
            [
                'name' => 'platinum',
                'min_points' => 5000,
                'points_multiplier' => 200,
                'discount_percentage' => 15,
                'benefits' => json_encode([
                    'Earn 2x points on all orders',
                    '15% discount on all orders',
                    'Free delivery on all orders',
                    'Exclusive platinum member rewards',
                    'VIP customer support',
                    'Early access to limited edition items',
                    'Complimentary birthday drink',
                ]),
                'badge_color' => '#E5E4E2',
                'badge_icon' => 'crown',
                'sort_order' => 4,
            ],
        ];

        foreach ($tiers as $tier) {
            LoyaltyTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }

        $this->command->info('Loyalty tiers seeded successfully!');
    }
}
