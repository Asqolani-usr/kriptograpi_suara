<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Crypto System</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #666; }
        input[type="file"], input[type="password"], select { width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 0.75rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-top: 1rem; transition: background 0.3s; }
        .btn:hover { background: #0056b3; }
        .radio-group { display: flex; gap: 1rem; margin-top: 0.5rem; }
        .radio-group label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; }
        .note { font-size: 0.8rem; color: #888; margin-top: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Audio Crypto</h2>
        <form action="process.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">Select Audio File (.wav, .mp3)</label>
                <input type="file" name="file" id="file" accept=".wav,.mp3,.enc" required>
            </div>
            <div class="form-group">
                <label for="key">Secret Key</label>
                <input type="password" name="key" id="key" placeholder="Enter your secret key" required>
            </div>
            <div class="form-group">
                <label>Action</label>
                <div class="radio-group">
                    <label><input type="radio" name="action" value="encrypt" checked> Encrypt</label>
                    <label><input type="radio" name="action" value="decrypt"> Decrypt</label>
                </div>
            </div>
            <button type="submit" class="btn">Process</button>
        </form>
        <div class="note">Algorithm: AES-256-CBC</div>
    </div>
</body>
</html>
