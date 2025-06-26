<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>e-Presencas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Plataforma de Gestao de Eventos do INAGE" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>
        .timeline {
            position: relative;
            margin: 60px auto;
            padding: 0 20px;
            max-width: 960px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 4px;
            height: 100%;
            background: #dee2e6;
            transform: translateX(-50%);
            z-index: 0;
        }

        .timeline-step {
            position: relative;
            display: grid;
            grid-template-columns: 45% 10% 45%;
            align-items: center;
            margin-bottom: 30px;
        }

        .timeline-step.left .timeline-content {
            grid-column: 1 / 2;
            text-align: right;
        }

        .timeline-step.right .timeline-content {
            grid-column: 3 / 4;
            text-align: left;
        }

        .timeline-marker {
            grid-column: 2 / 3;
            justify-self: center;
            background-color: #ed184f;
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            font-weight: bold;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .qrcode-marker {
            width: 240px;
            height: 240px;
            background: white;
            padding: 8px;
            border-radius: 12px;
            border: 3px solid #343a40;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            grid-column: 2 / 3;
            justify-self: center;
        }

        .qrcode-marker img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .timeline-content h5 {
            margin-top: 0;
            font-weight: 600;
            color: #343a40;
        }

        @media screen and (max-width: 768px) {
            .timeline-step {
                grid-template-columns: 1fr;
            }

            .timeline-step.left .timeline-content,
            .timeline-step.right .timeline-content {
                grid-column: 1 / 2;
                text-align: center;
                margin-top: 20px;
            }

            .timeline-marker,
            .qrcode-marker {
                grid-column: 1 / 2;
                justify-self: center;
            }
        }


        .pinkBg {
            background-color: #ed184f !important;
            background-image: linear-gradient(90deg, #fd5581, #fd8b55);
        }

        .intro-banner-vdo-play-btn {
            height: 60px;
            width: 60px;
            position: absolute;
            top: 50%;
            left: 50%;
            text-align: center;
            margin: -30px 0 0 -30px;
            border-radius: 100px;
            z-index: 1
        }

        .intro-banner-vdo-play-btn i {
            line-height: 56px;
            font-size: 30px
        }

        .intro-banner-vdo-play-btn .ripple {
            position: absolute;
            width: 160px;
            height: 160px;
            z-index: -1;
            left: 50%;
            top: 50%;
            opacity: 0;
            margin: -80px 0 0 -80px;
            border-radius: 100px;
            -webkit-animation: ripple 1.8s infinite;
            animation: ripple 1.8s infinite
        }

        @-webkit-keyframes ripple {
            0% {
                opacity: 1;
                -webkit-transform: scale(0);
                transform: scale(0)
            }

            100% {
                opacity: 0;
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }

        @keyframes ripple {
            0% {
                opacity: 1;
                -webkit-transform: scale(0);
                transform: scale(0)
            }

            100% {
                opacity: 0;
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }

        .intro-banner-vdo-play-btn .ripple:nth-child(2) {
            animation-delay: .3s;
            -webkit-animation-delay: .3s
        }

        .intro-banner-vdo-play-btn .ripple:nth-child(3) {
            animation-delay: .6s;
            -webkit-animation-delay: .6s
        }
    </style>
</head>

<body>
    @yield("content")
    @include("layouts.shared.footer-script")
</body>

</html>