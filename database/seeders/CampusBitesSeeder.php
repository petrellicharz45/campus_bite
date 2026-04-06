<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampusBitesSeeder extends Seeder
{
    public function run(): void
    {
        $seedPassword = app()->environment(['local', 'testing'])
            ? 'password'
            : Str::random(32);

        CompanySetting::query()->updateOrCreate(
            ['id' => 1],
            CompanySetting::defaults()
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@campusbites.test'],
            [
                'name' => 'Campus Bites Admin',
                'phone' => '+233 20 555 0101',
                'location' => 'Main Campus Office',
                'role' => 'admin',
                'password' => $seedPassword,
                'email_verified_at' => now(),
            ]
        );

        $student = User::query()->updateOrCreate(
            ['email' => 'student@campusbites.test'],
            [
                'name' => 'Ama Student',
                'phone' => '+233 24 555 0102',
                'location' => 'Volta Hall, Block B',
                'role' => 'client',
                'password' => $seedPassword,
                'email_verified_at' => now(),
            ]
        );

        $categories = collect([
            [
                'name' => 'Breakfast Boosters',
                'slug' => 'breakfast-boosters',
                'description' => 'Fast campus breakfasts before the first lecture starts.',
                'accent_color' => '#d97706',
                'icon' => 'sunrise',
            ],
            [
                'name' => 'Lecture Lunches',
                'slug' => 'lecture-lunches',
                'description' => 'Balanced bowls and filling plates for busy afternoons.',
                'accent_color' => '#ea580c',
                'icon' => 'bowl',
            ],
            [
                'name' => 'Snack Lab',
                'slug' => 'snack-lab',
                'description' => 'Affordable bites for study sessions and late breaks.',
                'accent_color' => '#65a30d',
                'icon' => 'cookie',
            ],
            [
                'name' => 'Sip Station',
                'slug' => 'sip-station',
                'description' => 'Coffee, smoothies, and cool drinks between classes.',
                'accent_color' => '#0f766e',
                'icon' => 'cup',
            ],
        ])->mapWithKeys(function (array $category): array {
            $model = Category::query()->updateOrCreate(['slug' => $category['slug']], $category);

            return [$category['slug'] => $model];
        });

        $products = [
            [
                'category' => 'breakfast-boosters',
                'name' => 'Sunrise Waakye Box',
                'slug' => 'sunrise-waakye-box',
                'short_description' => 'Rice, beans, boiled egg, spaghetti, and pepper sauce.',
                'description' => 'A classic campus breakfast packed for easy pickup before morning lectures.',
                'image_url' => 'https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=900&q=80',
                'price' => 18.50,
                'prep_time' => 12,
                'calories' => 540,
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category' => 'breakfast-boosters',
                'name' => 'Pancake Pocket Combo',
                'slug' => 'pancake-pocket-combo',
                'short_description' => 'Mini pancakes, banana slices, and iced coffee.',
                'description' => 'A lighter breakfast combo for students who want a sweet start and fast service.',
                'image_url' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=900&q=80',
                'price' => 16.00,
                'prep_time' => 10,
                'calories' => 460,
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category' => 'lecture-lunches',
                'name' => 'Campus Jollof Tray',
                'slug' => 'campus-jollof-tray',
                'short_description' => 'Smoky jollof, grilled chicken, and coleslaw.',
                'description' => 'A signature lunch tray with bold flavour and portion sizing that fits student budgets.',
                'image_url' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=900&q=80',
                'price' => 24.00,
                'prep_time' => 18,
                'calories' => 720,
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category' => 'lecture-lunches',
                'name' => 'Peppered Rice Bowl',
                'slug' => 'peppered-rice-bowl',
                'short_description' => 'Steamed rice, stir-fried veggies, and peppered turkey.',
                'description' => 'A filling rice bowl for midday orders with quick prep and a clean finish.',
                'image_url' => 'https://images.unsplash.com/photo-1543332164-6e82f355badc?auto=format&fit=crop&w=900&q=80',
                'price' => 22.50,
                'prep_time' => 15,
                'calories' => 640,
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category' => 'snack-lab',
                'name' => 'Study Break Shawarma',
                'slug' => 'study-break-shawarma',
                'short_description' => 'Soft wrap with chicken strips, cabbage, and creamy sauce.',
                'description' => 'A high-demand snack that works for solo bites or bundled combo orders.',
                'image_url' => 'https://images.unsplash.com/photo-1529006557810-274b9b2fc783?auto=format&fit=crop&w=900&q=80',
                'price' => 14.00,
                'prep_time' => 9,
                'calories' => 410,
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category' => 'snack-lab',
                'name' => 'Crunchy Plantain Pack',
                'slug' => 'crunchy-plantain-pack',
                'short_description' => 'Sweet plantain chips with spicy groundnut dip.',
                'description' => 'A shareable low-cost snack that fits the after-class rush.',
                'image_url' => 'https://images.unsplash.com/photo-1467453678174-768ec283a940?auto=format&fit=crop&w=900&q=80',
                'price' => 8.50,
                'prep_time' => 5,
                'calories' => 280,
                'is_featured' => false,
                'is_available' => true,
            ],
            [
                'category' => 'sip-station',
                'name' => 'Library Latte',
                'slug' => 'library-latte',
                'short_description' => 'Campus-roasted coffee with milk foam and caramel drizzle.',
                'description' => 'A warm drink designed for revision nights and early assignments.',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80',
                'price' => 11.00,
                'prep_time' => 6,
                'calories' => 210,
                'is_featured' => true,
                'is_available' => true,
            ],
            [
                'category' => 'sip-station',
                'name' => 'Freshers Mango Smoothie',
                'slug' => 'freshers-mango-smoothie',
                'short_description' => 'Mango, yogurt, honey, and chilled ice blend.',
                'description' => 'A bright, refreshing smoothie that adds colour and variety to the menu.',
                'image_url' => 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?auto=format&fit=crop&w=900&q=80',
                'price' => 13.50,
                'prep_time' => 7,
                'calories' => 260,
                'is_featured' => false,
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'name' => $product['name'],
                    'short_description' => $product['short_description'],
                    'description' => $product['description'],
                    'image_url' => $product['image_url'],
                    'price' => $product['price'],
                    'prep_time' => $product['prep_time'],
                    'calories' => $product['calories'],
                    'is_featured' => $product['is_featured'],
                    'is_available' => $product['is_available'],
                ]
            );
        }

        $jollof = Product::query()->where('slug', 'campus-jollof-tray')->firstOrFail();
        $latte = Product::query()->where('slug', 'library-latte')->firstOrFail();

        $order = Order::query()->updateOrCreate(
            ['order_number' => 'CB-DEMO-1001'],
            [
                'user_id' => $student->id,
                'status' => 'preparing',
                'fulfillment_type' => 'delivery',
                'payment_method' => 'flutterwave',
                'payment_status' => 'paid',
                'payment_reference' => 'CB-DEMO-1001',
                'payment_provider_reference' => 'FLW-DEMO-1001',
                'payment_channel' => 'card',
                'phone' => $student->phone,
                'location' => $student->location,
                'notes' => 'Leave at hall porter desk.',
                'subtotal' => 35.00,
                'delivery_fee' => 2.50,
                'total' => 37.50,
                'placed_at' => now()->subHours(2),
                'paid_at' => now()->subHours(2),
                'payment_meta' => ['demo' => true, 'source' => 'flutterwave'],
            ]
        );

        $order->items()->delete();
        $order->items()->createMany([
            [
                'product_id' => $jollof->id,
                'product_name' => $jollof->name,
                'unit_price' => $jollof->price,
                'quantity' => 1,
                'line_total' => $jollof->price,
            ],
            [
                'product_id' => $latte->id,
                'product_name' => $latte->name,
                'unit_price' => $latte->price,
                'quantity' => 1,
                'line_total' => $latte->price,
            ],
        ]);
        $order->paymentActivities()->delete();
        $order->paymentActivities()->createMany([
            [
                'source' => 'checkout',
                'type' => 'payment_initialized',
                'status' => 'pending',
                'message' => 'Order created and prepared for Flutterwave checkout.',
                'payload' => ['demo' => true],
                'happened_at' => now()->subHours(2)->subMinutes(5),
            ],
            [
                'source' => 'flutterwave-callback',
                'type' => 'payment_confirmed',
                'status' => 'paid',
                'message' => 'Flutterwave payment verified successfully through the callback flow.',
                'payload' => ['demo' => true, 'payment_type' => 'card'],
                'happened_at' => now()->subHours(2),
            ],
        ]);

        $secondOrder = Order::query()->updateOrCreate(
            ['order_number' => 'CB-DEMO-1002'],
            [
                'user_id' => $student->id,
                'status' => 'ready',
                'fulfillment_type' => 'pickup',
                'payment_method' => 'cash-on-delivery',
                'payment_status' => 'cash-on-delivery',
                'payment_reference' => 'CB-DEMO-1002',
                'payment_provider_reference' => null,
                'payment_channel' => 'cash',
                'phone' => $student->phone,
                'location' => 'Main Campus Canteen',
                'notes' => 'Collect after the 2pm lecture.',
                'subtotal' => 18.50,
                'delivery_fee' => 0,
                'total' => 18.50,
                'placed_at' => now()->subDay(),
                'paid_at' => null,
                'payment_meta' => ['demo' => true, 'source' => 'cash-on-delivery'],
            ]
        );

        $waakye = Product::query()->where('slug', 'sunrise-waakye-box')->firstOrFail();
        $secondOrder->items()->delete();
        $secondOrder->items()->create([
            'product_id' => $waakye->id,
            'product_name' => $waakye->name,
            'unit_price' => $waakye->price,
            'quantity' => 1,
            'line_total' => $waakye->price,
        ]);
        $secondOrder->paymentActivities()->delete();
        $secondOrder->paymentActivities()->create([
            'source' => 'checkout',
            'type' => 'cash_on_delivery_selected',
            'status' => 'cash-on-delivery',
            'message' => 'Customer selected Cash on Delivery for this order.',
            'payload' => ['demo' => true],
            'happened_at' => now()->subDay(),
        ]);

        User::query()->whereKey($admin->id)->update(['email_verified_at' => now()]);
    }
}
