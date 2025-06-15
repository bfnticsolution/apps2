<?php
require 'config.php';

// Gestion des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Récupérer les produits depuis la base de données avec gestion des erreurs
try {
    $stmt = $pdo->query("SELECT * FROM produits1");
    $produits = $stmt->fetchAll();
} catch (PDOException $e) {
    $produits = [];
    $error_message = "Erreur lors du chargement des produits: " . $e->getMessage();
}

// Fonction pour formater le prix
function formatPrice($price) {
    return number_format($price, 0, ',', ' ') . ' FCFA';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PREMIUM Store - Vos applications premium en un clic">
    <title>PREMIUM Store - Applications Android Premium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #2b2d42;
            --accent-color: #f72585;
            --accent-dark: #e5177b;
            --light-color: #f8f9fa;
            --light-gray: #e9ecef;
            --medium-gray: #adb5bd;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #ef233c;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 10px 15px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: var(--light-color);
            overflow-x: hidden;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 0;
            box-shadow: var(--box-shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        header.scrolled {
            padding: 15px 0;
            background: rgba(43, 45, 66, 0.95);
            backdrop-filter: blur(10px);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            font-size: 2rem;
            color: var(--accent-color);
        }

        .logo-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0;
            line-height: 1.2;
        }

        .logo-text span {
            color: var(--accent-color);
        }

        .logo-text p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 0;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 5px;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--accent-color);
            background: rgba(255, 255, 255, 0.1);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            padding: 80px 0 40px;
            text-align: center;
            background: linear-gradient(rgba(67, 97, 238, 0.1), transparent);
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
            font-weight: 700;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--medium-gray);
            max-width: 700px;
            margin: 0 auto 30px;
        }

        .search-bar {
            max-width: 600px;
            margin: 0 auto 40px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid var(--light-gray);
            border-radius: 50px;
            font-size: 1rem;
            transition: var(--transition);
            padding-right: 50px;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .search-bar button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-bar button:hover {
            background: var(--primary-dark);
        }

        /* Apps Grid */
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .section-title h3 {
            font-size: 2rem;
            color: var(--secondary-color);
            display: inline-block;
        }

        .section-title h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-color);
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }

        .app-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .app-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--box-shadow-hover);
        }

        .app-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .app-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .app-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .app-card:hover .app-image img {
            transform: scale(1.1);
        }

        .app-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .app-info h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .app-info p.description {
            color: var(--medium-gray);
            margin-bottom: 15px;
            font-size: 0.9rem;
            flex-grow: 1;
        }

        .app-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .app-rating {
            color: var(--warning-color);
        }

        .app-downloads {
            color: var(--medium-gray);
        }

        .price {
            font-weight: bold;
            color: var(--accent-color);
            font-size: 1.3rem;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            text-align: center;
            margin-top: auto;
        }

        .btn:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .btn-accent {
            background: linear-gradient(to right, var(--accent-color), var(--accent-dark));
        }

        .btn-accent:hover {
            background: linear-gradient(to right, var(--accent-dark), var(--accent-color));
        }

        /* Categories */
        .categories {
            margin: 40px 0;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .categories-container {
            display: flex;
            gap: 15px;
            padding: 10px 0;
        }

        .category-btn {
            padding: 8px 20px;
            background: white;
            border: 1px solid var(--light-gray);
            border-radius: 50px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .category-btn:hover, .category-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Features */
        .features {
            padding: 60px 0;
            background: rgba(67, 97, 238, 0.05);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            text-align: center;
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-hover);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .feature-card h4 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .feature-card p {
            color: var(--medium-gray);
            font-size: 0.95rem;
        }

        /* Testimonials */
        .testimonials {
            padding: 60px 0;
            background: var(--secondary-color);
            color: white;
        }

        .testimonials .section-title h3 {
            color: white;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.1);
            font-family: serif;
            line-height: 1;
        }

        .testimonial-content {
            margin-bottom: 20px;
            font-style: italic;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info h5 {
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .author-info p {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        /* Newsletter */
        .newsletter {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
        }

        .newsletter h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .newsletter p {
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .newsletter-form input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .newsletter-form button {
            padding: 0 30px;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            background: var(--accent-dark);
        }

        /* Footer */
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 60px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .footer-logo h3 span {
            color: var(--accent-color);
        }

        .footer-logo p {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background-color: var(--accent-color);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links a:hover {
            color: var(--accent-color);
            padding-left: 5px;
        }

        .footer-links i {
            width: 20px;
            text-align: center;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .contact-info p {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 20px;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 999;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--accent-dark);
            transform: translateY(-5px);
        }

        /* Loading animation */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(67, 97, 238, 0.2);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            padding: 30px;
            position: relative;
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--medium-gray);
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--dark-color);
        }

        .modal h3 {
            margin-bottom: 20px;
            color: var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h2 {
                font-size: 2rem;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form button {
                padding: 15px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 60px 0 30px;
            }

            .hero h2 {
                font-size: 1.8rem;
            }

            .section-title h3 {
                font-size: 1.5rem;
            }

            .features {
                padding: 40px 0;
            }

            .testimonials {
                padding: 40px 0;
            }

            .newsletter {
                padding: 40px 0;
            }
        }

        @media (max-width: 576px) {
            .logo-text h1 {
                font-size: 1.5rem;
            }

            .hero h2 {
                font-size: 1.5rem;
            }

            .apps-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="main-header">
        <div class="container header-container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="logo-text">
                    <h1><span>PREMIUM</span>Store</h1>
                    <p>Vos applications premium en un clic</p>
                </div>
            </div>
            <nav class="nav-links">
                <a href="#apps">Applications</a>
                <a href="#features">Avantages</a>
                <a href="#testimonials">Témoignages</a>
                <a href="#contact">Contact</a>
            </nav>
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Téléchargez les meilleures applications Android premium</h2>
            <p>Accédez à des fonctionnalités exclusives sans restrictions et profitez d'une expérience utilisateur optimale</p>
            
            <div class="search-bar">
                <input type="text" placeholder="Rechercher une application...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Categories -->
        <section class="categories">
            <div class="categories-container">
                <button class="category-btn active">Toutes</button>
                <button class="category-btn">Productivité</button>
                <button class="category-btn">Réseaux sociaux</button>
                <button class="category-btn">Multimédia</button>
                <button class="category-btn">Jeux</button>
                <button class="category-btn">Utilitaires</button>
                <button class="category-btn">Photographie</button>
            </div>
        </section>

        <!-- Apps Grid -->
        <section id="apps">
            <div class="section-title">
                <h3>Nos Applications Premium</h3>
            </div>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (empty($produits)): ?>
                <div class="loading">
                    <div class="spinner"></div>
                </div>
                <p class="text-center">Aucun produit disponible pour le moment.</p>
            <?php else: ?>
                <div class="apps-grid">
                    <?php foreach ($produits as $produit): ?>
                    <div class="app-card">
                        <?php if (!empty($produit['nouveau']) && $produit['nouveau']): ?>
                            <span class="app-badge">Nouveau</span>
                        <?php endif; ?>
                        
                        <div class="app-image">
                            <img src="<?php echo !empty($produit['image']) ? htmlspecialchars($produit['image']) : 'https://via.placeholder.com/300x200?text=PREMIUM+Store'; ?>" 
                                 alt="<?php echo htmlspecialchars($produit['nom']); ?>"
                                 loading="lazy">
                        </div>
                        <div class="app-info">
                            <h3><?php echo htmlspecialchars($produit['nom']); ?></h3>
                            <p class="description"><?php echo htmlspecialchars($produit['description']); ?></p>
                            
                            <div class="app-meta">
                                <div class="app-rating">
                                    <i class="fas fa-star"></i> 4.8
                                </div>
                                <div class="app-downloads">
                                    <i class="fas fa-download"></i> 10K+
                                </div>
                            </div>
                            
                            <p class="price"><?php echo formatPrice($produit['prix']); ?></p>
                           <a href="<?php echo !empty($produit['lien de paiement']) ? htmlspecialchars($produit['lien de paiement']) : '#'; ?>" 
   class="btn btn-block btn-accent" 
   target="_blank" 
   rel="noopener noreferrer">
    Acheter Maintenant
</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Features -->
        <section class="features" id="features">
            <div class="container">
                <div class="section-title">
                    <h3>Pourquoi choisir PREMIUM Store?</h3>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Sécurité garantie</h4>
                        <p>Toutes nos applications sont vérifiées et sans malware pour une expérience sécurisée.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4>Performances optimales</h4>
                        <p>Applications optimisées pour des performances fluides et une consommation réduite.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h4>Mises à jour régulières</h4>
                        <p>Accès aux dernières versions avec toutes les fonctionnalités premium débloquées.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>Support 24/7</h4>
                        <p>Notre équipe est disponible pour vous aider à tout moment en cas de problème.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials" id="testimonials">
            <div class="container">
                <div class="section-title">
                    <h3>Ce que disent nos clients</h3>
                </div>
                
                <div class="testimonials-grid">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>J'ai acheté plusieurs applications sur PREMIUM Store et je suis vraiment satisfait. Les applications fonctionnent parfaitement et le support est réactif.</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Jean K.">
                            </div>
                            <div class="author-info">
                                <h5>Jean K.</h5>
                                <p>Client depuis 2024</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>La qualité des applications est excellente et les prix sont très raisonnables. Je recommande vivement PREMIUM Store à tous mes amis.</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Aïcha B.">
                            </div>
                            <div class="author-info">
                                <h5>Aïcha B.</h5>
                                <p>Client depuis 2023</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>J'ai économisé des centaines de dollars en achetant les versions premium sur ce site plutôt que de payer les abonnements officiels. Service impeccable!</p>
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Paul T.">
                            </div>
                            <div class="author-info">
                                <h5>Paul T.</h5>
                                <p>Client depuis 2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Newsletter -->
    <section class="newsletter">
        <div class="container">
            <h3>Abonnez-vous à notre newsletter</h3>
            <p>Recevez les dernières actualités, promotions et nouvelles applications directement dans votre boîte mail.</p>
            
            <form class="newsletter-form">
                <input type="email" placeholder="Votre adresse email" required>
                <button type="submit">S'abonner</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <h3><span>PREMIUM</span>Store</h3>
                        <p>Vos applications premium en un clic</p>
                    </div>
                    <p>Nous proposons les meilleures applications Android premium à des prix imbattables.</p>
                    
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-telegram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Liens rapides</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Accueil</a></li>
                        <li><a href="#apps"><i class="fas fa-chevron-right"></i> Applications</a></li>
                        <li><a href="#features"><i class="fas fa-chevron-right"></i> Avantages</a></li>
                        <li><a href="#testimonials"><i class="fas fa-chevron-right"></i> Témoignages</a></li>
                        <li><a href="#" id="faq-link"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Informations</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-chevron-right"></i> À propos</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Politique de confidentialité</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Conditions d'utilisation</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Méthodes de paiement</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Politique de remboursement</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contactez-nous</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> mosaicdigital.pro@gmail.com</p>
                        <p><i class="fas fa-phone"></i> +226 67 22 28 63</p>
                        <p><i class="fas fa-map-marker-alt"></i> Ouagadougou, Burkina Faso</p>
                    </div>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2025 MOSAIC DP. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <div class="back-to-top" id="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- FAQ Modal -->
    <div class="modal" id="faq-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h3>Foire Aux Questions</h3>
            
            <div class="faq-item">
                <h4>Comment fonctionne le processus d'achat?</h4>
                <p>Après avoir cliqué sur "Acheter Maintenant", vous serez redirigé vers une page de paiement sécurisée. Une fois le paiement confirmé, vous recevrez un lien de téléportement par email.</p>
            </div>
            
            <div class="faq-item">
                <h4>Les applications sont-elles sûres à utiliser?</h4>
                <p>Oui, toutes nos applications sont testées pour garantir qu'elles ne contiennent aucun malware ou virus. Nous vérifions chaque application avant de la proposer sur notre plateforme.</p>
            </div>
            
            <div class="faq-item">
                <h4>Que faire si j'ai un problème avec une application?</h4>
                <p>Notre équipe de support est disponible 24/7. Vous pouvez nous contacter par email ou via notre page de contact et nous vous répondrons dans les plus brefs délais.</p>
            </div>
            
            <div class="faq-item">
                <h4>Comment recevrai-je les mises à jour?</h4>
                <p>Vous recevrez un email avec les liens de téléportement pour chaque mise à jour importante. Vous pouvez également vérifier régulièrement votre compte sur notre site.</p>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
        });

        // Back to top button
        const backToTopBtn = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('visible');
                document.getElementById('main-header').classList.add('scrolled');
            } else {
                backToTopBtn.classList.remove('visible');
                document.getElementById('main-header').classList.remove('scrolled');
            }
        });
        
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // FAQ Modal
        const faqModal = document.getElementById('faq-modal');
        const faqLink = document.getElementById('faq-link');
        const closeModal = document.querySelector('.close-modal');
        
        faqLink.addEventListener('click', (e) => {
            e.preventDefault();
            faqModal.style.display = 'flex';
        });
        
        closeModal.addEventListener('click', () => {
            faqModal.style.display = 'none';
        });
        
        window.addEventListener('click', (e) => {
            if (e.target === faqModal) {
                faqModal.style.display = 'none';
            }
        });

        // Category filter
        const categoryBtns = document.querySelectorAll('.category-btn');
        
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                categoryBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Ici vous pourriez ajouter la logique pour filtrer les applications
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-bar input');
        const searchBtn = document.querySelector('.search-bar button');
        
        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                // Ici vous pourriez ajouter la logique pour rechercher des applications
                alert(`Recherche pour: ${searchTerm}`);
            }
        });
        
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                searchBtn.click();
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (window.innerWidth <= 992) {
                        navLinks.style.display = 'none';
                    }
                }
            });
        });
    </script>
</body>
</html>