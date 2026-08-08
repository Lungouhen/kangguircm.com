<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - {{ config('app.name', 'KangGui RCM') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .hero {
            text-align: center;
            padding: 6rem 2rem;
            color: white;
        }
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .feature-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .feature-card ul {
            list-style: none;
            padding-left: 0;
        }
        .feature-card li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .feature-card li:last-child {
            border-bottom: none;
        }
        .feature-card li:before {
            content: "✓";
            color: #22c55e;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        footer {
            text-align: center;
            padding: 2rem;
            color: white;
            opacity: 0.8;
            margin-top: 4rem;
        }
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>KangGui RCM</h1>
            <p>All-in-one SaaS Platform for Content Management, Email Marketing, and Human Resource Management</p>
            <a href="/admin/dashboard" class="btn">Get Started</a>
        </div>

        <div class="features">
            <div class="feature-card">
                <h3>📝 CMS</h3>
                <ul>
                    <li>Create and manage blog posts</li>
                    <li>Static page builder</li>
                    <li>Category management</li>
                    <li>Media library with upload</li>
                    <li>SEO-friendly URLs</li>
                </ul>
            </div>

            <div class="feature-card">
                <h3>📧 Email Marketing</h3>
                <ul>
                    <li>Subscriber list management</li>
                    <li>Email template builder</li>
                    <li>Campaign creation & sending</li>
                    <li>Open & click tracking</li>
                    <li>Analytics dashboard</li>
                </ul>
            </div>

            <div class="feature-card">
                <h3>👥 HRM</h3>
                <ul>
                    <li>Employee profiles</li>
                    <li>Attendance tracking</li>
                    <li>Leave request management</li>
                    <li>Payroll processing</li>
                    <li>Department hierarchy</li>
                </ul>
            </div>
        </div>

        <footer>
            <p>&copy; {{ date('Y') }} KangGui RCM. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
