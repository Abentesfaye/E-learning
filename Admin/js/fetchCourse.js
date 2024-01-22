document.addEventListener("DOMContentLoaded", function () {
    function updateCourses(classId) {
        fetch('fetch_course.php?class_id=' + classId)
            .then(response => response.json())
            .then(data => {
                var courseDropdown = document.getElementById("course");
                courseDropdown.innerHTML = '<option value="" disabled selected>Select Course</option>';

                data.forEach(courseData => {
                    var option = document.createElement("option");
                    option.value = courseData.id;
                    option.text = courseData.course_name;
                    courseDropdown.add(option);
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    }

    document.getElementById("class").addEventListener("change", function () {
        var selectedClassId = this.value;
        updateCourses(selectedClassId);
    });
});
