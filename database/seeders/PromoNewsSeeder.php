<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promo;
use App\Models\News;

class PromoNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample promos
        Promo::create([
            'title' => 'Discount 30% - FOREveryday Treats',
            'description' => 'Nikmati diskon 30% untuk setiap pembelian kopi dan croissant setiap hari!',
            'image' => 'promo1.png',
            'active' => true,
        ]);

        Promo::create([
            'title' => 'Saturday Free Upsize',
            'description' => 'Setiap hari Sabtu, dapatkan free upsize untuk semua minuman favorit Anda!',
            'image' => 'promo2.png',
            'active' => true,
        ]);

        Promo::create([
            'title' => 'Bundle Deal 35% Off',
            'description' => 'Beli paket bundling kopi dan pastry dengan diskon hingga 35%!',
            'image' => 'promo3.png',
            'active' => true,
        ]);

        // Create sample news
        News::create([
            'title' => 'Coffee Express Strengthens Sustainability Initiatives',
            'content' => 'Solo, 12 November 2025 - PT Coffee Express Indonesia terus berkomitmen untuk meningkatkan inisiatif keberlanjutan dengan program penanaman pohon dan penggunaan kemasan ramah lingkungan.',
            'image' => 'news1.png',
            'author' => 'PT Coffee Express Indonesia',
            'published_at' => '2025-11-12',
        ]);

        News::create([
            'title' => '[Press Release] Coffee Express Hits 62% YoY Growth',
            'content' => 'Jakarta, October 23, 2025 - PT Coffee Express Indonesia mencatat pertumbuhan year-over-year sebesar 62% dengan ekspansi ke berbagai kota besar di Indonesia.',
            'image' => 'news2.png',
            'author' => 'PT Coffee Express Indonesia',
            'published_at' => '2025-10-23',
        ]);

        News::create([
            'title' => '[Press Release] Coffee Express Optimistically Rolls Into New Cities',
            'content' => 'Tangerang, September 9, 2025 - Coffee Express dengan optimis membuka cabang baru di berbagai kota untuk memberikan pengalaman kopi terbaik kepada lebih banyak pelanggan.',
            'image' => 'news3.png',
            'author' => 'Coffee Express',
            'published_at' => '2025-09-09',
        ]);

        News::create([
            'title' => '[Press Release] Coffee Express Expands Rapidly in Java',
            'content' => 'Jakarta, July 29, 2025 - Coffee Express menunjukkan ekspansi yang pesat di Pulau Jawa dengan membuka lebih dari 20 outlet baru dalam 6 bulan terakhir.',
            'image' => 'news4.png',
            'author' => 'Coffee Express',
            'published_at' => '2025-07-29',
        ]);
    }
}
