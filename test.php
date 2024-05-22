<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
}

header, footer {
    background-color: #f8f9fa;
}

nav {
    background-color: #6c757d;
    color: white;
}

nav a {
    color: white;
    text-decoration: none;
}

nav a:hover {
    text-decoration: underline;
}

main {
    background-color: #ffffff;
}

aside {
    background-color: #f8f9fa;
}

aside ul {
    list-style-type: none;
    padding-left: 0;
}

aside ul li {
    margin: 5px 0;
}

aside ul li a {
    color: #007bff;
    text-decoration: none;
}

aside ul li a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <header class="bg-light p-3 text-center">
        <h1>Website Header</h1>
    </header>
    
    <nav class="bg-secondary p-2 text-center">
        <ul class="nav justify-content-center">
            <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Services</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>
        </ul>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 bg-white p-4">
                <h2>Main Content Area</h2>
                <p>Welcome to our website. Here you will find a variety of services and information about what we offer.</p>
                <p>Feel free to browse through our content and learn more about our services. We are here to help you achieve your goals.</p>
                <h3>Section 1</h3>
                <p>This is the first section of the main content. It provides detailed information on a particular topic of interest.</p>
                <h3>Section 2</h3>
                <p>This is the second section of the main content. It covers another important aspect of our offerings.</p>
            </main>
            <aside class="col-md-3 bg-light p-4">
                <h2>Sidebar Content</h2>
                <ul>
                    <li><a href="#">Link 1</a></li>
                    <li><a href="#">Link 2</a></li>
                    <li><a href="#">Link 3</a></li>
                    <li><a href="#">Link 4</a></li>
                </ul>
                <h3>Related Articles</h3>
                <p>Read our latest articles on various topics:</p>
                <ul>
                    <li><a href="#">Article 1</a></li>
                    <li><a href="#">Article 2</a></li>
                    <li><a href="#">Article 3</a></li>
                </ul>
            </aside>
        </div>
    </div>
    
    <footer class="bg-light p-3 text-center mt-3">
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
