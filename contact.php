<?php
$pageTitle = "Contact Aqua Galaxy | Thivim, North Goa";

require_once __DIR__ . "/config/app.php";
require_once __DIR__ . "/config/database.php";

$success = "";
$error = "";

if (($_SERVER["REQUEST_METHOD"] ?? '') === "POST") {
    $name = trim($_POST["name"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $businessType = $_POST["business_type"] ?? "general";
    $message = trim($_POST["message"] ?? "");

    // Validation
    if ($name === "" || strlen($name) > 100) {
        $error = "Please enter your name (up to 100 characters).";
    } elseif ($phone === "" && $email === "") {
        $error = "Please provide at least a phone number or email address so we can reply.";
    } elseif ($message === "" || strlen($message) > 2000) {
        $error = "Please enter your message (up to 2000 characters).";
    } else {
        if (!in_array($businessType, ['aquarium', 'tailoring', 'general'], true)) {
            $businessType = 'general';
        }

        $savedInDb = false;

        if (isset($pdo) && $pdo !== null) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO enquiries (name, phone, email, business_type, message, status)
                    VALUES (:name, :phone, :email, :business_type, :message, 'new')
                ");
                $stmt->execute([
                    "name" => $name,
                    "phone" => $phone !== "" ? $phone : null,
                    "email" => $email !== "" ? $email : null,
                    "business_type" => $businessType,
                    "message" => $message
                ]);
                $savedInDb = true;
            } catch (Exception $e) {
                error_log("Enquiry insert failed: " . $e->getMessage());
            }
        }

        $success = "Thank you, " . htmlspecialchars($name) . "! Your message has been received. We will get back to you shortly.";
    }
}

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";
?>

<main class="pt-24 sm:pt-28 min-h-screen">

    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-slate-900 via-sky-950 to-slate-900 text-white py-12 sm:py-16 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-sky-500/20 text-cyan-300 text-xs font-semibold tracking-wide border border-sky-400/20 mb-4 backdrop-blur-md">
                <span>📍</span> Thivim, North Goa
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white">
                Contact <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-cyan-400">Aqua Galaxy</span>
            </h1>
            <p class="mt-3 text-slate-300 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed">
                Whether you’re looking for vibrant ornamental fish, custom aquarium gear, or professional ladies' tailoring, we’d love to assist you.
            </p>
        </div>
    </section>

    <!-- Quick Action Cards for Mobile Visitors -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <!-- Call Action -->
            <a href="tel:+918806048011" 
               class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-md hover:shadow-xl transition-all flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl group-hover:scale-110 transition shrink-0">
                    📞
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Call Directly</span>
                    <span class="text-sm sm:text-base font-extrabold text-slate-900">+91 88060 48011</span>
                </div>
            </a>

            <!-- WhatsApp Action -->
            <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20have%20an%20enquiry:" 
               target="_blank" rel="noopener"
               class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-md hover:shadow-xl transition-all flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition shrink-0">
                    💬
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Chat on WhatsApp</span>
                    <span class="text-sm sm:text-base font-extrabold text-emerald-700">Instant Reply</span>
                </div>
            </a>

            <!-- Email Action -->
            <a href="mailto:bentonmonteiro@gmail.com" 
               class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-md hover:shadow-xl transition-all flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition shrink-0">
                    ✉️
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Send Email</span>
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 truncate block">bentonmonteiro@gmail.com</span>
                </div>
            </a>

        </div>
    </section>

    <!-- Main Content: Form & Shop Details -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Contact Form (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Send Us an Enquiry
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Fill in your details and we will answer your questions promptly.
                    </p>
                </div>

                <?php if ($success): ?>
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3">
                        <span class="text-lg">✅</span>
                        <div>
                            <p class="font-bold">Enquiry Received!</p>
                            <p class="mt-0.5"><?= $success ?></p>
                            <div class="mt-3">
                                <a href="https://wa.me/918806048011?text=Hello%20Aqua%20Galaxy,%20I%20just%20submitted%20an%20enquiry%20on%20your%20website." 
                                   target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-3 py-1.5 rounded-lg transition">
                                    <span>Follow up on WhatsApp</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                        <span class="text-lg">⚠️</span>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" class="space-y-4">
                    
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Your Full Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required 
                            placeholder="e.g. Maria Fernandes"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                required
                                placeholder="e.g. 9876543210"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            >
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Email Address <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="e.g. maria@gmail.com"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="business_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            What Service Are You Inquiring About?
                        </label>
                        <select 
                            id="business_type" 
                            name="business_type" 
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        >
                            <option value="aquarium">🐠 Ornamental Fish &amp; Live Shrimp</option>
                            <option value="aquarium">🪸 Aquarium Gear, Feeds &amp; Accessories</option>
                            <option value="tailoring">✂️ Ladies' Tailoring &amp; Custom Stitching</option>
                            <option value="tailoring">👗 Dress &amp; Blouse Alterations</option>
                            <option value="general" selected>💬 General Inquiry</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Your Message or Specific Requirements <span class="text-rose-500">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="4" 
                            required 
                            placeholder="Tell us what fish breed, tank size, or tailoring style you are looking for..."
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition"
                        ></textarea>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-sky-500 to-cyan-500 hover:from-sky-600 hover:to-cyan-600 text-white py-3.5 px-6 rounded-xl font-bold text-sm shadow-lg shadow-sky-500/25 transition transform active:scale-98"
                    >
                        Send Enquiry Now
                    </button>

                </form>

            </div>

            <!-- Shop Info & Working Hours (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Store Info Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                    <span class="text-xs font-bold text-sky-600 uppercase tracking-wider">Store Information</span>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight mt-1">
                        Aqua Galaxy
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Benton Monteiro &amp; Team
                    </p>

                    <div class="mt-6 space-y-4 text-sm">
                        
                        <div class="flex items-start gap-3">
                            <span class="text-sky-500 text-lg">📍</span>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase">Location</span>
                                <span class="text-slate-600">Thivim, North Goa, 403502, India</span>
                                <span class="text-xs text-slate-400 block mt-0.5">Conveniently located with nearby parking</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <span class="text-sky-500 text-lg">⏰</span>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase">Store Timings</span>
                                <div class="text-slate-600 space-y-0.5 text-xs sm:text-sm mt-0.5">
                                    <p><strong>Monday – Saturday:</strong> 9:30 AM – 8:30 PM</p>
                                    <p><strong>Sunday:</strong> Open by appointment / On Call</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 pt-3 border-t border-slate-100">
                            <span class="text-sky-500 text-lg">💳</span>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs uppercase">Accepted Payments</span>
                                <span class="text-xs text-slate-600 block mt-0.5">UPI (GPay / PhonePe / Paytm), Cash</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Google Maps Direction Banner -->
                <div class="bg-gradient-to-tr from-slate-900 to-sky-950 text-white rounded-3xl p-6 border border-slate-800 shadow-md">
                    <span class="text-xs font-bold text-sky-400 uppercase tracking-wider block">Visit Us in Person</span>
                    <h4 class="text-lg font-black mt-1">Directions to Thivim Shop</h4>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                        Need help finding our store location? Tap below to open Google Maps navigation directly on your phone.
                    </p>

                    <a href="https://maps.google.com/?q=Thivim+Goa" 
                       target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 mt-4 bg-white text-slate-900 hover:bg-sky-50 px-5 py-2.5 rounded-xl text-xs font-bold shadow transition">
                        <span>Open in Google Maps</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

            </div>

        </div>
    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
