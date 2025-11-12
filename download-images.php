<?php

$images = [
    // LIVROS - Capas reais
    'livros/senhor-dos-aneis.jpg' => 'https://covers.openlibrary.org/b/ISBN/9789720046212-L.jpg',
    'livros/1984.jpg' => 'https://covers.openlibrary.org/b/ISBN/9789720070610-L.jpg',
    'livros/don-quixote.jpg' => 'https://covers.openlibrary.org/b/ISBN/9789720047325-L.jpg',
    'livros/nome-da-rosa.jpg' => 'https://covers.openlibrary.org/b/ISBN/9789720048230-L.jpg',

    // AUTORES - Fotos reais
    'autores/tolkien.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/66/J._R._R._Tolkien%2C_1940s.jpg/300px-J._R._R._Tolkien%2C_1940s.jpg',
    'autores/orwell.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/George_Orwell_press_photo.jpg/300px-George_Orwell_press_photo.jpg',
    'autores/cervantes.jpg' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Cervantes_J%C3%A1uregui.jpg/300px-Cervantes_J%C3%A1uregui.jpg',

    // EDITORAS - Logos reais
    'editoras/porto.png' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Porto_Editora_logo.svg/400px-Porto_Editora_logo.svg.png',
    'editoras/penguin.png' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/Penguin_Logo.svg/400px-Penguin_Logo.svg.png',
    'editoras/leya.png' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7c/Leya_logo.svg/400px-Leya_logo.svg.png',
];

foreach ($images as $localPath => $url) {
    $fullPath = "public/images/{$localPath}";
    
    echo "📥 Baixando: {$url}\n";
    echo "💾 Para: {$fullPath}\n";
    
    $imageData = @file_get_contents($url);
    
    if ($imageData !== false) {
        // Criar diretório se não existir
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($fullPath, $imageData);
        echo "✅ Sucesso: {$localPath}\n\n";
    } else {
        echo "❌ Erro ao baixar: {$url}\n\n";
    }
}

echo "🎉 Download de imagens concluído!\n";