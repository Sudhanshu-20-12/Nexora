cat > resources/views/welcome.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexora</title>
</head>
<body>

    <div id="app"></div>

    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

</body>
</html>
EOF