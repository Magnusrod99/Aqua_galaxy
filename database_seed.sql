-- =======================================================
-- Aqua Galaxy - Sample Seed Data
-- Import this into phpMyAdmin (aqua_galaxy database)
-- =======================================================

USE `aqua_galaxy`;

-- 1. Fish Categories
INSERT INTO `fish_categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'Goldfish', 'goldfish', 'Coldwater and fancy pond and tank varieties', 1),
(2, 'Livebearers & Guppies', 'livebearers', 'Hardy, colorful, and active livebearing fish', 1),
(3, 'Tetras & Rasboras', 'tetras', 'Peaceful, schooling community favorites', 1),
(4, 'Cichlids & Angelfish', 'cichlids', 'Graceful and intelligent centerpiece fish', 1),
(5, 'Betta (Fighter)', 'betta', 'Labyrinth fish with flowing colorful fins', 1),
(6, 'Shrimp & Bottom Dwellers', 'shrimp', 'Clean-up crew, algae eaters, and invertebrates', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`);

-- 2. Ornamental Fish Stock
INSERT INTO `fish` (`id`, `category_id`, `name`, `slug`, `description`, `price_min`, `price_max`, `care_level`, `tank_size`, `temperature`, `food`, `status`) VALUES
(1, 1, 'Red Cap Oranda Goldfish', 'red-cap-oranda', 'Gentle and distinctive with its iconic red crown cap. Extremely popular, active, and peaceful community favorite.', 250.00, 600.00, 'easy', '20+ Gallons', '18°C - 24°C', 'Sinking Pellets & Greens', 1),
(2, 2, 'Albino Full Red Dumbo Guppy', 'dumbo-ear-guppy', 'Spectacular oversized pectoral fins resembling elephant ears with vivid red hues. Peaceful and prolific breeders.', 180.00, 350.00, 'easy', '10+ Gallons', '22°C - 28°C', 'Micro Pellets, Flakes', 1),
(3, 3, 'Neon Tetra (Schooling Fish)', 'neon-tetra', 'Radiant electric blue and crimson red iridescent stripe. Thrives beautifully in planted community aquariums in groups.', 40.00, 80.00, 'easy', '15+ Gallons', '20°C - 26°C', 'Small Flakes, Artemia', 1),
(4, 5, 'Halfmoon Plakat (HMPK) Betta', 'hmpk-betta', 'Vibrant coloration, strong finnage, and full 180-degree tail spread. Healthy, alert, and active personalities.', 250.00, 750.00, 'easy', '5+ Gallons', '24°C - 30°C', 'Betta Bites, Bloodworms', 1),
(5, 4, 'Blue Diamond Angelfish', 'blue-diamond-angelfish', 'Graceful, diamond-patterned scales with shimmering icy blue highlights. The centerpiece for tall aquarium setups.', 220.00, 550.00, 'medium', '30+ Gallons', '24°C - 29°C', 'Granules, Live Daphnia', 1),
(6, 6, 'Red Cherry Dwarf Shrimp', 'red-cherry-shrimp', 'Bright red, busy algae-eaters. Excellent for nano planted tanks, gentle on plants, and fun to observe cleaning your tank.', 50.00, 90.00, 'easy', '5+ Gallons', '21°C - 27°C', 'Algae Wafers, Biofilm', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price_min`=VALUES(`price_min`), `price_max`=VALUES(`price_max`);

-- 3. Aquarium Product Categories
INSERT INTO `aquarium_product_categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'Fish Food & Nutrition', 'food', 'Nutritious flakes, pellets, and vacation blocks', 1),
(2, 'Filters & Pumps', 'filters', 'Internal, sponge, and canister filtration equipment', 1),
(3, 'Aquarium Lighting', 'lighting', 'Full spectrum LED fixtures and clip lamps', 1),
(4, 'Water Care & Conditioners', 'water-care', 'Anti-chlorine solutions, medicines, and test kits', 1),
(5, 'Decor & Plants', 'decor', 'Natural driftwood, live plants, and gravel', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- 4. Aquarium Products
INSERT INTO `aquarium_products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `status`) VALUES
(1, 1, 'Hikari & Taiyo Premium Color Enhancing Pellets', 'hikari-taiyo-pellets', 'Rich in spirulina, astaxanthin, and essential vitamins. Boosts fish immunity and natural vibrant colors.', 180.00, 1),
(2, 2, 'Sobo Submersible Power Internal Filter (600 L/H)', 'sobo-internal-filter-600', 'Dual mechanical and biological sponge filtration with silent water circulation and oxygen nozzle.', 450.00, 1),
(3, 3, 'Slim Clip-On Full Spectrum LED Aquarium Light', 'slim-clip-on-led-light', 'Bright white, blue, and plant-growth boosting LEDs. Highlights natural luster and supports live plants.', 590.00, 1),
(4, 2, 'Ultra-Quiet Dual Nozzle Air Pump & Aerator', 'dual-nozzle-air-pump', 'High oxygen output with low power consumption and anti-vibration rubber feet for whisper-silent operation.', 380.00, 1),
(5, 4, 'Instant Anti-Chlorine & Water Conditioner (250ml)', 'anti-chlorine-water-conditioner', 'Instantly neutralizes toxic tap water chlorine, chloramines, and heavy metals. Protects fish slime coats.', 220.00, 1),
(6, 5, 'Hardy Aquatic Live Plants (Anubias & Java Fern)', 'hardy-live-plants-bundle', 'Easy-to-maintain live plants that thrive in low-light setups without needing CO2 injection.', 150.00, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price`=VALUES(`price`);

-- 5. Ladies Tailoring Services
INSERT INTO `tailoring_services` (`id`, `name`, `slug`, `description`, `price`, `status`) VALUES
(1, 'Designer Saree Blouse Stitching', 'saree-blouse-stitching', 'Precision tailored blouses with perfect cup fit, princess cuts, boat necks, backless designs, and lining fabric finish.', 450.00, 1),
(2, 'Salwar Suit & Churidar Tailoring', 'salwar-suit-churidar', 'Traditional Punjabi suits, straight-cut palazzo sets, and flared Anarkalis tailored comfortably to your exact body measurements.', 600.00, 1),
(3, 'Everyday & Festive Kurti Stitching', 'everyday-festive-kurti', 'Chic A-line kurtis, office wear tunics, and festive designer kurtas with side slits, pocket additions, and delicate piping.', 380.00, 1),
(4, 'Western & Indo-Western Gowns', 'western-indo-western-gowns', 'Custom stitched evening party gowns, maxi dresses, flared skirts, and birthday outfits designed for an elegant drape and flow.', 950.00, 1),
(5, 'Precision Alterations & Size Adjustments', 'precision-alterations', 'Resize, nip the waist, alter shoulder straps, and adjust lengths with clean invisible stitches.', 120.00, 1),
(6, 'Saree Fall Stitching & Pico Edging', 'saree-fall-pico', 'Neat edge overlocking, zigzag pico hem, and secure hand-stitched fall to give your silk and cotton sarees a flawless drape.', 100.00, 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `price`=VALUES(`price`);
