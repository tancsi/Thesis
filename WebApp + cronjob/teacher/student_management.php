<?php
ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $username = $_SESSION['username'];
	$teacher_id=$_SESSION['teacher_id'];
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add custom CSS for the menu -->
    <link rel="stylesheet" type="text/css" href="../styles/student_management.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
	<link href="../styles/select2/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
    <!-- Navbar with advanced menu -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #333;">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="teacher_desktop.php"><i class="fas fa-road"></i> Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student_management.php"><i class="fas fa-users"></i> Student Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
				<li class="nav-item delimiter"></li>
				<li class="nav-item">
					<span class="nav-link no-hover">
						<i class="fas fa-user"></i> <?php echo $username; ?>
					</span>
				</li>
            </ul>
        </div>
    </nav>

    <!-- Main content -->
    <div class="content">
	 <h1>Students</h1>
	 <div class="filter-container">
		<div class="search-bar">
			<input type="text" id="student-search-input" placeholder="Search for students...">
		</div>
		<div class="search-bar">
			<input type="text" id="teacher-search-input" placeholder="Search for teachers...">
		</div>
		<div class="date-filter">
			<label for="reg-date-from">From:</label>
			<input type="date" id="reg-date-from">
			<label for="reg-date-to">To:</label>
			<input type="date" id="reg-date-to">
			<button id="filter-button">Filter</button>
		</div>
		<div class="active-filter">
			<label for="active-filter">Active Status:</label>
			<select id="active-filter">
				<option value="">All</option>
				<option value="1">Active</option>
				<option value="0">Not Active</option>
			</select>
		</div>
		<div class="reset-filters">
			<button id="reset-filters-button" >Reset Filters</button>
		</div>
	</div>

    <div class="student-table">
        <table id="student-table" class="table table-bordered">
            <thead>
                <tr>
					<th>Name (User Id)</th>
                    <th>Total Trips</th>
                    <th>Teacher(s) Name</th>
					<th>Registration Time</th>
					<th>Active</th>
					<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
				include "../kapcsolat.php";
				 $sql = "SELECT
						s.user_id AS student_id,
						CONCAT(s.first_name, ' ', s.last_name, ' (', s.user_id, ')') AS student_fullname,
						s.total_trips AS student_total_trips,
						IFNULL(GROUP_CONCAT(CONCAT(t.first_name, ' ', t.last_name, ' (', t.username, ')') SEPARATOR ', '), 'No teacher assigned') AS teacher_names,
						s.reg_time AS student_registration_time,
						s.active
						FROM
							students AS s
						LEFT JOIN
							teacher_student_con AS ts ON s.user_id = ts.user_id
						LEFT JOIN
							teacher AS t ON ts.teacher_id = t.teacher_id
						GROUP BY
							s.user_id, s.first_name, s.last_name, s.total_trips, s.reg_time
						ORDER BY
							s.reg_time";
				$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["student_fullname"] . "</td>";
                        echo "<td>" . $row["student_total_trips"] . "</td>";
                        echo "<td>" . $row["teacher_names"] . "</td>";
                        echo "<td>" . $row["student_registration_time"] . "</td>";
						 if ($row["active"] == 1) {
							echo "<td>Yes</td>";
							echo "<td class='text-center'><button class='btn btn-danger btn-inactivate' data-id='" . $row["student_id"] . "'>Inactivate</button></td>";
						} else {
							echo "<td>No</td>";
							echo "<td class='text-center'><button class='btn btn-primary btn-activate' data-id='" . $row["student_id"] . "'>Activate</button></td>";
						}
						
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No students found.</td></tr>";
                }
                $db->close();
                ?>
            </tbody>
        </table>
    </div>
	<div class="add-student-form">
		<h2>Add New Student</h2>
		<form id="student-form"  class="form-group">
			<label for="first-name">First Name:</label>
			<input type="text" id="first-name" name="first_name" class="form-control" required><br><br>

			<label for="last-name" >Last Name:</label>
			<input type="text" id="last-name" name="last_name" class="form-control" required><br><br>
			
			<div class="form-check">
			<input type="radio" id="assign-later" name="assignment-option" class="form-check-input" value="later">
			<label for="assign-later" class="form-check-label">Assign the student later</label>
			<input type="radio" id="assign-to-me" name="assignment-option" class="form-check-input" value="to-me">
			<label for="assign-to-me" class="form-check-label">Assign the student to me</label><br><br>
			</div>

			<button type="submit" class="btn btn-primary">Add Student</button>
		</form>
		<div id="message-container" ></div>
	</div>
	<div class="assign-student-form">
    <h2 class="form-title">Assign Student(s)</h2>
    <div class="form-group">
        <select class="js-example-basic-multiple form-select" name="students[]" multiple="multiple">
        </select>
    </div>
    <button id="assign-button" class="form-button">Assign</button>
    <div id="assign-message" class="form-message"></div>
	</div>
	<div class="assign-student-form">
    <h2 class="form-title">Remove assignment</h2>
    <div class="form-group">
        <select class="js-example-basic-multiple form-select" id="removeStudentsSelect" name="removeStudents[]" multiple="multiple" >
        </select>
    </div>
    <button id="remove-assign-button" class="form-button">Remove assignment</button>
    <div id="remove-assign-message" class="form-message"></div>
	</div>

	

    </div>

    <!-- Add Bootstrap and Font Awesome icons -->
    <!-- Add Bootstrap and jQuery scripts at the end of the file -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="../styles/select2/dist/js/select2.min.js"></script>
	<script>
    $(document).ready(function () {
		 $.ajax({
			url: 'get_students.php',
			method: 'GET',
			success: function (response) {
				var formattedStudents = response.map(function (student) {
					return { id: student.user_id, text: student.name }; 
				});
				$('.js-example-basic-multiple').select2({
					data: formattedStudents
				});
			},
			error: function (error) {
				console.error('Error fetching students:', error);
			}
		});
		function initializeSelect2(data) {
			 var selectElement = $('#removeStudentsSelect');
			 selectElement.empty();
			 selectElement.select2({
				data: data
			 });
		}
		function fetchStudentsAndInitializeSelect2() {
			 $.ajax({
				url: 'get_remove_students.php',
				method: 'GET',
				data: { teacher_id: <?php echo $_SESSION['teacher_id']; ?> },
				success: function (response) {
					var formattedStudents = response.map(function (student) {
						return { id: student.user_id, text: student.name }; 
					});
					initializeSelect2(formattedStudents);
				},
				error: function (error) {
					console.error('Error fetching students:', error);
				}
			});
		}
		function removeAssignments() {
			var selectedStudents = $('#removeStudentsSelect').val();
			if (selectedStudents && selectedStudents.length > 0) {
				var teacherId = <?php echo $_SESSION['teacher_id']; ?>;
				var data = {
					teacher_id: teacherId,
					student_ids: selectedStudents
				};
				 $('#removeStudentsSelect').prop('disabled', true);
				fetch('delete_teacher_student_connections.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(data)
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						$('#remove-assign-message').html('<p style="color: green;">Remove assignment was successful</p>');
						reloadTable();
					} else {
						$('#remove-assign-message').html('<p style="color: red;">Error: ' + data.message + '</p>');
					}
					$('#removeStudentsSelect').prop('disabled', false)
				})
				.catch(error => {
					console.error('Error deleting connections:', error);
					$('#remove-assign-message').html('<p style="color: red;">Error occurred while deleting connections</p>');
					$('#removeStudentsSelect').prop('disabled', false);
				});
			} else {
				$('#remove-assign-message').html('<p style="color: red;">Please choose at least one student to remove assignment</p>');
			}
		}
		fetchStudentsAndInitializeSelect2();
		

		function filterTable(tableId, inputId, columnIndex) {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById(inputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[columnIndex]; 
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }


        function filterByDate() {
            var from = document.getElementById("reg-date-from").value;
            var to = document.getElementById("reg-date-to").value;
            var table = document.getElementById("student-table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) { 
                var dateCell = rows[i].getElementsByTagName("td")[3]; 
                if (dateCell) {
                    var dateValue = dateCell.textContent || dateCell.innerText;
                    var currentDate = new Date(dateValue);
                    var fromDate = new Date(from);
                    var toDate = new Date(to);
                    toDate.setHours(23, 59, 59);

                    if (currentDate >= fromDate && currentDate <= toDate) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }
		function filterByActive() {
			var activeFilter = document.getElementById("active-filter");
			var selectedOption = activeFilter.options[activeFilter.selectedIndex].value;
			var table = document.getElementById("student-table");
			var rows = table.getElementsByTagName("tr");

			for (var i = 1; i < rows.length; i++) {
				var activeCell = rows[i].getElementsByTagName("td")[4]; 
				if (activeCell) {
					var activeValue = activeCell.textContent.trim(); 
					if (selectedOption === "" || activeValue === (selectedOption === "1" ? "Yes" : "No")) {
						rows[i].style.display = "";
					} else {
						rows[i].style.display = "none";
					}
				}
			}
		}
		function resetFilters() {
			$("#student-search-input").val(""); 
			$("#teacher-search-input").val(""); 
			$("#reg-date-from").val(null); 
			$("#reg-date-to").val(null); 
			$("#active-filter").val(""); 

			filterTable("student-table", "student-search-input", 0);
			filterTable("student-table", "teacher-search-input", 2);
			filterByDate();
			filterByActive();
		}
		
		$(document).on("click", ".btn-inactivate", function () {
        var studentId = $(this).data("id");
        $.ajax({
				url: "inactivate.php",
				method: "GET",
				data: { student_id: studentId },
				success: function (response) {
					reloadTable();					
				},
				error: function (error) {
					console.error("Error:", error);
				}
			});
		});
		
		
		$(document).on("click", ".btn-activate", function () {
        var studentId = $(this).data("id");
        $.ajax({
				url: "activate.php", 
				method: "GET",
				data: { student_id: studentId },
				success: function (response) {
					reloadTable();
				},
				error: function (error) {
					console.error("Error:", error);
				}
			});
		});
		
		function reloadTable() {
		$.ajax({
				url: "reloadtable.php",
				method: "GET",
				success: function (response) {
					$("#student-table tbody").empty();
					response.forEach(function(student) {
						var isActive = student.active == 1 ? 'Yes' : 'No';
						var row = "<tr>" +
									"<td>" + student.student_fullname + "</td>" +
									"<td>" + student.student_total_trips + "</td>" +
									"<td>" + student.teacher_names + "</td>" +
									"<td>" + student.student_registration_time + "</td>" +
									"<td>" + isActive + "</td>";
						if (student.active == 1) {
							row += "<td class='text-center'><button class='btn btn-danger btn-inactivate' data-id='" + student.student_id + "'>Inactivate</button></td>";
						} else {
							row += "<td class='text-center'><button class='btn btn-primary btn-activate' data-id='" + student.student_id + "'>Activate</button></td>";
						}

						row += "</tr>";

						$("#student-table tbody").append(row);
					})
				},
				error: function (error) {
					console.error("Error:", error);
				}
			});
			fetchStudentsAndInitializeSelect2();
		}
		
		function generateUserId() {
			const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			const numbers = '0123456789';
			let userId = '';

			for (let i = 0; i < 4; i++) {
				userId += letters.charAt(Math.floor(Math.random() * letters.length));
			}

			for (let i = 0; i < 4; i++) {
				userId += numbers.charAt(Math.floor(Math.random() * numbers.length));
			}

			return userId;
		}
		
		
		$("#student-form").submit(function (event) {
        event.preventDefault();
        
        var firstName = $("#first-name").val();
        var lastName = $("#last-name").val();
        var userId = generateUserId();
		var assignmentOption = $("input[name='assignment-option']:checked").val();
        function checkUserIdExists() {
            userId = generateUserId();
            $.ajax({
                url: "check_user_id.php", 
                method: "POST",
                data: { user_id: userId },
                success: function (response) {
                    if (response.exists) {
                        checkUserIdExists();
                    } else {
                        if (assignmentOption === "later") {
							addStudentToDatabase(userId, firstName, lastName);
						} else if (assignmentOption === "to-me") {
							addStudentWithAssignment(userId, firstName, lastName);
						}
                    }
                },
                error: function (error) {
                    console.error("Error:", error);
                }
            });
        }
        checkUserIdExists();
		
    });
		function addStudentToDatabase(userId, firstName, lastName) {
			$.ajax({
				url: "add_student.php",
				method: "POST",
				data: {
					user_id: userId,
					first_name: firstName,
					last_name: lastName
				},
				success: function (response) {
					$("#first-name").val("");
                    $("#last-name").val("");
					$("#message-container").text("Student added successfully!").css("color", "green");
					reloadTable();
				},
				error: function (error) {
					$("#message-container").text("Error adding student. Please try again.").css("color", "red");
					console.error("Error:", error);
				}
			});
		}
		function addStudentWithAssignment(userId, firstName, lastName) {
			$.ajax({
				url: "add_student.php",
				method: "POST",
				data: {
					user_id: userId,
					first_name: firstName,
					last_name: lastName
				},
				success: function (response) {
					var teacherId = <?php echo $teacher_id; ?>;
					$.ajax({
						url: "assign_student.php",
						method: "POST",
						data: {
							teacher_id: teacherId,
							user_id: userId
						},
						success: function (assignResponse) {
							$("#first-name").val("");
							$("#last-name").val("");
							$("#message-container").text("Student added and assigned successfully!").css("color", "green");
							reloadTable();
						},
						error: function (assignError) {
							$("#message-container").text("Error assigning student. Please try again.").css("color", "red");
							console.error("Error:", assignError);
						}
					});
				},
				error: function (error) {
					$("#message-container").text("Error adding student. Please try again.").css("color", "red");
					console.error("Error:", error);
				}
			});
}
		   $("#assign-button").click(function() {
			var teacherId = <?php echo $teacher_id; ?>;
			var selectedStudents = $(".js-example-basic-multiple").val();
			
			if (selectedStudents && selectedStudents.length > 0) {   
				$.ajax({
					url: 'assign_later_student.php',
					type: 'POST',
					data: {teacherId: teacherId, students: selectedStudents},
					dataType: 'json',
					success: function(response) {
						if (response.error) {
							$("#assign-message").text(response.error).css("color", "red");
						} else {
							var successMessage = "";
							if (response.successfully_assigned && response.successfully_assigned.length > 0) {
								successMessage += "Assigned students: " + response.successfully_assigned.join(", ") + " ";
							}
							if (response.already_assigned && response.already_assigned.length > 0) {
								successMessage += "Already assigned: " + response.already_assigned.join(", ") + " ";
							}
							if (response.half_assigned && response.half_assigned.length > 0) {
								successMessage += "Could not assign: " + response.half_assigned.join(", ") + " ";
							}
							 if (response.successfully_assigned && response.successfully_assigned.length > 0 &&
								response.already_assigned && response.already_assigned.length > 0) {
								$("#assign-message").text(successMessage.trim()).css("color", "orange");
							} else if (response.successfully_assigned && response.successfully_assigned.length > 0) {
								$("#assign-message").text(successMessage.trim()).css("color", "green");
							} else {
								$("#assign-message").text(successMessage.trim()).css("color", "red");
							}
						}
						reloadTable();
					},
					error: function(xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
			} else {
				$("#assign-message").text("Please choose at least one student to assign").css("color", "red");
			}
		});

        $("#student-search-input").on("input", function () {
            filterTable("student-table", "student-search-input", 0); 
        });

        $("#teacher-search-input").on("input", function () {
            filterTable("student-table", "teacher-search-input", 2);
        });

        $("#filter-button").on("click", function () {
            filterByDate();
        });
		$("#active-filter").on("change", function () {
			filterByActive();
		});
		$("#reset-filters-button").on("click", function () {
			resetFilters();
		});
		$('#remove-assign-button').on('click', function() {
			removeAssignments();
		});
});
</script>


</body>
</html>
