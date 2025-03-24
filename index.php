<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "report";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$testReportNo = '';
$data = [
    'Name' => '',
    'address' => '',
    'description' => '',
    'condition_found' => '',
    'sample_provided' => '',
    'temperature' => '',
    'humidity' => '',
    'method_used' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $testReportNo = $_POST['testReportNo'];
    $sql = "SELECT * FROM clients WHERE TestReportNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $testReportNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        echo "No results found";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Report</title>
    <link rel="stylesheet" href="testreport.css">
    <link rel="stylesheet" href="print.css" media="print">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <div class="report-container">
        <button id="pbtn" onclick="window.print()">Print Report</button>
        <header>
            <h1>GOVERNMENT OF PAKISTAN</h1>
            <h1>Ministry of Science & Technology</h1>
            <h1>Pakistan Council of Scientific & Industrial Research</h1>
            <h1>Laboratories Complex, Karachi.</h1>
            <h4>Shahrah-e-Dr. Salimuzzaman Siddiqui, Off University Road, Karachi-75280</h4>
            <div>
                <table>
                    <tbody>
                        <tr>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                            <td><input type="text"></td>
                        </tr>
                    </tbody>
                </table>
                <h1 id="test-heading">TEST REPORT</h1>
            </div>
        </header>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <span id="report">
                <label for="testReportNo">Test Report No.</label>
                <input type="text" id="testReportNo" name="testReportNo" value="<?php echo htmlspecialchars($testReportNo); ?>">
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="testDate" id="dates">Date:</label>
            <input type="date" id="testDate" name="testDate">
            <button id="fbtn" type="submit">Fetch Report</button>
            
            <div class="form-group">
                <label for="NameandAddress">
                    <h4>1. Name and Address of Client</h4>
                </label>
                <textarea id="field1" name="NameandAddress" rows="3" cols="50"><?php echo htmlspecialchars($data['Name'] . "\n" . $data['address']); ?></textarea>
                <div id="dropdown" class="dropdown"></div>
            </div>

            <script>
                document.getElementById('field1').addEventListener('input', function() {
                    let query = this.value;
                    if (query.length > 0) {
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', 'fetch_names.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (this.status == 200) {
                                let results = JSON.parse(this.responseText);
                                let dropdown = document.getElementById('dropdown');
                                dropdown.innerHTML = '';
                                results.forEach(result => {
                                    let div = document.createElement('div');
                                    div.textContent = result.Name;
                                    div.addEventListener('click', function() {
                                        document.getElementById('field1').value = this.textContent;
                                        dropdown.style.display = 'none';
                                    });
                                    dropdown.appendChild(div);
                                });
                                dropdown.style.display = 'block';
                            }
                        }
                        xhr.send('query=' + query);
                    } else {
                        document.getElementById('dropdown').style.display = 'none';
                    }
                });
            </script>

            <div class="form-group">
                <label for="reqNo" id="refno"><h4>Ref. No/ Req No</h4></label>
                <input type="text" id="reqNo" name="reqNo">
                <label for="testDate" id="date2"><h4>Date:</h4></label>
                <input type="date" id="testDate" name="testDate">
            </div>
            <div class="form-group">
                <label for="field2" id="NameandAddress"><h4>2. Description of the Sample</h4></label>
                <textarea id="field2" name="description"><?php echo htmlspecialchars($data['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="item" id="refno"><h4>Item</h4></label>
                <input type="text" id="item" name="item">
                <label for="testDate" id="date2">Date of Receipt:</label>
                <input type="date" id="testDate"  name="receiptDate">
            </div>
            <div class="form-group">
                <label for="item" id="refno"><h4>Lab Code No.</h4></label>
                <input type="text" id="item" name="labCode">
                <label for="make" id="date2">Make:</label>
                <input type="text" id="make" name="make">
            </div>
            <div class="form-group">
                <label for="mark" id="receipt"><h4>Condition found on receipt</h4></label>
                <input type="text" id="mark" name="condition_found" value="<?php echo htmlspecialchars($data['condition_found']); ?>">
                <label for="markifany1" id="markifany">Mark if any:</label>
                <input type="text" id="markifany1" name="markifany1">
            </div>
            <div class="form-group">
                <label for="sampling" id="NameandAddress"><h4>3. Sample Plain/Procedure Used</h4></label>
                <textarea id="sampling" name="sampling"></textarea>
                <label for="dtsample" id="dtsampling"><h4>Dt. of Sampling:</h4></label>
                <input type="date" id="dtsample" name="dtsample">
            </div>
            <div class="form-group">
                <label for="env" id="NameandAddress"><h4>4. Environmental Conditions<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Where applicable)</h4></label>
                <input type="text" id="env" name="env">
                <label for="temp" id="tempe"><h4>Temperature:</h4></label>
                <input type="text" id="temp" name="temp" value="">
                <label for="hum" id="humi"><h4>Humidity:</h4></label>
                <input type="text" id="hum" name="hum" >
            </div>
            <div class="form-group">
                <label for="field5" id="NameandAddress"><h4>5. Method Used</h4></label>
                <textarea id="field5" name="method_used"><?php echo htmlspecialchars($data['method_used']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="results" id="NameandAddress"><h4>6. Measurement & Results</h4></label>
            </div>
        </form>
        <section>
            <textarea name="editor1"></textarea>
            <script>
                CKEDITOR.replace('editor1', {
                    toolbar: [
                        { name: 'document', items: ['Source', '-', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak'] },
                        { name: 'styles', items: ['Styles', 'Format'] },
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'] },
                        { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
                        { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
                    ],
                    contentsCss: ['style1.css', 'print.css'],
                    bodyClass: 'print-area'
                });
            </script>
        </section>
        <div class="form-group">
            <label for="statement" id="state"><h4>7. Statement of Compliance</h4></label>
            <input type="text" id="statement" name="testReportNo">
        </div>
        <div class="form-group">
            <label for="statement" id="state"><h4>8. Opinion / Interpretation</h4></label>
            <input type="text" id="statement" name="testReportNo">
        </div>
        <footer>
            <p>****************************END OF REPORT****************************</p>
        </footer>
    </div>
    <button id="pbtn" onclick="window.print()">Print Report</button>
</body>
</html>
