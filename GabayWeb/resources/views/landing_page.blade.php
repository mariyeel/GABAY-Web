<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gabay Landing Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Montserrat", sans-serif;
            min-height: 100vh;
            overflow: hidden;
            background: #062844;
        }

        .hero {
            position: relative;
            width: 100vw;
            height: 100vh;
            color: #ffffff;
            background:
                radial-gradient(circle at 72% 53%, rgba(68, 198, 210, 0.22), transparent 25%),
                linear-gradient(115deg, #06213a 0%, #062b49 52%, #041b2f 100%);
            overflow: hidden;
            padding: 34px 56px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .logo-mark {
            width: 23px;
            height: 23px;
            border: 3px solid #fff;
            border-radius: 50%;
            position: relative;
        }

        .logo-mark::before {
            content: "";
            position: absolute;
            width: 7px;
            height: 13px;
            border: 3px solid #fff;
            border-bottom: none;
            border-radius: 7px 7px 0 0;
            left: 50%;
            top: 5px;
            transform: translateX(-50%);
        }

        .logo-mark::after {
            content: "";
            position: absolute;
            width: 3px;
            height: 8px;
            background: #fff;
            left: 50%;
            bottom: -6px;
            transform: translateX(-50%);
            border-radius: 3px;
        }

        .content {
            position: relative;
            z-index: 3;
            margin-top: 132px;
            max-width: 640px;
        }

        .year-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            margin-left: 25px;
        }

        .year-row .line {
            width: 256px;
            height: 1px;
            background: rgba(255, 255, 255, 0.75);
        }

        .year-row span {
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
        }

        h1 {
            font-size: clamp(88px, 13vw, 132px);
            line-height: 0.78;
            font-weight: 900;
            letter-spacing: 8px;
            margin-bottom: 26px;
        }

        .tagline {
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 2px;
            margin-bottom: 88px;
            white-space: nowrap;
        }

        .description {
            width: 520px;
            max-width: 90vw;
            font-size: 12px;
            line-height: 1.65;
            font-weight: 500;
            letter-spacing: 0.35px;
            margin-left: 7px;
            margin-bottom: 24px;
        }

        .cta {
            margin-left: 0;
            border: 1px solid rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            padding: 13px 24px;
            width: 142px;
            height: 40px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 13px;
            cursor: pointer;
            box-shadow: inset 0 0 14px rgba(255, 255, 255, 0.06);
        }

        .cta span {
            font-size: 19px;
            line-height: 0;
        }

        .globe {
            position: absolute;
            width: 720px;
            height: 720px;
            right: -155px;
            top: -8px;
            border-radius: 50%;
            z-index: 2;
            opacity: 0.98;
        }

        .globe-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(120, 212, 221, 0.38);
        }

        .globe-ring.r1 {
            transform: rotate(13deg) scaleX(0.48);
        }

        .globe-ring.r2 {
            transform: rotate(28deg) scaleX(0.55);
        }

        .globe-ring.r3 {
            transform: rotate(47deg) scaleX(0.64);
        }

        .globe-ring.r4 {
            transform: rotate(-13deg) scaleX(0.72);
        }

        .globe-ring.r5 {
            transform: rotate(74deg) scaleX(0.46);
        }

        .lat {
            position: absolute;
            left: 0;
            right: 0;
            border-radius: 50%;
            border: 1.6px solid rgba(120, 212, 221, 0.32);
            transform: rotate(-12deg);
        }

        .lat.l1 {
            top: 95px;
            height: 98px;
        }

        .lat.l2 {
            top: 190px;
            height: 145px;
        }

        .lat.l3 {
            top: 315px;
            height: 122px;
        }

        .lat.l4 {
            top: 440px;
            height: 80px;
        }

        .map-shape {
            position: absolute;
            background: rgba(88, 195, 205, 0.42);
            border: 5px solid rgba(141, 231, 237, 0.78);
            filter: drop-shadow(0 0 10px rgba(104, 232, 239, 0.35));
            opacity: 0.92;
        }

        .shape1 {
            width: 170px;
            height: 250px;
            top: 120px;
            left: 135px;
            clip-path: polygon(37% 0, 62% 9%, 55% 28%, 72% 43%, 56% 57%, 67% 78%, 42% 100%, 21% 75%, 12% 45%, 27% 25%);
        }

        .shape2 {
            width: 165px;
            height: 210px;
            top: 345px;
            left: 250px;
            clip-path: polygon(31% 3%, 76% 11%, 89% 33%, 73% 58%, 90% 88%, 45% 98%, 28% 77%, 5% 60%, 20% 36%);
        }

        .shape3 {
            width: 190px;
            height: 190px;
            top: 62px;
            left: 380px;
            clip-path: polygon(18% 12%, 52% 0, 84% 16%, 68% 44%, 92% 71%, 55% 96%, 22% 82%, 0 49%);
        }

        .dots {
            position: absolute;
            inset: 50px 80px 55px 95px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.62) 1.15px, transparent 1.15px);
            background-size: 12px 12px;
            clip-path: polygon(26% 5%, 83% 0, 98% 29%, 84% 74%, 59% 100%, 13% 83%, 0 36%);
            opacity: 0.75;
        }

        .node {
            position: absolute;
            width: 27px;
            height: 27px;
            border-radius: 50%;
            background: #bcc7ca;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.3);
            z-index: 5;
        }

        .node.n1 {
            right: 28px;
            top: 15px;
        }

        .node.n2 {
            right: 46px;
            top: 141px;
        }

        .node.n3 {
            right: 192px;
            top: 240px;
        }

        .node.n4 {
            right: 87px;
            top: 333px;
        }

        .node.n5 {
            right: 205px;
            top: 426px;
        }

        .node.n6 {
            right: 10px;
            top: 519px;
        }

        .node.n7 {
            right: 142px;
            top: 607px;
            width: 16px;
            height: 16px;
        }

        .node.n8 {
            right: 8px;
            bottom: 20px;
            width: 20px;
            height: 20px;
        }

        .network-line {
            position: absolute;
            height: 1.5px;
            background: rgba(130, 220, 229, 0.42);
            transform-origin: left center;
            z-index: 4;
        }

        .nl1 {
            width: 260px;
            right: 30px;
            top: 155px;
            transform: rotate(136deg);
        }

        .nl2 {
            width: 245px;
            right: 50px;
            top: 345px;
            transform: rotate(156deg);
        }

        .nl3 {
            width: 230px;
            right: 20px;
            top: 532px;
            transform: rotate(192deg);
        }

        .nl4 {
            width: 190px;
            right: 156px;
            top: 252px;
            transform: rotate(68deg);
        }

        @media (max-width: 850px) {
            .hero {
                padding: 28px 28px;
            }

            .content {
                margin-top: 95px;
            }

            .globe {
                right: -410px;
                opacity: 0.55;
            }

            .tagline {
                white-space: normal;
                line-height: 1.5;
            }

            .year-row .line {
                width: 165px;
            }

            h1 {
                font-size: 72px;
                letter-spacing: 5px;
            }
        }
    </style>
</head>

<body>
    <main class="hero">
        <div class="logo">
            <div class="logo-mark"></div>
            <span>GABAY</span>
        </div>

        <section class="content">
            <div class="year-row">
                <div class="line"></div>
                <span>2025</span>
            </div>

            <h1>GABAY</h1>
            <p class="tagline">GUIDING INDEPENDENCE THROUGH EVERY JOURNEY</p>

            <p class="description">
                Walk with confidence and peace of mind as GABAY guides your journey,
                helping you move safely and independently wherever you go
            </p>

            <a href="{{ route('signup.create') }}" class="cta">Get Started <span>➜</span></a>
        </section>

        <div class="globe" aria-hidden="true">
            <div class="globe-ring r1"></div>
            <div class="globe-ring r2"></div>
            <div class="globe-ring r3"></div>
            <div class="globe-ring r4"></div>
            <div class="globe-ring r5"></div>
            <div class="lat l1"></div>
            <div class="lat l2"></div>
            <div class="lat l3"></div>
            <div class="lat l4"></div>
            <div class="dots"></div>
            <div class="map-shape shape1"></div>
            <div class="map-shape shape2"></div>
            <div class="map-shape shape3"></div>
        </div>

        <div class="network-line nl1"></div>
        <div class="network-line nl2"></div>
        <div class="network-line nl3"></div>
        <div class="network-line nl4"></div>

        <span class="node n1"></span>
        <span class="node n2"></span>
        <span class="node n3"></span>
        <span class="node n4"></span>
        <span class="node n5"></span>
        <span class="node n6"></span>
        <span class="node n7"></span>
        <span class="node n8"></span>
    </main>
</body>

</html>
