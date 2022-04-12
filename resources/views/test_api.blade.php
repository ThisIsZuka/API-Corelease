<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test POST API</title>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>

    <div class="container mt-4">
        <div class="mb-3">
            <label for="formFile" class="form-label">image</label>
            <input class="form-control" type="file" id="formFile">
        </div>
    </div>

    <button id="test-api"> test </button>

</body>

</html>

<script>
    $('#test-api').on('click', function() {

        const formData = new FormData();
        const file = document.querySelector('#formFile');
        formData.append("aa", 'aa');
        formData.append("file", file.files[0]);
        console.log(file.files[0])

        axios({
                // headers: {
                //     'Content-Type': 'multipart/form-data'
                // },
                method: 'POST',
                url: 'test_new_prospect_cus',
                data: formData
            }).then(function(response) {
                console.log(response);
            })
            .catch(function(error) {
                console.log(error);
            });
    })
</script>
