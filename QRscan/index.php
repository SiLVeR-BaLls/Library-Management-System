<!DOCTYPE html>
<html>
<head>
    <!-- Character encoding and viewport settings -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QR Code | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <!-- Online scripts and styles -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    
    <!-- Custom styles camera button-->
    <style>
        #divvideo {
            box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.1);
        }
        #stopCameraButton, #startCameraButton {
            margin-top: 10px;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        #stopCameraButton {
            background-color: red;
            color: white;
            display: none; /* Initially hidden */
        }
        #stopCameraButton:hover {
            background-color: darkred;
        }
        #startCameraButton {
            background-color: green;
            color: white;
        }
        #startCameraButton:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body style="background-image: url(../Registration/pic/polygon-scatter-haikei.png);">

    <!-- Navigation Bar
    <nav class="navbar" style="background:#fff">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">QR Code Attendance</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-cog"></span> Maintenance <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Student</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-plus-sign"></span> New Student</a></li>
                        <li><a href="attendance.php"><span class="glyphicon glyphicon-calendar"></span> Attendance</a></li>
                    </ul>
                </li>
                <li><a href="#"><span class="glyphicon glyphicon-align-justify"></span> Reports</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-time"></span> Check Attendance</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>
        </div>
    </nav> -->

    <!-- Main Container -->
    <div class="container">
        <div class="row">

            <!-- QR Code Scanner -->
            <div class="col-md-4" style="padding:10px;background:#fff;border-radius: 5px;" id="divvideo">
                <center><p class="login-box-msg"> <i class="glyphicon glyphicon-camera"></i> TAP HERE</p></center>
                <video id="preview" width="100%" style="border-radius:10px;"></video>
                <br>
                <button id="stopCameraButton">Stop Camera</button>
                <button id="startCameraButton">Start Camera</button>
                <br><br>

                <!-- Display Error and Success Messages -->
                <?php
                if(isset($_SESSION['error'])){
                    echo "
                    <div class='alert alert-danger alert-dismissible' style='background:red;color:#fff'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-warning'></i> Error!</h4>
                        ".$_SESSION['error']."
                    </div>
                    ";
                    unset($_SESSION['error']);
                }
                if(isset($_SESSION['success'])){
                    echo "
                    <div class='alert alert-success alert-dismissible' style='background:green;color:#fff'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> Success!</h4>
                        ".$_SESSION['success']."
                    </div>
                    ";
                    unset($_SESSION['success']);
                }
                ?>
            </div>
            
            <!-- Form and Data Table -->
            <div class="col-md-8">
                <!-- Form for QR Code scanning -->
                <form action="insert.php" method="post" class="form-horizontal" style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
                    <i class="glyphicon glyphicon-qrcode"></i> <label>SCAN QR CODE</label> <p id="time"></p>
                    <input type="text" name="studentID" id="text" placeholder="scan qrcode" class="form-control" autofocus>
                </form>

                <!-- Data Table for Attendance Records -->
                <div style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
                    <table id="example1" class="table table-bordered">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>User ID</td>
                                <td>First Name</td>
                                <td>Last Name</td>
                                <td>Time In</td>
                                <td>Time Out</td>
                                <td>Log Date</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // PHP to fetch and display attendance records
                            $server = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "lms";
                        
                            $conn = new mysqli($server, $username, $password, $dbname);
                            $date = date('Y-m-d');
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql = "
                                SELECT a.ID, a.IDno, u.Fname, u.Sname, a.TIMEIN, a.TIMEOUT, a.LOGDATE, a.STATUS
                    FROM attendance a
                    JOIN users_info u ON a.IDno = u.IDno
                    ORDER BY a.LOGDATE DESC
                            ";
                            $query = $conn->query($sql);
                            while ($row = $query->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['ID']; ?></td>
                                    <td><?php echo $row['IDno']; ?></td>
                                    <td><?php echo $row['Fname']; ?></td>
                                    <td><?php echo $row['Sname']; ?></td>
                                    <td><?php echo $row['TIMEIN']; ?></td>
                                    <td><?php echo $row['TIMEOUT']; ?></td>
                                    <td><?php echo $row['LOGDATE']; ?></td>
                                    <td><?php echo $row['STATUS'] == 1 ? 'Out' : ' In'; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                   
                </div> 
                
                <!-- Export Button -->
                <button type="submit" class="btn btn-success pull-right" onclick="Export()">
                    <i class="fa fa-excel-o fa-fw"></i> Export to excel
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript for exporting data -->
    <script>
        function Export() {
            var conf = confirm("Please confirm if you wish to proceed in exporting the attendance into Excel file");
            if (conf == true) {
                window.open("export.php", '_blank');
            }
        }
    </script>

    <!-- JavaScript for QR Code scanning -->
    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        let activeCamera = null;

        function startCamera() {
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    activeCamera = cameras[0];
                    scanner.start(activeCamera);
                    document.getElementById('startCameraButton').style.display = 'none';
                    document.getElementById('stopCameraButton').style.display = 'block';
                } else {
                    alert('No cameras found');
                }
            }).catch(function(e) {
                console.error(e);
            });
        }

        document.getElementById('startCameraButton').addEventListener('click', function() {
            if (!activeCamera) {
                startCamera();
            }
        });

        document.getElementById('stopCameraButton').addEventListener('click', function() {
            if (activeCamera) {
                scanner.stop();
                activeCamera = null;
                document.getElementById('startCameraButton').style.display = 'block';
                document.getElementById('stopCameraButton').style.display = 'none';
            }
        });

        scanner.addListener('scan', function(c) {
            document.getElementById('text').value = c;
            document.forms[0].submit();
        });

        // Start the camera by default
        startCamera();
    </script>

    <!-- JavaScript for updating time -->
    <script type="text/javascript">
        var timestamp = '<?=time();?>';
        function updateTime() {
            $('#time').html(new Date(timestamp * 1000).toLocaleString());
            timestamp++;
        }
        $(function() {
            setInterval(updateTime, 1000);
        });
    </script>

    <!-- DataTables initialization -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>
</html>
