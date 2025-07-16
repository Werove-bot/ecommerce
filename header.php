<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/includes/db.php';
$title = $title ?? 'My Store';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title) ?></title>

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Flowbite (komponen Tailwind, termasuk carousel) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  
  <!-- Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Inter', sans-serif; }
    .logo-font { font-family: 'Orbitron', monospace; }
    
    /* Advanced Gradient Background */
    .advanced-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
      background-size: 400% 400%;
      animation: gradientShift 8s ease infinite;
    }
    
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    /* Glassmorphism Effect */
    .glass-nav {
      backdrop-filter: blur(20px);
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Neon Glow Effect */
    .neon-glow {
      box-shadow: 0 0 20px rgba(102, 126, 234, 0.5),
                  0 0 40px rgba(102, 126, 234, 0.3),
                  0 0 60px rgba(102, 126, 234, 0.1);
    }
    
    /* 3D Logo Effect */
    .logo-3d {
      text-shadow: 0 1px 0 #ccc,
                   0 2px 0 #c9c9c9,
                   0 3px 0 #bbb,
                   0 4px 0 #b9b9b9,
                   0 5px 0 #aaa,
                   0 6px 1px rgba(0,0,0,.1),
                   0 0 5px rgba(0,0,0,.1),
                   0 1px 3px rgba(0,0,0,.3),
                   0 3px 5px rgba(0,0,0,.2),
                   0 5px 10px rgba(0,0,0,.25);
    }
    
    /* Floating Animation */
    .float-animation {
      animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    /* Hover Effects */
    .nav-link {
      position: relative;
      transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .nav-link::before {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 3px;
      background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
      transition: width 0.4s ease;
      border-radius: 2px;
    }
    
    .nav-link:hover::before {
      width: 100%;
    }
    
    .nav-link:hover {
      transform: translateY(-3px);
      text-shadow: 0 0 15px rgba(255, 255, 255, 0.8);
    }
    
    /* Cart Badge */
    .cart-badge {
      background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
      animation: pulse 2s infinite, bounce 1s ease-in-out infinite;
      box-shadow: 0 0 15px rgba(255, 107, 107, 0.5);
    }
    
    @keyframes bounce {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.2); }
    }
    
    /* Mobile Menu Animation */
    .mobile-menu {
      transform: translateX(-100%);
      transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      background: linear-gradient(45deg, rgba(102, 126, 234, 0.95), rgba(118, 75, 162, 0.95));
      backdrop-filter: blur(20px);
    }
    
    .mobile-menu.active {
      transform: translateX(0);
    }
    
    /* Particle Background */
    .particles {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      pointer-events: none;
    }
    
    .particle {
      position: absolute;
      width: 3px;
      height: 3px;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 50%;
      animation: particleMove 15s linear infinite;
    }
    
    @keyframes particleMove {
      0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10% { opacity: 1; }
      90% { opacity: 1; }
      100% { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
    }
    
    /* Hamburger Menu Animation */
    .hamburger {
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .hamburger:hover {
      transform: scale(1.1);
    }
    
    .hamburger.active .line1 {
      transform: rotate(-45deg) translate(-5px, 6px);
    }
    
    .hamburger.active .line2 {
      opacity: 0;
    }
    
    .hamburger.active .line3 {
      transform: rotate(45deg) translate(-5px, -6px);
    }
    
    .hamburger-line {
      width: 25px;
      height: 3px;
      background: white;
      margin: 4px 0;
      transition: 0.3s;
      border-radius: 2px;
    }
    
    /* Search Bar Animation */
    .search-bar {
      width: 0;
      opacity: 0;
      transition: all 0.5s ease;
    }
    
    .search-bar.active {
      width: 200px;
      opacity: 1;
    }
    
    /* Notification Dot */
    .notification-dot {
      animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    
    @keyframes ping {
      75%, 100% {
        transform: scale(2);
        opacity: 0;
      }
    }
  </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
  
  <!-- ===== NAVBAR ===== -->
  <nav class="relative overflow-hidden">
    <!-- Animated Background -->
    <div class="advanced-gradient relative">
      <!-- Particle Effects -->
      <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 6s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 8s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 10s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 12s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 14s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 16s;"></div>
      </div>
      
      <!-- Glass Navigation -->
      <div class="glass-nav relative z-10">
        <div class="container mx-auto px-6 py-4">
          <div class="flex justify-between items-center">
            
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
              <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-white to-gray-200 rounded-full flex items-center justify-center shadow-2xl neon-glow float-animation">
                  <i class="fas fa-glasses text-purple-600 text-xl"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full notification-dot"></div>
              </div>
              <div class="flex flex-col">
                <a href="index.php" class="logo-font font-black text-3xl text-white logo-3d hover:text-purple-200 transition-all duration-300">
                  KacamataKu
                </a>
                <span class="text-xs text-purple-200 font-light">Premium Eyewear</span>
              </div>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center space-x-8">
              <!-- Search Bar -->
              <div class="relative">
                <input type="text" placeholder="Cari produk..." class="search-bar bg-white/20 text-white placeholder-purple-200 px-4 py-2 rounded-full border border-white/30 focus:outline-none focus:border-white/50 backdrop-blur-sm">
                <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-200 hover:text-white transition-colors">
                  <i class="fas fa-search"></i>
                </button>
              </div>
              
              <!-- Navigation Links -->
              <a class="nav-link text-white hover:text-purple-200 font-medium flex items-center space-x-2 px-4 py-2 rounded-full hover:bg-white/10" href="index.php">
                <i class="fas fa-home text-lg"></i>
                <span>Beranda</span>
              </a>
              
              <a class="nav-link text-white hover:text-purple-200 font-medium flex items-center space-x-2 px-4 py-2 rounded-full hover:bg-white/10" href="about.php">
                <i class="fas fa-info-circle text-lg"></i>
                <span>Tentang</span>
              </a>
              
              <a class="nav-link text-white hover:text-purple-200 font-medium flex items-center space-x-2 px-4 py-2 rounded-full hover:bg-white/10 relative" href="cart.php">
                <i class="fas fa-shopping-cart text-lg"></i>
                <span>Keranjang</span>
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                  <span class="cart-badge absolute -top-2 -right-2 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                    <?= count($_SESSION['cart']) ?>
                  </span>
                <?php endif; ?>
              </a>
              
              <!-- User Profile -->
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:scale-110 transition-transform">
                  <i class="fas fa-user text-white"></i>
                </div>
              </div>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden hamburger focus:outline-none" id="mobile-menu-btn">
              <div class="hamburger-line line1"></div>
              <div class="hamburger-line line2"></div>
              <div class="hamburger-line line3"></div>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu fixed top-0 left-0 w-full h-full z-50 lg:hidden" id="mobile-menu">
      <div class="flex flex-col h-full">
        <!-- Mobile Header -->
        <div class="flex justify-between items-center p-6 border-b border-white/20">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg">
              <i class="fas fa-glasses text-purple-600 text-xl"></i>
            </div>
            <span class="logo-font font-bold text-2xl text-white">KacamataKu</span>
          </div>
          <button class="text-white hover:text-purple-200 focus:outline-none" id="mobile-menu-close">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>
        
        <!-- Mobile Search -->
        <div class="p-6 border-b border-white/20">
          <div class="relative">
            <input type="text" placeholder="Cari produk..." class="w-full bg-white/20 text-white placeholder-purple-200 px-4 py-3 rounded-full border border-white/30 focus:outline-none focus:border-white/50 backdrop-blur-sm">
            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-200">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="flex-1 flex flex-col justify-center space-y-8 px-6">
          <a class="text-white hover:text-purple-200 font-medium text-2xl flex items-center space-x-4 p-4 rounded-xl hover:bg-white/10 transition-all duration-300" href="index.php">
            <i class="fas fa-home text-xl"></i>
            <span>Beranda</span>
          </a>
          <a class="text-white hover:text-purple-200 font-medium text-2xl flex items-center space-x-4 p-4 rounded-xl hover:bg-white/10 transition-all duration-300" href="about.php">
            <i class="fas fa-info-circle text-xl"></i>
            <span>Tentang</span>
          </a>
          <a class="text-white hover:text-purple-200 font-medium text-2xl flex items-center space-x-4 p-4 rounded-xl hover:bg-white/10 transition-all duration-300 relative" href="cart.php">
            <i class="fas fa-shopping-cart text-xl"></i>
            <span>Keranjang</span>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
              <span class="cart-badge text-white text-sm rounded-full w-8 h-8 flex items-center justify-center font-bold ml-auto">
                <?= count($_SESSION['cart']) ?>
              </span>
            <?php endif; ?>
          </a>
        </div>
        
        <!-- Mobile Footer -->
        <div class="p-6 border-t border-white/20">
          <div class="flex items-center justify-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center shadow-lg">
              <i class="fas fa-user text-white"></i>
            </div>
            <span class="text-white font-medium">Profil Pengguna</span>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- ===== MAIN ===== -->
  <main class="container mx-auto flex-1 px-4 py-6">

  <script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const hamburger = document.querySelector('.hamburger');

    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.add('active');
      hamburger.classList.add('active');
    });

    mobileMenuClose.addEventListener('click', () => {
      mobileMenu.classList.remove('active');
      hamburger.classList.remove('active');
    });

    // Close mobile menu when clicking outside
    mobileMenu.addEventListener('click', (e) => {
      if (e.target === mobileMenu) {
        mobileMenu.classList.remove('active');
        hamburger.classList.remove('active');
      }
    });

    // Search bar toggle
    const searchButton = document.querySelector('.fa-search');
    const searchBar = document.querySelector('.search-bar');
    
    if (searchButton && searchBar) {
      searchButton.addEventListener('click', () => {
        searchBar.classList.toggle('active');
        if (searchBar.classList.contains('active')) {
          searchBar.focus();
        }
      });
    }

    // Add scroll effect
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('nav');
      if (window.scrollY > 50) {
        nav.classList.add('backdrop-blur-md');
      } else {
        nav.classList.remove('backdrop-blur-md');
      }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>