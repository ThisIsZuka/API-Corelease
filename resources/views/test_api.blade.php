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

    <button id="test-api"> test upload to new server</button>

</body>

</html>

<script>
    axios({
            method: 'POST',
            url: 'api/master_university',
        }).then(function(response) {
            // console.log(populateData(response.data))
            read_Faculty(response.data.data)
        })
        .catch(function(error) {
            // console.log(error);
        });

    // console.log(university)


    function read_Faculty(data) {
        for (i = 0; i < data.length; i++) {
            // console.log(data[i].MT_UNIVERSITY_ID)
            // http://localhost/API-Corelease/api/master_faculty?MT_UNIVERSITY_ID=2
            axios({
                    method: 'GET',
                    url: 'api/master_faculty?MT_UNIVERSITY_ID=' + data[i].MT_UNIVERSITY_ID,
                }).then(function(response) {
                    // console.log(response);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }




    $('#test-api').on('click', function() {

        const formData = new FormData();
        // const file = document.querySelector('#formFile');
        formData.append("U_Search", 'ลาดกระบัง');
        // formData.append("file", file.files[0]);
        // console.log(file.files[0])

        axios({
                // headers: {
                //     'Content-Type': 'multipart/form-data'
                // },
                method: 'POST',
                url: 'api/master_university',
                data: formData
            }).then(function(response) {
                console.log(response);
            })
            .catch(function(error) {
                console.log(error);
            });
    })
</script>
