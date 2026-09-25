<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Announcement;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Markets
        $centralMarket = Market::create([
            'market_name' => 'Central Downtown Farmers Market',
            'address' => 'Market Square, Main Downtown Boulevard',
            'city' => 'Metropolis',
            'operating_days' => 'Saturday, Sunday',
            'timings' => '07:30 AM - 02:00 PM',
            'latitude' => 24.8607,
            'longitude' => 67.0011,
            'map_provider' => 'OpenStreetMap',
            'description' => 'The largest and oldest farmers market in the city, featuring over 40 local producers, fresh organic fruits, vegetables, and artisan breads.',
        ]);

        $cliftonMarket = Market::create([
            'market_name' => 'Clifton Seaside Farmers Market',
            'address' => 'Pier 4, Seaside Promenade, Clifton Beach Road',
            'city' => 'Metropolis',
            'operating_days' => 'Sunday',
            'timings' => '08:00 AM - 01:00 PM',
            'latitude' => 24.8138,
            'longitude' => 67.0305,
            'map_provider' => 'OpenStreetMap',
            'description' => 'Enjoy refreshing coastal breezes while browsing farm-fresh produce, coastal honey, dairy eggs, and handmade jams.',
        ]);

        $gulshanMarket = Market::create([
            'market_name' => 'Gulshan Green Community Market',
            'address' => 'Block 6 Community Ground, University Road',
            'city' => 'Metropolis',
            'operating_days' => 'Wednesday, Saturday',
            'timings' => '08:00 AM - 12:30 PM',
            'latitude' => 24.9200,
            'longitude' => 67.0900,
            'map_provider' => 'OpenStreetMap',
            'description' => 'A lively neighborhood market connecting suburban families directly with sustainable regional growers.',
        ]);

        $northHillsMarket = Market::create([
            'market_name' => 'North Hills Village Organic Market',
            'address' => 'Hillview Park Plaza, North Valley Avenue',
            'city' => 'Metropolis',
            'operating_days' => 'Tuesday, Friday',
            'timings' => '07:00 AM - 11:30 AM',
            'latitude' => 24.9500,
            'longitude' => 67.0400,
            'map_provider' => 'OpenStreetMap',
            'description' => 'Certified organic produce, hydroponic herbs, microgreens, and artisanal breakfast pastries.',
        ]);

        // 2. Categories
        $catVeg = Category::create([
            'name' => 'Fresh Vegetables',
            'slug' => 'vegetables',
            'icon' => 'bi-flower2',
            'description' => 'Crisp, organically cultivated and pesticide-free garden vegetables harvested within 24 hours.',
        ]);

        $catFruit = Category::create([
            'name' => 'Orchard Fruits',
            'slug' => 'fruits',
            'icon' => 'bi-apple',
            'description' => 'Naturally sweet, tree-ripened seasonal fruits harvested at peak freshness.',
        ]);

        $catDairy = Category::create([
            'name' => 'Dairy & Eggs',
            'slug' => 'dairy-eggs',
            'icon' => 'bi-egg-fried',
            'description' => 'Free-range pasture-raised eggs, artisan goat milk cheese, and cultured farm butter.',
        ]);

        $catBakery = Category::create([
            'name' => 'Artisan Bakery',
            'slug' => 'bakery',
            'icon' => 'bi-cup-hot',
            'description' => 'Wood-fired sourdough breads, rustic grain boules, pies, and wholesome morning rolls.',
        ]);

        $catHoney = Category::create([
            'name' => 'Honey & Preserves',
            'slug' => 'honey-preserves',
            'icon' => 'bi-droplet-half',
            'description' => 'Unpasteurized wildflower honey, small-batch fruit jams, and pickled country delicacies.',
        ]);

        $catHerbs = Category::create([
            'name' => 'Herbs & Microgreens',
            'slug' => 'herbs-microgreens',
            'icon' => 'bi-tree',
            'description' => 'Freshly cut culinary herbs, aromatic rosemary, vibrant mint, and nutrient-dense microgreens.',
        ]);

        // 3. Admin Account
        $admin = User::create([
            'name' => 'Platform Administrator',
            'username' => 'admin',
            'email' => 'admin@marketlink.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+1-555-010-9999',
            'address' => 'MarketLink HQ, Innovation Tower, Suite 400',
        ]);

        // 4. Farmers Accounts
        $farmer1 = User::create([
            'name' => 'John Miller',
            'contact_person' => 'John Miller (Owner)',
            'username' => 'greenvalley',
            'email' => 'farmer@marketlink.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'status' => 'active',
            'stall_name' => 'Green Valley Organic Produce',
            'market_id' => $centralMarket->id,
            'operating_days' => 'Saturday, Sunday',
            'pickup_windows' => '08:00 AM - 01:00 PM',
            'cutoff_hours' => 12,
            'phone' => '+1-555-014-2233',
            'address' => 'Stall #14, North Pavilion, Central Downtown Farmers Market',
            'latitude' => 24.8609,
            'longitude' => 67.0015,
            'bio' => 'Family-owned certified organic farm cultivating heirloom tomatoes, greens, and crisp seasonal vegetables for 3 generations.',
        ]);

        $farmer2 = User::create([
            'name' => 'Sarah Jenkins',
            'contact_person' => 'Sarah Jenkins',
            'username' => 'sunrisegrove',
            'email' => 'sunrisegrove@marketlink.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'status' => 'active',
            'stall_name' => 'Sunrise Grove Orchards',
            'market_id' => $cliftonMarket->id,
            'operating_days' => 'Sunday',
            'pickup_windows' => '08:30 AM - 12:30 PM',
            'cutoff_hours' => 18,
            'phone' => '+1-555-019-8877',
            'address' => 'Stall #3, Seaside Boardwalk, Clifton Seaside Market',
            'latitude' => 24.8140,
            'longitude' => 67.0308,
            'bio' => 'Growing sweet honeycrisp apples, golden peaches, and sun-drenched berries picked fresh Saturday afternoon for Sunday morning market.',
        ]);

        $farmer3 = User::create([
            'name' => 'Robert Davis',
            'contact_person' => 'Robert Davis',
            'username' => 'meadowbrook',
            'email' => 'meadowbrook@marketlink.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'status' => 'active',
            'stall_name' => 'Meadow Brook Dairy & Honey',
            'market_id' => $gulshanMarket->id,
            'operating_days' => 'Wednesday, Saturday',
            'pickup_windows' => '08:00 AM - 12:00 PM',
            'cutoff_hours' => 24,
            'phone' => '+1-555-017-3344',
            'address' => 'Stall #8, East Canopy, Gulshan Green Community Market',
            'latitude' => 24.9205,
            'longitude' => 67.0905,
            'bio' => 'Pasture-fed dairy cows and hives nestled in wildflower meadows. Known for unpasteurized raw honey and rich artisanal cheese.',
        ]);

        // Pending approval farmer for testing Admin approval feature
        $farmerPending = User::create([
            'name' => 'Clara Woods',
            'contact_person' => 'Clara Woods',
            'username' => 'heritagebakes',
            'email' => 'pending@marketlink.com',
            'password' => Hash::make('farmer123'),
            'role' => 'farmer',
            'status' => 'pending_approval',
            'stall_name' => 'Heritage Bakes & Sourdough',
            'market_id' => $northHillsMarket->id,
            'operating_days' => 'Tuesday, Friday',
            'pickup_windows' => '07:30 AM - 11:00 AM',
            'cutoff_hours' => 12,
            'phone' => '+1-555-018-5522',
            'address' => 'Stall #5, North Hills Village Market',
            'latitude' => 24.9505,
            'longitude' => 67.0408,
            'bio' => 'Natural stoneground wheat flours, 72-hour wild yeast fermentation, sourdough loaves, croissants, and morning pastries.',
        ]);

        // 5. Customer Accounts
        $customer1 = User::create([
            'name' => 'Alice Walker',
            'username' => 'alice_w',
            'email' => 'customer@marketlink.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '+1-555-011-4455',
            'address' => 'Apartment 7B, Greenview Heights, Central District',
            'preferred_market_id' => $centralMarket->id,
        ]);

        $customer2 = User::create([
            'name' => 'David Smith',
            'username' => 'davids',
            'email' => 'david@marketlink.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '+1-555-012-7788',
            'address' => '12 Marina Vista Road, Clifton Area',
            'preferred_market_id' => $cliftonMarket->id,
        ]);

        $customer3 = User::create([
            'name' => 'Emily Clark',
            'username' => 'emilyc',
            'email' => 'emily@marketlink.com',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
            'status' => 'active',
            'phone' => '+1-555-013-9900',
            'address' => '84 University Boulevard, Block 4',
            'preferred_market_id' => $gulshanMarket->id,
        ]);

        // 6. Products
        // Farmer 1 (Green Valley - Vegetables & Greens)
        $p1 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $catVeg->id,
            'category' => 'Fresh Vegetables',
            'name' => 'Organic Heirloom Tomatoes',
            'description' => 'Vine-ripened multi-color heirloom tomatoes bursting with natural sweet acidity. Perfect for caprese salads and pasta.',
            'price' => 4.50,
            'unit' => 'kg',
            'stock_quantity' => 45,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p2 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $catVeg->id,
            'category' => 'Fresh Vegetables',
            'name' => 'Crisp Baby Spinach & Arugula',
            'description' => 'Tender, triple-washed organic greens packed with vitamins. Harvested fresh dawn of market day.',
            'price' => 3.25,
            'unit' => 'bunch',
            'stock_quantity' => 30,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p3 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $catVeg->id,
            'category' => 'Fresh Vegetables',
            'name' => 'Sweet Rainbow Bell Peppers',
            'description' => 'Crunchy red, yellow, and orange bell peppers grown pesticide-free in open sunlight.',
            'price' => 3.80,
            'unit' => 'kg',
            'stock_quantity' => 25,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p4 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $catHerbs->id,
            'category' => 'Herbs & Microgreens',
            'name' => 'Aromatic Sweet Genovese Basil',
            'description' => 'Fragrant fresh cut Italian basil bunch, ideal for homemade pesto and tomato pairings.',
            'price' => 2.00,
            'unit' => 'bunch',
            'stock_quantity' => 40,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p5 = Product::create([
            'farmer_id' => $farmer1->id,
            'category_id' => $catVeg->id,
            'category' => 'Fresh Vegetables',
            'name' => 'Sweet Bi-Color Butter Sweetcorn',
            'description' => 'Plump, juicy ears of sweet corn freshly picked from the stalk. Delicious grilled or boiled.',
            'price' => 1.50,
            'unit' => 'piece',
            'stock_quantity' => 60,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        // Farmer 2 (Sunrise Grove - Fruits & Berries)
        $p6 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $catFruit->id,
            'category' => 'Orchard Fruits',
            'name' => 'Crisp Honeycrisp Orchard Apples',
            'description' => 'Extra crisp, sweet-tart apples freshly hand-picked from mountain hillside orchards.',
            'price' => 5.20,
            'unit' => 'kg',
            'stock_quantity' => 50,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p7 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $catFruit->id,
            'category' => 'Orchard Fruits',
            'name' => 'Sweet Farm Strawberries (Box)',
            'description' => 'Deep red, highly fragrant strawberries harvested at the sweetest peak of perfection.',
            'price' => 4.00,
            'unit' => 'box',
            'stock_quantity' => 35,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p8 = Product::create([
            'farmer_id' => $farmer2->id,
            'category_id' => $catFruit->id,
            'category' => 'Orchard Fruits',
            'name' => 'Juicy Golden Sun Peaches',
            'description' => 'Velvety skin, yellow sweet flesh, bursting with tree-ripened orchard juice.',
            'price' => 6.00,
            'unit' => 'kg',
            'stock_quantity' => 20,
            'is_available' => true,
            'is_weekly_template' => false,
        ]);

        // Farmer 3 (Meadow Brook - Dairy & Honey)
        $p9 = Product::create([
            'farmer_id' => $farmer3->id,
            'category_id' => $catDairy->id,
            'category' => 'Dairy & Eggs',
            'name' => 'Pasture-Raised Brown Farm Eggs',
            'description' => 'Dozen large eggs from pasture-roaming hens with deep golden yolks and superior flavor.',
            'price' => 5.50,
            'unit' => 'dozen',
            'stock_quantity' => 40,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p10 = Product::create([
            'farmer_id' => $farmer3->id,
            'category_id' => $catHoney->id,
            'category' => 'Honey & Preserves',
            'name' => 'Raw Unfiltered Wildflower Honey (500g)',
            'description' => 'Pure unpasteurized honey gathered by bees from clover, lavender, and summer wildflowers.',
            'price' => 9.50,
            'unit' => 'jar',
            'stock_quantity' => 25,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        $p11 = Product::create([
            'farmer_id' => $farmer3->id,
            'category_id' => $catDairy->id,
            'category' => 'Dairy & Eggs',
            'name' => 'Artisan Handcrafted Goat Cheese',
            'description' => 'Creamy, tangy soft chèvre goat cheese lightly rolled in crushed wild herbs.',
            'price' => 7.00,
            'unit' => 'piece',
            'stock_quantity' => 18,
            'is_available' => true,
            'is_weekly_template' => true,
        ]);

        // 7. Sample Orders (Demonstrating different statuses and pickup slots)
        // Order 1: Placed (Can be modified or cancelled by customer!)
        $order1 = Order::create([
            'order_number' => 'ML-2026-1001',
            'customer_id' => $customer1->id,
            'farmer_id' => $farmer1->id,
            'market_id' => $centralMarket->id,
            'total_amount' => 15.50,
            'order_status' => 'placed',
            'pickup_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'pickup_time_slot' => '09:00 AM - 10:00 AM',
            'cutoff_time' => Carbon::now()->addDays(2)->subHours(12),
            'customer_notes' => 'Please pick firm ripe tomatoes suitable for slicing.',
            'farmer_notes' => null,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'unit' => $p1->unit,
            'quantity' => 2,
            'price' => 4.50,
            'subtotal' => 9.00,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p2->id,
            'product_name' => $p2->name,
            'unit' => $p2->unit,
            'quantity' => 2,
            'price' => 3.25,
            'subtotal' => 6.50,
        ]);

        // Order 2: Accepted by Farmer
        $order2 = Order::create([
            'order_number' => 'ML-2026-1002',
            'customer_id' => $customer2->id,
            'farmer_id' => $farmer2->id,
            'market_id' => $cliftonMarket->id,
            'total_amount' => 14.40,
            'order_status' => 'accepted',
            'pickup_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'pickup_time_slot' => '10:00 AM - 11:00 AM',
            'cutoff_time' => Carbon::now()->addDays(3)->subHours(18),
            'customer_notes' => 'Looking forward to the Honeycrisp apples!',
            'farmer_notes' => 'Reserved our finest crate for you!',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p6->id,
            'product_name' => $p6->name,
            'unit' => $p6->unit,
            'quantity' => 2,
            'price' => 5.20,
            'subtotal' => 10.40,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p7->id,
            'product_name' => $p7->name,
            'unit' => $p7->unit,
            'quantity' => 1,
            'price' => 4.00,
            'subtotal' => 4.00,
        ]);

        // Order 3: Ready for Pickup
        $order3 = Order::create([
            'order_number' => 'ML-2026-1003',
            'customer_id' => $customer1->id,
            'farmer_id' => $farmer3->id,
            'market_id' => $gulshanMarket->id,
            'total_amount' => 15.00,
            'order_status' => 'ready_for_pickup',
            'pickup_date' => Carbon::now()->format('Y-m-d'),
            'pickup_time_slot' => '08:30 AM - 09:30 AM',
            'cutoff_time' => Carbon::now()->subHours(5),
            'customer_notes' => 'Pre-order for morning market run.',
            'farmer_notes' => 'Packed in cooler box with your name on it!',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $p9->id,
            'product_name' => $p9->name,
            'unit' => $p9->unit,
            'quantity' => 1,
            'price' => 5.50,
            'subtotal' => 5.50,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $p10->id,
            'product_name' => $p10->name,
            'unit' => $p10->unit,
            'quantity' => 1,
            'price' => 9.50,
            'subtotal' => 9.50,
        ]);

        // Order 4: Completed (allows customer reviews!)
        $order4 = Order::create([
            'order_number' => 'ML-2026-0995',
            'customer_id' => $customer3->id,
            'farmer_id' => $farmer1->id,
            'market_id' => $centralMarket->id,
            'total_amount' => 9.00,
            'order_status' => 'completed',
            'pickup_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
            'pickup_time_slot' => '09:00 AM - 10:00 AM',
            'cutoff_time' => Carbon::now()->subDays(5),
            'customer_notes' => 'Cash ready at pickup.',
            'farmer_notes' => 'Collected in person. Thank you!',
        ]);

        OrderItem::create([
            'order_id' => $order4->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'unit' => $p1->unit,
            'quantity' => 2,
            'price' => 4.50,
            'subtotal' => 9.00,
        ]);

        // 8. Reviews and Ratings
        $rev1 = Review::create([
            'order_id' => $order4->id,
            'product_id' => $p1->id,
            'farmer_id' => $farmer1->id,
            'customer_id' => $customer3->id,
            'rating' => 5,
            'comment' => 'The heirloom tomatoes were the sweetest and juiciest I have tasted this year! Perfect condition, no bruising.',
            'farmer_reply' => 'Thank you Emily! We pick them just hours before the market starts. See you next weekend!',
            'farmer_replied_at' => Carbon::now()->subDays(3),
            'is_flagged' => false,
        ]);

        $rev2 = Review::create([
            'order_id' => null,
            'product_id' => $p9->id,
            'farmer_id' => $farmer3->id,
            'customer_id' => $customer1->id,
            'rating' => 5,
            'comment' => 'Deep orange yolks and very fresh. You can really tell the difference with pasture-raised hens.',
            'farmer_reply' => 'Delighted you enjoyed them, Alice! Our hens graze freely on natural pasture clover every day.',
            'farmer_replied_at' => Carbon::now()->subDays(2),
            'is_flagged' => false,
        ]);

        $rev3 = Review::create([
            'order_id' => null,
            'product_id' => $p6->id,
            'farmer_id' => $farmer2->id,
            'customer_id' => $customer2->id,
            'rating' => 5,
            'comment' => 'Fantastic crisp apples! Made wonderful homemade apple cider and snacks for the kids.',
            'farmer_reply' => null,
            'is_flagged' => false,
        ]);

        // 9. Favorites
        Favorite::create([
            'customer_id' => $customer1->id,
            'favoritable_type' => 'Farmer',
            'favoritable_id' => $farmer1->id,
        ]);

        Favorite::create([
            'customer_id' => $customer1->id,
            'favoritable_type' => 'Product',
            'favoritable_id' => $p1->id,
        ]);

        Favorite::create([
            'customer_id' => $customer2->id,
            'favoritable_type' => 'Farmer',
            'favoritable_id' => $farmer2->id,
        ]);

        Favorite::create([
            'customer_id' => $customer2->id,
            'favoritable_type' => 'Product',
            'favoritable_id' => $p6->id,
        ]);

        // 10. Announcements & Notifications
        Announcement::create([
            'user_id' => null, // Platform-wide
            'title' => 'Welcome to MarketLink Platform!',
            'message' => 'Connect directly with local farmers, pre-order fresh produce for market pickup, and support sustainable local agriculture.',
            'type' => 'announcement',
            'link' => '/markets',
        ]);

        Announcement::create([
            'user_id' => null, // Platform-wide
            'title' => 'Weekend Farmers Markets Now Open',
            'message' => 'Central Downtown and Clifton Seaside markets are open this Saturday and Sunday with over 40 fresh stalls.',
            'type' => 'announcement',
            'link' => '/markets',
        ]);

        Announcement::create([
            'user_id' => $customer1->id,
            'title' => 'Order #ML-2026-1003 is Ready for Pickup!',
            'message' => 'Your order with Meadow Brook Dairy & Honey is packed and waiting at Stall #8 in Gulshan Green Market.',
            'type' => 'order_status',
            'link' => '/orders/3',
        ]);

        Announcement::create([
            'user_id' => $customer1->id,
            'title' => 'Restock Alert: Organic Heirloom Tomatoes',
            'message' => 'Green Valley Organic Produce has updated fresh weekly stock for Heirloom Tomatoes. Reserve yours now!',
            'type' => 'restock',
            'link' => '/products/' . $p1->id,
        ]);

        // 11. Initial Admin Report
        Report::create([
            'generated_by' => $admin->id,
            'report_type' => 'Platform Launch Overview',
            'parameters' => ['period' => 'all_time'],
            'summary_data' => [
                'total_markets' => 4,
                'total_farmers' => 4,
                'total_customers' => 3,
                'total_orders' => 4,
                'total_revenue' => 53.90,
            ],
            'generated_at' => Carbon::now(),
        ]);
    }
}