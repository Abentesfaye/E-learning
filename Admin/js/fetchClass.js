document.addEventListener("DOMContentLoaded", function () {
    function updateClasses(departmentId) {
        fetch('fetch_classes.php?department_id=' + departmentId)
            .then(response => response.json())
            .then(data => {
                var classDropdown = document.getElementById("class");
                classDropdown.innerHTML = '<option value="" disabled selected>Select Class</option>';

                data.forEach(classData => {
                    var option = document.createElement("option");
                    option.value = classData.id;
                    option.text = classData.class_name;
                    classDropdown.add(option);
                });
            })
            .catch(error => console.error('Error fetching classes:', error));
    }

    document.getElementById("department").addEventListener("change", function () {
        var selectedDepartmentId = this.value;
        updateClasses(selectedDepartmentId);
    });
});
