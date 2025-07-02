<?php

// Memproses input dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $input = isset($_POST['nilai_soal']) ? $_POST['nilai_soal'] : '';
    $nilai_total_soal = isset($_POST['nilai_total_soal']) ? (int)$_POST['nilai_total_soal'] : 0;
    
    // memisahkan input berdasarkan koma dan mengonversi ke array integer
    $nilai_soal = array_map('intval', array_filter(explode(',', $input), 'is_numeric'));
    $nilai_soal = array_filter($nilai_soal, function($val) { return $val > 0; });
    
    // Batasi jumlah pertanyaan maksimal 10
    if (count($nilai_soal) > 10) {
        $nilai_soal = array_slice($nilai_soal, 0, 10);
    }
    
    if (!empty($nilai_soal) && $nilai_total_soal > 0) {
        $result = [];
        $current = [];
        $n = count($nilai_soal);

        // Fungsi rekursif untuk mencari kombinasi
        function backtrack($start, $remaining, &$result, &$current, $nilai_soal, $n) {
            if ($remaining == 0) {
                $result[] = $current;
                return;
            }

            for ($i = $start; $i < $n; $i++) {
                if ($nilai_soal[$i] > $remaining) {
                    continue;
                }

                $current[] = $i;
                backtrack($i + 1, $remaining - $nilai_soal[$i], $result, $current, $nilai_soal, $n);
                array_pop($current);
            }
        }

        backtrack(0, $nilai_total_soal, $result, $current, $nilai_soal, $n);
        $combinations = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAL TEST</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background: #555;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>SOAL TES MEMBUAT PROGRAM PHP</h1>
    
    <form method="post">
        <label for="nilai_soal">Soal (masukkan nilai tiap-tiap pertanyaan dalam soal yang dipisah dengan koma, maksimal 10 pertanyaan):</label><br>
        <input type="text" id="nilai_soal" name="nilai_soal" placeholder="Contoh: 10,10,10,15,15" value="<?php echo isset($input) ? htmlspecialchars($input) : ''; ?>" required><br>
        
        <label for="nilai_total_soal">T:</label><br>
        <input type="number" id="nilai_total_soal" name="nilai_total_soal" placeholder="Contoh: 30" value="<?php echo isset($nilai_total_soal) ? htmlspecialchars($nilai_total_soal) : ''; ?>" min="1" required>
        
        <input type="submit" value="Hitung">
    </form>
<?php if (isset($combinations)): ?>
        <h2>SOAL</h2>
        <pre>Array
(
<!-- menampilkan nilai soal -->
<?php foreach ($nilai_soal as $index => $row_nilai): ?>
    [Pertanyaan <?php echo $index + 1; ?>] => <?php echo $row_nilai; ?><br>
<?php endforeach; ?>
) 
Dengan Nilai Total Soal (T) = <?php echo $nilai_total_soal; ?> ?</pre>
<!-- menampilkan jawaban -->
<h2>JAWABAN</h2>
        <p>Jumlah semua Kombinasi (K) = <?php echo count($combinations); ?></p>
        

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p class="error">Input tidak valid. Pastikan Anda memasukkan angka-angka positif yang dipisahkan dengan koma.</p>
    <?php endif; ?>
</body>
</html>