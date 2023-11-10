<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Upload Image to Firebase Storage</title>
</head>

<body>
    <h1>Upload an Image</h1>
    <h2>
        @if(session('success'))
        
            {{ session('success') }}
        
        @endif

    </h2>
    <form action="/upload-image" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" accept="image/*">
        <button type="submit">Upload</button>
    </form>
</body>

</html>