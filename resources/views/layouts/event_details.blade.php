<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>e-Presencas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Plataforma de Gestao de Eventos do INAGE" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <style>
        .register {
            background: -webkit-linear-gradient(left, #3931af, #00c6ff);
            margin-top: 3%;
            padding: 3%;
            border-radius: 1rem;
        }

        .register-left {
            text-align: center;
            color: #fff;
        }

        .register-left img {
            margin: 20px auto;
            width: 50%;
            -webkit-animation: mover 1s infinite alternate;
            animation: mover 1s infinite alternate;
        }

        @-webkit-keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        @keyframes mover {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-20px);
            }
        }

        .register-left input {
            border: none;
            border-radius: 1.5rem;
            padding: 10px 20px;
            width: 75%;
            max-width: 250px;
            background: #f8f9fa;
            font-weight: bold;
            color: #383d41;
            margin: 20px 0;
            cursor: pointer;
        }

        .register-left p {
            font-weight: lighter;
            padding: 0 10%;
        }

        .register-right {
            background: #f8f9fa;
            border-top-left-radius: 10% 50%;
            border-bottom-left-radius: 10% 50%;
            padding: 30px;
        }

        .register .register-form {
            padding: 5% 0;
        }

        .btnRegister {
            width: 100%;
            margin-top: 20px;
            border: none;
            border-radius: 1.5rem;
            padding: 10px;
            background: #0062cc;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .register .nav-tabs {
            margin-top: 20px;
            border: none;
            background: #0062cc;
            border-radius: 1.5rem;
            width: 100%;
        }

        .register .nav-tabs .nav-link {
            padding: 10px;
            font-weight: 600;
            color: #fff;
            border-radius: 1.5rem;
        }

        .register .nav-tabs .nav-link.active {
            background: #fff;
            color: #0062cc;
            border: 2px solid #0062cc;
        }

        .register-heading {
            text-align: center;
            margin: 20px 0;
            color: #495057;
        }

        @media (max-width: 768px) {
            .register {
                padding: 5%;
            }

            .register-left,
            .register-right {
                border-radius: 0 !important;
                text-align: center;
            }

            .register .nav-tabs {
                justify-content: center;
            }

            .register-right {
                border-radius: 1rem;
            }

            .desc_icon {
                color: red !important;
            }
        }
    </style>
</head>

<body>
  @yield("content")
  @include("layouts.shared.footer-script")
</body>

</html>
