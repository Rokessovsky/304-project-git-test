<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

  <!-- 
      chmod 755 ~/public_html/uber_dash_DBMS.php
  -->

  <html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="uber_dash_DBMS.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <div class="row" name="insertRow">
            <div class="column" name="insertCusColumn">
                <h3>Insert Values into Customer</h3>
                <form method="POST" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                    Account Username: <input type="text" name="insCusAU"> <br /><br />
                    Email: <input type="text" name="insCusEmail"> <br /><br />
                    Address: <input type="text" name="insCusAddr"> <br /><br />
                    Name: <input type="text" name="insCusName"> <br /><br />

                    <input type="submit" value="Insert" name="insertSubmit"></p>
                </form>
            </div>

            <div class="column" name="insertFPColumn">
                <h3>Insert Values into FoodProvider</h3>
                <form method="POST" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
                    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                    Food Provider Name: <input type="text" name="insFPName"> <br /><br />
                    Food Provider Location: <input type="text" name="insFPLoc"> <br /><br />
                    Phone Number: <input type="text" name="insFPPhoneNo"> <br /><br />

                    <input type="submit" value="Insert" name="insertSubmit"></p>
                </form>
            </div>

        <!--    <div class="column" name="insertOrderColumn">
                <h3>Insert Values into Order</h3>
                <form method="POST" action="uber_dash_DBMS.php">
                    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                    Order Number: <input type="text" name="insOrderNo"> <br /><br />
                    Order Price: <input type="text" name="insOrderPrice"> <br /><br />
                    Order Time: <input type="text" name="insOrderTime"> <br /><br />
                    Account Username: <input type="text" name="insOrderAU"> <br /><br />
                    Food Provider Name: <input type="text" name="insOrderFPName"> <br /><br />
                    Food Provider Location: <input type="text" name="insOrderFPLoc"> <br /><br />

                    <input type="submit" value="Insert" name="insertSubmit"></p>
                </form>
            </div>
        -->    
        </div>

        <hr />

        <h2>Update Customer Info</h2>
        <p>If you don't want to change a value, leave the field as blank</p>

        <form method="POST" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Account Username: <input type="text" name="Username"> <br /><br />
            New Email: <input type="text" name="newEmail"> <br /><br />
            New Address:<input type="text" name="newAddress"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />


        <h2>Projection </h2>
        <form method="GET" action="uber_dash_DBMS.php">
            <input type="hidden" id="projectionQueryRequest" name="projectionQueryRequest">
            <label for="OrderAttribute">Choose an attribute from Order:</label>
                <select name="OrderAttribute" id="OrderAttribute">
                <option value="select">select</option> 
                <option value="see_all">All</option>    
                <option value="order_number">Order Number</option>
                <option value="order_price">Price</option>
                <option value="account_username">Username</option>
                <option value="food_provider_name">Food Provider</option>
                </select>
            <input type="submit" value="select" name="project"></p>
        </form>   
        <hr />

        <h2>Select all name in DemoTable</h2>

        <form method="GET" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
            <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
            Account Username: <input type="text" name = "Username"> <br /><br />
            <input type="submit" value="Select" name="selectSubmit"></p>
        </form>

        <hr />

        <h2>Get the customer's email and FP name from order that exceeds the specified price</h2>
        <form method="GET" action="uber_dash_DBMS.php">
            <input type="hidden" id="selectQueryRequest" name="selectThresholdPriceRequest">
            Threshold Price: <input type="number" name = "ThresholdPrice"> <br /><br />

            <input type="submit" value="Select" name="selectSubmit"></p>
        </form>

        <hr />

        <h2>Find the Average Order Price</h2>
        <form method="GET" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
            <input type="hidden" id="averageOrderRequest" name="averageOrderRequest">
            <input type="submit" value="Average Price" name="averagePrice"></p>
        </form>

        <hr />

        <h2>Count Orders for each Customer</h2>
        <form method="GET" action="uber_dash_DBMS.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countOrdersRequest" name="countOrdersRequest">
            <input type="submit" value="Count Orders" name="countOrders"></p>
        </form>

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table Customer:<br>";
            echo "<table>";
            echo "<tr><th>AccountUsername</th><th>Email</th><th>Address</th><th>CustomerName</ th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printCountResult($result) { //prints results from a count group by statement
            echo "<br>Retrieved data from table Order:<br>";
            echo "<table>";
            echo "<tr><th>Count</th><th>AccountUsername</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printPriceResult($result) { //prints results from a threshold price statement
            echo "<br>Retrieved data from joined tables Order x Customer x FoodProvider:<br>";
            echo "<table>";
            echo "<tr><th>Customer's email</th><th>FoodProvider's name</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" .$row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }

            echo "</table>";
        }

        function printProjectionResult($result,$case){
            echo "<br>Retrieved data from table FunkyOrder:<br>";
            echo "<table>";
        
            if($case==1){ //ALL
                echo "<tr><th>order_number</th><th>price</th><th>time</th><th>username</th><th>food_provider_name</th><th>food_provider_location</th></tr>";
            }else if($case==2){ 
                echo "<tr><th>order_number</th></tr>";
            }else if($case==3){ 
                echo "<tr><th>order_price</th></tr>";
            }else if($case==4){ 
                echo "<tr><th>account_username</th></tr>";
            }else if($case==5){ 
                echo "<tr><th>food_provider_name</th></tr>";
            }
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                if($case==1){
                    echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td> <td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td></tr>";             
                }else {
                    echo "<tr><td>" . $row[0] . "</td></tr>";
                }
            }
            
            
            
            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_smethven", "a11305109", "dbhost.students.cs.ubc.ca:1522/stu");
            //$db_conn = OCILogon("ora_zqz827", "a66474206", "dbhost.students.cs.ubc.ca:1522/stu");
            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $username = $_POST['Username'];
            $new_email = $_POST['newEmail'];
            $new_address = $_POST['newAddress'];
            $new_name = $_POST['newName'];
            //if user leave a field as blank, don't update
            if(isset($new_email) && trim($new_email) != ''){
                executePlainSQL("UPDATE Customer SET email='" . $new_email . "' WHERE account_username='".$username."'");
            }
            if(isset($new_address) && trim($new_address) != ''){
                executePlainSQL("UPDATE Customer SET address='" . $new_address . "' WHERE account_username='".$username."'");
            }
            if(isset($new_name) && trim($new_name) != ''){
                executePlainSQL("UPDATE Customer SET customer_name='" . $new_name . "' WHERE account_username='".$username."'");
            }
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
            global $db_conn;

            $username = $_POST['Username'];
            
            executePlainSQL("DELETE FROM Customer WHERE account_username='" . $username . "'");

            OCICommit($db_conn);
        }

        function handleSelectRequest() {
            global $db_conn;

            $username = $_GET['Username'];

            $result = executePlainSQL("SELECT * FROM Customer WHERE account_username='" . $username . "'");
            printResult($result);
            OCICommit($db_conn);
        }
        function handleProjectRequest(){
            global $db_conn;
            
            //if($_GET['OrderAttribute']=='see_all'){
            if(!empty($_GET['OrderAttribute'])){
                $selected = $_GET['OrderAttribute'];
                if($selected=='see_all'){
                    $result = executePlainSQL("SELECT * FROM funkyOrder");
                    $case=1;
               }else if($selected=='order_number'){
                    $result = executePlainSQL("SELECT order_number FROM funkyOrder");
                    $case=2;
               }else if($selected=='order_price'){
                    $result = executePlainSQL("SELECT distinct order_price FROM funkyOrder");
                    $case=3;
               }else if($selected=='account_username'){
                    $result = executePlainSQL("SELECT distinct account_username FROM funkyOrder");
                    $case=4;
               }else if($selected=='food_provider_name'){
                    $result = executePlainSQL("SELECT distinct food_provider_name FROM funkyOrder");
                    $case=5;
                }
            }
            
            printProjectionResult($result,$case);
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            OCICommit($db_conn);
        }


        /***
         * 
         * This section contains 3 functions that are handling insert operation
         */
        function handleInsertCusRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insCusAU'],
                ":bind2" => $_POST['insCusEmail'],
                ":bind3" => $_POST['insCusAddr'],
                ":bind4" => $_POST['insCusName']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Customer values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
            OCICommit($db_conn);
        }

        function handleInsertFPRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insFPName'],
                ":bind2" => $_POST['insFPLoc'],
                ":bind3" => $_POST['insFPPhoneNo']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into FoodProvider values (:bind1, :bind2, :bind3)", $alltuples);
            OCICommit($db_conn);
        }

        // function handleInsertOrderRequest() {
        //     global $db_conn;

        //     //Getting the values from user and insert data into the table
        //     $tuple = array (
        //         ":bind1" => $_POST['insOrderNo'],
        //         ":bind2" => $_POST['insOrderPrice'],
        //         ":bind3" => $_POST['insOrderTime'],
        //         ":bind4" => $_POST['insOrderAU'],
        //         ":bind5" => $_POST['insOrderFPName'],
        //         ":bind6" => $_POST['insOrderFPLoc']
        //     );

        //     $alltuples = array (
        //         $tuple
        //     );

        //     executeBoundSQL("insert into funkyOrder values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
        //     OCICommit($db_conn);
        // }
/////////////////////////////////////////////////////////////
        /**
         * Below Are functions handling get requests
         * 
         */

        //handle the Threshold Price request(JOIN)
        function handleThresholdPriceRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insOrderNo'],
                ":bind2" => $_POST['insOrderPrice'],
                //":bind3" => $_POST['insOrderTime'],
                ":bind3" =>  TO_TIMESTAMP('YYYY-MM-DD HH24:MI:SS'),
                ":bind4" => $_POST['insOrderAU'],
                ":bind5" => $_POST['insOrderFPName'],
                ":bind6" => $_POST['insOrderFPLoc']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into funkyOrder values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
            OCICommit($db_conn);
        }
        
        //handle the average request
        function handleAverageRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT AVG(order_price) FROM funkyOrder");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The average order price is: " . $row[0] . "<br>";
            }
            ocicommit($db_conn);
        }


        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT COUNT(*), account_username FROM funkyOrder GROUP BY account_username");

            printCountResult($result);
            OCICommit($db_conn);
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    if(array_key_exists('insCusAU', $_POST)) {
                        handleInsertCusRequest();
                    }                   
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('averagePrice', $_GET)) {
                    handleAverageRequest();
                } else if (array_key_exists('selectQueryRequest', $_GET)){
        
                    handleSelectRequest();
  
                }else if(array_key_exists('projectionQueryRequest',$_GET)){
                    handleProjectRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['selectQueryRequest']) || isset($_GET['project'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>