<?php
// Include necessary files and start the session
include("../includes/conn.php");

// Check if chapterId is provided in the GET request
if (isset($_GET['chapterId'])) {
    // Get the chapter ID from the GET request
    $chapterId = $_GET['chapterId'];

    // Prepare and execute a query to fetch topics for the given chapter ID
    $query = "SELECT id, topic_name FROM topics WHERE chapter_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $chapterId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any topics are found for the chapter
    if ($result->num_rows > 0) {
        // Initialize an empty string to store the options
        $options = "";

        // Loop through each topic and create options for the dropdown
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['id'] . "'>" . $row['topic_name'] . "</option>";
        }

        // Return the options as the response
        echo $options;
    } else {
        // If no topics are found, return a default option
        echo "<option value=''>No topics available</option>";
    }
} else {
    // If chapterId is not provided in the request, return an error message
    echo "Error: Chapter ID not provided";
}
?>
