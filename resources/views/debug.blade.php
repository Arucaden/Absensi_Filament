<!-- resources/views/debug.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Controller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Debug Controller</h2>
        <form method="POST" action="{{ route('debug.check-face-match') }}">
            @csrf
            <div class="mb-3">
                <label for="id_karyawan" class="form-label">ID Karyawan</label>
                <input type="text" class="form-control" id="id_karyawan" name="id_karyawan" placeholder="Masukkan ID Karyawan" value="2">
            </div>
            <div class="mb-3">
                <label for="face_vector" class="form-label">Face Vector (JSON)</label>
                <textarea class="form-control" id="face_vector" name="face_vector" rows="5" placeholder='[0.1, 0.2, 0.3]'>[0.1, 0.2, 0.3]</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>

        @if(session('debug'))
            <div class="mt-5">
                <h4>Debug Output</h4>
                <pre>{{ json_encode(session('debug'), JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </div>
</body>
</html>
