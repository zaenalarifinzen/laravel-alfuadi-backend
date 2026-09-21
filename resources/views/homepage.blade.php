@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
    <style>
        .homepage {
            color: #191d21;
        }

        .homepage-hero {
            /* width: 100vw; */
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            padding: 56px 0 0;
            position: relative;
            overflow: hidden;
        }

        .homepage-hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px 70px;
            box-sizing: border-box;
        }

        .hero-left {
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .homepage-badge-top {
            background-color: #e9fbf8;
            color: #138a84;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            border: 1px solid #c2f2ea;
        }

        .homepage-title {
            color: #103d3a;
            font-size: 44px;
            font-weight: 800;
            line-height: 1.18;
            margin: 10px 0 18px;
            max-width: 560px;
            letter-spacing: -0.5px;
        }

        html[data-theme="dark"] .homepage-title {
            color: #f2fffe;
        }

        .homepage-title span {
            color: #138a84;
            background: linear-gradient(135deg, #138a84 0%, #0d6561 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .homepage-lead {
            color: #667085;
            font-size: 17px;
            line-height: 1.8;
            margin: 0 0 28px;
            max-width: 480px;
        }

        .homepage-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-start;
            gap: 18px;
            margin-bottom: 34px;
        }

        .btn-hero-play {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #103d3a;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
        }

        .btn-hero-play .play-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #138a84;
            color: #138a84;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all .2s ease;
        }

        .btn-hero-play:hover .play-circle {
            background: #138a84;
            color: #fff;
        }

        .btn-hero-play:hover {
            color: #138a84;
        }

        .hero-social-proof {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hero-avatar-stack {
            display: flex;
        }

        .hero-avatar-stack .hero-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            margin-left: -10px;
        }

        .hero-avatar-stack .hero-avatar:first-child {
            margin-left: 0;
        }

        .hero-social-proof-text .hero-stars {
            color: #f5b942;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .hero-social-proof-text small {
            color: #475467;
            font-size: 13px;
            font-weight: 600;
        }

        /* Hero visual (right side) */
        .hero-right {
            position: relative;
            min-height: 460px;
        }

        .hero-visual-panel {
            border-radius: 20px;
            height: 460px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .hero-visual-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .hero-floating-card {
            position: absolute;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 14px 34px rgba(16, 61, 58, .18);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 2;
            max-width: 220px;
        }

        .hero-floating-card .fc-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e9fbf8;
            color: #138a84;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }

        .hero-floating-card p {
            margin: 0;
            font-size: 12.5px;
            line-height: 1.4;
            color: #344054;
            font-weight: 600;
        }

        .hero-fc-top {
            top: 6%;
            right: -4%;
        }

        .hero-fc-stat {
            top: 40%;
            right: -10%;
            text-align: left;
        }

        .hero-fc-stat .fc-stat-value {
            font-size: 20px;
            font-weight: 800;
            color: #103d3a;
            line-height: 1.1;
        }

        .hero-fc-stat .fc-stat-label {
            font-size: 11px;
            color: #667085;
            font-weight: 600;
        }

        .hero-fc-stat .fc-stat-badge {
            color: #16a34a;
            font-size: 11px;
            font-weight: 700;
        }

        .hero-fc-testi {
            bottom: 4%;
            left: -6%;
            max-width: 240px;
            align-items: flex-start;
        }

        .hero-fc-testi .testi-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #138a84;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-fc-testi .testi-stars {
            color: #f5b942;
            font-size: 10px;
            margin-bottom: 3px;
        }

        .hero-fc-testi p {
            font-weight: 500;
            font-style: italic;
        }

        .hero-fc-testi .testi-name {
            font-weight: 700;
            font-style: normal;
            color: #103d3a;
            margin-top: 2px;
            display: block;
        }

        /* Trust strip below hero */
        .homepage-trust-strip {
            background: linear-gradient(135deg, #e9fbf8 0%, #f4fffd 100%);
            border: 1px solid #c2f2ea;
            border-radius: 18px;
            box-shadow: 0 14px 34px rgba(19, 138, 132, .08);
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 26px 32px;
            position: relative;
            overflow: hidden;
        }

        .homepage-trust-strip::before {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(19, 138, 132, .08), transparent 70%);
            top: -60px;
            right: -40px;
        }

        .homepage-trust-strip .trust-label {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .3px;
            color: #138a84;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .trust-logo-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .trust-logo-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: 1px solid #c2f2ea;
            border-radius: 50px;
            padding: 9px 18px 9px 9px;
            color: #103d3a;
            font-weight: 700;
            font-size: 13.5px;
            box-shadow: 0 6px 16px rgba(16, 61, 58, .06);
            transition: all .2s ease;
        }

        .trust-logo-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(19, 138, 132, .14);
            border-color: #7fd8cf;
        }

        .trust-logo-item i {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Section Titles */
        .homepage-section-header {
            margin-bottom: 45px;
        }

        .homepage-section-subtitle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            font-weight: 700;
            color: #138a84;
            background: #e9fbf8;
            border: 1px solid #c2f2ea;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 14px;
        }

        .homepage-section-title {
            color: #103d3a;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        html[data-theme="dark"] .homepage-section-title {
            color: #f2fffe;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .homepage-section-lead {
            color: #667085;
            margin: 0 auto;
            max-width: 680px;
            font-size: 16px;
        }

        /* Contained panel background for alternating sections (keeps side spacing) */
        .homepage-panel-section {
            background: linear-gradient(180deg, #f4fffd 0%, #ffffff 100%);
            border: 1px solid #e2f1ee;
            border-radius: 28px;
            padding: 56px 40px;
            position: relative;
            overflow: hidden;
        }

        .homepage-panel-section::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(19, 138, 132, .07), transparent 70%);
            top: -100px;
            right: -80px;
            z-index: 0;
        }

        .homepage-panel-section::after {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 185, 66, .10), transparent 70%);
            bottom: -80px;
            left: -60px;
            z-index: 0;
        }

        .homepage-panel-section>* {
            position: relative;
            z-index: 1;
        }

        /* Card Styles  */
        .bab-card {
            border: 1px solid #e2f1ee;
            border-radius: 16px;
            background: #fff;
            padding: 28px;
            height: 100%;
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .bab-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(19, 138, 132, .14);
            border-color: #7fd8cf;
        }

        .bab-number {
            font-size: 12px;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #138a84, #0d6561);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 16px;
            width: fit-content;
        }

        .bab-card h3 {
            font-size: 19px;
            font-weight: 700;
            color: #103d3a;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .bab-card p {
            color: #667085;
            font-size: 14px;
            line-height: 1.65;
            margin-bottom: 18px;
            flex-grow: 1;
        }

        .bab-meta {
            font-size: 13px;
            font-weight: 600;
            color: #138a84;
            display: flex;
            align-items: center;
            gap: 6px;
            padding-top: 14px;
            border-top: 1px dashed #e2f1ee;
        }

        /* Course / class pricing cards */
        .course-card {
            background: #fff;
            border: 1px solid #e2f1ee;
            border-radius: 16px;
            padding: 34px 28px 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(19, 138, 132, .12);
            border-color: #b7e8df;
        }

        .course-card.is-popular {
            border: 2px solid #138a84;
            box-shadow: 0 16px 40px rgba(19, 138, 132, .16);
        }

        .course-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 50px;
            box-shadow: 0 8px 18px rgba(19, 138, 132, .3);
            white-space: nowrap;
        }

        .course-card h3 {
            color: #103d3a;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .course-card>p {
            color: #667085;
            font-size: 14px;
            line-height: 1.65;
            margin-bottom: 22px;
        }

        .course-features {
            list-style: none;
            padding: 0;
            margin: 0 0 28px;
            flex-grow: 1;
        }

        .course-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #344054;
            margin-bottom: 12px;
        }

        .course-features li i {
            color: #138a84;
            font-size: 15px;
        }

        .course-card .btn {
            margin-top: auto;
            border-radius: 10px;
            font-weight: 700;
            padding: 10px;
        }

        .course-card .btn-outline-primary {
            color: #138a84;
            border-color: #138a84;
        }

        .course-card.is-popular .btn-outline-primary {
            background: linear-gradient(135deg, #138a84, #0d6561);
            border-color: #138a84;
            color: #fff;
        }

        /* Testimonial Cards */
        .testimonial-card {
            border: 1px solid #e9f4f2;
            border-radius: 16px;
            background: #fff;
            padding: 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 6px 20px rgba(15, 23, 42, .03);
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(19, 138, 132, .12);
            border-color: #b7e8df;
        }

        .testimonial-stars {
            color: #f5b942;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .testimonial-quote {
            color: #344054;
            font-size: 15px;
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 24px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .testimonial-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: #103d3a;
            margin: 0;
        }

        .testimonial-info p {
            font-size: 13px;
            color: #667085;
            margin: 0;
        }

        /* CTA Section */
        .homepage-cta-banner {
            /* background: linear-gradient(135deg, #103d3a 0%, #138a84 100%); */
            border-radius: 16px;
            padding: 48px;
            /* color: #fff; */
            text-align: center;
            margin: 60px 0 30px;
            position: relative;
            overflow: hidden;
            /* box-shadow: 0 20px 45px rgba(16, 61, 58, .2); */
        }

        .homepage-cta-banner h2 {
            /* color: #fff; */
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .homepage-cta-banner p {
            /* color: rgba(255, 255, 255, .84); */
            font-size: 17px;
            max-width: 640px;
            margin: 0 auto 30px;
        }

        /* =========================================================
               DARK THEME OVERRIDES
               ========================================================= */
        html[data-theme="dark"] .homepage {
            color: #e6f3f1;
        }

        html[data-theme="dark"] .homepage-badge-top {
            background-color: #123330;
            border-color: #1f4d49;
            color: #5eead4;
        }

        html[data-theme="dark"] .homepage-lead,
        html[data-theme="dark"] .homepage-section-lead {
            color: #9fb8b4;
        }

        html[data-theme="dark"] .btn-hero-play {
            color: #f2fffe;
        }

        html[data-theme="dark"] .btn-hero-play .play-circle {
            border-color: #5eead4;
            color: #5eead4;
        }

        html[data-theme="dark"] .btn-hero-play:hover .play-circle {
            background: #5eead4;
            color: #0d2624;
        }

        html[data-theme="dark"] .btn-hero-play:hover {
            color: #5eead4;
        }

        html[data-theme="dark"] .hero-avatar-stack .hero-avatar {
            border-color: #0b1f1d;
        }

        html[data-theme="dark"] .hero-social-proof-text small {
            color: #9fb8b4;
        }

        html[data-theme="dark"] .hero-floating-card {
            background: #14302d;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .35);
        }

        html[data-theme="dark"] .hero-floating-card .fc-icon {
            background: #1f4d49;
            color: #5eead4;
        }

        html[data-theme="dark"] .hero-floating-card p {
            color: #d7e6e4;
        }

        html[data-theme="dark"] .hero-fc-stat .fc-stat-value {
            color: #f2fffe;
        }

        html[data-theme="dark"] .hero-fc-stat .fc-stat-label {
            color: #9fb8b4;
        }

        html[data-theme="dark"] .hero-fc-stat .fc-stat-badge {
            color: #4ade80;
        }

        html[data-theme="dark"] .hero-fc-testi .testi-name {
            color: #f2fffe;
        }

        html[data-theme="dark"] .homepage-trust-strip {
            background: linear-gradient(135deg, #123330 0%, #0d2624 100%);
            border-color: #1f4d49;
            box-shadow: 0 14px 34px rgba(0, 0, 0, .3);
        }

        html[data-theme="dark"] .homepage-trust-strip .trust-label {
            color: #5eead4;
        }

        html[data-theme="dark"] .trust-logo-item {
            background: #17403c;
            border-color: #1f4d49;
            color: #f2fffe;
            box-shadow: 0 6px 16px rgba(0, 0, 0, .25);
        }

        html[data-theme="dark"] .trust-logo-item:hover {
            border-color: #2f6b64;
            box-shadow: 0 10px 22px rgba(0, 0, 0, .35);
        }

        html[data-theme="dark"] .homepage-section-subtitle {
            background: #123330;
            border-color: #1f4d49;
            color: #5eead4;
        }

        html[data-theme="dark"] .homepage-panel-section {
            background: linear-gradient(180deg, #191030ad 0%, #0b0d1f 100%);
            ;
            border-color: #281d5b;
        }

        html[data-theme="dark"] .homepage-panel-section::before {
            background: radial-gradient(circle, rgba(94, 234, 212, .10), transparent 70%);
        }

        html[data-theme="dark"] .homepage-panel-section::after {
            background: radial-gradient(circle, rgba(245, 185, 66, .12), transparent 70%);
        }

        html[data-theme="dark"] .bab-card {
            background: #0d191e94;
            border-color: #1f4d49;
        }

        html[data-theme="dark"] .bab-card:hover {
            border-color: #2f6b64;
            box-shadow: 0 16px 35px rgba(0, 0, 0, .3);
        }

        html[data-theme="dark"] .bab-card h3 {
            color: #f2fffe;
        }

        html[data-theme="dark"] .bab-card p {
            color: #a9c6c2;
        }

        html[data-theme="dark"] .bab-meta {
            color: #5eead4;
            border-top-color: rgba(255, 255, 255, .12);
        }

        html[data-theme="dark"] .course-card {
            background: #0d191e94;
            border-color: #1f4d49;
        }

        html[data-theme="dark"] .course-card:hover {
            border-color: #2f6b64;
            box-shadow: 0 16px 35px rgba(0, 0, 0, .3);
        }

        html[data-theme="dark"] .course-card.is-popular {
            border-color: #2dd4bf;
            box-shadow: 0 16px 40px rgba(45, 212, 191, .18);
        }

        html[data-theme="dark"] .course-card h3 {
            color: #f2fffe;
        }

        html[data-theme="dark"] .course-card>p {
            color: #9fb8b4;
        }

        html[data-theme="dark"] .course-features li {
            color: #d7e6e4;
        }

        html[data-theme="dark"] .course-features li i {
            color: #5eead4;
        }

        html[data-theme="dark"] .course-card .btn-outline-primary {
            color: #5eead4;
            border-color: #5eead4;
        }

        html[data-theme="dark"] .course-card.is-popular .btn-outline-primary {
            background: linear-gradient(135deg, #138a84, #0d6561);
            border-color: #2dd4bf;
            color: #fff;
        }

        html[data-theme="dark"] .testimonial-card {
            background: #0d191e94;
            border-color: #1f4d49;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .2);
        }

        html[data-theme="dark"] .testimonial-card:hover {
            border-color: #2f6b64;
            box-shadow: 0 16px 35px rgba(0, 0, 0, .3);
        }

        html[data-theme="dark"] .testimonial-quote {
            color: #d7e6e4;
        }

        html[data-theme="dark"] .testimonial-info h4 {
            color: #f2fffe;
        }

        html[data-theme="dark"] .testimonial-info p {
            color: #9fb8b4;
        }

        /* html[data-theme="dark"] .homepage-cta-banner {
                box-shadow: 0 20px 45px rgba(0, 0, 0, .4);
            } */

        @media (max-width: 991.98px) {
            .homepage-hero-grid {
                grid-template-columns: 1fr;
                gap: 60px;
            }

            .hero-left {
                text-align: center;
            }

            .homepage-title,
            .homepage-lead {
                margin-left: auto;
                margin-right: auto;
            }

            .homepage-actions {
                justify-content: center;
            }

            .hero-social-proof {
                justify-content: center;
            }

            .hero-right {
                min-height: 400px;
                max-width: 460px;
                margin: 0 auto;
            }

            .hero-visual-panel {
                height: 380px;
            }

            .hero-fc-top {
                right: 2%;
            }

            .hero-fc-stat {
                right: 0;
            }

            .hero-fc-testi {
                left: 2%;
            }
        }

        @media (max-width: 767.98px) {
            .homepage-hero {
                padding-top: 24px;
                margin: 15px;
            }

            .homepage-panel-section {
                padding: 36px 20px;
                border-radius: 20px;
            }

            .homepage-title {
                font-size: 28px;
            }

            .homepage-lead {
                font-size: 15px;
            }

            .hero-right {
                min-height: 320px;
            }

            .hero-visual-panel {
                height: 300px;
            }

            .hero-visual-icon {
                width: 140px;
                height: 140px;
                font-size: 54px;
            }

            .hero-floating-card {
                max-width: 170px;
                padding: 9px 12px;
            }

            .hero-fc-stat .fc-stat-value {
                font-size: 16px;
            }

            .homepage-trust-strip {
                padding: 22px 18px;
                border-radius: 14px;
            }

            .trust-logo-item {
                font-size: 12.5px;
                padding: 7px 14px 7px 7px;
            }

            .homepage-cta-banner {
                padding: 32px 20px;
            }

            .homepage-cta-banner h2 {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content homepage">
        <section class="section">

            <!-- HERO SECTION -->
            <div class="homepage-hero">
                <div class="homepage-hero-grid">
                    <div class="hero-left">
                        @guest
                            <div class="homepage-badge-top">
                                <i class="fas fa-sparkles"></i> Metode Pembelajaran Nahwu Modern & Terstruktur
                            </div>

                            <h1 class="homepage-title">
                                Kuasai Ilmu <span>Nahwu</span> dengan Mudah & Efektif
                            </h1>

                            <p class="homepage-lead">
                                Pelajari tata bahasa, sintaksis, dan struktur i'rob Al-Qur'an secara interaktif melalui
                                pendekatan bertahap bersama Metode Al-Fuadi.
                            </p>

                            <div class="homepage-actions">
                                <a class="btn btn-primary btn-lg px-4" href="{{ route('register') }}">
                                    Mulai Belajar Gratis
                                </a>
                                <a class="btn-hero-play" href="{{ route('courses') }}">
                                    <i class="fas fa-search"></i>
                                    Jelajahi Kelas Online
                                </a>
                            </div>

                            <div class="hero-social-proof">
                                <div class="hero-avatar-stack">
                                    <span class="hero-avatar">AF</span>
                                    <span class="hero-avatar">ZA</span>
                                    <span class="hero-avatar">SN</span>
                                    <span class="hero-avatar">HR</span>
                                </div>
                                <div class="hero-social-proof-text">
                                    <div class="hero-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <small>Bergabung dengan 2.000+ santri belajar Nahwu</small>
                                </div>
                            </div>
                        @else
                            <div class="homepage-badge-top">
                                <i class="fas fa-user-check"></i> Selamat Datang Kembali
                            </div>

                            <h1 class="homepage-title">
                                Halo, <span>{{ auth()->user()->name }}</span>
                            </h1>

                            <p class="homepage-lead">
                                Siap untuk melanjutkan pembelajaran? Mari perdalam pemahaman kaidah nahwu dan tingkatkan
                                latihan analisa i'rob Anda hari ini.
                            </p>

                            <div class="homepage-actions">
                                <a class="btn btn-primary btn-lg px-4" href="{{ route('enrollments') }}">
                                    Lanjutkan Belajar<span class="ms-2"><i class="fas fa-arrow-right"></i></span>
                                </a>
                                <a class="btn-hero-play" href="{{ route('courses') }}">
                                    <span class="play-circle"><i class="fas fa-search"></i></span>
                                    Lihat Kelas Online
                                </a>
                            </div>

                            <div class="hero-social-proof">
                                <div class="hero-avatar-stack">
                                    <span class="hero-avatar">ZA</span>
                                    <span class="hero-avatar">SY</span>
                                    <span class="hero-avatar">EW</span>
                                    <span class="hero-avatar">MS</span>
                                </div>
                                <div class="hero-social-proof-text">
                                    <div class="hero-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <small>Bergabung dengan 2.000+ Sobat Fuadi</small>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <div class="hero-right">
                        <div class="hero-visual-panel">
                            <img src="{{ asset('img/hero.webp') }}" alt="Ilustrasi belajar Nahwu Al-Qur'an"
                                class="hero-visual-img">
                        </div>

                        {{-- <div class="hero-floating-card hero-fc-top">
                            <span class="fc-icon"><i class="fas fa-heart"></i></span>
                            <p>Pahami i'rob Al-Qur'an dengan percaya diri</p>
                        </div>

                        <div class="hero-floating-card hero-fc-stat">
                            <div>
                                <span class="fc-stat-label">Progres Belajar</span><br>
                                <span class="fc-stat-value">92%</span>
                                <span class="fc-stat-badge">+18% bulan ini</span>
                            </div>
                        </div>

                        <div class="hero-floating-card hero-fc-testi">
                            <span class="testi-avatar">ZA</span>
                            <p>
                                <span class="testi-stars">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                </span>
                                "Metode ini mengubah cara saya memahami Nahwu."
                                <span class="testi-name">- Zaenal A.</span>
                            </p>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="homepage-trust-strip">
                <p class="trust-label">Dipercaya santri, pengajar, dan lembaga di seluruh Indonesia</p>
                <div class="trust-logo-row">
                    <span class="trust-logo-item"><i class="fas fa-mosque"></i> Pesantren</span>
                    <span class="trust-logo-item"><i class="fas fa-school"></i> Sekolah</span>
                    <span class="trust-logo-item"><i class="fas fa-graduation-cap"></i> Universitas</span>
                    <span class="trust-logo-item"><i class="fas fa-users"></i> Kajian</span>
                </div>
            </div>

            <div class="section-body">
                <section id="bab-materi" class="py-4">
                    <div class="homepage-panel-section">
                        <div class="text-center homepage-section-header">
                            <span class="homepage-section-subtitle">Metode Al-Fuadi</span>
                            <h2 class="homepage-section-title">Modul & Kurikulum Materi Nahwu</h2>
                            <p class="homepage-section-lead">
                                Materi disusun secara sistematis agar Anda dapat menguasai fondasi kaidah tata bahasa Arab
                                hingga praktik analisis i'rob.
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 01</span>
                                    <h3>Kalimat dan Macamnya</h3>
                                    <p>
                                        Pengenalan dasar 3 jenis kata dalam bahasa Arab: Isim, Fi'il, dan Harf beserta ciri
                                        dan
                                        tandanya.
                                    </p>

                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-file-lines"></i> Pembagian Al-Kalimah
                                </div> --}}
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 02</span>
                                    <h3>I'rob dan Macamnya</h3>
                                    <p>
                                        Memahami 4 kondisi I'rob (Rofa', Nasab, Jar dan Jazm) pada kalimat.
                                    </p>
                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-tags"></i> العلامات والإعراب
                                </div> --}}
                                </div>
                            </div>

                            <!-- Bab 3 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 03</span>
                                    <h3>Hukum Kalimat Isim</h3>
                                    <p>
                                        Memahami hukum pada kalimat serta alasannya mengapa di hukumi Mu'rob ataupun Mabni.
                                    </p>
                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-arrow-up-right-dots"></i> Subjek & Predikat (مرفوعات)
                                </div> --}}
                                </div>
                            </div>

                            <!-- Bab 4 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 04</span>
                                    <h3>Tanda I'rob Kalimat Isim</h3>
                                    <p>
                                        Mengenali tanda i'rob pada halimat isim berdasarkan kategorinya.
                                    </p>
                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-arrows-left-right"></i> Objek & Pelengkap (منصوبات)
                                </div> --}}
                                </div>
                            </div>

                            <!-- Bab 5 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 05</span>
                                    <h3>Mubtada dan Khobar</h3>
                                    <p>
                                        Pelajari kedudukan Mubtada dan Khobar pada susunan kalimat.
                                    </p>
                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-link"></i> Sandaran & Pengikut (مجرورات)
                                </div> --}}
                                </div>
                            </div>

                            <!-- Bab 6 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="bab-card">
                                    <span class="bab-number">Materi 06</span>
                                    <h3>Jar dan Majrur</h3>
                                    <p>
                                        Praktik langsung membedah kedudukan kalimat kata demi kata dalam ayat-ayat pilihan
                                        Al-Qur'an secara presisi.
                                    </p>
                                    {{-- <div class="bab-meta">
                                    <i class="fas fa-circle-nodes"></i> Praktik I'rob (إعراب القرآن)
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 2: KELAS & PROGRAM KURSUS (3 CARDS) -->
                <section id="kelas-kursus" class="py-5">
                    <div class="text-center homepage-section-header">
                        <span class="homepage-section-subtitle">Jenjang Pembelajaran</span>
                        <h2 class="homepage-section-title">Pilihan Kelas & Program Belajar</h2>
                        <p class="homepage-section-lead">
                            Pilih program belajar yang sesuai dengan tingkat pemahaman dan target capaian Anda.
                        </p>
                    </div>

                    <div class="row">
                        <!-- Kelas 1 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card">
                                <h3>Kelas Al-Qur'an</h3>
                                <p>Dirancang untuk santri & umum yang ingin membaca Al-Qur'an dengan baik dan benar.</p>
                                <ul class="course-features">
                                    <li><i class="fas fa-circle-check"></i> Pengenalan Bacaan Tajwid</li>
                                    <li><i class="fas fa-circle-check"></i> Teknik Membaca yang Benar</li>
                                    <li><i class="fas fa-circle-check"></i> Latihan Kuis Pilihan Ganda</li>
                                </ul>
                                <a href="{{ route('dashboard') }}"class="btn btn-outline-primary btn-block disabled">Segera
                                    Hadir</a>
                            </div>
                        </div>

                        <!-- Kelas 2 (Popular) -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card is-popular">
                                <span class="course-badge">Paling Populer</span>
                                <h3>Kelas Nahwu</h3>
                                <p>Mempelajari kaidah tata bahasa Arab untuk menganalisis struktur kalimat dan kata.</p>
                                <ul class="course-features">
                                    <li><i class="fas fa-circle-check"></i> Kaidah I'rob</li>
                                    <li><i class="fas fa-circle-check"></i> Latihan Analisa Kalimat</li>
                                    <li><i class="fas fa-circle-check"></i> Peta Konsep Nahwu</li>
                                </ul>
                                <a href="{{ route('dashboard') }}"class="btn btn-outline-primary btn-block disabled">Segera
                                    Hadir</a>
                            </div>
                        </div>

                        <!-- Kelas 3 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="course-card">
                                <h3>Kelas Shorof</h3>
                                <p>Program intensif belajar ilmu Shorof untuk baca kitab gundul.</p>
                                <ul class="course-features">
                                    <li><i class="fas fa-circle-check"></i> Bentuk Kata</li>
                                    <li><i class="fas fa-circle-check"></i> Ujian & Bank Soal Komprehensif</li>
                                    <li><i class="fas fa-circle-check"></i> Sertifikat Capaian Pembelajaran</li>
                                </ul>
                                <a href="{{ route('dashboard') }}"
                                    class="btn btn-outline-primary btn-block disabled">Segera Hadir</a>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 3: TESTIMONI -->
                <section id="testimoni" class="py-4">
                    <div class="homepage-panel-section">
                        <div class="text-center homepage-section-header">
                            <span class="homepage-section-subtitle">Pengalaman Peserta</span>
                            <h2 class="homepage-section-title">Apa Kata Mereka yang Sudah Belajar?</h2>
                            <p class="homepage-section-lead">
                                Testimoni dari santri, pengajar, dan penggiat ilmu Al-Qur'an yang telah merasakan kemudahan
                                Metode Al-Fuadi.
                            </p>
                        </div>

                        <div class="row">
                            <!-- Testimoni 1 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card">
                                    <div>
                                        <div class="testimonial-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <p class="testimonial-quote">
                                            "Metode ini sangan praktis, sangat membantu, terstruktur dengan baik, dan
                                            dilengkapi I'robul Qur'an."
                                        </p>
                                    </div>
                                    <div class="testimonial-author">
                                        {{-- <div class="testimonial-avatar">NS</div> --}}
                                        <div class="testimonial-info">
                                            <h4>Prof. Dr. KH. Nasarudin Umar, M.A.</h4>
                                            <p>Menteri Agama RI</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimoni 2 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card">
                                    <div>
                                        <div class="testimonial-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <p class="testimonial-quote">
                                            "Sangat membantu dalam memahami i'rob ayat Al-Qur'an secara presisi. Visualisasi
                                            skema nahwunya luar biasa jelas!"
                                        </p>
                                    </div>
                                    <div class="testimonial-author">
                                        {{-- <div class="testimonial-avatar">SM</div> --}}
                                        <div class="testimonial-info">
                                            <h4>Siti Maryam, S.Pd.</h4>
                                            <p>Guru</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Testimoni 3 -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="testimonial-card">
                                    <div>
                                        <div class="testimonial-stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <p class="testimonial-quote">
                                            "Latihan interaktifnya membuat saya lebih percaya diri saat membaca dan
                                            menganalisis
                                            struktur kalimat dalam Al-Qur'an."
                                        </p>
                                    </div>
                                    <div class="testimonial-author">
                                        {{-- <div class="testimonial-avatar">AA</div> --}}
                                        <div class="testimonial-info">
                                            <h4>Ust. Ardani Ahmad Al-Hafidz</h4>
                                            <p>Pembimbing Kajian Bahasa Arab</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 4: CALL TO ACTION (CTA) -->
                <section class="homepage-cta-banner">
                    <h2 class="homepage-section-title">Mulai Perjalanan Memahami Al-Qur'an Hari Ini</h2>
                    <p>
                        Bergabunglah dengan platform pembelajaran Metode Al-Fuadi dan rasakan pengalaman belajar nahwu yang
                        modern, terstruktur, dan efektif.
                    </p>
                    <div>
                        @auth
                            <a class="btn btn-primary btn-lg px-4" href="{{ route('enrollments') }}">
                                Kelas Saya<span class="ms-2"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        @else
                            <a class="btn btn-primary btn-lg px-4" href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-2"></i>Buat Akun Gratis
                            </a>
                            <a class="btn btn-outline-primary btn-lg m-4 " href="{{ route('login') }}">
                                <i class="fas fa-right-to-bracket mr-2"></i></span>
                                Masuk
                            </a>
                        @endauth
                    </div>
                </section>

            </div>
        </section>
    </div>
@endsection
