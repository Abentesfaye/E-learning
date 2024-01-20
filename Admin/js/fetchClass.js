document.addEventListener("DOMContentLoaded", function () {
    // Function to update class options based on selected department
    function updateClasses(departmentId) {
        // Fetch classes from the database for the selected department
        fetch('fetch_classes.php?department_id=' + departmentId)
            .then(response => response.json())
            .then(data => {
                var classDropdown = document.getElementById("class");
                
                // Clear existing options
                classDropdown.innerHTML = '<option value="" disabled selected>Select Class</option>';

                // Populate class options based on the fetched data
                data.forEach(classData => {
                    var option = document.createElement("option");
                    option.value = classData.id;
                    option.text = classData.class_name;
                    classDropdown.add(option);
                });
            });
    }

    // Event listener for department dropdown change
    document.getElementById("department").addEventListener("change", function () {
        var selectedDepartmentId = this.value;
        updateClasses(selectedDepartmentId);
    });
});
