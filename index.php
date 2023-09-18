<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Response Viewer</title>
</head>

<body>
    <div style="max-width:100%; margin-top: 20px ;">
        <form id="urlForm" style="font-size:20px;">
            <label for="userText">Enter URL:</label>
            <input style="width:80%; line-height: 30px;" type="text" id="userText" name="user_text">
            <button style="width:5%; line-height: 30px; font-size:20px;" type="submit">Query</button>
        </form>

    </div>
    <div id="responseContainer"></div>

    <script>
        document.getElementById('urlForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var userText = document.getElementById('userText').value;
            fetch('response.php', {
                method: 'POST',
                body: new FormData(this),
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('responseContainer').innerHTML = data;
                });
        });
    </script>
</body>

</html>