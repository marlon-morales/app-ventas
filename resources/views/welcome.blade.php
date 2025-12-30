<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software de Gestión | Marlon Morales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e1e2f 0%, #4e54c8 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
            color: white;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%);
        }

        .btn-access {
            background-color: #ffc107;
            color: #1e1e2f;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            border: none;
        }

        .btn-access:hover {
            background-color: #fff;
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: #4e54c8;
        }

        /* Card de Desarrollador */
        .dev-card {
            background: white;
            border-radius: 25px;
            padding: 40px;
            margin-top: -100px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-bottom: 5px solid #4e54c8;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: #f0f2ff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            color: #4e54c8;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .social-link {
            color: #4e54c8;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .social-link:hover {
            color: #ffc107;
        }
    </style>
</head>
<body>

    <section class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-3 font-weight-bold mb-4">Potencia tu Negocio con nuestra Tecnología</h1>
                    <p class="lead mb-5 opacity-75">Sistema integral de gestión de productos y ventas diseñado para optimizar tu eficiencia operativa.</p>


                    <div>
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-access">Ir al Panel de Control</a>
                            <br><br><br><br><br><br>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-access">Acceder al Sistema <i class="fas fa-arrow-right ml-2"></i></a>
                            <br><br><br><br><br><br>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="dev-card">
                    <div class="row align-items-center">
                        <div class="col-md-6 border-right">
                            <h5 class="text-muted text-uppercase small font-weight-bold mb-3">Desarrollado por</h5>
                            <h2 class="font-weight-bold mb-2">Marlon Morales Cobo</h2>
                            <p class="text-muted">Ingeniero de Software apasionado por crear soluciones digitales que transforman empresas.</p>
                            <div class="mt-4">
                                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                        <div class="col-md-6 pl-md-5 mt-4 mt-md-0">
                            <h5 class="text-muted text-uppercase small font-weight-bold mb-3">Datos de Contacto</h5>

                            <div class="d-flex align-items-center mb-3">
                                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                                <div>
                                    <p class="mb-0 small text-muted">Teléfono</p>
                                    <h6 class="mb-0 font-weight-bold">3163041687</h6>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                <div>
                                    <p class="mb-0 small text-muted">Correo Electrónico</p>
                                    <h6 class="mb-0 font-weight-bold">marlon.m.cobo@gmail.com</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-4 text-muted">
        <p class="small">&copy; {{ date('Y') }} Todos los derechos reservados | Diseñado por Marlon Morales Cobo</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>