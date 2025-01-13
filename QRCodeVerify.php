<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <style>
        #reader {
            width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
        #result {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            color: green;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">QR Code Scanner</h2>
    <div id="reader"></div>
    <div id="result"></div>
    <div id="details"></div>

    <script>
        // 成功扫描后的处理函数
        // Success function after scanning QR code
        function onScanSuccess(decodedText, decodedResult) {
            const parsedData = JSON.parse(decodedText)[0]; // Extract the string from the array
            console.log(`Code scanned = ${parsedData}`, decodedResult);

            // Redirect to display.html with the scanned data in query parameters
            window.location.href = `display.html?visitor_code=${encodeURIComponent(parsedData)}`;
        }


        // 扫描错误处理
        function onScanError(errorMessage) {
            // 不显示任何错误信息，只静默处理
        }

        // 发送扫描数据到后端验证
        function validateQRCode(scannedData) {
        // Send only the relevant data to the backend
        const parsedData = JSON.parse(scannedData); // Extract the string from the array

        const newcode=parsedData["visitor_code"];
        console.log(newcode);
        // console.log(`Scanned data = ${parsedData}`);
        

        fetch('validate.php', { // Make sure your PHP script path is correct
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ scannedData: newcode }) // Send the string directly
        })
        .then(response => response.json())
        .then(data => {
            // Display the validation result
            if (data.status === 'success') {
                document.getElementById('result').innerText = 'QR Code Validated Successfully!';
            } else {
                document.getElementById('result').innerText = data.message;
            }
        })
        .catch(error => {
            console.error('Error validating QR code:', error);
            document.getElementById('result').innerText = 'Error validating QR code. Please try again.';
        });
    }


        // 初始化 QR Code 扫描器
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: 250 }, 
            false
        );

        // 渲染扫描器，仅在检测到二维码时才读取
            html5QrcodeScanner.render(validateQRCode, onScanError);
    </script>
</body>
</html>
