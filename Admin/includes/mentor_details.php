<div class="mentor-details container mt-4 border p-2">
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="mentor-photo">
                <img src="<?php echo $mentorDetails['photo']; ?>" width="150" height="200" class="rounded img-hover" alt="Mentor Photo" onclick="showImage('<?php echo $mentorDetails['photo']; ?>')">
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="mentor-id">
                <img src="<?php echo $mentorDetails['id_photo']; ?>" width="250" height="170" class="rounded img-hover" alt="Mentor ID" onclick="showImage('<?php echo $mentorDetails['id_photo']; ?>')">
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="mentor-doc">
                <button class="btn btn-primary" onclick="downloadDocument('<?php echo $mentorDetails['document_path']; ?>')">Download Document</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mentor-info">
                <h1><?php echo $mentorDetails['first_name'] . ' ' . $mentorDetails['last_name']; ?></h1>
                <p>Email: <?php echo $mentorDetails['email_address']; ?></p>
                <p>Phone: <?php echo $mentorDetails['phone_number']; ?></p>
                <p>Account Code: <?php echo $mentorDetails['account_code']; ?></p>
                <p class="mb-0">Essay: <?php echo $mentorDetails['why_mentor']; ?></p>
                <p>Sex: <?php echo $mentorDetails['gender']; ?></p>
                <p>Status: <?php echo $mentorDetails['status']; ?></p>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12 text-center">
            <?php if ($mentorDetails['status'] === 'active'): ?>
                <p>This mentor is approved.</p>
            <?php elseif ($mentorDetails['status'] === 'rejected'): ?>
                <p>This mentor is rejected.</p>
            <?php elseif ($mentorDetails['status'] === 'pending'): ?>
                <div class="action-buttons">
                    <form method="post">
                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    function showImage(imageUrl) {
        var modal = document.createElement('div');
        modal.classList.add('image-modal');
        modal.innerHTML = '<div class="modal-content"><span class="close" onclick="closeModal()">&times;</span><img src="' + imageUrl + '" class="img-fluid" alt="Image"></div>';
        document.body.appendChild(modal);
    }

    function closeModal() {
        var modal = document.querySelector('.image-modal');
        modal.parentNode.removeChild(modal);
    }
    function showImage(imageUrl) {
        window.open(imageUrl, '_blank');
    }
</script>
