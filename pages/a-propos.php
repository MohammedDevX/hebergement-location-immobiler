<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Propriété</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <script src="a-propos.js" defer></script> -->
</head>
<body class="font-[Grotesk] bg-[#fafafa]">
    <div class="bg-white">
        <?php include "../includes/navbar.php" ?>
    </div>
    <div id="main-content">
        <div class="bg-gray-100 text-gray-800 leading-relaxed m-0 p-0">
        
        <!-- Hero  -->
        <section class="relative text-center bg-gradient-to-r from-black/60 to-black/60 bg-cover bg-center py-24" style="background-image: url(MAISON.webp);">
            <h1 class="text-5xl  font-[Krylon] mb-15 py-5 bg-white/80 hover:text-primary ">About Us</h1>
            <!-- Texte sous l'image dans une div spéciale -->
            <div class="absolute px-4 left-0  right-0 text-center text-gray-800 text-xl">
                Discover why we are the best choice for your perfect <span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">Stay</span> 
            </div>
        </section>
        <section class="relative text-center bg-gradient-to-r from-black/60 to-black/60 bg-cover bg-center py-24" style="background-image: url(MAISON.webp);">
            <h1 class="text-5xl mb-5 font-[Krylon] mb-15 py-5 bg-white/80 hover:text-primary">Our Mission</h1>
            <!-- Texte sous l'image dans une div spéciale -->
            <div class="absolute px-4 left-0 right-0 text-center text-gray-800 text-xl">
                To create a world where anyone can belong  <span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">anywhere.</span>  We aim to help people feel at home, no matter where they are. 
            </div>
        </section>
        <section class="relative text-center bg-gradient-to-r from-black/60 to-black/60 bg-cover bg-center py-24" style="background-image: url(MAISON.webp);">
            <h1 class="text-5xl mb-5 font-[Krylon] mb-15 py-5 bg-white/80 hover:text-primary">Our Values</h1>
            <!-- Texte sous l'image dans une div spéciale -->
             <div class="flex justify-center items-center">
                <div class=" text-left px-4 text-gray-800 text-xl">
                    <div class="mb-2"><span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">Belonging :</span> We believe in inclusion and connection.<br></div>
                    <div class="mb-2"><span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">Trust :</span> We work hard to build a trusted community of hosts and guests.<br></div>
                    <div class="mb-2"><span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">Innovation :</span> Constantly improving how people travel and experience the world.<br></div>
                    <div class=""><span class="font-[Krylon] text-2xl bg-[#005555] text-white p-1">Sustainability :</span> Promoting eco-friendly stays and community-driven tourism. <br></div>
                </div>
            </div>
        </section>


        <!-- About Content -->
        <section class="px-[10%] py-20 mt-10">
            <!-- Team Section -->
            <div class="mb-20">
                <h2 class="text-4xl text-center font-[Krylon] mb-12 --py-20 text-primary relative after:content-[''] after:absolute after:-bottom-4 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-primary">Our Team</h2>
                <p class="text-center text-md mb-8">Meet our dedicated team of professionals</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8 mt-10">
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:-translate-y-3 transition-transform">
                        <div class="p-5 text-center">
                            <h4 class="mb-2 font-[Krylon] text-xl">Elouali abdessamad</h4>
                            <p class="text-gray-600 mb-4">developer</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:-translate-y-3 transition-transform">
                        <div class="p-5 text-center">
                            <h4 class="mb-2 font-[Krylon] text-xl">bakhtawi mohamed</h4>
                            <p class="text-gray-600 mb-4">developer</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:-translate-y-3 transition-transform">
                        <div class="p-5 text-center">
                            <h4 class="mb-2 font-[Krylon] text-xl">Azamri ayoub</h4>
                            <p class="text-gray-600 mb-4">developer</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:-translate-y-3 transition-transform">
                        <div class="p-5 text-center">
                            <h4 class="mb-2 font-[Krylon] text-xl">ilyasse elissawi</h4>
                            <p class="text-gray-600 mb-4">developer</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:-translate-y-3 transition-transform">
                        <div class="p-5 text-center">
                            <h4 class="mb-2 font-[Krylon] text-xl">oussama Keddouri</h4>
                            <p class="text-gray-600 mb-4">developer</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-primary"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-primary"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
    
            <div class="mb-20">
                            <h2 class="text-4xl text-center mb-12 text-primary relative after:content-[''] after:absolute after:-bottom-4 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-primary">Why Choose Us</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-home"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">Wide Selection of Properties</h4>
                                    <p class="text-gray-600">More than 10,000 properties available across France, catering to all budgets and lifestyles.</p>
                                </div>
                                
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-search"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">Personalized Search</h4>
                                    <p class="text-gray-600">Our advanced algorithm finds properties that match your exact search criteria.</p>
                                </div>
                                
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-briefcase"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">Expert Agents</h4>
                                    <p class="text-gray-600">Our team of experienced real estate agents guides you through every step of the process.</p>
                                </div>
                                
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-chart-line"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">Market Analysis</h4>
                                    <p class="text-gray-600">Receive detailed insights into market trends to make informed decisions.</p>
                                </div>
                                
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-mobile-alt"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">User-Friendly Platform</h4>
                                    <p class="text-gray-600">Our website and mobile app provide a smooth and intuitive user experience.</p>
                                </div>
                                
                                <div class="bg-white p-8 rounded-lg shadow-md text-center hover:-translate-y-3 hover:shadow-lg transition-all">
                                    <div class="text-4xl mb-5 text-primary"><i class="fas fa-handshake"></i></div>
                                    <h4 class="mb-4 text-primary font-[Krylon] text-xl">24/7 Customer Service</h4>
                                    <p class="text-gray-600">Our support team is available anytime to answer your questions and concerns.</p>
                                </div>
                            </div>
                        </div>
        </section>
    </div>
    </div>
    
    <div><?php include "../includes/footer.html" ?></div>
    
    <script src="https://kit.fontawesome.com/5a47e1be6f.js" crossorigin="anonymous"></script>
</body>
</html>